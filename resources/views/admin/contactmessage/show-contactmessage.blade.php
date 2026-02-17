@extends('admin.layouts')
@section('content')
    <html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
        data-template="vertical-menu-template-free">

    <body>
        @include('sweetalert::alert')
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <div class="layout-page">
                    <div class="card mt-5 shadow-sm rounded" style="margin: 31px;">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
                            <h5 class="card-title mb-0 text-md-start text-center">All Contact Message</h5>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-striped border-top" id="example">
                                <thead class="table-light">
                                    <tr class="text-muted text-uppercase small">
                                        <th>Sr. No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Received At</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($messages as $key => $msg)
                                        <tr class="border-bottom">
                                            <td>{{ $key + 1 }}</td>
                                            <td class="fw-semibold">{{ $msg->name }}</td>
                                            <td>{{ $msg->email }}</td>
                                            <td>{{ $msg->subject ?? '-' }}</td>
                                            <td style="max-width:250px; white-space:normal;">
                                                {{ Str::limit($msg->message, 80) }}
                                            </td>
                                            <td>{{ $msg->created_at->format('d M Y h:i A') }}</td>

                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">

                                                    @can('contactmessage delete')
                                                        <form action="{{ route('contactmessages.delete', $msg->id) }}" method="POST"
                                                            class="ajax-delete-form d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger fs-5 p-0 m-0"
                                                                title="Delete this Message" data-bs-toggle="tooltip"
                                                                data-bs-placement="top">
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


                    </div>


                </div>
            </div>
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    </body>

    </html>
@endsection