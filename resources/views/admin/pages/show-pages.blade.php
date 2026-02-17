@extends('admin.layouts')
@section('content')
        @include('sweetalert::alert')

        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <div class="layout-page">
                    <div class="card mt-5 shadow-sm rounded" style="margin: 31px;">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
                            <h5 class="card-title mb-0 text-md-start text-center">All Pages</h5>
                            @can('page add')
                                <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                    data-bs-target="#addPageModal">
                                    <i class="bx bx-plus icon-sm"></i>
                                    <span class="d-none d-sm-inline-block">Create Page</span>
                                </button>
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-striped border-top" id="example">
                                <thead class="table-light">
                                    <tr class="text-muted text-uppercase small">
                                        <th>Sr. No</th>
                                        <th>Page Name</th>
                                        <th>Page Url Name</th>
                                        <th>Parent Page</th>
                                        <th>Slider</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pages as $key => $page)
                                        <tr class="border-bottom">
                                            <td>{{ $key + 1 }}</td>
                                            <td class="fw-semibold">{{ $page->page_title }}</td>
                                            <td class="fw-semibold">{{ $page->sub_title }}</td>
                                            <td>{{ $page->parent ? $page->parent->page_title : '—' }}</td>
                                            <td>{{ $page->slider ? $page->slider->title : '—' }}</td>
                                            <td>
                                                @if ($page->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    @can('page edit')
                                                        <a href="{{ route('pages.edit', $page->id) }}" title="Edit"
                                                            class="text-primary fs-5">
                                                            <i class='bx bx-edit'></i>
                                                        </a>
                                                    @endcan
                                                    @can('page delete')
                                                        <form action="{{ route('pages.destroy', $page->id) }}" method="POST"
                                                            class="ajax-delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-link text-danger fs-5 p-0 m-0">
                                                                <i class='bx bx-trash'></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Add Page Modal -->
                        <div class="modal fade" id="addPageModal" tabindex="-1" aria-labelledby="addPageModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('pages.store') }}" method="POST" enctype="multipart/form-data">

                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addPageModalLabel">Add New Page</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            {{-- Page Title --}}
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Page Name</label>
                                                <input type="text" name="page_title" class="form-control" id="title"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Page Url Name</label>
                                                <input type="text" name="sub_title" class="form-control" id="title"
                                                    required>
                                            </div>
                                            {{-- Select Event (Optional) --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Select Events (Multiple)</label>

                                                <div class="border rounded p-3"
                                                    style="max-height: 350px; overflow-y: auto;">
                                                    @php
                                                        $selectedEvents = old('event_ids', []);
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



                                            {{-- Body (HTML content) --}}
                                            <div class="mb-3">
                                                <label for="body" class="form-label">Page Content</label>
                                                <textarea name="body" id="body" class="form-control" rows="6"></textarea>
                                            </div>
                                            {{-- Toggle to show PDF upload --}}
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="enableDocumentUpload">
                                                <label class="form-check-label">Upload PDF Document</label>
                                            </div>

                                            <div id="documentFields" style="display:none;">

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">PDF Document</label>
                                                    <input type="file" name="document" id="document"
                                                        class="form-control" accept="application/pdf">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Thumbnail Image</label>
                                                    <input type="file" name="thumbnail" id="thumbnail"
                                                        class="form-control" accept="image/*">
                                                </div>

                                            </div>



                                            {{-- Parent Page --}}
                                            <div class="mb-3">
                                                <label for="parent_id" class="form-label">Parent Page (Optional)</label>
                                                <select name="parent_id" id="parent_id" class="form-select">
                                                    <option value="">-- None (Top-level) --</option>
                                                    @foreach ($parents as $parent)
                                                        <option value="{{ $parent->id }}">{{ $parent->page_title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Slider --}}
                                            <div class="mb-3">
                                                <label for="slider_id" class="form-label">Select Slider</label>
                                                <select name="slider_id" id="slider_id" class="form-select">
                                                    <option value="">-- Select Slider --</option>
                                                    @foreach ($sliders as $slider)
                                                    <option value="{{ $slider->id }}"
                                                            {{ old('slider_id') == $slider->id ? 'selected' : '' }}>
                                                            {{ $slider->title ?? 'Untitled Slider' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>


                                            {{-- Navbar Show/Hide --}}
                                            <div class="mb-3 form-check">
                                                <input type="hidden" name="is_navbar" value="0">
                                                <input type="checkbox" name="is_navbar" id="is_navbar" value="1"
                                                    class="form-check-input" checked>
                                                <label for="is_navbar" class="form-check-label">Show in Navbar</label>
                                            </div>
                                            {{-- Sidebar Option --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Show in Menu(s) (Multiple)</label>

                                                <div class="border rounded p-3"
                                                    style="max-height: 250px; overflow-y: auto;">
                                                    @foreach ($menus as $menu)
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" class="form-check-input"
                                                                name="menu_ids[]" id="menu_{{ $menu->id }}"
                                                                value="{{ $menu->id }}" {{-- ✅ Keep checked if old value or already linked --}}
                                                                {{ in_array($menu->id, old('menu_ids', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="menu_{{ $menu->id }}">
                                                                {{ $menu->name }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <small class="text-muted d-block mt-1">
                                                    Select one or more menus in which this page should appear.
                                                </small>
                                            </div>


                                            {{-- Active / Inactive --}}
                                            <div class="mb-3 form-check">
                                                <input type="hidden" name="is_active" value="0">
                                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                                    class="form-check-input" checked>
                                                <label for="is_active" class="form-check-label">Active</label>
                                            </div>



                                            {{-- Sort Order --}}
                                            <div class="mb-3">
                                                <label for="sort_order" class="form-label">Sort Order</label>
                                                <input type="number" name="sort_order" class="form-control"
                                                    id="sort_order" value="0" min="0">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Add Page</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- End Add Page Modal -->
                    </div>
                </div>
            </div>
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const eventSelect = document.getElementById('event_id');
                const bodyField = document.querySelector('.form-label[for="body"]').closest('.mb-3');
                const docField = document.querySelector('.form-label[for="document"]').closest('.mb-3');

                eventSelect.addEventListener('change', function() {
                    if (this.value) {
                        bodyField.style.display = 'none';
                        docField.style.display = 'none';
                    } else {
                        bodyField.style.display = 'block';
                        docField.style.display = 'block';
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {

                $('#enableDocumentUpload').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#documentFields').slideDown();
                    } else {
                        $('#documentFields').slideUp();

                        // FIXED IDs
                        $('#document').val('');
                        $('#thumbnail').val('');
                    }
                });

            });
        </script>
@endsection
