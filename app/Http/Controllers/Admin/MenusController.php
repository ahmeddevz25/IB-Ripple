<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Menu;
use App\Models\Page;
use App\Models\MediaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MenusController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:menus management')->only('index');
        $this->middleware('permission:menus add')->only('create', 'store');
        $this->middleware('permission:menus edit')->only('edit', 'update');
        $this->middleware('permission:menus delete')->only('destroy');
    }

    public function index()
    {
        $menus = Menu::with('pages')->orderBy('name')->get();

        // Load all top-level pages with their children
        $pages = Page::with('children.children.children.children')
            ->whereNull('parent_id')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->get();

        return view('admin.menus.show-menus', compact('menus', 'pages'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name'       => 'required|string|max:255|unique:menus,name',
                'is_active'  => 'boolean',
                'page_ids'   => 'nullable|array',
                'page_ids.*' => 'integer|exists:pages,id',
            ]);

            $menu = Menu::create([
                'name'      => $data['name'],
                'is_active' => $request->has('is_active') ? $request->is_active : 0,
            ]);

            if (! empty($data['page_ids'])) {
                $menu->pages()->sync($data['page_ids']);
            }

            return redirect()->route('menus.index')->with('success', 'Menu created successfully!');
        } catch (Exception $e) {
            Log::error('Menu Store Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong while creating the menu.');
        }
    }

    public function edit(Menu $menu)
    {
        $pages = Page::where('is_active', 1)->orderBy('page_title')->get();
        $menu->load('pages');
        return view('admin.menus.form', compact('menu', 'pages'));
    }

    public function update(Request $request, Menu $menu)
    {
        try {
            $data = $request->validate([
                'name'       => 'required|string|max:255|unique:menus,name,' . $menu->id,
                'is_active'  => 'boolean',
                'page_ids'   => 'nullable|array',
                'page_ids.*' => 'integer|exists:pages,id',
            ]);

            $menu->update([
                'name'      => $data['name'],
                'is_active' => $request->has('is_active') ? $request->is_active : 0,
            ]);

            $menu->pages()->sync($data['page_ids'] ?? []);

            return redirect()->route('menus.index')->with('success', 'Menu updated successfully!');
        } catch (Exception $e) {
            Log::error('Menu Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong while updating the menu.');
        }
    }

    public function destroy(Menu $menu)
    {
        try {
            $menu->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Menu deleted successfully!'
                ]);
            }

            return redirect()->route('menus.index')->with('success', 'Menu deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Menu Delete Error: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete the menu.'
                ], 500);
            }

            return back()->with('error', 'Failed to delete the menu.');
        }
    }
}
