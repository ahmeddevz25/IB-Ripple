@extends('admin.layouts')
@section('content')
    <html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
        data-assets-path="../assets/" data-template="vertical-menu-template-free">
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
    </style>

    <body>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">

                <div class="layout-page">


                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Update</span> Role</h4>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h2>Update Role</h2>

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

                                        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                                            @csrf
                                            {{-- Permission Name --}}
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Permission Name</label>
                                                <input type="text" name="name" class="form-control" id="name"
                                                    value="{{ old('name', $permission->name) }}"
                                                    placeholder="Enter permission name" required>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Update Permission</button>
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
        </div>
        <script async defer src="https://buttons.github.io/buttons.js"></script>
    </body>

    </html>
@endsection
