@extends('admin.layouts')
@section('content')
    @include('sweetalert::alert')

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="layout-page">
                <div class="card mt-5 shadow-sm rounded" style="margin: 31px;">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
                        <h5 class="card-title mb-0 text-md-start text-center">All Sliders</h5>

                        @can('slider add')
                            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                data-bs-target="#addSliderModal">
                                <i class="bx bx-plus icon-sm"></i>
                                <span class="d-none d-sm-inline-block">Add Slider</span>
                            </button>
                        @endcan
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-striped border-top" id="example">
                            <thead class="table-light">
                                <tr class="text-muted text-uppercase small">
                                    <th>Slider ID</th>
                                    <th>Title</th>
                                    <th>Images</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($sliders as $slider)
                                    <tr class="border-bottom">
                                        <td>{{ $slider->id }}</td>

                                        {{-- ✅ Title (nullable safe) --}}
                                        <td>{{ $slider->title ?? '—' }}</td>

                                        {{-- ✅ Show all images for this slider --}}
                                        <td>
                                            @if ($slider->images && $slider->images->count() > 0)
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($slider->images as $img)
                                                        <img src="{{ asset('storage/' . $img->image) }}" width="100" height="70"
                                                            class="rounded border" style="object-fit: cover;">
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="badge bg-secondary">No Images</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($slider->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>

                                        {{-- ✅ Action Buttons --}}
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                @can('slider edit')
                                                    <a href="{{ route('sliders.edit', $slider->id) }}" title="Edit"
                                                        class="text-primary fs-5">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                @endcan

                                                @can('slider delete')
                                                    <form action="{{ route('sliders.destroy', $slider->id) }}" method="POST"
                                                        class="ajax-delete-form">
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    <!-- Add Slider Modal -->
                    <div class="modal fade" id="addSliderModal" tabindex="-1" aria-labelledby="addSliderModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addSliderModalLabel">Add New Slider</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        {{-- Slider Title --}}
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Slider Title</label>
                                            <input type="text" name="title" class="form-control" id="title"
                                                placeholder="Enter slider title">
                                        </div>

                                        {{-- Slider Images --}}
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Upload Image(s)</label>
                                            <input type="file" name="images[]" id="image" class="form-control" multiple
                                                required>

                                            <small class="text-muted">You can select one or multiple images.</small>
                                        </div>
                                        {{-- Slider Active Status --}}
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                                value="1" checked>
                                            <label class="form-check-label" for="is_active">Active</label>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Add Slider</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
@endsection