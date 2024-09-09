@extends('home')

@section('content')
    <div class="page-breadcrumb">

        <div class="row">
            <div class="col-8">
                <h4 class="page-title text-info fw-bold"> {{ $siteSettings->business_name ?? null }}</h4>
            </div>
            <div class="col-4">
                <div class="text-end text-primary fw-bold">
                    @php
                        use Carbon\Carbon;
                        $currentFormattedDate = Carbon::now($timezoneName)->format('D d, M Y');
                        $currentFormattedDateTime = Carbon::now($timezoneName)->format('h:i:s A T');
                    @endphp

                    {{ $currentFormattedDate }}
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

        <div class="row">

            <div class="col-8">

                <div class="row">
                    <div class="col-12">
                        <div class="bg-success shadow mb-4 py-3 px-3" style="color: #FFF;border-radius: 4px;">
                            <h5 class="uppercase">Welcome to {{ $siteSettings->business_name ?? null }}!</h5>
                            <div class="mt-1">{!! $siteSettings->description_long ?? null !!}</div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-border shadow">
                            <div class="card-body">




                                <div class="d-md-flex align-items-center mt-2">
                                    <div>
                                        <h5 class="card-title text-info uppercase mb-1">Approval Pending to Close the Job
                                        </h5>
                                        <h5 class="ft12">Technician marked the job as closed
                                        </h5>
                                    </div>
                                </div>
                                <div class="table-responsive mt-1">
                                    <table id="" class="table table-bordered text-nowrap">
                                        <thead class="uppercase">
                                            <tr>
                                                <th>Jobs Details</th>
                                                <th>Customer</th>
                                                <th>Technician</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jobcompleteyes as $item)
                                                <tr>
                                                    <td>
                                                        <a href="{{ url('tickets/' . $item->JobModel->id) }}"
                                                            class="font-medium link">
                                                            {{ $item->JobModel->job_title ?? null }}
                                                        </a><br />
                                                        {{ Str::limit($item->JobModel->description ?? '', 20) }}

                                                    </td>

                                                    <td>{{ $item->JobModel->user->name ?? null }}</td>
                                                    <td>{{ $item->technician->name ?? null }}</td>
                                                    <td>
                                                        @php
                                                            $time_interval = Session::get('time_interval', 0);
                                                        @endphp

                                                        @if ($item && $item->start_date_time)
                                                            <div class="font-medium link ft12">
                                                            {{ $modifyDateTime($item->start_date_time ?? null, $time_interval, 'add', 'm-d-Y') }}
                                                            </div>

                                                            <div class="ft12">
                                                               {{ $modifyDateTime($item->start_date_time ?? null, $time_interval, 'add', 'h:i A') }}
                                                                to
                                                              {{ $modifyDateTime($item->end_date_time ?? null, $time_interval, 'add', 'h:i A') }}
                                                            </div>
                                                        @else
                                                            <div></div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>


                            </div>



                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-border shadow">
                            <div class="card-body">




                                <div class="d-md-flex align-items-center mt-2">
                                    <div>
                                        <h5 class="card-title text-info uppercase mb-1">Upcoming Job </h5>
                                        <h5 class="ft12">Overview of Upcoming Job </h5>
                                    </div>
                                </div>
                                <div class="table-responsive mt-1">
                                    <table id="" class="table table-bordered text-nowrap">
                                        <thead class="uppercase">
                                            <tr>
                                                <th>Jobs Details</th>
                                                <th>Customer</th>
                                                <th>Technician</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($job as $item)
                                                <tr>
                                                    <td>
                                                        <a href="{{ url('tickets/' . $item->JobModel->id) }}"
                                                            class="font-medium link">
                                                            {{ $item->JobModel->job_title ?? null }}
                                                        </a><br />
                                                        {{ Str::limit($item->JobModel->description ?? '', 20) }}
                                                    </td>

                                                    <td>{{ $item->JobModel->user->name ?? null }}</td>
                                                    <td>{{ $item->technician->name ?? null }}</td>
                                                    <td>
                                                        @if ($item && $item->start_date_time)
                                                            <div class="font-medium link ft12">
                                                            {{ $modifyDateTime($item->start_date_time ?? null, $time_interval, 'add', 'm-d-Y')}}
                                                            </div>
                                                        @else
                                                            <div></div>
                                                        @endif
                                                         <div class="ft12">
                                                               {{ $modifyDateTime($item->start_date_time ?? null, $time_interval, 'add', 'h:i A') }}
                                                                to
                                                              {{ $modifyDateTime($item->end_date_time ?? null, $time_interval, 'add', 'h:i A') }}
                                                            </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>



                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-border shadow">
                            <div class="card-body">


                                <div class="d-md-flex align-items-center mt-2">
                                    <div>
                                        <h5 class="card-title text-info uppercase mb-1"> Open invoices </h5>
                                        {{-- <h5 class="ft12">Overview of Upcoming Open invoices </h5> --}}
                                    </div>
                                </div>
                                <div class="table-responsive mt-1">
                                    <table id="" class="table table-bordered text-nowrap">
                                        <thead>
                                            <!-- start row -->
                                            <tr>
                                                <th>ID</th>

                                                <th>Customer</th>
                                                <th>Technician</th>
                                                <th>Inv. Date</th>
                                                <th>Due Date</th>
                                                <th>Amount</th>

                                            </tr>
                                            <!-- end row -->
                                        </thead>
                                        <tbody>
                                            <!-- start row -->
                                            @foreach ($paymentclose as $index => $item)
                                                <tr>
                                                    <td><a
                                                            href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number ?? null }}</a>
                                                    </td>

                                                    <td>{{ $item->user->name ?? null }}</td>
                                                    <td>{{ $item->JobModel->technician->name ?? null }}</td>
                                                    <td>{{ $convertDateToTimezone($item->issue_date ?? null) }}</td>
                                                    <td>{{ $convertDateToTimezone($item->due_date ?? null) }}</td>
                                                    <td>${{ $item->total ?? null }}</td>


                                                </tr>
                                                <!-- Modal for adding comment -->
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-border shadow">
                            <div class="card-body">


                                <div class="d-md-flex align-items-center mt-2">
                                    <div>
                                        <h5 class="card-title text-info uppercase mb-1"> Paid invoices </h5>
                                        {{-- <h5 class="ft12">Overview of Upcoming Paid invoices </h5> --}}
                                    </div>
                                </div>
                                <div class="table-responsive mt-1">
                                    <table id="" class="table table-bordered text-nowrap">
                                        <thead>
                                            <!-- start row -->
                                            <tr>
                                                <th>ID</th>

                                                <th>Customer</th>
                                                <th>Technician</th>
                                                <th>Inv. Date</th>
                                                <th>Due Date</th>
                                                <th>Amount</th>

                                            </tr>
                                            <!-- end row -->
                                        </thead>
                                        <tbody>
                                            <!-- start row -->
                                            @foreach ($paymentopen as $index => $item)
                                                <tr>
                                                    <td><a
                                                            href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number ?? null }}</a>
                                                    </td>

                                                    <td>{{ $item->user->name ?? null }}</td>
                                                    <td>{{ $item->JobModel->technician->name ?? null }}</td>
                                                    <td>{{ $convertDateToTimezone($item->issue_date ?? null) }}</td>
                                                    <td>{{ $convertDateToTimezone($item->due_date ?? null) }}</td>
                                                    <td>${{ $item->total ?? null }}</td>


                                                </tr>
                                                <!-- Modal for adding comment -->
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>




                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-border shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table customize-table mb-0 v-middle">
                                        <thead>
                                            <tr>
                                                <th>MY ACTIVITY</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($activity as $record)
                                                <tr>
                                                    <td>
                                                        <div>{{ $record->activity }}</div>
                                                        <div class="text-muted">
                                                            {{ \Carbon\Carbon::parse($record->created_at)->format('D
                                                                                                                                                                                                                                        n/j/y
                                                                                                                                                                                                                                        g:ia') ??
                                                                'null' }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <a class="nav-link text-dark" href="{{ route('myprofile.activity') }}">
                                    <strong>View All</strong>
                                    <i data-feather="chevron-right" class="feather-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-border shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table customize-table mb-0 v-middle">
                                        <thead>
                                            <tr>
                                                <th>MY NOTIFICATIONS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($userNotifications as $record)
                                                <tr>
                                                    <td @if ($record->is_read == 0) class="text-muted" @endif>
                                                        <div class="fw-normal">
                                                            {{ $record->notice->notice_title ?? '' }}
                                                        </div>
                                                        <div class="text-muted">
                                                            {{ \Carbon\Carbon::parse($record->notice->notice_date)->format('D
                                                                                                                                                                                                                                        n/j/y g:ia') ??
                                                                'null' }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <a class="nav-link text-dark" href="{{ route('myprofile.activity') }}">
                                    <strong>View All </strong>
                                    <i data-feather="chevron-right" class="feather-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="row mt-3">
                    <div>
                        <h5 class="card-title text-info uppercase mb-2 px-1">Active Technicians</h5>
                    </div>
                </div>
                <div class="row">
                    @foreach ($technicianuser as $item)
                        <div class="col-lg-4">
                            <div class="card card-border shadow">
                                <div class="card-body">
                                    <h5 class="card-title ft13 uppercase text-primary"> {{ $item->name ?? null }}</h5>
                                    <h6 class="ft11 mb-2 d-flex align-items-center">
                                        <i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i>
                                        @if (isset($item->area_name) && !empty($item->area_name))
                                            {{ $item->area_name ?? null }}
                                        @endif
                                    </h6>
                                    <p class="card-text pt-2 ft12">
                                        {{ $item->completed_jobs_count }}/{{ $item->total_jobs_count }} Job
                                        Completed<br />
                                        Completion Rate: {{ number_format($item->completion_rate, 2) }}%
                                    </p>
                                    <a href="{{ route('technicians.show', ['id' => $item->id]) }}" class="card-link">View
                                        Profile</a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>


                <div class="row mt-3">
                    <div>
                        <h5 class="card-title uppercase text-info text-info mb-2 px-1">Top Customers</h5>
                    </div>
                </div>
                <div class="row">
                    @foreach ($customeruser as $user)
                        <div class="col-lg-4">
                            <div class="card card-border shadow">
                                <div class="card-body">
                                    <h5 class="card-title ft13 uppercase text-primary">{{ $user->name ?? null }}</h5>
                                    @foreach ($user->user_addresses as $address)
                                        <h6 class="ft11 mb-2 d-flex align-items-center">
                                            <i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i>
                                            {{ $address->address_line1 ?? null }}, {{ $address->city ?? null }},
                                            {{ $address->state_name ?? null }}, {{ $address->zipcode ?? null }}
                                        </h6>
                                    @endforeach
                                    <p class="card-text pt-2 ft12">
                                        {{ count($user->jobs) }} Jobs<br />
                                        LifetimeValue: ${{ $user->gross_total ?? 0 }}</p>
                                    <a href="{{ route('users.show', ['id' => $user->id]) }}" class="card-link">View
                                        Profile</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>





            </div>

            <div class="col-4">


                <div class="card card-border shadow ">
                    <div class="card-body">
                        <h5 class="card-title uppercase text-info">Jobs by manufacturer</h5>
                        <div id="chart-pie-simple"></div>
                    </div>
                </div>

                <div class="card card-border shadow mt-4">
                    <div class="card-body">
                        <h5 class="card-title uppercase text-info">Jobs by Service Types</h5>
                        <div id="chart-pie-donut"></div>
                    </div>
                </div>

                <div class="card card-border shadow">
                    <div class="card-body">
                        <div class="row">
                        <h4>Stats</h4>
                            <div class="col-6 mb-3">
                                <div class="text-white bg-primary rounded">
                                    <div class="card-body">
                                        <span><i class="ri-group-line" style="font-size: 36px;"></i></span>
                                        <h3 class="card-title mt-1 mb-0 text-white">{{ $customerCount ?? null }}</h3>
                                        <p class="card-text text-white-50 fs-3 fw-normal">Customers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="text-white bg-warning rounded">
                                    <div class="card-body">
                                        <span><i class="ri-contacts-line" style="font-size: 36px;"></i></span>
                                        <h3 class="card-title mt-1 mb-0 text-white">{{ $technicianCount ?? null }}</h3>
                                        <p class="card-text text-white-50 fs-3 fw-normal">Technicians</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-white bg-success rounded">
                                    <div class="card-body">
                                        <span><i class="ri-admin-line" style="font-size: 36px;"></i></span>
                                        <h3 class="card-title mt-1 mb-0 text-white">{{ $dispatcherCount ?? null }}</h3>
                                        <p class="card-text text-white-50 fs-3 fw-normal">Dispatchers</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-white bg-danger rounded">
                                    <div class="card-body">
                                        <span><i class="ri-admin-fill" style="font-size: 36px;"></i></span>
                                        <h3 class="card-title mt-1 mb-0 text-white">{{ $adminCount ?? null }}</h3>
                                        <p class="card-text text-white-50 fs-3 fw-normal">Admin</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="card card-border shadow mt-4">
                    <div class="card-body">
                        <h5 class="card-title text-info uppercase">Quick Links</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a
                                    href="{{ route('download') }}">Download App </a></li>
                            <li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a
                                    href="#.">View
                                    Website </a></li>
                            <li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a
                                    href="{{ route('contact') }}">Contact Support </a></li>
                            <li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a
                                    href="{{ route('about') }}">About Dispat Channel</a></li>
                            <li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a
                                    href="{{ route('reviews') }}">Reviews</a></li>
                            <li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a
                                    href="{{ route('privacy') }}">Privacy Policy </a></li>
                            <li class="list-group-item"><i class="ri-file-list-line feather-sm me-2"></i> <a
                                    href="{{ route('documentation') }}">Documentation </a></li>
                        </ul>
                    </div>
                </div>



            </div>

        </div>






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

    </div>

@endsection
