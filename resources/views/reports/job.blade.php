@extends('home')

@section('content')
    <style>
        .list-group-item {
            display: flex;
            justify-content: space-between;
        }
    </style>

    <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h3 class="page-title">Job Report</h3>
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

                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Date</h4>
                                    <ul class="list-group list-group-flush" style="margin-left: -20px;">
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Job revenue earned</span>
                                            </div>
                                            <div>${{ $todayGrossTotalSum }}</div>
                                        </li>
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Average job size</span></div>
                                            <div>${{ intval($monthGrossTotalSum / $mothlyjobcount) }}</div>
                                        </li>
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Job count</span></div>
                                            <div>{{ $todayjobcount }}</div>
                                        </li>
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Daily</span></div>
                                            <div>{{ $daillyjobcount }}</div>
                                        </li>
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Weekly</span></div>
                                            <div>{{ $weeklyjobcount }}</div>
                                        </li>
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Monthly</span></div>
                                            <div>{{ $monthjobcount }}</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Type</h4>
                                    <ul class="list-group list-group-flush" style="margin-left: -20px;">
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Job tags</span></div>
                                            <div>{{ $totalSiteTagUsage }}</div>
                                        </li>
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Priority</span></div>
                                            <div>
                                                <div class="d-flex justify-content-between"> <span>Low:</span> <span>
                                                        {{ $lowcount }}</span></div>
                                                <div class="d-flex justify-content-between"> <span>Medium:</span>
                                                    <span>{{ $mediumcount }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between"> <span>High:</span>
                                                    <span>{{ $highcount }}</span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Job lead source</span></div>
                                            <div>{{ $sourcelead }}</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Customer</h4>
                                    <ul class="list-group list-group-flush" style="margin-left: -20px;">
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Customer name</span></div>
                                            <div>{{ $customerCount }}</div>
                                        </li>
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Zip code</span></div>
                                            <div>{{ $zipcode }}</div>
                                        </li>
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>City</span></div>
                                            <div>{{ $city }}</div>
                                        </li>
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>State</span></div>
                                            <div>{{ $zipcode }}</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Other</h4>
                                    <ul class="list-group list-group-flush" style="margin-left: -20px;">
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Manufacturers</span></div>
                                        </li>
                                        <li class="list-group-item">
                                            <div><i class="ri-bar-chart-line mx-2"></i> <span>Appliances</span></div>
                                            <div>{{ $applianaces }}</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>


                    </div>



                </div>





            </div>
        </div>
    </div>
@stop
