@extends('index.layout')
@section('content')
    @php
        // Sidebar: show children or siblings dynamically
        $sidebarPages = $page->children->count()
            ? $page->children
            : ($page->parent
                ? $page->parent->children
                : collect());
    @endphp

    <div class="container-fluid mt-4 mb-3">
        @if ($page->body)
            {!! $page->body !!}
        @elseif($page->page_title !== 'IB Ripple Conference 2025 - Highlights')
            <div class="col-12 text-center py-5">
                <h3 class="text-muted">Oops! That page can’t be found.</h3>
            </div>
        @endif
        @if($page->page_title === 'IB Ripple Conference 2025 - Highlights')
            @if ($events->count())
                <div class="position-relative overflow-hidden">
                    <section class="container my-5">
                        <!-- Event Tabs Navigation -->
                        <ul class="nav nav-pills justify-content-center mb-5" id="eventTabs" role="tablist">
                            @foreach($events as $index => $event)
                                <li class="nav-item mx-2" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }} px-4 py-2" id="tab-{{ $event->id }}"
                                        data-bs-toggle="pill" data-bs-target="#content-{{ $event->id }}" type="button" role="tab"
                                        aria-controls="content-{{ $event->id }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                        style="border-radius: 0; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">
                                        {{ $event->title }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Event Tabs Content -->
                        <div class="tab-content" id="eventTabsContent">
                            @foreach($events as $index => $event)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="content-{{ $event->id }}"
                                    role="tabpanel" aria-labelledby="tab-{{ $event->id }}">

                                    <div class="row">
                                        @php
                                            // Collect all images for this event
                                            $allImages = collect();
                                            if ($event->mediaItems) {
                                                foreach ($event->mediaItems as $item) {
                                                    if ($item->images) {
                                                        foreach ($item->images as $img) {
                                                            $allImages->push($img);
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp

                                        @if($allImages->count() > 0)
                                            @foreach($allImages as $imgIndex => $img)
                                                <div class="col-md-3 col-sm-6 mb-4">
                                                    <div class="gallery-item position-relative overflow-hidden shadow-sm"
                                                        style="cursor: pointer;">
                                                        <a href="{{ asset('storage/' . $img->file_path) }}"
                                                            data-fancybox="gallery-{{ $event->id }}"
                                                            data-caption="{{ $img->title ?? 'Event Image' }}">
                                                            <img src="{{ asset('storage/' . $img->file_path) }}" class="img-fluid w-100"
                                                                alt="{{ $img->title ?? 'Event Image' }}"
                                                                style="object-fit: cover; transition: transform 0.3s ease;">
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12 text-center py-5">
                                                <p class="text-muted">No images available for this event highlighting.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Gallery Modal Removed --}}
                            @endforeach
                        </div>
                        <style>
                            /* Nav Pills Styling */
                            .nav-pills .nav-link {
                                background-color: #f1f2f3;
                                /* Light Grey for inactive */
                                color: #666;
                                /* Grey text */
                                border: none;
                                transition: all 0.3s ease;
                            }

                            .nav-pills .nav-link:hover {
                                background-color: #e2e6ea;
                                color: #333;
                            }

                            .nav-pills .nav-link.active {
                                background-color: #00552e !important;
                                /* IB Green */
                                color: #fff !important;
                            }

                            /* Gallery Hover Effect */
                            .gallery-item:hover img {
                                transform: scale(1.05);
                                transition: transform 0.3s ease;
                            }

                            .gallery-item:hover {
                                background-color: transparent !important;
                            }

                            /* Fancybox Customization */
                            .fancybox__content {
                                height: 75% !important;
                                width: 90% !important;
                                margin: auto !important;
                                max-width: 90% !important;
                                max-height: 90% !important;
                            }

                            .fancybox__image {
                                max-width: 100%;
                                max-height: 100%;
                                object-fit: contain;
                                width: 100%;
                                height: 100%;
                                transform: scale(1);
                                /* Ensure no shrinking */
                            }

                            /* Ensure slide itself takes full space */
                            .fancybox__slide {
                                padding: 0 !important;
                                /* Remove padding */
                            }
                        </style>
                    </section>
                </div>
            @endif
        @endif
    </div>
    <style>
        .content-body p {
            color: #333;
            line-height: 1.9;
            margin-bottom: 1rem;
        }

        /* ===== Breadcrumb ===== */
        .breadcrumb-link,
        .breadcrumb-current {
            color: #7c7c7c;
            transition: color 0.3s ease;
        }

        .breadcrumb-link:hover {
            color: #f44336;
        }

        /* ===== Event Box ===== */
        .event-box a img {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .event-box a:hover img {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .event-box h5 {
            font-weight: 500;
            margin-top: 8px;
            text-align: center;
            word-wrap: break-word;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.08);
        }

        .sidebar-link {
            text-decoration: none;
            color: #7c7c7c;
            font-size: 14px;
            font-weight: bold;
            transition: color 0.3s ease;
            display: block;
            padding: 6px 0;
        }

        .sidebar-link:hover {
            color: #f67c78;
        }

        .sidebar-link.active {
            color: #f67c78 !important;
        }

        .bi-check2 {
            color: #f67c78;
            font-weight: bold;
            font-size: 16px;
            margin-right: 6px;
        }

        /* ===== Responsive Design ===== */
        @media (max-width: 992px) {
            .mb-5 {
                margin: 60px 30px !important;
            }

            .py-5 {
                padding: 30px 20px !important;
            }

            .content-body {
                font-size: 14px;
            }

            .sidebar {
                margin-top: 30px;
            }

            .carousel-item img {
                height: 250px !important;
            }
        }

        @media (max-width: 576px) {
            .py-5 {
                padding: 20px 15px !important;
            }

            h2 {
                font-size: 24px !important;
            }

            .content-body {
                font-size: 13px;
                line-height: 1.7;
            }

            .event-box img {
                width: 100px !important;
                height: 100px !important;
            }

            .sidebar {
                padding: 15px;
                text-align: center;
            }

            .sidebar-link {
                font-size: 13px;
                display: inline-block;
            }

            .row {
                flex-direction: column;
            }
        }

        .flip-card {
            perspective: 1000px;
        }

        .flip-thumb {
            width: 100%;
            object-fit: cover;
            border-radius: 6px;
            transition: transform 0.6s ease, box-shadow 0.3s ease;
            transform-origin: left center;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            background: #fff;
        }

        .flip-card:hover .flip-thumb {
            transform: rotateY(-18deg) scale(1.04);
            box-shadow: 18px 15px 35px rgba(0, 0, 0, 0.3);
        }
    </style>

@endsection