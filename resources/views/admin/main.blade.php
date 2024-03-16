@extends('home')

@section('content')
    <div class="page-breadcrumb">

        <div class="row">

            <div class="col-5 align-self-center">

                <h4 class="page-title">Frontier Services Inc</h4>

                <div class="d-flex align-items-center">

                    <nav aria-label="breadcrumb">

                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="#">Home</a></li>

                            <li class="breadcrumb-item active" aria-current="page">Dashboard </li>

                        </ol>

                    </nav>

                </div>

            </div>

            <div class="col-7 align-self-center">

                <div class="d-flex no-block justify-content-end align-items-center">

                    <div class="me-2">

                        <div class="lastmonth"></div>

                    </div>

                    <div class="">

                        <small>LAST MONTH</small>

                        <h4 class="text-info mb-0 font-medium">$58,256</h4>

                    </div>

                </div>

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

        <!-- -------------------------------------------------------------- -->

        <!-- Sales chart -->

        <!-- -------------------------------------------------------------- -->

        <div class="row">

            <div class="col-12">

                <!-- ---------------------

                                                start Sales Summary

                                            ---------------- -->

                <div class="card">

                    <div class="card-body">

                        <div class="d-md-flex align-items-center">

                            <div>

                                <h4 class="card-title">Sales Summary</h4>

                                <h5 class="card-subtitle">Overview of Latest Month</h5>

                            </div>

                            <div class="ms-auto d-flex no-block align-items-center">

                                <ul class="list-inline fs-2 dl me-3 mb-0">

                                    <li class="list-inline-item text-info">

                                        <i class="ri-checkbox-blank-circle-fill align-middle fs-3"></i>

                                        Current Year

                                    </li>

                                    <li class="list-inline-item text-primary">

                                        <i class="ri-checkbox-blank-circle-fill align-middle fs-3"></i>

                                        Last Year

                                    </li>

                                </ul>

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

                        <div class="row">

                            <!-- column -->

                            <div class="col-lg-4 d-md-flex d-lg-block justify-content-between">

                                <div>

                                    <h1 class="mb-0 mt-4">$6,890.68</h1>

                                    <h6 class="fw-light text-muted">Current Month Earnings</h6>

                                </div>

                                <div>

                                    <h3 class="mt-4 mb-0">1,540</h3>

                                    <h6 class="fw-light text-muted mb-md-0 mt-md-2 mt-lg-0 mt-0">

                                        Current Month Sales

                                    </h6>

                                </div>

                                <a class="btn btn-info mt-3 p-3 pl-4 pr-4 mb-3" href="javascript:void(0)">Last Month
                                    Summary</a>

                            </div>

                            <!-- column (sales summery chart) -->

                            <div class="col-lg-8">

                                <div class="sales-summery"></div>

                            </div>

                            <!-- column -->

                        </div>

                    </div>

                    <!-- -------------------------------------------------------------- -->

                    <!-- Wallet  Summary-->

                    <!-- -------------------------------------------------------------- -->

                    <div class="card-body border-top">

                        <div class="row mb-0">

                            <!-- col -->

                            <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">

                                <div class="d-flex align-items-center">

                                    <div class="me-2">

                                        <span class="text-orange display-5"><i class="ri-wallet-2-fill"></i></span>

                                    </div>

                                    <div>

                                        <span>Current Balance</span>

                                        <h3 class="font-medium mb-0">$3,567.53</h3>

                                    </div>

                                </div>

                            </div>

                            <!-- col -->

                            <!-- col -->

                            <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">

                                <div class="d-flex align-items-center">

                                    <div class="me-2">

                                        <span class="text-cyan display-5"><i class="ri-money-cny-circle-fill"></i></span>

                                    </div>

                                    <div>

                                        <span>Daily Earnings</span>

                                        <h3 class="font-medium mb-0">$769.08</h3>

                                    </div>

                                </div>

                            </div>

                            <!-- col -->

                            <!-- col -->

                            <div class="col-lg-3 col-md-6 mb-3 mb-md-0">

                                <div class="d-flex align-items-center">

                                    <div class="me-2">

                                        <span class="text-info display-5"><i class="ri-shopping-bag-fill"></i></span>

                                    </div>

                                    <div>

                                        <span>Estimate Sales</span>

                                        <h3 class="font-medium mb-0">589</h3>

                                    </div>

                                </div>

                            </div>

                            <!-- col -->

                            <!-- col -->

                            <div class="col-lg-3 col-md-6">

                                <div class="d-flex align-items-center">

                                    <div class="me-2">

                                        <span class="text-primary display-5"><i data-feather="dollar-sign"></i></span>

                                    </div>

                                    <div>

                                        <span>Total Earnings</span>

                                        <h3 class="font-medium mb-0">$23,568.90</h3>

                                    </div>

                                </div>

                            </div>

                            <!-- col -->

                        </div>

                    </div>

                </div>

                <!-- ---------------------

                                                end Sales Summary

                                            ---------------- -->

            </div>

        </div>

        <!-- -------------------------------------------------------------- -->

        <!-- Sales chart -->

        <!-- -------------------------------------------------------------- -->





        <div class="row">

            <div class="col-12">

                <!-- Start Simple Pie Chart -->

                <div class="row align-items-stretch">

                    <div class="col-lg-6">

                        <div class="card">

                            <div class="card-body">

                                <h4 class="card-title">Calls by Service Types</h4>

                                <div id="chart-pie-simple"></div>

                            </div>

                        </div>

                    </div>

                    <!-- Start Dinut Pie Chart -->

                    <div class="col-lg-6">

                        <div class="card">

                            <div class="card-body">

                                <h4 class="card-title">Earnings by Service Types</h4>

                                <div id="chart-pie-donut"></div>

                            </div>

                        </div>

                    </div>

                    <!-- End Dinut Pie Chart -->

                </div>

                <!-- End Simple Pie Chart -->

            </div>

        </div>





        <div class="row">

            <div class="col-12">

                <!-- ---------------------

                                                start Tickets

                                            ---------------- -->

                <div class="card">

                    <div class="card-body">

                        <h4 class="card-title">Tickets</h4>

                        <div class="row mt-4">

                            <!-- Column -->

                            <div class="col-md-6 col-lg-3 col-xlg-3">

                                <div class="card card-hover">

                                    <div class="p-2 rounded bg-light-primary text-center">

                                        <h1 class="fw-light text-primary">{{$totalCalls}}</h1>

                                        <h6 class="text-primary">Total Tickets</h6>

                                    </div>

                                </div>

                            </div>

                            <!-- Column -->

                            <div class="col-md-6 col-lg-3 col-xlg-3">

                                <div class="card card-hover">

                                    <div class="p-2 rounded bg-light-warning text-center">

                                        <h1 class="fw-light text-warning">{{$inProgress}}</h1>

                                        <h6 class="text-warning">In Progress</h6>

                                    </div>

                                </div>

                            </div>

                            <!-- Column -->

                            <div class="col-md-6 col-lg-3 col-xlg-3">

                                <div class="card card-hover">

                                    <div class="p-2 rounded bg-light-success text-center">

                                        <h1 class="fw-light text-success">{{$opened}}</h1>

                                        <h6 class="text-success">Opened</h6>

                                    </div>

                                </div>

                            </div>

                            <!-- Column -->

                            <div class="col-md-6 col-lg-3 col-xlg-3">

                                <div class="card card-hover">

                                    <div class="p-2 rounded bg-light-danger text-center">

                                        <h1 class="fw-light text-danger">{{$complete}}</h1>

                                        <h6 class="text-danger">Closed</h6>

                                    </div>

                                </div>

                            </div>

                            <!-- Column -->

                        </div>

                    </div>

                </div>

            </div>

        </div>





        <!-- Table -->

        <!-- -------------------------------------------------------------- -->

        <div class="row">

            <div class="col-lg-12">

                <!-- ---------------------

                                                start Projects of the Month

                                            ---------------- -->

                <div class="card">

                    <div class="card-body">

                        <div class="d-md-flex align-items-center">

                            <div>

                                <h4 class="card-title">Recent Tickets</h4>

                                <h5 class="card-subtitle">Overview of Recent Tickets</h5>

                            </div>

                            {{-- <div class="ms-auto d-flex no-block align-items-center">

                                <div class="dl">

                                    <select class="form-select">

                                        <option value="0" selected>Monthly</option>

                                        <option value="1">Daily</option>

                                        <option value="2">Weekly</option>

                                        <option value="3">Yearly</option>

                                    </select>

                                </div>

                            </div> --}}

                        </div>

                        <div class="table-responsive mt-4">

                            <table id="" class="table table-bordered text-nowrap">

                                <thead>

                                    <!-- start row -->

                                    <tr>

                                        <th>Status</th>

                                        <th>Title</th>

                                        <th>ID</th>

                                        <th>Customer</th>

                                        <th>Date</th>

                                        <th>Technician</th>

                                    </tr>

                                    <!-- end row -->

                                </thead>

                                <tbody>

                                    <!-- start row -->
                                    @foreach ($job as $item)
                                        <tr>

                                            <td>

                                                <span
                                                    class="badge bg-light-warning text-warning font-medium">{{ $item->status }}</span>

                                            </td>

                                            <td>

                                                <a href="{{ url('tickets/' . $item->id) }}"

                                                    class="font-medium link">{{ $item->job_title }}</a><br />
                                                @if ($item->jobDetails)
                                                    <span style="font-size:12px;">
                                                        Model: {{ $item->jobDetails->model_number ?? 'N/A' }} /
                                                        Serial Number: {{ $item->jobDetails->serial_number ?? 'N/A' }}
                                                    </span>
                                                @else
                                                    <span style="font-size:12px;">
                                                        Model: N/A / Serial Number: N/A
                                                    </span>
                                                @endif

                                            </td>

                                            <td>

                                                <a href="ticket-detail.html" class="fw-bold link"><span
                                                        class="mb-1 badge bg-primary">{{ $item->job_code }}</span></a>

                                            </td>



                                            <td>{{ $item->user->name ?? null}}</td>

                                            <td>
                                                {{ $convertDateToTimezone($item->created_at, null, 'm-d-Y') ?? '' }}

                                            </td>
                                            
                                            <td>{{ $item->technician->name }}</td>

                                        </tr>
                                    @endforeach
                                    <!-- end row -->


                                </tbody>



                            </table>

                        </div>





                    </div>

                </div>

                <!-- ---------------------

                                                end Projects of the Month

                                            ---------------- -->

            </div>

        </div>

        <!-- -------------------------------------------------------------- -->

        <!-- Table -->









        <!-- -------------------------------------------------------------- -->

        <!-- -------------------------------------------------------------- -->

        <!-- Ravenue - page-view-bounce rate -->

        <!-- -------------------------------------------------------------- -->

        <div class="row">

            <!-- column -->

            <div class="col-lg-4 d-flex align-items-stretch">

                <!-- ---------------------

                                                start Revenue Statistics

                                            ---------------- -->

                <div class="card bg-info text-white card-hover w-100">

                    <div class="card-body">

                        <h4 class="card-title">Revenue Statistics</h4>

                        <div class="d-flex align-items-center mt-4">

                            <div class="" id="ravenue"></div>

                            <div class="ms-auto">

                                <h3 class="font-medium white-text mb-0">$35661</h3>

                                <span class="white-text op-5">Jan 2023 - Oct 2023</span>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ---------------------

                                                end Revenue Statistics

                                            ---------------- -->

            </div>

            <!-- column -->

            <div class="col-lg-4 d-flex align-items-stretch">

                <!-- ---------------------

                                                start Page Views

                                            ---------------- -->

                <div class="card bg-cyan text-white card-hover w-100">

                    <div class="card-body">

                        <h4 class="card-title">New Customers</h4>

                        <h3 class="white-text mb-0"><i class="ri-arrow-up-line"></i> 658</h3>

                    </div>



                    <div class="card-body">

                        <h4 class="card-title">New Technicians</h4>

                        <h3 class="white-text mb-0"><i class="ri-arrow-up-line"></i> 45</h3>

                    </div>

                    <div class="mt-3" id="viewsDDD"></div>

                </div>

                <!-- ---------------------

                                                end Page Views

                                            ---------------- -->

            </div>

            <!-- column -->

            <div class="col-lg-4 d-flex align-items-stretch">

                <!-- ---------------------

                                                start Bounce Rate

                                            ---------------- -->

                <div class="card card-hover w-100">

                    <div class="card-body">

                        <h4 class="card-title">Growth Rate</h4>

                        <div class="d-flex no-block align-items-center mt-4">

                            <div class="">

                                <h3 class="font-medium mb-0">56.33%</h3>

                                <span class="">Total Growth</span>

                            </div>

                            <div class="ms-auto">

                                <div>

                                    <div id="bouncerate"></div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ---------------------

                                                end Bounce Rate

                                            ---------------- -->

            </div>

        </div>

        <!-- -------------------------------------------------------------- -->

        <!-- Ravenue - page-view-bounce rate -->

        <!-- -------------------------------------------------------------- -->

        <!-- -------------------------------------------------------------- -->

        <!-- Table -->

        <!-- -------------------------------------------------------------- -->

        <div class="row">

            <div class="col-lg-12">

                <!-- ---------------------

                                                start Projects of the Month

                                            ---------------- -->

                <div class="card">

                    <div class="card-body">

                        <div class="d-md-flex align-items-center">

                            <div>

                                <h4 class="card-title">Active Technicians</h4>

                                <h5 class="card-subtitle">Overview of Active Technicians</h5>

                            </div>

                            {{-- <div class="ms-auto d-flex no-block align-items-center">

                                <div class="dl">

                                    <select class="form-select">

                                        <option value="0" selected>Monthly</option>

                                        <option value="1">Daily</option>

                                        <option value="2">Weekly</option>

                                        <option value="3">Yearly</option>

                                    </select>

                                </div>

                            </div> --}}

                        </div>

                        <div class="table-responsive">

                            <table class="table no-wrap v-middle">

                                <thead class="header-item">


                                    <th>Name</th>
                                    <th>Contacts</th>
                                    <th>Service Area</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                    <!-- Loop through each user -->
                                    @foreach ($users as $user)
                                        <tr class="search-items">

                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($user->user_image)
                                                        <img src="{{ asset('public/images/technician/' . $user->user_image) }}"
                                                            class="rounded-circle" width="45" />
                                                    @else
                                                        <img src="{{ asset('public/images/login_img_bydefault.png') }}"
                                                            alt="avatar" class="rounded-circle" width="45" />
                                                    @endif

                                                    <div class="ms-2">
                                                        <div class="user-meta-info">
                                                            <a href="{{ route('technicians.show', $user->id) }}">
                                                                <h5 class="user-name mb-0" data-name="name">
                                                                    {{ $user->name }}
                                                                </h5>
                                                            </a>

                                                            <span class="badge bg-dark">{{ $user->status }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12">

                                                        <span
                                                            class="user-work text-muted">{{ $user->email }}</span><br />
                                                        <span
                                                            class="user-work text-muted">{{ $user->mobile }}</span><br />
                                                    </div>



                                                    <div class="row">
                                                        <div class="col-12">
                                                            <span class="badge bg-info"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </td>
                                            <td>

                                                @if (isset($user->area_name) && !empty($user->area_name))
                                                    {{ $user->area_name }}
                                                @endif
                                            </td>
                                            <td class="action footable-last-visible" style="display: table-cell;">
                                                <div class="action-btn" style="display:flex">
                                                    <a href="{{ route('technicians.show', $user->id) }}"
                                                        class="text-info edit">
                                                        <span class="badge bg-info">
                                                            <i data-feather="eye" class="feather-sm fill-white"></i> View
                                                        </span>
                                                    </a>
                                                    <a href="{{ route('technicians.edit', $user->id) }}"
                                                        class="text-info edit ms-2">
                                                        <span class="badge bg-success">
                                                            <i data-feather="eye" class="feather-sm fill-white"></i> Edit
                                                        </span>
                                                    </a>



                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>



                            </table>

                        </div>

                    </div>

                </div>

                <!-- ---------------------

                                                end Projects of the Month

                                            ---------------- -->

            </div>

        </div>

        <!-- -------------------------------------------------------------- -->

        <!-- Table -->

        <!-- -------------------------------------------------------------- -->





        <!-- -------------------------------------------------------------- -->

        <!-- Recent comment and chats -->

        <!-- -------------------------------------------------------------- -->

        <div class="row">

            <!-- column -->

            <div class="col-lg-6">

                <br />

            </div>

            <!-- column -->

            <div class="col-lg-6">

                <br />

            </div>

        </div>

        <!-- -------------------------------------------------------------- -->

        <!-- Recent comment and chats -->

        <!-- -------------------------------------------------------------- -->

    </div>
@endsection
