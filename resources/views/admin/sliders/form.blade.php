@extends('admin.layouts')
@section('content')
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <div class="layout-page">

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4">
                            <span class="text-muted fw-light">Update</span> Slider
                        </h4>

                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h2>Update Slider</h2>

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

                                        <form action="{{ route('sliders.update', $slider->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            {{-- Slider Title --}}
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Slider Title</label>
                                                <input type="text" name="title" id="title" class="form-control"
                                                    value="{{ old('title', $slider->title) }}"
                                                    placeholder="Enter slider title">
                                            </div>

                                            {{-- Existing Images --}}
                                            @if ($slider->images->count())
                                                <div class="mb-3">
                                                    <label class="form-label">Existing Images</label>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        @foreach ($slider->images as $img)
                                                            <div class="position-relative" style="width:130px;">
                                                                <img src="{{ asset('storage/' . $img->image) }}"
                                                                    class="img-thumbnail mb-2" width="130"
                                                                    height="130">

                                                                <!-- Delete Button -->
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-image-btn"
                                                                    data-image-id="{{ $img->id }}"
                                                                    style="padding: 2px 6px; line-height: 1;">&times;
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <p class="text-muted">No images uploaded yet.</p>
                                            @endif


                                            {{-- Upload New Images --}}
                                            <div class="mb-3">
                                                <label for="images" class="form-label">Upload New Image(s)</label>
                                                <input type="file" name="images[]" id="images" class="form-control"
                                                    multiple>
                                                <small class="text-muted">You can add one or multiple new images. Old images
                                                    will remain unless deleted.</small>
                                            </div>
                                            <div class="mb-3 form-check">
                                                {{-- Hidden fallback ensures unchecked sends 0 --}}
                                                <input type="hidden" name="is_active" value="0">
                                                <input type="checkbox" name="is_active" class="form-check-input"
                                                    id="is_active" value="1"
                                                    {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">Active</label>
                                            </div>

                                            {{-- Buttons --}}
                                            <button type="submit" class="btn btn-primary">Update Slider</button>
                                            <a href="{{ route('sliders.index') }}" class="btn btn-secondary">Cancel</a>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
            $(document).on('click', '.delete-image-btn', function() {
                let imageId = $(this).data('image-id');
                let button = $(this);

                if (confirm('Are you sure you want to delete this image?')) {
                    $.ajax({
                        url: "{{ route('slider.image.delete', '') }}/" + imageId,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                toastr.success(response.message);
                                button.closest('.position-relative').remove();
                            } else {
                                toastr.error(response.message || 'Failed to delete image');
                            }
                        },
                        error: function(xhr) {
                            toastr.error('AJAX Error');
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        </script>
@endsection
