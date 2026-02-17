<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Slider;
use App\Models\SliderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:slider management')->only('index');
        $this->middleware('permission:slider add')->only('create', 'store');
        $this->middleware('permission:slider edit')->only('edit', 'update');
        $this->middleware('permission:slider delete')
            ->only('destroy', 'deleteImage');
    }

    public function index()
    {
        $sliders = Slider::with('images')->latest()->get();
        return view('admin.sliders.show-sliders', compact('sliders'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'nullable|string|max:255',
                'images' => 'required',
                'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
                'is_active' => 'nullable|boolean',
            ]);

            // Create slider (title + active flag)
            $slider = Slider::create([
                'title' => $request->title ?: null,
                'is_active' => $request->has('is_active') ? $request->is_active : false,
            ]);

            // Store uploaded images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('sliders', 'public');
                    SliderImage::create([
                        'slider_id' => $slider->id,
                        'image' => $path,
                    ]);
                }
            }
            logActivity('created', $slider, "Slider '{$slider->title}' created");
            return redirect()->route('sliders.index')->with('success', 'Slider added successfully!');
        } catch (Exception $e) {
            Log::error('Slider Store Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong while saving the slider.');
        }
    }

    public function edit(Slider $slider)
    {
        try {
            $slider->load('images'); // eager load images for preview
            return view('admin.sliders.form', compact('slider'));
        } catch (Exception $e) {
            Log::error('Slider Edit Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while loading the slider.');
        }
    }

    public function update(Request $request, Slider $slider)
    {
        try {
            $request->validate([
                'title' => 'nullable|string|max:255',
                'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
                'is_active' => 'nullable|boolean',
            ]);

            // Update title + active flag
            $slider->update([
                'title' => $request->title ?: null,
                'is_active' => (bool) $request->input('is_active', false),

            ]);

            // Add new images (if uploaded)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $path = $img->store('sliders', 'public');
                    SliderImage::create([
                        'slider_id' => $slider->id,
                        'image' => $path,
                    ]);
                }
            }
            logActivity('updated', $slider, "Slider '{$slider->title}' updated");
            return redirect()->route('sliders.index')->with('success', 'Slider updated successfully!');
        } catch (Exception $e) {
            Log::error('Slider Update Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update slider. Please try again.');
        }
    }

    public function destroy(Slider $slider)
    {
        try {
            $slider->delete();
            logActivity('deleted', $slider, "Slider '{$slider->title}' deleted");

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Slider deleted successfully!'
                ]);
            }

            return redirect()->route('sliders.index')->with('success', 'Slider deleted successfully!');
        } catch (Exception $e) {
            Log::error('Slider Delete Error: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete the slider. Please try again.'
                ], 500);
            }

            return back()->with('error', 'Failed to delete the slider. Please try again.');
        }
    }

    public function deleteImage($id)
    {
        try {
            $image = SliderImage::findOrFail($id);

            if (Storage::exists('public/' . $image->image_path)) {
                Storage::delete('public/' . $image->image_path);
            }

            $image->delete();

            return response()->json(['status' => 'success', 'message' => 'Image deleted']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error deleting image']);
        }
    }
}
