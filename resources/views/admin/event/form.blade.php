@extends('admin.layouts')
@section('content')
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="layout-page">

                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4">
                        <span class="text-muted fw-light">Update</span> Event
                    </h4>

                    <div class="row">
                        <div class="col-xl">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h2>Update Event</h2>

                                    {{-- Success Message --}}
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    {{-- Validation Errors --}}
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('events.update', $event->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        {{-- Event Title --}}
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Event Title</label>
                                            <input type="text" name="title" class="form-control" id="title"
                                                value="{{ old('title', $event->title) }}" placeholder="Enter event title"
                                                required>
                                        </div>

                                        {{-- Event Date --}}
                                        <div class="mb-3">
                                            <label for="event_date" class="form-label">Event Date</label>
                                            <input type="date" name="event_date" class="form-control"
                                                value="{{ old('event_date', $event->event_date ? $event->event_date->format('Y-m-d') : '') }}">
                                        </div>

                                        {{-- Thumbnail --}}
                                        <div class="mb-3">
                                            <label for="thumbnail" class="form-label">Thumbnail</label>
                                            <input type="file" name="thumbnail" class="form-control" id="thumbnail">
                                            @if ($event->thumbnail)
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $event->thumbnail) }}" width="120"
                                                        class="img-thumbnail" alt="Current Thumbnail">
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Submit --}}
                                        <button type="submit" class="btn btn-primary">Update Event</button>
                                        <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
@endsection