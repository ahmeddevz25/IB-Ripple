<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Event;
use App\Models\Slider;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MediaDocument;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:page management')->only('index');
        $this->middleware('permission:page add')->only('store');
        $this->middleware('permission:page edit')->only('edit', 'update');
        $this->middleware('permission:page delete')->only('destroy');
    }
    public function index()
    {
        $pages = Page::with(['parent', 'slider', 'menus'])->get();
        $parents = Page::orderBy('sort_order')->get();
        $sliders = Slider::orderBy('id', 'desc')->get();
        $menus = Menu::orderBy('name')->get();

        $events = Event::select('id', 'title')
            ->orderBy('title')
            ->get();

        return view('admin.pages.show-pages', compact('pages', 'parents', 'sliders', 'menus', 'events'));
    }

    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'page_title' => 'required|string|max:255',
                'sub_title' => 'nullable|string|max:255',
                'body' => 'nullable',
                'parent_id' => 'nullable|integer|exists:pages,id',
                'slider_id' => 'nullable|integer|exists:sliders,id',
                'event_ids' => 'nullable|array',
                'event_ids.*' => 'integer|exists:events,id',
                'is_active' => 'boolean',
                'sidebar_page_ids' => 'nullable|array',
                'sidebar_page_ids.*' => 'integer|exists:pages,id',
                'sort_order' => 'integer|nullable',

                // ✅ PDF + Thumbnail
                'document' => 'nullable|file|mimes:pdf|max:204800',
                'thumbnail' => 'nullable|image|max:5120',
            ]);

            // ✅ GENERATE UNIQUE SLUG
            $baseSlug = Str::slug($request->page_title);
            $slug = $baseSlug;
            $count = 1;

            while (Page::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count;
                $count++;
            }

            $data['slug'] = $slug;

            $data['parent_id'] = $request->filled('parent_id') ? $request->parent_id : null;
            $data['slider_id'] = $request->filled('slider_id') ? $request->slider_id : null;
            $data['is_navbar'] = $request->input('is_navbar') == 1 ? 1 : 0;
            $data['is_active'] = $request->input('is_active') == 1 ? 1 : 0;
            $data['event_ids'] = $request->input('event_ids', []);

            // ✅ CREATE PAGE FIRST
            $page = Page::create($data);

            // ✅ NOW SAVE DOCUMENT (IF EXISTS)
            if ($request->hasFile('document')) {

                $file = $request->file('document');

                // Original filename
                $originalName = $file->getClientOriginalName();
                $safeName = time() . '_' . preg_replace('/\s+/', '_', $originalName);

                // Store PDF
                $pdfPath = $file->storeAs('documents', $safeName, 'public');

                // ✅ THUMBNAIL
                if ($request->hasFile('thumbnail')) {

                    $thumbFile = $request->file('thumbnail');
                    $thumbName = time() . '_' . preg_replace('/\s+/', '_', $thumbFile->getClientOriginalName());

                    $thumbPath = $thumbFile->storeAs('thumbnails', $thumbName, 'public');
                } else {
                    $thumbPath = 'thumbnails/pdf.png';
                }

                // ✅ CREATE MEDIA RECORD
                MediaDocument::create([
                    'file_path' => $pdfPath,
                    'thumbnail' => $thumbPath,
                    'page_id' => $page->id,
                ]);
            }

            // ✅ MENUS
            if ($request->filled('menu_ids')) {
                $page->menus()->sync($request->menu_ids);
            }

            logActivity('created', $page, "Page '{$page->page_title}' created");

            return redirect()
                ->route('pages.index')
                ->with('success', 'Page created successfully!');
        } catch (Exception $e) {

            Log::error('Page Store Error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the page.');
        }
    }



    public function edit(Page $page)
    {
        try {
            $parents = Page::where('id', '!=', $page->id)
                ->orderBy('sort_order')
                ->get();

            $sliders = Slider::where('is_active', 1)
                ->orderBy('title')
                ->get();

            $menus = Menu::orderBy('name')->get();

            // Get one event per unique type

            // Get all events
            $events = Event::select('id', 'title')
                ->orderBy('title')
                ->get();

            $page->load(['menus', 'parent', 'slider', 'event']);

            return view('admin.pages.form', compact('page', 'parents', 'sliders', 'menus', 'events'));
        } catch (Exception $e) {
            Log::error('Page Edit Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to open page edit form.');
        }
    }

    public function update(Request $request, Page $page)
    {
        try {

            $data = $request->validate([
                'page_title' => 'required|string|max:255',
                'sub_title' => 'nullable|string|max:255',
                'body' => 'nullable',
                'parent_id' => 'nullable|integer|exists:pages,id',
                'slider_id' => 'nullable|integer|exists:sliders,id',
                'event_ids' => 'nullable|array',
                'event_ids.*' => 'integer|exists:events,id',
                'is_navbar' => 'boolean',
                'is_active' => 'boolean',
                'sort_order' => 'integer|nullable',
                'sidebar_page_ids' => 'nullable|array',
                'sidebar_page_ids.*' => 'integer|exists:pages,id',

                // ✅ SINGLE PDF
                'document' => 'nullable|mimes:pdf|max:204800',
            ]);

            $data['slug'] = Str::slug($request->page_title);
            $data['parent_id'] = $request->filled('parent_id') ? $request->parent_id : null;
            $data['slider_id'] = $request->filled('slider_id') ? $request->slider_id : null;
            $data['is_navbar'] = $request->input('is_navbar') == 1 ? 1 : 0;
            $data['is_active'] = $request->input('is_active') == 1 ? 1 : 0;

            // ✅ MULTIPLE PDF upload with ORIGINAL NAME
            if ($request->hasFile('document')) {

                $files = $request->file('document');

                // if single file, convert to array
                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $file) {

                    // Original name
                    $originalName = $file->getClientOriginalName();

                    // Clean filename (spaces, special chars fix)
                    $safeName = time() . '_' . preg_replace('/\s+/', '_', $originalName);

                    // Store using original name
                    $pdfPath = $file->storeAs('documents', $safeName, 'public');

                    if ($request->hasFile('thumbnail')) {

                        $thumbOriginal = $request->file('thumbnail')->getClientOriginalName();
                        $thumbName = time() . '_' . preg_replace('/\s+/', '_', $thumbOriginal);

                        $thumbPath = $request->file('thumbnail')->storeAs('thumbnails', $thumbName, 'public');
                    } else {
                        $thumbPath = 'thumbnails/pdf.png';
                    }
                    MediaDocument::create([
                        'file_path' => $pdfPath,
                        'thumbnail' => $thumbPath,
                        'page_id' => $page->id,
                    ]);
                }
            }

            $originalSlug = $data['slug'];
            $count = 1;
            while (Page::where('slug', $data['slug'])->where('id', '!=', $page->id)->exists()) {
                $data['slug'] = "{$originalSlug}-{$count}";
                $count++;
            }

            $data['event_ids'] = $request->input('event_ids', []);
            $page->update($data);
            $page->menus()->sync($request->menu_ids ?? []);

            logActivity('updated', $page, "Page '{$page->page_title}' updated");

            return redirect()->route('pages.index')->with('success', 'Page updated successfully!');
        } catch (Exception $e) {
            Log::error('Page Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong while updating the page.');
        }
    }


    public function destroy(Page $page)
    {
        try {
            $page->delete();
            logActivity('deleted', $page, "Page '{$page->page_title}' deleted");

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Page deleted successfully!'
                ]);
            }

            return redirect()->route('pages.index')->with('success', 'Page deleted successfully!');
        } catch (Exception $e) {
            Log::error('Page Delete Error: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete the page.'
                ], 500);
            }

            return back()->with('error', 'Failed to delete the page.');
        }
    }
    public function delete_document($id)
    {
        try {
            $doc = MediaDocument::findOrFail($id);

            // delete files from storage
            if (Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }
            if (Storage::disk('public')->exists($doc->thumbnail)) {
                Storage::disk('public')->delete($doc->thumbnail);
            }

            $doc->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
