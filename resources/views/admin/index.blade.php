@extends('admin.layouts')
@section('content')
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="layout-page">
                <div class="container-xxl flex-grow-1 container-p-y">

                    <div class="row">
                        <div class="col-lg-12 mb-12 order-0 mb-3">
                            <div class="card">
                                <div class="d-flex align-items-end row">
                                    <div class="col-sm-7">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">Congratulations
                                                {{ Auth::user()->name }}! 🎉</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <h5 class="mb-3 fw-bold">Tracking Summary</h5> --}}
                    <div class="row g-4 mb-3">

                        <!-- Today's Visitors -->
                        <div class="col-md-3 col-6">
                            <div class="card border-0 shadow-sm h-100 hover-card">
                                <div class="card-body">
                                    <p class="text-muted small mb-1">TODAY'S VISITORS</p>
                                    <h4 class="fw-bold">{{ $todayVisitors }}</h4>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Yesterday {{ $yesterdayVisitors }}</small>
                                        <small class="{{ $todayGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $todayGrowth }}%
                                            <i
                                                class="bx {{ $todayGrowth >= 0 ? 'bx-trending-up' : 'bx-trending-down' }}"></i>
                                        </small>
                                    </div>
                                    <div class="progress mt-2" style="height:3px;">
                                        <div class="progress-bar bg-primary"
                                            style="width: {{ min(abs($todayGrowth), 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Yesterday's Visitors -->
                        <div class="col-md-3 col-6">
                            <div class="card border-0 shadow-sm h-100 hover-card">
                                <div class="card-body">
                                    <p class="text-muted small mb-1">YESTERDAY'S VISITORS</p>
                                    <h4 class="fw-bold">{{ $yesterdayVisitors }}</h4>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ now()->subDay()->format('d M') }}</small>
                                        <small class="text-danger">
                                            {{ $yesterdayVisitors > 0 ? '-87.5%' : '0%' }}
                                            <i class="bx bx-trending-down"></i>
                                        </small>
                                    </div>
                                    <div class="progress mt-2" style="height:3px;">
                                        <div class="progress-bar bg-danger" style="width: 40%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- All Visitors -->
                        <div class="col-md-3 col-6">
                            <div class="card border-0 shadow-sm h-100 hover-card">
                                <div class="card-body">
                                    <p class="text-muted small mb-1">ALL VISITORS <span class="float-end">This
                                            Week</span></p>
                                    <h4 class="fw-bold">{{ $allVisitors }}</h4>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Previous Week {{ $previousWeekVisitors }}</small>
                                        <small class="{{ $weekGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $weekGrowth }}%
                                            <i
                                                class="bx {{ $weekGrowth >= 0 ? 'bx-trending-up' : 'bx-trending-down' }}"></i>
                                        </small>
                                    </div>
                                    <div class="progress mt-2" style="height:3px;">
                                        <div class="progress-bar {{ $weekGrowth >= 0 ? 'bg-success' : 'bg-danger' }}"
                                            style="width: {{ min(abs($weekGrowth), 100) }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- New Visitors -->
                        <div class="col-md-3 col-6">
                            <div class="card border-0 shadow-sm h-100 hover-card">
                                <div class="card-body">
                                    <p class="text-muted small mb-1">NEW VISITORS <span class="float-end">This
                                            Week</span></p>
                                    <h4 class="fw-bold">{{ $newVisitors }}</h4>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Previous Week {{ $previousWeekVisitors }}</small>
                                        <small class="text-danger">
                                            -51.52% <i class="bx bx-trending-down"></i>
                                        </small>
                                    </div>
                                    <div class="progress mt-2" style="height:3px;">
                                        <div class="progress-bar bg-danger" style="width: 50%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-12 order-0 mb-3">
                            <div class="card">
                                <div class="d-flex align-items-end row">
                                    <div class="card-body">
                                        <h5 class="fw-bold text-primary">Visitors Analytics (This Week)</h5>
                                        <div id="visitorsChart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                series: [{
                        name: 'This Week',
                        data: {!! json_encode(array_values($thisWeekVisitorsDaily->toArray())) !!}
                    },
                    {
                        name: 'Previous Week',
                        data: {!! json_encode(array_values($previousWeekVisitorsDaily->toArray())) !!}
                    },
                    {
                        name: 'Yesterday',
                        data: [{{ $yesterdayVisitors }}] // Single point
                    }
                ],
                chart: {
                    height: 350,
                    type: 'area'
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                xaxis: {
                    type: 'datetime',
                    categories: {!! json_encode(array_keys($thisWeekVisitorsDaily->toArray())) !!}
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy'
                    }
                },
                colors: ['#1E90FF', '#FF5733', '#28A745']
            };

            var chart = new ApexCharts(document.querySelector("#visitorsChart"), options);
            chart.render();
        });
    </script>
@endsection
