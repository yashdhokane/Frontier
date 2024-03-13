@extends('home')
@section('content')

<style>
    .activegreen {
        border: 2px solid green !important;
    }

    .user_head_link {
        color: #2962ff !important;
        text-transform: uppercase;
        font-size: 13px;
    }

    .user_head_link:hover {
        color: #ee9d01 !important;
    }


    .dts2 {
        min-height: 60px;
    }

    .table> :not(caption)>*>* {
        padding: 0.3rem;
    }

    .dat table th {
        text-align: center;
    }

    .dts {
        background: #3699ff;
        padding: 5px;
        border-radius: 5px;
        color: #FFFFFF;
    }

    .dts p {
        margin-bottom: 5px;
        line-height: 17px;
    }

    .out {
        background: #fbeccd !important;
    }

    .out:hover {
        background: #fbeccd !important;
    }

    .out .dts {
        background: #fbeccd !important;
    }

    .table-hover>tbody>tr:hover>* {
        --bs-table-color-state: var(--bs-table-hover-color);
        --bs-table-bg-state: transparent;
    }

    img.calimg2 {
        width: 224px;
        margin: 0px 10px;
    }
</style>
<div class="page-wrapper" style="display: inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h2 class="page-title">Performance Metrix</h2>
            </div>
            <div class="col-7 align-self-right" style="text-align: right;padding-right: 40px;">
                <a href="#." style="margin-right: 10px;font-size: 13px;"><i class="fas fa-calendar-alt"></i> Select
                    Dates</a>
                <a href="#." style="margin-right: 10px;font-size: 13px;"><i class="fas fa-calendar-check"></i> Daily</a>
                <a href="#." style="margin-right: 10px;font-size: 13px;"><i class="fas fa-calendar-alt"></i> Weekly</a>
                <a href="#." style="margin-right: 10px;font-size: 13px;color: #ee9d01;font-weight: bold;"> Monthly</a>
            </div>
        </div>
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->
    <div class="container-fluid">
        <div class="row">



            <div class="container">

                <div class="row">

                    <div class="col bg-light py-2 px-3 border">

                        <div class="card w-100">
                            <div class="card-body">
                                <h4 class="card-title">Earnings</h4>
                                <h5 class="card-subtitle" style="font-size: 14px;color: var(--bs-card-title-color);">
                                    Total earnings of the month</h5>
                                <h2 class="text-info fw-bold mb-1">${{$oneMonthTotalEarningCount }}</h2>
                                <span class="fw-bold text-muted">{{$formattedPercentage }}% (${{$oneMonthClosedEarningCount}}) <i
                                        class="ri-arrow-up-line text-info fw-bold"></i></span>
                                <div class="mt-3 mb-4 text-center">
                                    <div class="earnings-card mx-auto" style="max-width: 250px"></div>
                                </div>
                                <hr />
                            </div>
                        </div>

                    </div>

                    <div class="col bg-light py-2 px-3 border">

                        <div class="card w-100">
                            <div class="card-body">
                                <h4 class="card-title">Calls/Tickets Details</h4>
                                <h5 class="card-subtitle" style="font-size: 14px;color: var(--bs-card-title-color);">
                                    Total Calls/Tickets of the month</h5>
                                <h2 class="text-info fw-bold mb-1">{{$onemonthCount}}</h2>
                                <span class="fw-bold text-muted">{{$formattedPercentage}}% ({{$onemonthcompleteCount }}) <i
                                        class="ri-arrow-up-line text-info fw-bold"></i></span>
                            </div>
                        </div>
                        <div class="row center-align mt-5000">
                            <div class="col-md-6">
 <div class="poll mt-3" ></div>
                            </div>
                            <!-- column -->
                            <div class="col-md-6">
                                <ul class="list-style-none">
                                    <li class="mt-3">
                                        <i class="
                              ri-checkbox-blank-circle-fill
                              me-1
                              fs-4
                              text-success
                              fs-2
                              align-middle
                            "></i>
                                        Complete ({{$closedCount}})
                                    </li>
                                    <li class="mt-3">
                                        <i class="
                              ri-checkbox-blank-circle-fill
                              me-1
                              fs-4
                              text-orange
                              fs-2
                              align-middle
                            "></i>
                                        In-Process ({{$openCount}})
                                    </li>
                                    <li class="mt-3">
                                        <i class="
                              ri-checkbox-blank-circle-fill
                              me-1
                              fs-4
                              text-secondary
                              fs-2
                              align-middle
                            "></i>
                                        Pending ({{$pendingCount}})
                                    </li>
                                    <li class="mt-3">
                                        <i class="
                              ri-checkbox-blank-circle-fill
                              me-1
                              fs-4
                              text-danger
                              fs-2
                              align-middle
                            "></i>
                                        Rejected ({{$rejectedCount}})
                                    </li>
                                </ul>
                            </div>
                            <!-- column -->
                        </div>



                    </div>


                    <div class="col bg-light py-2 px-3 border">

                        <!-- ---------------------
                            start Email Campaign
                        ---------------- -->
                        <div class="card e-campaign">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h4 class="card-title">Job Success Rate</h4>
                                        <h5 class="card-subtitle"
                                            style="font-size: 14px;color: var(--bs-card-title-color);">Total job
                                            completion rate</h5>
                                    </div>

                                    <div class="ms-auto">
                                        <!-- Tabs -->
                                        <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                                            <li class="nav-item" style="display: none;">
                                                <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                                    href="#current-month" role="tab" aria-selected="true">This Month</a>
                                            </li>
                                        </ul>
                                        <!-- Tabs -->
                                    </div>
                                </div>
                                <div class="tab-content mt-3" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="current-month" role="tabpanel"
                                        aria-labelledby="pills-home-tab">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 text-center">
                                                <div class="css-bar css-bar-10 css-bar-xlg css-bar-primary">
                                                    <div class="data-text">
                                                        <h1 class="success-rate text-info fw-light">
                                                            {{$successratecounts}}<span class="icon">%</span>
                                                        </h1>
                                                        <div class="rate-label font-15">Success Rate</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ---------------------
                            end Email Campaign
                        ---------------- -->

                    </div>

                </div>


                <div class="row mt-5">

                    <div class="col bg-light py-2 px-3 border">
                        <div class="card w-100" style="margin-bottom: 0px;">
                            <div class="card-body">
                                <h4 class="card-title">Top Performers</h4>
                                <h5 class="card-subtitle" style="font-size: 12px;color: var(--bs-card-title-color);">Top
                                    5 Performers of the month</h5>
                            </div>
                        </div>
                      <ul class="list-style-none mt-400">
    @foreach ($topPerformers as $performer)
    <div style="margin-bottom: 12px;">
        <li>
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">{{ $performer->name }} <span class="fw-light">({{ $performer->total_completed }}/{{ $performer->total_assigned }} Completed)</span></h6>
                </div>
                <div class="ms-auto">
                    <h6 class="mb-0 fw-bold">{{ number_format($performer->percentage_completed, 2) }}%</h6>
                </div>
            </div>
            <div class="progress mt-2">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ number_format($performer->percentage_completed, 2) }}%" aria-valuenow="{{ number_format($performer->percentage_completed, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </li>
    </div>
    @endforeach
</ul>

                    </div>

                    <div class="col bg-light py-2 px-3 border">
                        <div class="card w-100" style="margin-bottom: 0px;">
                            <div class="card-body">
                                <h4 class="card-title">Poor Performers</h4>
                                <h5 class="card-subtitle" style="font-size: 12px;color: var(--bs-card-title-color);">Top
                                    5 Poor Performers
                                    of the month</h5>
                            </div>
                        </div>
                     <ul class="list-style-none mt-400">
    @foreach ($goodPerformers as $performer)
    <div style="margin-bottom: 12px;">
        <li>
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">{{ $performer->name }}<span class="fw-light">({{ $performer->total_completed }}/{{ $performer->total_assigned }} Completed)</span></h6>
                </div>
                <div class="ms-auto">
                    <h6 class="mb-0 fw-bold">{{ number_format($performer->percentage_completed, 2) }}%</h6>
                </div>
            </div>
            <div class="progress mt-2">
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ number_format($performer->percentage_completed, 2) }}%" aria-valuenow="{{ number_format($performer->percentage_completed, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </li>
    </div>
    @endforeach
</ul>

                    </div>

                    <div class="col bg-light py-2 px-3 border">
                        <div class="card w-100" style="margin-bottom: 0px;">
                            <div class="card-body">
                                <h4 class="card-title">Critical Performers</h4>
                                <h5 class="card-subtitle" style="font-size: 12px;color: var(--bs-card-title-color);">
                                    Top 5 Critical performance of the month</h5>
                            </div>
                        </div>
                       <ul class="list-style-none mt-400">
    @foreach ($poorPerformers as $performer)
    <div style="margin-bottom: 12px;">
        <li>
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">{{ $performer->name }}<span class="fw-light">({{ $performer->total_completed }}/{{ $performer->total_assigned }} Completed)</span></h6>
                </div>
                <div class="ms-auto">
                    <h6 class="mb-0 fw-bold">{{ number_format($performer->percentage_completed, 2) }}%</h6>
                </div>
            </div>
            <div class="progress mt-2">
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ number_format($performer->percentage_completed, 2) }}%" aria-valuenow="{{ number_format($performer->percentage_completed, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </li>
    </div>
    @endforeach
</ul>

                    </div>


                </div>


                <div class="row mt-5">

                    <div class="col bg-light py-2 px-3 border">

                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <h4 class="card-title">Technician Performance Report<br /><span
                                            class="card-subtitle"
                                            style="font-size: 12px;color: var(--bs-card-title-color);">Detailed
                                            Performance Report of the month</span></h4>
                                    <button type="button"
                                        class="justify-content-center w-100 btn btn-rounded btn-outline-primary d-flex align-items-center"
                                        style="width: 160px !important; margin-left: 20px;margin-top: -10px;"><i
                                            class="fas fa-download fill-white me-2"></i>Download</button>
                                    {{-- <button type="button"
                                        class="justify-content-center w-100 btn btn-rounded btn-outline-primary d-flex align-items-center"
                                        style="width: 160px !important; margin-left: 20px;margin-top: -10px;"><i
                                            class="fas fa-print fill-white me-2"></i>Print</button> --}}

                                    <div class="ms-auto d-flex no-block align-items-center">
                                        <div class="dl">
                                            <select class="form-select">
                                                <option value="0" selected>Monthly</option>
                                                <option value="1">Daily</option>
                                                <option value="2">Weekly</option>
                                                <option value="3">Yearly</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                      <ul class="list-style-none mt-400">
    @foreach ($allPerformers as $performer)
    <div style="margin-bottom: 12px;">
        <li>
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0 fw-bold">{{ $performer->name }}<span class="fw-light">({{ $performer->total_completed }}/{{ $performer->total_assigned }} Completed)</span></h6>
                </div>
                <div class="ms-auto">
                    <h6 class="mb-0 fw-bold">{{ number_format($performer->percentage_completed, 2) }}%</h6>
                </div>
            </div>
            <div class="progress mt-2">
                @if ($performer->percentage_completed >= 50)
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ number_format($performer->percentage_completed, 2) }}%" aria-valuenow="{{ number_format($performer->percentage_completed, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
                @elseif ($performer->percentage_completed >= 30 && $performer->percentage_completed < 50)
                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ number_format($performer->percentage_completed, 2) }}%" aria-valuenow="{{ number_format($performer->percentage_completed, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
                @else
                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ number_format($performer->percentage_completed, 2) }}%" aria-valuenow="{{ number_format($performer->percentage_completed, 2) }}" aria-valuemin="0" aria-valuemax="100"></div>
                @endif
            </div>
        </li>
    </div>
    @endforeach
</ul>

                </div>


            </div>

        </div>





    </div>
</div>
</div>
</div>
</div>

@section('script')
<script src="{{ asset('public/admin/dist/js/app.min.js')}}"></script>
<script src="{{ asset('public/admin/dist/js/app.init.js')}}"></script>
<script src="{{ asset('public/admin/dist/js/app-style-switcher.js')}}"></script>
<script src="{{ asset('public/admin/dist/libs/jvectormap/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('public/admin/assets/extra-libs/jvectormap/jquery-jvectormap-us-aea-en.js') }}"></script>
    <script src="{{asset('public/admin/dist/libs/apexcharts/dist/apexcharts.min.js')}}"></script>
    <script src="{{asset('public/admin/dist/js/pages/dashboards/dashboard6.js')}}"></script>

@endsection
@stop
