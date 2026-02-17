<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\MediaItem;
use App\Models\MediaImage;
use App\Models\MediaVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MediaItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:gallerymanager')->only('index', 'show');
        $this->middleware('permission:gallerymanager add')->only('create', 'store');
        $this->middleware('permission:gallerymanager edit')->only('edit', 'update');
        $this->middleware('permission:gallerymanager delete')
            ->only('destroy', 'deleteFile');
    }

    /** Show all media items **/
    // app/Http/Controllers/YourController.php

    public function index()
    {
        // ✅ Load both 'images' (MediaImage) AND 'videos' (MediaVideo) relations
        $mediaItems = MediaItem::with(['event', 'images', 'videos'])->latest()->get(); // <-- 'videos' relation added here

        // Grouping media items by event_id for the table display
        $groupedMedia = $mediaItems->groupBy('event_id');

        $events = Event::orderBy('event_date', 'desc')->get();

        // Aapko 'mediaItems' ke bajaye 'groupedMedia' pass karna chahiye, jaisa aapka blade use kar raha hai.
        return view('admin.mediaItems.show-mediaItems', compact('mediaItems', 'groupedMedia', 'events'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'title' => 'nullable|string|max:150',
            'files' => 'required|array|min:1',
            'files.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:512000',
        ]);

        try {
            DB::transaction(function () use ($request) {

                // Create Media Item
                $mediaItem = MediaItem::create([
                    'event_id' => $request->event_id,
                ]);

                // Store Uploaded Files
                foreach ($request->file('files') as $index => $file) {
                    $mime = $file->getMimeType();
                    $isVideo = str_starts_with($mime, 'video');

                    Log::info("📂 Processing file #{$index}", [
                        'original_name' => $file->getClientOriginalName(),
                        'mime' => $mime,
                    ]);

                    if (!$isVideo) {
                        $path = $file->store('media_images', 'public');
                        MediaImage::create([
                            'media_item_id' => $mediaItem->id,
                            'title' => $request->title,
                            'file_path' => $path,
                        ]);
                        Log::info("🖼️ Image stored", ['path' => $path]);
                    } else {
                        $path = $file->store('media_videos', 'public');
                        MediaVideo::create([
                            'media_item_id' => $mediaItem->id,
                            'title' => $request->title,
                            'file_path' => $path,
                        ]);
                        Log::info("🎬 Video stored", ['path' => $path]);
                    }
                }
            });

            return redirect()
                ->route('media-items.index')
                ->with('success', 'Media item created successfully!');
        } catch (\Exception $e) {

            Log::error('❌ Error in MediaItemController@store', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong while uploading media.');
        }
    }


    /** Edit existing media item **/
    public function edit(MediaItem $mediaItem)
    {
        $events = Event::all();
        $mediaItem->load('images', 'videos', 'event');

        return view('admin.mediaItems.form', compact('mediaItem', 'events'));
    }

    /** Update existing media item **/
    public function update(Request $request, MediaItem $mediaItem)
    {
        $request->validate([
            'event_id' => 'nullable|exists:events,id',
            'file' => 'nullable',
            'file.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:204800',
            'titles' => 'array',
            'titles.*' => 'nullable|string|max:150',
            'title' => 'nullable|string|max:150',
        ]);

        // Update main item
        $mediaItem->update([
            'event_id' => $request->event_id,
        ]);

        // Update titles if provided
        if ($request->has('titles')) {
            foreach ($request->titles as $type => $items) {
                if (!is_array($items))
                    continue; // Safety check

                foreach ($items as $id => $title) {
                    if ($type === 'image') {
                        $model = MediaImage::find($id);
                    } elseif ($type === 'video') {
                        $model = MediaVideo::find($id);
                    } else {
                        continue;
                    }

                    if ($model && $model->media_item_id === $mediaItem->id) {
                        $model->update(['title' => $title]);
                    }
                }
            }
        }

        // Update shared title (for all items)
        if ($request->filled('title')) {
            foreach ($mediaItem->images as $file) {
                $file->update(['title' => $request->title]);
            }
            foreach ($mediaItem->videos as $file) {
                $file->update(['title' => $request->title]);
            }
        }

        // Handle new file uploads
        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $mime = $file->getMimeType();
                $isVideo = str_starts_with($mime, 'video');

                if (!$isVideo) {
                    MediaImage::create([
                        'media_item_id' => $mediaItem->id,
                        'title' => $request->title ?: null,
                        'file_path' => $file->store('media_images', 'public'),
                    ]);
                } else {
                    MediaVideo::create([
                        'media_item_id' => $mediaItem->id,
                        'title' => $request->title ?: null,
                        'file_path' => $file->store('media_videos', 'public'),
                    ]);
                }
            }
        }

        return redirect()->route('media-items.index')
            ->with('success', 'Media item updated successfully!');
    }

    /** Delete full media item (and all its images) **/
    public function destroy(MediaItem $mediaItem)
    {
        // delete all attached files
        foreach ($mediaItem->images as $image) {
            if (Storage::disk('public')->exists($image->file_path)) {
                Storage::disk('public')->delete($image->file_path);
            }
            $image->delete();
        }

        $mediaItem->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Media item deleted successfully!'
            ]);
        }

        return back()->with('success', 'Media item deleted successfully!');
    }

    /** AJAX Delete Single Image **/
    // app/Http/Controllers/MediaItemController.php

    public function deleteFile(string $type, int $id)
    {
        // Determine the model based on the 'type' parameter
        if ($type === 'image') {
            $model = \App\Models\MediaImage::class;
        } elseif ($type === 'video') {
            $model = \App\Models\MediaVideo::class;
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid file type.'], 400);
        }

        // Find the file record (Image or Video)
        $file = $model::find($id);

        if (!$file) {
            return response()->json(['status' => 'error', 'message' => ucfirst($type) . ' not found.'], 404);
        }

        // Delete file from storage
        if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        // Delete DB record
        $file->delete();

        return response()->json(['status' => 'success', 'message' => ucfirst($type) . ' deleted successfully.']);
    }
}
