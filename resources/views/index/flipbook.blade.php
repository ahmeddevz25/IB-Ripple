@extends('index.layout')

@section('content')
    <div class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
                <h2 class="fw-bold" style="color: #004d25; font-family: 'Oswald', Sans-serif;">{{ $title }}</h2>
            </div>
            
            <div class="col-12 col-lg-10 col-xl-8">
                {{-- This class is specifically targeted by the 3d flipbook script in partials.flipbook --}}
                <div class="custom-flipbook-container shadow-sm border" data-src="{{ $pdf_url }}" style="height: 80vh; min-height: 500px; width: 100%; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); background-color: #f8f9fa;"></div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- Include the partial which has the flipbook scripts and initialization logic --}}
    @include('partials.flipbook')
@endpush
