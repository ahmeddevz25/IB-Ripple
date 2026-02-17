<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Page;
use App\Models\Event;
use App\Models\Slider;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch navbar pages
        $navPages = Page::where('is_navbar', 1)
            ->whereNull('parent_id')
            ->with([
                'children' => function ($query) {
                    $query->where('is_navbar', 1)->orderBy('sort_order');
                }
            ])
            ->orderBy('sort_order')
            ->get();

        $sliders = Slider::with('images')
            ->where('is_active', 1)
            ->orderBy('id', 'asc')
            ->get();

        $pages = Page::where('is_active', 1)->get();

        $subTitles = $pages->pluck('sub_title');

        return view('index.index', compact('navPages', 'sliders', 'subTitles'));
    }

    public function page($sub_title)
    {
        $page = Page::with([
            'children.children.children',
            'menus.pages' => function ($q) {
                $q->with([
                    'children.children.children', // ✅ get nested children for all menu pages
                ]);
            },
        ])
            ->where('sub_title', $sub_title)
            ->firstOrFail();
        // dd($page);
        // ✅ Load menu relationships safely
        if ($page->menus && $page->menus->count()) {
            $page->menus->load('pages.children.children');
        }

        // ✅ Navbar pages
        $navPages = Page::where('is_navbar', 1)->orderBy('sort_order')->get();

        // ✅ Slider (if assigned)
        $slider = null;
        if ($page->slider_id) {
            $slider = Slider::with('images')
                ->where('id', $page->slider_id)
                ->where('is_active', 1)
                ->first();
        }

        // ✅ Fetch all events linked to this page
        $events = collect();
        if ($page->page_title === 'IB Ripple Conference 2025 - Highlights') {
            // Fetch all events for the highlight page specifically
            $events = Event::with(['mediaItems.images', 'mediaItems.videos'])->get();
        } elseif (!empty($page->event_ids)) {
            $events = Event::whereIn('id', $page->event_ids)
                ->with(['mediaItems.images', 'mediaItems.videos'])
                ->get();
        }
        // dd($events);
        return view('index.page', compact('page', 'navPages', 'slider', 'events'));
    }

    public function eventmedia($slug)
    {
        // ✅ Fetch the clicked event with its media
        $event = Event::where('slug', $slug)
            ->with(['mediaItems.images', 'mediaItems.videos'])
            ->firstOrFail();

        // ✅ Try to find the linked page containing this event’s ID inside event_ids JSON
        $page = Page::with([
            'children.children.children',
            'menus.pages' => function ($q) {
                $q->with(['children.children.children']);
            },
        ])
            ->whereJsonContains('event_ids', (string) $event->id)
            ->first();

        // dd($page);
        // ✅ Safe menu loading
        if ($page && optional($page->menus)->count()) {
            $page->menus->load('pages.children.children');
        }

        // ✅ Navbar pages
        $navPages = Page::where('is_navbar', 1)
            ->orderBy('sort_order')
            ->get();

        // ✅ Slider
        $slider = null;
        if ($page && $page->slider_id) {
            $slider = Slider::with('images')
                ->where('id', $page->slider_id)
                ->where('is_active', 1)
                ->first();
        }

        return view('index.event-media', compact('event', 'page', 'navPages', 'slider'));
    }

    public function contactus()
    {
        return view('index.contactus');
    }
    public function submit(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:120'],
                'email' => ['required', 'email', 'max:190'],
                'phone' => ['nullable', 'string', 'max:50'],
                'subject' => ['nullable', 'string', 'max:190'],
                'message' => ['required', 'string', 'max:5000'],
            ]);

            $contact = ContactMessage::create($data);

            // Mail sending removed as per request
            // Mail::to(config('mail.contact_admin'))->send(new ContactSubmitted($contact));

            return redirect()->back()->with('success', 'Your message has been sent successfully!');
        } catch (Exception $e) {
            Log::error('Error submitting contact form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while submitting your message.');
        }
    }

    public function newsletterSubmit(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => ['required', 'email', 'max:190'],
            ]);

            // Create ContactMessage with only email
            ContactMessage::create([
                'email' => $data['email'],
                'name' => null, // Optional as per request
                'phone' => null,
                'subject' => 'Newsletter Subscription',
                'message' => 'User subscribed to newsletter.',
            ]);

            return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter!');
        } catch (Exception $e) {
            Log::error('Error submitting newsletter: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while subscribing.');
        }
    }

    public function cacheclear()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return redirect()->back()->with('success', 'Cache Cleared successfully!');
    }
}
