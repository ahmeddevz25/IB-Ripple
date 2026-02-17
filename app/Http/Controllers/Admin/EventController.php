<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:gallerymanager')->only('index', 'show');
        $this->middleware('permission:gallerymanager add')->only('create', 'store');
        $this->middleware('permission:gallerymanager edit')->only('edit', 'update');
        $this->middleware('permission:gallerymanager delete')->only('destroy');
    }

    public function index()
    {
        $events = Event::get();
        return view('admin.event.show-events', compact('events'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:150',
            'event_date' => 'nullable|date',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('events', 'public');
        }

        Event::create($data);

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        return view('admin.event.form', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => 'required|string|max:150',
            'event_date' => 'nullable|date',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data['slug'] = Str::slug($request->title);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully!'
            ]);
        }

        return back()->with('success', 'Event deleted successfully!');
    }
}
