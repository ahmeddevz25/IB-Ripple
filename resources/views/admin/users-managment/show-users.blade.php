@extends('admin.layouts')
@section('content')
    <html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
        data-template="vertical-menu-template-free">
    <style>
        /* Force Toastr styles in case theme is overriding */

        .toast-success {
            background-color: #51A351 !important;
        }

        .toast-error {
            background-color: #BD362F !important;
        }

        .toast-info {
            background-color: #2F96B4 !important;
        }

        .toast-warning {
            background-color: #F89406 !important;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table-striped>tbody>tr:nth-of-type(odd) {
            background-color: #f9f9fb;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f4f9;
            transition: background-color 0.2s ease-in-out;
        }
    </style>

    <body>
        @include('sweetalert::alert')
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <div class="layout-page">
                    <div class="card mt-5 shadow-sm rounded" style="margin: 31px;">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light border-bottom">
                            <h5 class="card-title mb-0 text-md-start text-center">All User</h5>
                            @can('user add')
                                <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                                    data-bs-target="#addUserModal">
                                    <i class="bx bx-plus icon-sm"></i>
                                    <span class="d-none d-sm-inline-block">Add User</span>
                                </button>
                            @endcan
                        </div>


                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-striped border-top" id="example">
                                <thead class="table-light">
                                    <tr class="text-muted text-uppercase small">
                                        <th>Sr. No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Roles</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $key => $user)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="fw-semibold">{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>

                                            {{-- Role(s) --}}
                                            <td>
                                                @foreach ($user->getRoleNames() as $role)
                                                    <span class="badge bg-primary">{{ $role }}</span>
                                                @endforeach
                                            </td>

                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    @can('user edit')
                                                        <a href="{{ route('users.edit', $user->id) }}" title="Edit"
                                                            class="text-primary fs-5">
                                                            <i class='bx bx-edit'></i>
                                                        </a>
                                                    @endcan

                                                    @can('user delete')
                                                        <form action="{{ route('users.delete', $user->id) }}" method="POST"
                                                            class="ajax-delete-form d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger fs-5 p-0 m-0"
                                                                title="Delete">
                                                                <i class='bx bx-trash'></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No users available.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                        <!-- Add User Modal -->
                        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('users.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            {{-- Name --}}
                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" name="name" class="form-control" required>
                                            </div>

                                            {{-- Email --}}
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control" required>
                                            </div>

                                            {{-- Password --}}
                                            <div class="mb-3">
                                                <label class="form-label">Password</label>
                                                <input type="password" name="password" class="form-control" required>
                                            </div>

                                            {{-- Role --}}
                                            <div class="mb-3">
                                                <label class="form-label">Assign Role</label>
                                                <select name="role" class="form-select" required>
                                                    <option value="" disabled selected>-- Select Role --</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Create User</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /Modal -->

                    </div>
                </div>
            </div>
        </div>

        </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        </div>


        <script async defer src="https://buttons.github.io/buttons.js"></script>
    </body>

    </html>
@endsection