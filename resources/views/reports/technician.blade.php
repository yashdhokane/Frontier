@extends('home')

@section('content')

    <div class="page-wrapper" style="display:inline;">
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <div class="page-breadcrumb" style="padding-top: 0px;">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Technician Report</h4>
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

                        <div class="col-md-6">
                            <div class="card shadow card-border">
                                <div class="card-body">
                                    <h5 class="card-title uppercase">Jobs completed by technician</h5>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-striped  customize-table mb-0 v-middle overflow-auto">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th class="border-bottom border-top">Technician Name</th>
                                                            <th class="border-bottom border-top">Job Revenue</th>
                                                            <th class="border-bottom border-top">Job Count</th>
                                                            <th class="border-bottom border-top">Avg. Job Size</th>
                                                        </tr>
                                                        <tr class="table-success border-success">
                                                            <th class="border-bottom border-top">Total</th>
                                                            <th class="border-bottom border-top">${{ $job }}</th>
                                                            <th class="border-bottom border-top">{{ $jobcount }}</th>
                                                            @if ($jobcount > 0)
                                                                <td>${{ intval($job / $jobcount) }}
                                                                </td>
                                                            @else
                                                                <td>N/A</td>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($technician as $user)
                                                            <tr>
                                                                <td>{{ $user->name ?? null }}</td>
                                                                <td>${{ $grossTotalByTechnician[$user->id] }}</td>
                                                                <td>{{ $jobCountsByTechnician[$user->id] }}</td>
                                                                @if ($jobCountsByTechnician[$user->id] > 0)
                                                                    <td>${{ intval($grossTotalByTechnician[$user->id] / $jobCountsByTechnician[$user->id]) }}
                                                                    </td>
                                                                @else
                                                                    <td>N/A</td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="card shadow card-border">
                                <div class="card-body">
                                    <h5 class="card-title uppercase">Time tracking (completed jobs)</h5>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-striped  customize-table mb-0 v-middle overflow-auto">
                                                    <thead class="table-dark">
                                                        <tr>
                                                            <th class="border-bottom border-top">Technician Name</th>
                                                            <th class="border-bottom border-top">Total On Job Hrs</th>
                                                            <th class="border-bottom border-top">Total Travel Hrs</th>
                                                            <th class="border-bottom border-top">Total Hrs Per Job</th>
                                                        </tr>
                                                        <tr class="table-success border-success">
                                                            <th class="border-bottom border-top">Total</th>
                                                            <th class="border-bottom border-top">{{ $totalHours / 60 }}</th>
                                                            <th class="border-bottom border-top">
                                                                {{ $totaldrivingHours / 60 }}
                                                            </th>
                                                            @if ($totaldrivingHours > 0)
                                                                <td>${{ intval($totalHours / $totaldrivingHours)  }}
                                                                </td>
                                                            @else
                                                                <td>N/A</td>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($technician as $user)
                                                            <tr>
                                                                <td>{{ $user->name ?? null }}</td>
                                                                <td>{{ $jobHours[$user->id] / 60 }}</td>
                                                                <td>{{ $drivingHours[$user->id] / 60 }}</td>
                                                                @if ($drivingHours[$user->id] > 0)
                                                                    <td>{{ intval($jobHours[$user->id] / $drivingHours[$user->id]) }}
                                                                    </td>
                                                                @else
                                                                    <td>N/A</td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>



                </div>





            </div>
        </div>
    </div>
    </div>

@stop
