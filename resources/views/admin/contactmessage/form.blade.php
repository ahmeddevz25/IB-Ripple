@extends('admin.layouts')
@section('content')
    <html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
        data-assets-path="../assets/" data-template="vertical-menu-template-free">

    <body>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">

                <div class="layout-page">


                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Update</span> Instruction Image</h4>
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h2>Update Instruction Image</h2>

                                        @if (session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        {{-- $row = InstructionGuid instance --}}
                                        <form action="{{ route('instructionguides.update', $row->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf

                                            {{-- Type (static dropdown) --}}
                                            <div class="mb-3">
                                                <label class="form-label">Image Type</label>
                                                <select name="type" class="form-select" required>
                                                    @php
                                                        $currentType = old('type', $row->type ?? '');
                                                    @endphp
                                                    <option value="size_guide"
                                                        {{ $currentType === 'size_guide' ? 'selected' : '' }}>Size Guide
                                                    </option>
                                                    <option value="washing_instructions"
                                                        {{ $currentType === 'washing_instructions' ? 'selected' : '' }}>
                                                        Washing Instructions</option>
                                                </select>
                                                @error('type')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- Current image preview --}}
                                            <div class="mb-3">
                                                <label class="form-label">Current Image</label>
                                                <div>
                                                    @if (!empty($row->image_path))
                                                        <img src="{{ asset('storage/' . $row->image_path) }}"
                                                            alt="Current image"
                                                            style="max-height:140px;width:auto;border-radius:6px;object-fit:contain;">
                                                    @else
                                                        <span class="text-muted">No image uploaded</span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Replace image (optional) --}}
                                            <div class="mb-3">
                                                <label class="form-label">Replace Image (optional)</label>
                                                <input type="file" name="image" class="form-control"
                                                    accept=".jpg,.jpeg,.png,.webp">
                                                @error('image')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                                <div class="form-text">Max 4MB. Allowed: jpg, jpeg, png, webp</div>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <a href="{{ route('instructionguides') }}" class="btn btn-light">Cancel</a>
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
