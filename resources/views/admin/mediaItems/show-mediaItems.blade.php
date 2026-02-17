@extends('admin.layouts')
@section('content')
    <style>
        video {
            max-width: 100%;
            height: auto;
        }
    </style>
    @include('sweetalert::alert')

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="layout-page">
                <div class="card mt-5 shadow-sm rounded" style="margin: 31px;">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
                        <h5 class="card-title mb-0 text-md-start text-center">All Media Items</h5>

                        @can('gallerymanager add')
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('events.index') }}"> <button
                                        class="btn btn-outline-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                        data-bs-target="#addEventModal">

                                        <i class="bx bx-arrow-back icon-sm"></i>
                                        <span class="d-none d-sm-inline-block">Back to Event</span>
                                    </button></a>
                                <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                    data-bs-target="#addMediaModal">
                                    <i class="bx bx-plus icon-sm"></i>
                                    <span class="d-none d-sm-inline-block">Add Media Item</span>
                                </button>
                            </div>
                        @endcan
                    </div>


                    <div class="table-responsive">
                        {{-- Assuming $mediaItems is passed from the controller and is NOT grouped yet --}}
                        @php
                            // Grouping $mediaItems passed from controller (which must include 'images' and 'videos' relations)
                            $groupedMedia = $mediaItems->groupBy('event_id');
                        @endphp

                        <table class="table table-hover align-middle table-striped border-top" id="example">
                            <thead class="table-light">
                                <tr class="text-muted text-uppercase small">
                                    <th>Sr. No</th>
                                    <th>Event Name</th>
                                    <th>Files</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupedMedia as $eventId => $items)
                                    @php $event = $items->first()->event; @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $event?->title ?? '—' }}</td>

                                        <td>
                                            @php
                                                // ✅ FIX 1: Calculate Total Files (Images + Videos)
                                                $totalImages = $items->sum(fn($item) => $item->images->count());
                                                $totalVideos = $items->sum(fn($item) => $item->videos->count());
                                                $totalFiles = $totalImages + $totalVideos;
                                            @endphp

                                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#mediaModal-{{ $eventId }}">
                                                View {{ $totalFiles }} {{ Str::plural('File', $totalFiles) }}
                                            </button>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                @can('gallerymanager edit')
                                                    <a href="{{ route('media-items.edit', $items->first()->id) }}"
                                                        class="text-primary fs-5" title="Edit">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                @endcan
                                                @can('gallerymanager delete')
                                                    <form action="{{ route('media-items.destroy', $items->first()->id) }}"
                                                        method="POST" class="ajax-delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger fs-5 p-0 m-0">
                                                            <i class='bx bx-trash'></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Modal for viewing event media --}}
                                    <div class="modal fade" id="mediaModal-{{ $eventId }}" tabindex="-1"
                                        aria-labelledby="mediaModalLabel-{{ $eventId }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="mediaModalLabel-{{ $eventId }}">
                                                        {{ $event?->title ?? 'Event Media' }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body">
                                                    @php
                                                        // ✅ FIX 2: Collect all images and videos into separate, flat collections
                                                        $allImages = collect();
                                                        $allVideos = collect();

                                                        foreach ($items as $item) {
                                                            $allImages = $allImages->merge($item->images);
                                                            $allVideos = $allVideos->merge($item->videos);
                                                        }
                                                    @endphp

                                                    @if ($allImages->isNotEmpty())
                                                        <h6 class="text-uppercase text-muted mb-3">Images
                                                            ({{ $allImages->count() }})
                                                        </h6>
                                                        <div class="row g-3 mb-4">
                                                            @foreach ($allImages as $image)
                                                                <div class="col-6 col-md-4 col-lg-3 text-center">
                                                                    <div style="height: 100px; overflow: hidden;">
                                                                        <img src="{{ asset('storage/' . $image->file_path) }}"
                                                                            class="img-fluid rounded shadow-sm border w-100 h-100"
                                                                            alt="{{ $image->title ?? 'Image' }}"
                                                                            style="object-fit: cover;">
                                                                    </div>
                                                                    <div class="small text-muted mt-1">
                                                                        {{ $image->title ?? 'Untitled' }}
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if ($allVideos->isNotEmpty())
                                                        <h6 class="text-uppercase text-muted mt-4 mb-3">Videos
                                                            ({{ $allVideos->count() }})</h6>
                                                        <div class="row g-3">
                                                            @foreach ($allVideos as $video)
                                                                <div class="col-6 col-md-4 col-lg-3 text-center">
                                                                    <video width="100%" controls playsinline preload="metadata"
                                                                        class="rounded shadow-sm border">
                                                                        {{-- ✅ FIX 3: Use the video's actual path for the source tag
                                                                        --}}
                                                                        <source src="{{ asset('storage/' . $video->file_path) }}"
                                                                            type="video/{{ strtolower(pathinfo($video->file_path, PATHINFO_EXTENSION)) }}">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                    <div class="small text-muted mt-1">
                                                                        {{ $video->title ?? 'Untitled' }}
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if ($allImages->isEmpty() && $allVideos->isEmpty())
                                                        <p class="text-center text-muted">No media files available for
                                                            this event.</p>
                                                    @endif

                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Add Media Modal -->
                <div class="modal fade" id="addMediaModal" tabindex="-1" aria-labelledby="addMediaModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action="{{ route('media-items.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addMediaModalLabel">Add New Media Item</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    {{-- Title --}}
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            placeholder="Enter media title (optional)">
                                    </div>



                                    {{-- Event --}}
                                    <div class="mb-3">
                                        <label class="form-label">Event</label>
                                        <select name="event_id" id="event_id" class="form-select">
                                            <option value="">— Select Event —</option>
                                            @foreach ($events as $event)
                                                <option value="{{ $event->id }}">{{ $event->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- File Upload --}}
                                    <div class="mb-3">
                                        <label class="form-label">Upload Files (multiple allowed)</label>
                                        <input type="file" name="files[]" class="form-control" multiple required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Add Media</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
@endsection