@extends('home')
@section('content')
    <!-- Page wrapper  -->

    @php
    $address = '';
    if (isset($location->address_line1) && $location->address_line1 !== '') {
        $address .= $location->address_line1 . ', ';
    }
    if (isset($location->address_line2) && $location->address_line2 !== '') {
        $address .= $location->address_line2 . ', ';
    }
    if (isset($user->Location->city) && $user->Location->city !== '') {
        $address .= $user->Location->city . ', ';
    }
    if (isset($location->state_name) && $location->state_name !== '') {
        $address .= $location->state_name . ', ';
    }
    if (isset($location->zipcode) && $location->zipcode !== '') {
        $address .= $location->zipcode;
    }
@endphp


    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-9 align-self-center">
                <h4 class="page-title">{{ $commonUser->name }} <small class="text-muted"
                        style="font-size: 10px;">Technician</small></h4>
            </div>
            <div class="col-3 text-end px-4">
                <a href="https://dispatchannel.com/portal/technicians"
                    class="justify-content-center d-flex align-items-center"><i class="ri-contacts-line"
                        style="margin-right: 8px;"></i> Back to Technicians List </a>
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

        @if (Session::has('success'))
            <div class="alert_wrap">
                <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
                    {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- Row -->
        <div class="row">
            <!-- Column -->

            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-12 col-xlg-9 col-md-7">
                <!-- ---------------------
                                                    start Timeline
                                                ---------------- -->
                <div class="card">
                    <!-- Tabs -->
                    <ul class="nav nav-pills custom-pills nav-fill flex-column flex-sm-row user_profile_tabs" id="pills-tab"
                        role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" href="#profile_tab"
                                role="tab" aria-controls="pills-profile" aria-selected="true"><i
                                    class="ri-contacts-line"></i> Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-setting-tab" data-bs-toggle="pill" href="#calls_tab"
                                role="tab" aria-controls="pills-setting" aria-selected="false"><i
                                    class="fas fa-calendar-check"></i> Calls</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-payment-tab" data-bs-toggle="pill" href="#payment_tab"
                                role="tab" aria-controls="pills-payments" aria-selected="false"><i
                                    class="ri-money-dollar-box-line"></i> Payments</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#estimate_tab"
                                role="tab" aria-controls="pills-timeline" aria-selected="false"><i
                                    class="far ri-price-tag-2-line"></i> Estimates</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#fleet_tab"
                                role="tab" aria-controls="pills-timeline" aria-selected="false"><i
                                    class="ri-truck-line"></i> Vehicle</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#edit_profile_tab"
                                role="tab" aria-controls="pills-timeline" aria-selected="false"><i
                                    class="ri-edit-fill"></i> Edit Details</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#others_tab"
                                role="tab" aria-controls="pills-timeline" aria-selected="false"><i
                                    class="ri-draft-line"></i> Notes</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#"
                                role="button" aria-expanded="false"> <span>More</span> </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" id="pills-timeline-tab" data-bs-toggle="pill"
                                        href="#edit_service_tab" role="tab" aria-controls="pills-timeline"
                                        aria-selected="false"><i class="ri-service-line fas"></i> Service Area</a></li>
                                <li><a class="dropdown-item" data-bs-toggle="pill" href="#parts_tab" role="tab"
                                        aria-controls="pills-timeline" aria-selected="false"><i
                                            class="mdi mdi-book-multiple"></i> Parts</a></li>
                                <li><a class="dropdown-item" data-bs-toggle="pill" href="#settings_tab" role="tab"
                                        aria-controls="pills-timeline" aria-selected="false"><i
                                            class="fas ri-settings-2-line"></i> Settings</a></li>
                                <li><a class="dropdown-item" data-bs-toggle="pill" href="#activity_tab" role="tab"
                                        aria-controls="pills-timeline" aria-selected="false"><i
                                            class="fas fa-calendar-check"></i> Schedule</a></li>
                            </ul>
                        </li>

                    </ul>



                    <!-- Tabs -->
                    <div class="tab-content" id="pills-tabContent">

                        <div class="card-border shadow tab-pane fade show active" id="profile_tab" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <div class="card-body">

                                <div class="row">

                                    <div class="col-lg-3 col-xlg-9">

                                        <div class="row text-left justify-content-md-left">

                                            <div class="col-12">
                                                <center class="mt-1">
                                                    @if ($commonUser->user_image)
                                                        <img src="{{ asset('public/images/Uploads/users/' . $commonUser->id . '/' . $commonUser->user_image) }}"
                                                            class="rounded-circle" width="150" />
                                                    @else
                                                        <img src="{{ asset('public/images/login_img_bydefault.png') }}"
                                                            alt="avatar" class="rounded-circle" width="150" />
                                                    @endif
                                                    <h5 class="card-title uppercase mt-1">{{ $commonUser->name }}</h5>
                                                    <h6 class="card-subtitle">Technician</h6>
                                                </center>
                                            </div>


                                            <div class="col-12">
                                                <h5 class="card-title uppercase mt-4">Tags</h5>
                                                <div class="mt-0">
                                                    @if ($commonUser->tags->isNotEmpty())
                                                        @foreach ($commonUser->tags as $tag)
                                                            <span class="badge bg-dark">{{ $tag->tag_name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="badge bg-dark">No tags available</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <h5 class="card-title uppercase mt-4">Files & Attachments</h5>
                                                <div class="mt-0">
                                                    @foreach ($customerimage as $image)
                                                        @if ($image->filename)
                                                            <a href="{{ asset('storage/app/' . $image->filename) }}"
                                                                download>
                                                                <p><i class="fas fa-file-alt"></i></p>
                                                                <img src="{{ asset('storage/app/' . $image->filename) }}"
                                                                    alt="Customer Image"
                                                                    style="width: 50px; height: 50px;">
                                                            </a>
                                                        @else
                                                            <!-- Default image if no image available -->
                                                            <img src="{{ asset('public/admin/assets/images/users/1.jpg') }}"
                                                                alt="Default Image" style="width: 50px; height: 50px;">
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>

                                        </div>

                                    </div>


                                    <div class="col-lg-9 col-xlg-9">
                                        <div class="row">

                                            <div class="col-md-3 col-xs-6 b-r">
                                                <div class="col-12">
                                                    <h5 class="card-title uppercase mt-4">Contact info</h5>
                                                    <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i>
                                                        {{ $commonUser->mobile }}</h6>
                                                    <h6 style="font-weight: normal;"><i class="fas fa-envelope"></i>
                                                        {{ $commonUser->email }}</h6>

                                                        <h5 class="card-title uppercase mt-5">Address</h5>
                                                        <h6 style="font-weight: normal;"><i class="ri-map-pin-line"></i>
                                                          {{ $address ?? ''}}</h6>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h5 class="card-title uppercase mt-4">Summary</h5>
                                                        </div>
                                                        <div class="col-12">
                                                            <small class="text-muted pt-1 db">Jobs Completed</small>
                                                            <h6>0</h6>
                                                        </div>
                                                        <div class="col-12">
                                                            <small class="text-muted pt-1 db">Jobs Open</small>
                                                            <h6>0</h6>
                                                        </div>
                                                        <div class="col-12">
                                                            <small class="text-muted pt-1 db">Revenue Earned</small>
                                                            <h6>$0.00</h6>
                                                        </div>
                                                        <div class="col-12">
                                                            <small class="text-muted pt-1 db">Profile Created</small>
                                                            <h6>{{ $commonUser->created_at ? \Carbon\Carbon::parse($commonUser->created_at)->format('m-d-Y') : null }}
                                                            </h6>
                                                        </div>
                                                        <div class="col-12">
                                                            <small class="text-muted pt-1 db">Last service</small>
                                                            <h6>
                                                                {{ $jobasigndate && $jobasigndate->start_date_time
                                                                    ? \Carbon\Carbon::parse($jobasigndate->start_date_time)->format('m-d-Y')
                                                                    : null }}
                                                            </h6>
                                                        </div>
                                                        <div class="col-12">
                                                            <small class="text-muted pt-1 db">Status</small>
                                                            <h6 class="ucfirst">{{ $commonUser->status ?? null }}</h6>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-md-1 col-xs-6 b-r">&nbsp;</div>
                                            <div class="col-md-8 col-xs-6 b-r">
                                                    <div class="mt-4">
                                                        <iframe id="map{{ $location->address_id }}" width="100%"
                                                            height="300" frameborder="0" style="border: 0"
                                                            allowfullscreen></iframe>
                                                        <div style="display:flex;">
                                                            <h6>{{ $address?? '' }}
                                                            </h6>
                                                        </div>
                                                    </div>
                                            </div>



                                        </div>

                                    </div>
                                </div>



                            </div>
                        </div>


                        <div class="tab-pane fade" id="calls_tab" role="tabpanel" aria-labelledby="pills-setting-tab">
                            <div class="card-body card-border shadow">
                                <h5 class="card-title uppercase">Jobs / Calls</h5>

                                @if ($tickets->where('technician_id', $commonUser->id)->isEmpty())
                                    <div class="alert alert-info mt-4 col-md-12" role="alert">Calls not available for
                                        {{ $commonUser->name ?? '' }}. <strong><a href="{{ route('schedule') }}">Add
                                                New</a></strong></div>
                                @else
                                    <div class="table-responsive table-custom2 mt-2">
                                        <table id="zero_config" class="table table-hover table-striped text-nowrap"
                                            data-paging="true" data-paging-size="7">
                                            <thead>
                                                <tr>
                                                    <th>Job No</th>
                                                    <th>Job Details</th>
                                                    <th>Customer</th>
                                                    <th>Technician</th>
                                                    <th>Date & Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tickets->where('technician_id', $commonUser->id) as $ticket)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('tickets.show', $ticket->id) }}"
                                                                class="fw-bold link"><span
                                                                    class="mb-1 badge bg-primary">{{ $ticket->job_code }}</span></a>
                                                        </td>
                                                        <td>
                                                            <div class="text-wrap2">
                                                                <a href="{{ route('tickets.show', $ticket->id) }}"
                                                                    class="font-medium link">{{ $ticket->job_title ?? null }}</a>
                                                                <span
                                                                    class="badge bg-light-warning text-warning font-medium">{{ $ticket->status }}</span>
                                                            </div>
                                                            <div style="font-size:12px;">
                                                                @if ($ticket->jobdetailsinfo && $ticket->jobdetailsinfo->apliencename)
                                                                    {{ $ticket->jobdetailsinfo->apliencename->appliance_name }}/
                                                                @endif
                                                                @if ($ticket->jobdetailsinfo && $ticket->jobdetailsinfo->manufacturername)
                                                                    {{ $ticket->jobdetailsinfo->manufacturername->manufacturer_name }}/
                                                                @endif
                                                                @if ($ticket->jobdetailsinfo && $ticket->jobdetailsinfo->model_number)
                                                                    {{ $ticket->jobdetailsinfo->model_number }}/
                                                                @endif
                                                                @if ($ticket->jobdetailsinfo && $ticket->jobdetailsinfo->serial_number)
                                                                    {{ $ticket->jobdetailsinfo->serial_number }}
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if ($ticket->user)
                                                                <a href="{{ route('users.show', $ticket->user->id) }}"
                                                                    class="link">{{ $ticket->user->name }}</a>
                                                            @else
                                                                Unassigned
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($ticket->technician)
                                                                <a href="{{ route('technicians.show', $ticket->technician->id) }}"
                                                                    class="link">{{ $ticket->technician->name }}</a>
                                                            @else
                                                                Unassigned
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($ticket->jobassignname && $ticket->jobassignname->start_date_time)
                                                                <div class="font-medium link">
                                                                    {{ $convertDateToTimezone($ticket->jobassignname->start_date_time) }}
                                                                </div>
                                                            @else
                                                                <div></div>
                                                            @endif
                                                            <div style="font-size:12px;">
                                                                {{ $convertTimeToTimezone($ticket->JobAssign->start_date_time ?? null, 'H:i:a') }}
                                                                to
                                                                {{ $convertTimeToTimezone($ticket->JobAssign->end_date_time ?? null, 'H:i:a') }}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                            </div>
                        </div>

                        <div class="tab-pane fade" id="payment_tab" role="tabpanel" aria-labelledby="pills-payment-tab">
                            <div class="card-body card-border shadow">
                                <h5 class="card-title uppercase">Payments & Invoices</h5>
                                @if ($payments->isEmpty())
                                    <div class="alert alert-info mt-4" role="alert">
                                        Payments not available for {{ $commonUser->name ?? '' }}.
                                        <strong><a href="{{ route('schedule') }}">Add New</a></strong>
                                    </div>
                                @else
                                    <div class="table-responsive table-custom2 mt-2">
                                        <table id="zero_config2" class="table table-hover table-striped text-nowrap"
                                            data-paging="true" data-paging-size="7">
                                            <thead>
                                                <tr>
                                                    <th># Invoice No.</th>
                                                    <th>Job Details</th>
                                                    <th>Due Date</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Technician</th>
                                                    <th>Customer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($payments as $payment)
                                                    <tr>
                                                        @php
                                                            $jobname = DB::table('jobs')
                                                                ->where('id', $payment->job_id)
                                                                ->first();
                                                        @endphp
                                                        <td>
                                                            <a href=" {{ route('invoicedetail', ['id' => $payment->id]) }}"
                                                                class="font-medium link">{{ $payment->id ?? 'N/A' }}</a>
                                                        </td>

                                                        <td><a href=" {{ route('invoicedetail', ['id' => $payment->id]) }}"
                                                                class="font-medium link">{{ $jobname->job_title ?? 'N/A' }}</a>
                                                        </td>

                                                        <td>{{ isset($payment->created_at) ? \Carbon\Carbon::parse($payment->created_at)->format('m-d-Y @ g:ia') : null }}
                                                        </td>
                                                        <td>{{ $payment->total ?? null }}</td>
                                                        <td>{{ $payment->status ?? null }} </td>
                                                        <td>
                                                            @php
                                                                $job = DB::table('jobs')
                                                                    ->where('id', $payment->job_id)
                                                                    ->first(); // Retrieve job details
                                                                if ($job) {
                                                                    $technician1 = DB::table('users')
                                                                        ->where('id', $job->technician_id)
                                                                        ->first(); // Retrieve technician details
                                                                    $technician_name = $commonUser
                                                                        ? $commonUser->name
                                                                        : 'Unknown';
                                                                    // Get technician's name or set to 'Unknown' if not found
} else {
    $technician_name = 'Unknown';
                                                                }
                                                            @endphp
                                                            <a href="{{ route('technicians.show', ['id' => $technician1->id]) }}"
                                                                class="link">{{ $technician1->name ?? null }}</a>
                                                        </td>


                                                        <td>@php
                                                            $customer = DB::table('users')
                                                                ->where('id', $payment->customer_id)
                                                                ->first();
                                                        @endphp
                                                            <a href="{{ route('users.show', ['id' => $customer->id]) }}"
                                                                class="link">{{ $customer->name ?? null }}</a>

                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <br />


                            </div>
                        </div>


                        <div class="tab-pane fade" id="fleet_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                            <div class="card-body card-border shadow">
                                <h5 class="card-title uppercase">Vehicle </h5>


                                <div class="row mt-3">

                                    <div class="col-lg-6 col-xlg-6">
                                        <h5 class="card-title uppercase">Edit Vehicle Details </h5>
                                        @if (empty($vehiclefleet->technician_id))
                                            <div class="alert alert-info mt-4 col-md-12" role="alert">
                                                Please go to the Vehicle section and assign a vehicle to a
                                                {{ $commonUser->name ?? '' }}. <strong><a
                                                        href="{{ route('vehicles') }}">Add New</a></strong>
                                            </div>
                                        @else
                                            <p>Summary: {{ $vehiclefleet->vehicle_summary ?? '' }}</p>
                                            <form class="form" method="post"
                                                action="{{ route('update_fleet_technician') }}">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="vehicle_description"
                                                                class="control-label bold mb5 col-form-label required-field">Change
                                                                Vehicle (title)</label>
                                                            <select name="vehicle_description" class="form-select"
                                                                required>
                                                                @foreach ($vehicleDescriptions as $description)
                                                                    @if (in_array($description, explode(',', $vehiclefleet->vehicle_description)))
                                                                        <option value="{{ $description }}" selected>
                                                                            {{ $description }}</option>
                                                                    @else
                                                                        <option value="{{ $description }}">
                                                                            {{ $description }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                        <input type="hidden" class="form-control" name="technician_id"
                                                            value="{{ $commonUser->id }}" />

                                                        <div class="mb-3">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="col-lg-6 col-xlg-6">
                                        <h5 class="card-title uppercase">Fleet Maintenance </h5>

                                        <form class="form" method="post" action="{{ route('updatefleet') }}">
                                            @csrf
                                            <input class="form-control" type="hidden" value="{{ $commonUser->id }}"
                                                name="id">

                                            <div class="mb-3 row">
                                                <label for="oil_change" class="col-md-3 col-form-label">OIL
                                                    CHANGE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ old('oil_change', $oil_change) }}" name="oil_change">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="tune_up" class="col-md-3 col-form-label">TUNE UP</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $tune_up ?? '' }}" name="tune_up">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="tire_rotation" class="col-md-3 col-form-label">TIRE
                                                    ROTATION</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $tire_rotation ?? '' }}" name="tire_rotation">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="breaks" class="col-md-3 col-form-label">BREAKS</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $breaks ?? '' }}" name="breaks">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="inspection_codes" class="col-md-3 col-form-label">INSPECTION
                                                    / CODES</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $inspection_codes ?? '' }}" name="inspection_codes">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="mileage" class="col-md-3 col-form-label">MILEAGE AS OF
                                                    00/00/2024</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="date"
                                                        value="{{ $mileage ?? '' }}" name="mileage">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="registration_expiration_date"
                                                    class="col-md-3 col-form-label">REGISTRATION EXPIRATION DATE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="date"
                                                        value="{{ $registration_expiration_date ?? '' }}"
                                                        name="registration_expiration_date">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="vehicle_coverage" class="col-md-3 col-form-label">VEHICLE
                                                    COVERAGE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $vehicle_coverage ?? '' }}" name="vehicle_coverage">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="license_plate" class="col-md-3 col-form-label">LICENSE
                                                    PLATE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $license_plate ?? '' }}" name="license_plate">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="vin_number" class="col-md-3 col-form-label">VIN
                                                    NUMBER</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $vin_number ?? '' }}" name="vin_number">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="make" class="col-md-3 col-form-label">MAKE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $make ?? '' }}" name="make">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="model" class="col-md-3 col-form-label">MODEL</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $model ?? '' }}" name="model">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="year" class="col-md-3 col-form-label">YEAR</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $year ?? '' }}" name="year">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="color" class="col-md-3 col-form-label">COLOR</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $color ?? '' }}" name="color">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="vehicle_weight" class="col-md-3 col-form-label">VEHICLE
                                                    WEIGHT</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $vehicle_weight ?? '' }}" name="vehicle_weight">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="vehicle_cost" class="col-md-3 col-form-label">VEHICLE
                                                    COST</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $vehicle_cost ?? '' }}" name="vehicle_cost">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="use_of_vehicle" class="col-md-3 col-form-label">USE OF
                                                    VEHICLE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $use_of_vehicle ?? '' }}" name="use_of_vehicle">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="repair_services" class="col-md-3 col-form-label">REPAIR
                                                    SERVICES</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $repair_services ?? '' }}" name="repair_services">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="ezpass" class="col-md-3 col-form-label">E-ZPass</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $ezpass ?? '' }}" name="ezpass">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="service" class="col-md-3 col-form-label">SERVICE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $service ?? '' }}" name="service">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="additional_service_notes"
                                                    class="col-md-3 col-form-label">ADDITIONAL SERVICE NOTES</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $additional_service_notes ?? '' }}"
                                                        name="additional_service_notes">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="last_updated" class="col-md-3 col-form-label">LAST
                                                    UPDATED</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="date"
                                                        value="{{ $last_updated ?? '' }}" name="last_updated">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="epa_certification" class="col-md-3 col-form-label">EPA
                                                    CERTIFICATION</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ $epa_certification ?? '' }}" name="epa_certification">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <div class="col-md-9 offset-md-3">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>


                                        </form>

                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="tab-pane fade" id="edit_profile_tab" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body card-border shadow">
                                @include('technicians.edit_profile')
                            </div>
                        </div>


                        <div class="tab-pane fade" id="others_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                            <div class="card-body card-border shadow">
                                    @include('commonfiles.notes_for_profiles')

                            </div>
                        </div>

                        <div class="tab-pane fade" id="edit_service_tab" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body card-border shadow">
                                @include('technicians.service_area')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="estimate_tab" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body card-border shadow">

                                @if ($estimates->isEmpty())
                                    <h5 class="card-title uppercase">Estimates</h5>
                                    <div class="alert alert-info mt-4 col-md-12" role="alert">
                                        Estimates details not available for {{ $user->name ?? null }}. <strong><a
                                                href="{{ route('schedule') }}">Add New</a></strong>
                                    </div>
                                @else
                                    <div class="table-responsive table-custom2 mt-2">
                                        <table id="zero_config" class="table table-hover table-striped text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Ticket</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Technician</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($estimates as $estimate)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $estimate->ticket ?? null }}</td>
                                                        <td>{{ isset($estimate->date) ? \Carbon\Carbon::parse($estimate->date)->format('m-d-Y') : null }}
                                                        </td>
                                                        <td>{{ $estimate->amount ?? null }}</td>
                                                        <td>{{ $estimate->technician ?? null }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>

                        </div>



                        <div class="tab-pane fade" id="parts_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                            <div class="card-body card-border shadow">
                                @include('technicians.parts_view_and_assign')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="settings_tab" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body card-border shadow">
                                @include('technicians.myprofile_account_technician')
                            </div>
                        </div>

                        <div class="tab-pane fade" id="activity_tab" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">

                            <div class="card-body card-border shadow">

                                <h5 class="card-title uppercase">schedule</h5>

                                @if ($schedule->isEmpty())
                                    <div class="alert alert-info mt-4 col-md-12" role="alert">
                                        Schedule details not available for {{ $user->name ?? '' }}. <strong><a
                                                href="{{ route('schedule') }}">Add New</a></strong>
                                    </div>
                                @else
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="timeline timeline-left">
                                                @foreach ($schedule as $scheduleItem)
                                                    @if (isset($scheduleItem))
                                                        <li class="timeline-inverted timeline-item">
                                                            <div
                                                                class="timeline-badge
                                                     @if ($scheduleItem->schedule_type === 'job') danger
                                                            @elseif ($scheduleItem->schedule_type === 'event')
                                                             success @endif">
                                                                <span class="fs-2">
                                                                    @if ($scheduleItem->schedule_type === 'job')
                                                                        T
                                                                    @elseif ($scheduleItem->schedule_type === 'event')
                                                                        <i class="ri-cpu-fill fs-7"></i>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div class="timeline-panel shadow">
                                                                <div class="timeline-heading">
                                                                    <h5 class="timeline-title uppercase">
                                                                        <i class="ri-time-line align-middle"></i>
                                                                        {{ $convertDateToTimezone($scheduleItem->created_at) }}
                                                                        <span class="ft12">
                                                                            @if ($scheduleItem->schedule_type === 'job' || $scheduleItem->event->event_type === 'partial')
                                                                                {{ $convertTimeToTimezone($scheduleItem->start_date_time, 'H:i:a') }}
                                                                                to
                                                                                {{ $convertTimeToTimezone($scheduleItem->end_date_time, 'H:i:a') }}
                                                                            @elseif ($scheduleItem->event->event_type === 'full')
                                                                                FULL DAY
                                                                            @endif
                                                                        </span>
                                                                    </h5>
                                                                </div>
                                                                <div class="timeline-body">
                                                                    <div class="row mt1">
                                                                        <div class="col-md-12">
                                                                            <div class="mb-2">
                                                                                <h5 class="card-title uppercase">
                                                                                    @if ($scheduleItem->schedule_type === 'job')
                                                                                        {{ $scheduleItem->JobModel->job_title ?? null }}
                                                                                        @elseif ($scheduleItem->schedule_type === 'event')
                                                                                        {{ $scheduleItem->event->event_name ?? null }}
                                                                                    @endif
                                                                                </h5>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            @if ($scheduleItem->schedule_type === 'job')
                                                                                {{ $scheduleItem->JobModel->description ?? null }}
                                                                            @elseif ($scheduleItem->schedule_type === 'event')
                                                                                {{ $scheduleItem->event->event_description ?? null }}
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    @if ($scheduleItem->schedule_type === 'job')
                                                                        <div class="row mt-2">
                                                                            <div class="col-md-6">
                                                                                <div class="mb-2"><strong>Customer
                                                                                        Name:</strong>
                                                                                    {{ $scheduleItem->JobModel->user->name ?? '' }}
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <strong>Address:</strong>
                                                                                    @if (isset($scheduleItem->JobModel->addresscustomer->address_line1) &&
                                                                                            $scheduleItem->JobModel->addresscustomer->address_line1 !== '')
                                                                                        {{ $scheduleItem->JobModel->addresscustomer->address_line1 }},
                                                                                    @endif

                                                                                    @if (isset($scheduleItem->JobModel->addresscustomer->address_line2) &&
                                                                                            $scheduleItem->JobModel->addresscustomer->address_line2 !== '')
                                                                                        {{ $scheduleItem->JobModel->addresscustomer->address_line2 }},
                                                                                    @endif

                                                                                    @if (isset($scheduleItem->JobModel->addresscustomer->city) && $scheduleItem->JobModel->addresscustomer->city !== '')
                                                                                        {{ $scheduleItem->JobModel->addresscustomer->city }},
                                                                                    @endif

                                                                                    @if (isset($scheduleItem->JobModel->addresscustomer->state_name) &&
                                                                                            $scheduleItem->JobModel->addresscustomer->state_name !== '')
                                                                                        {{ $scheduleItem->JobModel->addresscustomer->state_name }},
                                                                                    @endif

                                                                                    @if (isset($scheduleItem->JobModel->addresscustomer->zipcode) &&
                                                                                            $scheduleItem->JobModel->addresscustomer->zipcode !== '')
                                                                                        {{ $scheduleItem->JobModel->addresscustomer->zipcode }}
                                                                                    @endif
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <strong>Mobile:</strong>
                                                                                    {{ $scheduleItem->JobModel->user->mobile ?? '' }}
                                                                                </div>
                                                                                <div class="mb-2"><strong>Email:</strong>
                                                                                    {{ $scheduleItem->JobModel->user->email ?? '' }}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="mb-2">
                                                                                    <strong>Duration:</strong>
                                                                                  @php
                                                                                    $minutes = $scheduleItem->JobModel->jobassignname->duration ?? 0;
                                                                                    $hours = intdiv($minutes, 60);
                                                                                    $remaining_minutes = $minutes % 60;
                                                                                    $duration = ($hours > 0 ? $hours . ' hour' . ($hours > 1 ? 's' : '') : '') . 
                                                                                                ($remaining_minutes > 0 ? ' ' . $remaining_minutes . ' minute' . ($remaining_minutes > 1 ? 's' : '') : '');
                                                                                @endphp

                                                                                {{ $duration }}

                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <strong>Priority:</strong>
                                                                                    {{ $scheduleItem->JobModel->priority ?? '' }}
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="mb-2">
                                                                                    <strong>Appliances:</strong>
                                                                                    {{ $scheduleItem->JobModel->JobAppliances->Appliances->appliance->appliance_name ?? '' }}
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <strong>Manufacturer:</strong>
                                                                                    {{ $scheduleItem->JobModel->JobAppliances->Appliances->manufacturer->manufacturer_name ?? '' }}
                                                                                </div>
                                                                                <div class="mb-2"><strong>Model
                                                                                        Number:</strong>
                                                                                    {{ $scheduleItem->JobModel->JobAppliances->Appliances->model_number ?? '' }}
                                                                                </div>
                                                                                <div class="mb-2"><strong>Serial
                                                                                        Number:</strong>
                                                                                    {{ $scheduleItem->JobModel->JobAppliances->Appliances->serial_number ?? '' }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endforeach



                                            </ul>
                                        </div>
                                    </div>
                                @endif

                            </div>

                        </div>






                    </div>
                </div>
                <!-- ---------------------
                                                    end Timeline
                                                ---------------- -->
            </div>
            <!-- Column -->
        </div>
        <!-- Row -->
        <!-- -------------------------------------------------------------- -->
        <!-- End PAge Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- Right sidebar -->
        <!-- -------------------------------------------------------------- -->
        <!-- .right-sidebar -->
        <!-- -------------------------------------------------------------- -->
        <!-- End Right sidebar -->
        <!-- -------------------------------------------------------------- -->
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Container fluid  -->
@section('script')
    <script>
        @foreach ($userAddresscity as $location)
            var latitude = {{ $location->latitude }}; // Example latitude
            var longitude = {{ $location->longitude }}; // Example longitude

            // Construct the URL with the latitude and longitude values
            var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' +
                latitude +
                ',' + longitude + '&zoom=13';

            document.getElementById('map{{ $location->address_id }}').src = mapUrl;
        @endforeach
    </script>
    <script>
        $(document).ready(function() {
            // Select the password and new password input fields
            var passwordField = $('input[name="password"]');
            var newPasswordField = $('input[name="confirm_password"]');
            var passwordMatchMessage = $('#passwordMatchMessage');

            // Select the form and attach a submit event listener
            $('form').submit(function(event) {
                // Prevent the form from submitting
                event.preventDefault();

                // Get the values of the password and new password fields
                var passwordValue = passwordField.val();
                var newPasswordValue = newPasswordField.val();

                // Check if the passwords match
                if (passwordValue === newPasswordValue) {
                    // If passwords match, submit the form
                    this.submit();
                } else {
                    // Show danger message
                    passwordMatchMessage.removeClass('alert-success').addClass('alert-danger').html(
                        'Passwords do not match. Please enter matching passwords.').show();
                }
            });
        });
    </script>
    <script>
        const firstNameInput = document.getElementById('first_name');
        const lastNameInput = document.getElementById('last_name');
        const displayNameInput = document.getElementById('display_name');

        // Function to update the display name field
        function updateDisplayName() {
            const firstName = firstNameInput.value.trim();
            const lastName = lastNameInput.value.trim();

            // Concatenate first and last name
            const displayName = firstName + ' ' + lastName;

            // Set the display name input value
            displayNameInput.value = displayName;
        }

        // Listen for input changes on first and last name fields
        firstNameInput.addEventListener('input', updateDisplayName);
        lastNameInput.addEventListener('input', updateDisplayName);
    </script>
    <script>
        document.getElementById('openChangePasswordModal').addEventListener('click', function(event) {
            event.preventDefault();
            $('#changePasswordModal').modal('show');
        });

        // Close modal when close button is clicked
        $('.close').on('click', function() {
            $('#changePasswordModal').modal('hide');
        });
    </script>
    <script>
        $(document).ready(function() {

            $('#state_id').change(function() {

                var stateId = $(this).val();

                var citySelect = $('#city');

                citySelect.html('<option selected disabled value="">Loading...</option>');



                // Make an AJAX request to fetch the cities based on the selected state

                $.ajax({

                    url: "{{ route('getcities') }}", // Correct route URL

                    type: 'GET',

                    data: {

                        state_id: stateId

                    },

                    dataType: 'json',

                    success: function(data) {

                        citySelect.html(
                            '<option selected disabled value="">Select City...</option>');

                        $.each(data, function(index, city) {

                            citySelect.append('<option value="' + city.city_id + '">' +
                                city.city + ' - ' + city.zip + '</option>');

                        });

                    },

                    error: function(xhr, status, error) {

                        console.error('Error fetching cities:', error);

                    }

                });

            });



            // Trigger another function to get zip code after selecting a city

            $('#city').change(function() {

                var cityId = $(this).val();

                var cityName = $(this).find(':selected').text().split(' - ')[
                    0]; // Extract city name from option text

                getZipCode(cityId, cityName); // Call the function to get the zip code

            });

        });


        // Function to get zip code
        function searchCity() {
            // Initialize autocomplete
            $("#city").autocomplete({
                source: function(request, response) {
                    // Clear previous autocomplete results
                    $("#autocomplete-results").empty();

                    $.ajax({
                        url: "{{ route('autocomplete.city') }}",
                        data: {
                            term: request.term
                        },
                        dataType: "json",
                        type: "GET",
                        success: function(data) {
                            response(data);
                        },
                        error: function(response) {
                            console.log("Error fetching city data:", response);
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $("#city").val(ui.item.city);
                    $("#city_id").val(ui.item.city_id);
                    return false;
                }
            }).data("ui-autocomplete")._renderItem = function(ul, item) {
                // Render each item
                var listItem = $("<li>").text(item.city).appendTo("#autocomplete-results");
                listItem.data("city_id", item.city_id);
                return listItem;
            };

            // Handle click on autocomplete results
            $("#autocomplete-results").on("click", "li", function() {
                var cityName = $(this).text();
                var cityId = $(this).data("city_id");

                // Check if cityId is retrieved properly
                console.log("Selected City ID:", cityId);

                // Set the city ID
                $("#city_id").val(cityId);

                // Set the city name
                $("#city").val(cityName);

                // Hide autocomplete results
                $("#autocomplete-results").hide();
            });

            // Handle input field click
            $("#city").click(function() {
                // Show autocomplete results box
                $("#autocomplete-results").show();
            });

            // Clear appended city when input is cleared
            $("#city").on("input", function() {
                var inputVal = $(this).val();
                if (inputVal === "") {
                    // If input is cleared, re-initialize autocomplete
                    $("#autocomplete-results").empty(); // Clear appended cities
                    searchCity(); // Re-initialize autocomplete
                }
            });
        }

        // Function to get zip code
        function getZipCode(cityId, cityName) {
            $.ajax({
                url: "{{ route('getZipCode') }}", // Adjust route URL accordingly
                type: 'GET',
                data: {
                    city_id: cityId,
                    city_name: cityName
                },
                dataType: 'json',
                success: function(data) {
                    var zipCode = data.zip_code; // Assuming the response contains the zip code
                    $('#zip_code').val(zipCode); // Set the zip code in the input field
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching zip code:', error);
                }
            });
        }
    </script>
    <script>
        document.getElementById("submitButton").addEventListener("click", function(event) {
            var comment = document.getElementById("comment").value.trim();
            if (comment === "") {
                event.preventDefault(); // Prevent form submission
                alert("Please add a comment before submitting.");
            }
        });
    </script>
@endsection

@endsection
