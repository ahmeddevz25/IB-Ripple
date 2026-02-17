@extends('admin.layouts')
@section('content')
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <div class="layout-page">

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">Update</span> Page
                        </h4>

                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h2>Update Page</h2>

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

                                        <form action="{{ route('pages.update', $page->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            {{-- Page Title --}}
                                            <div class="mb-3">
                                                <label for="page_title" class="form-label">Page Name</label>
                                                <input type="text" name="page_title" class="form-control" id="title"
                                                    value="{{ old('page_title', $page->page_title) }}"
                                                    placeholder="Enter page title" required>
                                            </div>
                                            {{-- Page Sub Title --}}
                                            <div class="mb-3">
                                                <label for="sub_title" class="form-label">Page URL Name</label>
                                                <input type="text" name="sub_title" id="sub_title" class="form-control"
                                                    value="{{ old('sub_title', $page->sub_title) }}" required>

                                            </div>
                                            {{-- Select Event (Optional) --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Select Events (Multiple)</label>

                                                <div class="border rounded p-3"
                                                    style="max-height: 350px; overflow-y: auto;">
                                                    @php
                                                        $selectedEvents = old('event_ids', $page->event_ids ?? []);
                                                    @endphp

                                                    @foreach ($events as $event)
                                                        <div class="form-check mb-2 ms-3">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="event_{{ $event->id }}" name="event_ids[]"
                                                                value="{{ $event->id }}"
                                                                {{ in_array($event->id, $selectedEvents) ? 'checked' : '' }}>
                                                            <label for="event_{{ $event->id }}"
                                                                class="form-check-label">
                                                                {{ $event->title }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <small class="text-muted d-block mt-1">
                                                    Select one or more events. If any are selected, Page Content and
                                                    Document Upload will be hidden.
                                                </small>
                                            </div>


                                            {{-- Page Content --}}
                                            <div class="mb-3" id="bodySection">
                                                <label for="body" class="form-label">Page Content</label>
                                                <textarea name="body" id="body" class="form-control" rows="6">{{ old('body', $page->body) }}</textarea>
                                            </div>

                                            {{-- Toggle to show PDF upload --}}
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="enableDocumentUpload">
                                                <label class="form-check-label" for="enableDocumentUpload">Upload PDF
                                                    Document</label>
                                            </div>

                                            {{-- PDF + Thumbnail fields (hidden by default) --}}
                                            <div id="documentFields" style="display: none;">
                                                {{-- PDF Upload --}}
                                                <div class="mb-3">
                                                    <label for="document" class="form-label fw-bold">PDF Document</label>
                                                    <input type="file" name="document" id="document"
                                                        class="form-control" accept="application/pdf">
                                                    <small class="text-muted">Upload a PDF file for this page.</small>
                                                </div>

                                                {{-- Thumbnail Upload --}}
                                                <div class="mb-3">
                                                    <label for="thumbnail" class="form-label fw-bold">Thumbnail
                                                        Image</label>
                                                    <input type="file" name="thumbnail" id="thumbnail"
                                                        class="form-control" accept="image/*">
                                                    <small class="text-muted">Upload a thumbnail image for the PDF.</small>
                                                </div>
                                            </div>

                                            {{-- Existing Document --}}
                                            @if ($page->mediaDocuments->count())
                                                <div class="mt-3">
                                                    <strong>Existing PDFs:</strong>
                                                    <div class="row">
                                                        @foreach ($page->mediaDocuments as $doc)
                                                            <div class="col-md-3 mb-2" id="doc-{{ $doc->id }}">
                                                                <div class="card shadow-sm p-2">
                                                                    <a href="{{ asset('storage/' . $doc->file_path) }}"
                                                                        target="_blank">
                                                                        <img src="{{ asset('storage/' . $doc->thumbnail) }}"
                                                                            class="img-fluid mb-1">
                                                                    </a>
                                                                    <div class="text-center">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger remove-pdf"
                                                                            data-id="{{ $doc->id }}">
                                                                            &times;
                                                                        </button>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Parent Page --}}
                                            <div class="mb-3">
                                                <label for="parent_id" class="form-label">Parent Page (Optional)</label>
                                                <select name="parent_id" id="parent_id" class="form-select">
                                                    <option value="">-- None (Top-level) --</option>
                                                    @foreach ($parents as $parent)
                                                        @if ($parent->id != $page->id)
                                                            <option value="{{ $parent->id }}"
                                                                {{ old('parent_id', $page->parent_id) == $parent->id ? 'selected' : '' }}>
                                                                {{ $parent->page_title }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Slider --}}
                                            <div class="mb-3">
                                                <label for="slider_id" class="form-label">Select Slider (Optional)</label>
                                                <select name="slider_id" id="slider_id" class="form-select">
                                                    <option value="">-- None --</option>
                                                    @foreach ($sliders as $slider)
                                                        <option value="{{ $slider->id }}"
                                                            {{ old('slider_id', $page->slider_id) == $slider->id ? 'selected' : '' }}>
                                                            {{ $slider->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Navbar Show/Hide --}}
                                            <div class="mb-3 form-check">
                                                <input type="hidden" name="is_navbar" value="0">
                                                <input type="checkbox" name="is_navbar" id="is_navbar" value="1"
                                                    class="form-check-input"
                                                    {{ old('is_navbar', $page->is_navbar) ? 'checked' : '' }}>
                                                <label for="is_navbar" class="form-check-label">Show in Navbar</label>
                                            </div>
                                            {{-- Sidebar Option (Multiple) --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Show in Menu(s) (Multiple)</label>

                                                <div class="border rounded p-3"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($menus as $menu)
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="menu_ids[]" id="menu_{{ $menu->id }}"
                                                                value="{{ $menu->id }}"
                                                                {{ in_array($menu->id, old('menu_ids', $page->menus->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="menu_{{ $menu->id }}">
                                                                {{ $menu->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <small class="text-muted d-block mt-1">
                                                    Select one or multiple menus this page belongs to.
                                                </small>
                                            </div>



                                            {{-- Active / Inactive --}}
                                            <div class="mb-3 form-check">
                                                <input type="hidden" name="is_active" value="0">
                                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                                    class="form-check-input"
                                                    {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                                                <label for="is_active" class="form-check-label">Active</label>
                                            </div>


                                            {{-- Sort Order --}}
                                            <div class="mb-3">
                                                <label for="sort_order" class="form-label">Sort Order</label>
                                                <input type="number" name="sort_order" class="form-control"
                                                    id="sort_order" value="{{ old('sort_order', $page->sort_order) }}"
                                                    min="0">
                                            </div>

                                            {{-- Submit --}}
                                            <button type="submit" class="btn btn-primary">Update Page</button>
                                            <a href="{{ route('pages.index') }}" class="btn btn-secondary">Cancel</a>
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const eventSelect = document.getElementById('event_id');
                const bodySection = document.getElementById('bodySection');
                const docSection = document.getElementById('documentSection');

                function toggleFields() {
                    // If an event is selected → hide fields
                    if (eventSelect.value && eventSelect.value.trim() !== '') {
                        bodySection.style.display = 'none';
                        docSection.style.display = 'none';
                    } else {
                        bodySection.style.display = 'block';
                        docSection.style.display = 'block';
                    }
                }

                // Run once when editing form loads
                toggleFields();

                // Re-run when dropdown changes
                eventSelect.addEventListener('change', toggleFields);
            });
        </script>
        {{-- JS to toggle fields --}}
        <script>
            document.getElementById('enableDocumentUpload').addEventListener('change', function() {
                const docFields = document.getElementById('documentFields');
                if (this.checked) {
                    docFields.style.display = 'block';
                } else {
                    docFields.style.display = 'none';
                    // Optionally, clear files
                    document.getElementById('document').value = '';
                    document.getElementById('thumbnail').value = '';
                }
            });
        </script>


        <script>
            $(document).ready(function() {
                $(document).on('click', '.remove-pdf', function(e) {
                    e.preventDefault();
                    var id = $(this).data('id');

                    if (!id) return;

                    if (confirm('Are you sure you want to delete this PDF?')) {
                        $.ajax({
                            url: '{{ route('media-document.destroy', ':id') }}'.replace(':id', id),
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                if (res.success) {
                                    $('#doc-' + id).fadeOut(300, function() {
                                        $(this).remove();
                                    });
                                } else {
                                    alert('Failed to delete PDF.');
                                }
                            },
                            error: function(xhr) {
                                alert('Something went wrong.');
                            }
                        });
                    }
                });
            });
        </script>
@endsection
