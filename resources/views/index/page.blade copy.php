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
                
                        {!! $page->body !!}
                        @if (Str::lower($page->page_title) === 'magazine & yearbook' && $page->mediaDocuments->count())
                            <div class="row justify-content-center">
                                @foreach ($page->mediaDocuments as $doc)
                                    <div class="col-md-6 col-sm-12 d-flex justify-content-center mb-4">
                                        <div class="card shadow-sm text-center flip-card">
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank">

                                                <img src="{{ asset('storage/' . $doc->thumbnail) }}"
                                                    class="img-fluid flip-thumb" alt="PDF">
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if (!empty($page->event_ids) && $events->count())
                            <section class="container my-5">
                                <div class="row justify-content-center">
                                    @foreach ($events as $event)
                                        <div class="col-6 col-sm-4 col-md-3 col-lg-3 text-center mb-4">
                                            <div class="event-box">
                                                <a href="{{ url('events/' . Str::slug($event->title)) }}">
                                                    <img src="{{ asset('storage/' . $event->thumbnail) }}"
                                                        alt="{{ $event->title }}" class="img-fluid rounded shadow-sm"
                                                        style="width:150px; height:150px; object-fit:cover;">
                                                </a>
                                                <h5 class="mt-2 text-dark" style="font-size:14px; line-height:1.3;">
                                                    {{ Str::title($event->title) }}
                                                </h5>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        @endif

                        @if ($slider && $slider->images->count())
                            <div id="pageSlider_{{ $slider->id }}" class="carousel slide carousel-fade mt-5 shadow"
                                data-ride="carousel" data-interval="2000">
                                <div class="carousel-inner rounded">
                                    @foreach ($slider->images as $key => $image)
                                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/' . $image->image) }}" class="d-block w-100"
                                                alt="{{ $image->title ?? 'Slide ' . ($key + 1) }}"
                                                style="height: 450px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Indicators -->
                                <div class="carousel-indicators mt-3">
                                    @foreach ($slider->images as $key => $image)
                                        <button type="button" data-bs-target="#pageSlider_{{ $slider->id }}"
                                            data-bs-slide-to="{{ $key }}"
                                            class="{{ $key === 0 ? 'active' : '' }}">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                @php
                    $menu = $page->menus->first();
                @endphp
                @if ($menu && $menu->pages->count())
                    <div class="col-lg-3 col-md-4 mt-4 mt-md-0">
                        <aside class="sidebar">
                            <ul class="list-unstyled" style="line-height: 1.8;">
                                @foreach ($menu->pages as $item)
                                    <li class="d-flex align-items-start mb-2">
                                        <b><i class="bi bi-check2 me-2 mt-1" style="color:#f67c78;"></i></b>
                                        @if (Str::contains($item->sub_title, '#'))
                                            <span class="sidebar-link text-muted">
                                                {{ $item->page_title }}
                                            </span>
                                        @else
                                            <b>
                                                <a href="{{ $item->document ? asset('storage/' . $item->document) : route('page', $item->sub_title) }}"
                                                    class="sidebar-link {{ $item->id == $page->id ? 'active' : '' }}"
                                                    target="{{ $item->document ? '_blank' : '_self' }}">
                                                    {{ $item->page_title }}
                                                </a>
                                            </b>
                                        @endif
                                    </li>
                                @endforeach

                                {{-- ✅ Add Static Contact Page Link --}}
                                <li class="d-flex align-items-start mb-2">
                                    <b><i class="bi bi-check2 me-2 mt-1" style="color:#f67c78;"></i></b>
                                    <b>
                                        <a href="{{ url('contact-us') }}"
                                            class="sidebar-link {{ request()->is('contact') ? 'active' : '' }}">
                                            Contact Us
                                        </a>
                                    </b>
                                </li>
                            </ul>
                        </aside>
                    </div>
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
