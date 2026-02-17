@extends('admin.layouts')
@section('content')
    <html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
        data-assets-path="../assets/" data-template="vertical-menu-template-free">

    <body>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <div class="layout-page">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">Update</span> Media Item
                        </h4>

                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <form action="{{ route('media-items.update', $mediaItem->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            {{-- Title --}}
                                            {{-- Title --}}
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Title</label>
                                                @php
                                                    // MediaItem ke type ke hisaab se title get karein
                                                    $currentTitle = '';
                                                    if ($mediaItem->type == 'image' && $mediaItem->images->first()) {
                                                        $currentTitle = $mediaItem->images->first()->title;
                                                    } elseif (
                                                        $mediaItem->type == 'video' &&
                                                        $mediaItem->videos->first()
                                                    ) {
                                                        $currentTitle = $mediaItem->videos->first()->title;
                                                    }
                                                @endphp
                                                <input type="text" name="title" id="title"
                                                    value="{{ old('title', $currentTitle) }}" class="form-control"
                                                    placeholder="Enter media title">
                                            </div>

                                            {{-- Type Removed --}}

                                            {{-- Event --}}
                                            <div class="mb-3">
                                                <label for="event_id" class="form-label">Event</label>
                                                <select name="event_id" id="event_id" class="form-select">
                                                    <option value="">— None —</option>
                                                    @foreach ($events as $event)
                                                        <option value="{{ $event->id }}"
                                                            {{ $mediaItem->event_id == $event->id ? 'selected' : '' }}>
                                                            {{ $event->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- File Upload Block --}}
                                            <div class="mb-3">
                                                <label for="file" class="form-label">Add New Files (optional)</label>
                                                <input type="file" name="file[]" id="file" class="form-control"
                                                    multiple>

                                                {{-- Current Files Display Block --}}
                                                @php
                                                    // Images aur Videos dono ko combine karke ek collection banao
                                                    $allFiles = collect()
                                                        ->merge($mediaItem->images)
                                                        ->merge($mediaItem->videos);
                                                @endphp

                                                @if ($allFiles->count())
                                                    <div class="mb-4 mt-3">
                                                        <p class="fw-semibold mb-2">
                                                            Current Files ({{ $allFiles->count() }})
                                                        </p>
                                                        <div class="row g-3">
                                                            {{-- ✅ $allFiles ko loop karein --}}
                                                            @foreach ($allFiles as $file)
                                                                @php
                                                                    // Check karein ke yeh file kis model (MediaImage ya MediaVideo) se aayi hai
                                                                    $isImageModel =
                                                                        get_class($file) ===
                                                                        \App\Models\MediaImage::class;
                                                                    $modelType = $isImageModel ? 'image' : 'video';
                                                                @endphp

                                                                <div class="col-2 col-md-2 col-lg-2 position-relative media-box"
                                                                    id="{{ $modelType }}-media-{{ $file->id }}">

                                                                    {{-- Delete Button: data-id aur data-type set hai --}}
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-media"
                                                                        data-id="{{ $file->id }}"
                                                                        data-type="{{ $modelType }}"
                                                                        title="Delete this file">
                                                                        <i class="bx bx-x"></i>
                                                                    </button>

                                                                    {{-- Image / Video Preview --}}
                                                                    @if ($isImageModel)
                                                                        <img src="{{ asset('storage/' . $file->file_path) }}"
                                                                            class="img-fluid rounded shadow-sm border w-100"
                                                                            style="height: 80px; object-fit: cover;"
                                                                            alt="{{ $file->title ?? 'Image' }}">
                                                                    @else
                                                                        <video width="100%"
                                                                            class="rounded shadow-sm border" controls>
                                                                            <source
                                                                                src="{{ asset('storage/' . $file->file_path) }}"
                                                                                type="video/{{ strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION)) }}">
                                                                        </video>
                                                                    @endif

                                                                    {{-- Editable Title Field: Name attribute mein model type shamil hai --}}
                                                                    <input type="text"
                                                                        name="titles[{{ $modelType }}][{{ $file->id }}]"
                                                                        value="{{ old("titles.{$modelType}.{$file->id}", $file->title) }}"

                                                                        class="form-control form-control-sm mt-2 text-center"
                                                                        placeholder="Enter file title">

                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="submit" class="btn btn-primary">Update Media</button>
                                                <a href="{{ route('media-items.index') }}"
                                                    class="btn btn-secondary">Cancel</a>
                                            </div>
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

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const csrfToken = '{{ csrf_token() }}';

                // ✅ Check File Count Limit (Server Default: 20)
                const fileInput = document.getElementById('file');
                if (fileInput) {
                    fileInput.addEventListener('change', function() {
                        if (this.files.length > 20) {
                            alert('⚠️ Warning: standard server limit is 20 files per upload.\n\nYou selected ' + this.files.length + ' files.\nOnly the first 20 might be saved.\n\nPlease upload in batches of 20 or less.');
                        }
                    });
                }

                document.querySelectorAll('.delete-media').forEach(button => {
                    button.addEventListener('click', function() {
                        const fileId = this.dataset.id;
                        const fileType = this.dataset.type;
                        const box = document.getElementById(fileType + '-media-' + fileId);

                        // ✅ New Unified Delete URL (Using dynamic type and id)
                        const deleteUrl = `{{ url('media') }}/${fileType}/${fileId}`;
                        // Agar aap ne URL mein '/admin' use kiya hai to woh yahan bhi lagana hoga.

                        if (!confirm(`Are you sure you want to delete this ${fileType}?`)) {
                            return;
                        }

                        // ... (fetch and deletion logic is the same as before) ...
                        fetch(deleteUrl, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    box.remove();
                                    toastr.success(
                                        `${fileType.charAt(0).toUpperCase() + fileType.slice(1)} deleted successfully.`
                                    );
                                } else {
                                    toastr.error(data.message || `Failed to delete ${fileType}.`);
                                }
                            })
                            .catch(() => {
                                toastr.error('An error occurred while deleting the file.');
                            });
                    });
                });
            });
        </script>
    </body>

    </html>
@endsection
