@extends('admin.layouts')
@section('content')
    @include('sweetalert::alert')

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="layout-page">
                <div class="card mt-5 shadow-sm rounded" style="margin: 31px;">
                    <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
                        <h5 class="card-title mb-0 text-md-start text-center">All Media Items</h5>
                        @can('gallerymanager add')
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <button class="btn btn-outline-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                    data-bs-target="#addEventModal">
                                    <i class="bx bx-calendar-plus icon-sm"></i>
                                    <span class="d-none d-sm-inline-block">Add Event</span>
                                </button>

                                <a href="{{ route('media-items.index') }}"><button
                                        class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                        data-bs-target="#addMediaModal">
                                        <i class="bx bx-right-arrow-alt icon-sm"></i>
                                        <span class="d-none d-sm-inline-block">Go To Media Item</span>
                                    </button></a>
                            </div>
                        @endcan
                    </div>


                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-striped border-top" id="example">
                            <thead class="table-light">
                                <tr class="text-muted text-uppercase small">
                                    <th>Sr. No</th>
                                    <th>Title</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $key => $event)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="fw-semibold">{{ $event->title }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                @can('gallerymanager edit')

                                                    <a href="{{ route('events.edit', $event->id) }}" class="text-primary fs-5">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('gallerymanager delete')

                                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST"
                                                        class="ajax-delete-form">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger fs-5 p-0 m-0">
                                                            <i class="bx bx-trash"></i>
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

                    <!-- Add Event Modal -->
                    <div class="modal fade" id="addEventModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add Event</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">

                                            <label for="title" class="form-label">Event Title</label>
                                            <input type="text" name="title" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->

                </div>
            </div>
        </div>
    </div>
@endsection