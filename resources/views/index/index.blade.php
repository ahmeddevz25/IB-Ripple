@extends('index.layout')
@section('content')
    <div class="container-fluid mt-4 mb-3">
        <div class="py-2 text-center font-weight-bold call-proposal-banner">
            Call for Proposal Now Open – Become a part of the IB Ripple Global Conference 2026
        </div>

        <div class="text-center mt-4 mx-auto intro-text-wrapper">
            <h5 class="intro-text-content">
                IB Ripple is an initiative by <span class="text-highlight-green">Learning Alliance
                    International</span><br>
                for the <span class="text-highlight-green">International Baccalaureate</span> Schools
                around the world. It is the first ever research journal of its kind being run<br>
                by an International Baccalaureate authorized ( IB Continuum ) school and serves as a platform for
                research publications by the IB students.
            </h5>
        </div>
    </div>
    @php
        $homeSection1 = $sliders->firstWhere('title', 'Home Slider');
    @endphp
    <div class="main-section-wrapper py-5">
        <div class="position-relative overflow-hidden">
            @if ($homeSection1 && $homeSection1->images->count() > 0)
                <div id="homepageSlider" class="carousel slide carousel-fade" data-ride="carousel" data-interval="2000">
                    <div class="carousel-inner">
                        @foreach ($homeSection1->images as $key => $image)
                            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image->image) }}" class="d-block w-100"
                                    style="height: 500px; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <img src="{{ asset('index/assets/img/eeshal-1.jpg') }}" class="w-100 d-block"
                    style="height: 500px; object-fit: cover;">
            @endif
        </div>

        {{-- Combined Home Content Card --}}
        <div class="home-content-card">
            {{-- Aims & Objectives Section --}}
            <div class="aims-section">
                <h2><b>Aims & Objectives</b></h2>
                <p>
                    IB Ripple aims to be a scholarly, peer-reviewed, and multi-disciplinary research-based IB Journal
                    published by Learning Alliance International. This Journal provides a platform for the publication
                    of
                    original research by young learners in IB Schools targeting a variety of global contexts, hence
                    promoting inquiry. The Journal aims to promote research via following an inquiry cycle.
                </p>
            </div>

            {{-- The Research Process Section --}}
            <div class="research-process-section">
                <h2 class="research-process-title"><b>The Research Process</b></h2>
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="research-card">
                            <img src="{{ asset('index/assets/images/exploration.jpg') }}" alt="Exploration">
                            <div class="card-label">Exploration</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="research-card">
                            <img src="{{ asset('index/assets/images/articulation.jpg') }}" alt="Articulation">
                            <div class="card-label">Articulation</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="research-card">
                            <img src="{{ asset('index/assets/images/engagement.jpg') }}" alt="Engagement">
                            <div class="card-label">Engagement</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="research-card">
                            <img src="{{ asset('index/assets/images/reflection.jpg') }}" alt="Reflection">
                            <div class="card-label">Reflection</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- The Ripple Effect Section --}}
        <div class="text-center">
            <h2 class="ripple-title"><b>The Ripple Effect</b></h2>
            <h4 class="ripple-subtitle"><b>IB Ripple Global Conferences</b></h4>
            <div class="row justify-content-center mt-4">
                <div class="col-lg-8">
                    <p class="ripple-description">
                        IB Ripple Global Conferences are a trademark event of IB RIPPLE, aimed to promote
                        critical thinking and celebrate research acumen of students world-wide.
                    </p>
                    <a href="{{ route('page', 'publications') }}" class="btn btn-ripple-read-more mt-4">Read More</a>
                </div>
            </div>
        </div>

        {{-- Parallax Section --}}
        <div class="parallax-section" style="background-image: url('{{ asset('index/assets/images/home-lower.jpg') }}');">
        </div>
    </div>

    <style>
        /* Core banner container */
        .banner-card {
            overflow: hidden;
            transition: all 0.4s ease-in-out;
        }

        .news-title {
            padding: 45px 0 29px;
        }

        .banner-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Image zoom */
        .banner-image {
            overflow: hidden;
        }

        .banner-image img {
            transition: transform 0.6s ease;
            height: 280px;
            object-fit: cover;
        }

        .banner-card:hover .banner-image img {
            transform: scale(1.08);
        }

        /* Banner base style */
        .banner-content {
            color: #fff;
            padding: 25px 30px;
            min-height: 130px;
            transition: background-color 0.4s ease;
        }

        /* Unique colors per banner */
        .bg-pyp {
            background-color: #ffcf2d;
        }

        .bg-myp {
            background-color: #f1787e;
        }

        .bg-dp {
            background-color: #57c4d0;
        }

        /* Darken on hover */
        .banner-card:hover .bg-pyp {
            background-color: #4d4d4d;
        }

        .banner-card:hover .bg-myp {
            background-color: #4d4d4d;
        }

        .banner-card:hover .bg-dp {
            background-color: #4d4d4d;
        }

        /* Icon animation */
        .banner-icon img {
            transition: transform 0.8s ease;
            transform-origin: center;
        }

        .banner-card:hover .banner-icon img {
            transform: rotate(360deg);
        }

        .card-img {
            height: 350px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card:hover .card-img {
            transform: scale(1.1);
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            opacity: 0.85;
            transition: opacity 0.3s ease, background 0.3s ease;
        }

        .card:hover .overlay {
            opacity: 1;
        }

        /* 🎨 Individual Overlay Colors */
        .overlay-red {
            background: rgba(255, 87, 87, 0.7);
        }

        .overlay-orange {
            background: rgba(255, 165, 0, 0.7);
        }

        .overlay-green {
            background: rgba(105, 187, 95, 0.7);
        }

        .overlay-blue {
            background: rgba(82, 188, 214, 0.7);
        }

        .overlay h5 {
            font-size: 1.2rem;
            letter-spacing: 1px;
        }

        .news-date:hover {
            color: #f67c78;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .banner-image img {
                height: 200px;
            }

            .banner-content {
                flex-direction: column;
                text-align: center;
            }

            .banner-icon {
                margin-bottom: 12px;
            }
        }

        .block_2 li .bg_block {
            position: absolute;
            display: block;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: #f67c78;
            opacity: 0.6;
            filter: alpha(opacity=60);
            -webkit-transition: all 0.5s ease;
            -moz-transition: all 0.5s ease;
            -o-transition: all 0.5s ease;
            transition: all 0.5s ease;
        }
    </style>

@endsection