@extends('home')
@section('content')
<!-- Page wrapper  -->
<!-- -------------------------------------------------------------- -->
<div class="page-wrapper" style="display:inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb" style="padding-top: 0px;">
        <div class="row">
            <div class="col-9 align-self-center">
                <h4 class="page-title">{{ $technician->name }}</h4>
            </div>
            <div class="col-3 text-end px-4">
                <a href="https://dispatchannel.com/portal/technicians"
                    class="justify-content-center btn btn-info d-flex align-items-center"><i class="ri-contacts-line"
                        style="margin-right: 8px;"></i> Technicians </a>
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
                    <ul class="nav nav-pills custom-pills nav-fill flex-column flex-sm-row user_profile_tabs"
                        id="pills-tab" role="tablist">
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
                                    class="ri-truck-line"></i> Fleet</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#edit_profile_tab"
                                role="tab" aria-controls="pills-timeline" aria-selected="false"><i
                                    class="ri-edit-fill"></i> Edit Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#others_tab"
                                role="tab" aria-controls="pills-timeline" aria-selected="false"><i
                                    class="ri-draft-line"></i> Notes</a>
                        </li>
                         <li class="nav-item">
                            <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#edit_service_tab"
                                role="tab" aria-controls="pills-timeline" aria-selected="false"><i
                                    class="ri-edit-fill"></i> Service Area</a>
                        </li>
                    </ul>
                    <!-- Tabs -->
                    <div class="tab-content" id="pills-tabContent">

                        <div class="card-border tab-pane fade show active" id="profile_tab" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-lg-3 col-xlg-9">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row text-left justify-content-md-left">
                                                    <h4 class="card-title mt-2">Summary</h4>
                                                    <div class="col-6">
                                                        <small class="text-muted pt-1 db">Last service</small>

                                                        <h6>
                                                            {{ $jobasigndate && $jobasigndate->start_date_time ?
                                                            \Carbon\Carbon::parse($jobasigndate->start_date_time)->format('m-d-Y')
                                                            :
                                                            null }}
                                                        </h6>

                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted pt-1 db">Profile Created</small>
                                                        <h6>{{ $technician->created_at ?
                                                            \Carbon\Carbon::parse($technician->created_at)->format('m-d-Y')
                                                            : null }}</h6>


                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted pt-1 db">Lifetime value</small>
                                                        <h6>$0.00</h6>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted pt-1 db">Outstanding balance</small>
                                                        <h6>$0.00</h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <h4 class="card-title mt-4">Contact info</h4>

                                                        <h6 style="font-weight: normal;">
                                                            <i class="fas fa-home"></i>{{ old('home_phone', $home_phone)
                                                            }}
                                                        </h6>
                                                        <h6 style="font-weight: normal;"><i
                                                                class="fas fa-mobile-alt"></i>
                                                            {{$technician->mobile}}
                                                        </h6>
                                                        {{-- <h6 style="font-weight: normal;"><i
                                                                class="fas fa-mobile-alt"></i> +1 123 456 7890
                                                        </h6> --}}
                                                        <h6 style="font-weight: normal;"><i class="fas fa-envelope"></i>
                                                            {{$technician->email}}
                                                        </h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <h4 class="card-title mt-4">Notifications</h4>
                                                        <h6 style="font-weight: normal;margin-bottom: 0px;"><i
                                                                class="fas fa-check"></i> Yes
                                                        </h6>
                                                    </div>
                                                    <div class="col-12">
                                                        <h4 class="card-title mt-4">Tags</h4>
                                                        <div class="mt-0">
                                                            @if($technician->tags->isNotEmpty())
                                                            @foreach($technician->tags as $tag)
                                                            <span class="badge bg-dark">{{ $tag->tag_name }}</span>
                                                            @endforeach
                                                            @else
                                                            <span class="badge bg-dark">No tags available</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-12" style="display:none;">
                                                        <h4 class="card-title mt-4">Lead Source</h4>
                                                        <div class="mt-0">
                                                            <span
                                                                class="mb-1 badge bg-primary">{{$technician->leadsourcename->source_name
                                                                ??
                                                                null }}</span>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-xlg-9">
                                        <div class="row">
                                            <div class="col-md-3 col-xs-6 b-r">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <center class="mt-1">
                                                            @if($technician->user_image)
                                                            <img src="{{ asset('public/images/Uploads/users/'. $technician->id . '/' . $technician->user_image) }}"
                                                                class="rounded-circle" width="150" />
                                                            @else
                                                            <img src="{{ asset('public/images/login_img_bydefault.png') }}"
                                                                alt="avatar" class="rounded-circle" width="150" />
                                                            @endif
                                                            <h4 class="card-title mt-1">{{ $technician->name }}</h4>
                                                            {{-- <h6 class="card-subtitle">{{ $technician->company ??
                                                                null }}
                                                            </h6> --}}
                                                        </center>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1 col-xs-6 b-r">&nbsp;</div>
                                            <div class="col-md-8 col-xs-6 b-r">
                                                <div>
                                                    @foreach($userAddresscity as $location)

                                                    <div>
                                                        <small class="text-muted pt-4 db">Address - {{
                                                            $location->address_type
                                                            }}</small>
                                                        <div style="display:flex;">

                                                            <h6>{{ $location->city}}</h6>&nbsp;
                                                            <h6>{{ $location->state_name ?? null }}</h6>
                                                            <span>,</span>
                                                            <h6>{{ $location->zipcode }}</h6>


                                                            <br />
                                                        </div>
                                                        <iframe id="map{{ $location->address_id }}" width="100%"
                                                            height="150" frameborder="0" style="border: 0"
                                                            allowfullscreen></iframe>
                                                        {{-- <iframe
                                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6991603.699017098!2d-100.0768425!3d31.168910300000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864070360b823249%3A0x16eb1c8f1808de3c!2sTexas%2C%20USA!5e0!3m2!1sen!2sin!4v1701086703789!5m2!1sen!2sin"
                                                            width="100%" height="300" style="border:0;"
                                                            allowfullscreen="" loading="lazy"
                                                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                        --}}

                                                    </div>
                                                    <hr />
                                                    @endforeach

                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row" style="margin-left:12px;">

                                                    <h4 class="card-title mt-4">Files & Attachments</h4>
                                                    <div class="row">
                                                        @foreach($customerimage as $image)
                                                        <div class="col-md-4 col-xs-6">

                                                            @if($image->filename)
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
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>



                            </div>
                        </div>


                        <div class="tab-pane fade" id="calls_tab" role="tabpanel" aria-labelledby="pills-setting-tab">
                            <div class="card-body">
                                <h4>Calls / Tickets</h4>
                                <div class="table-responsive mt-4 table-custom2">
                                    <table id="zero_config" class="table table-bordered text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Job Name </th>
                                                <th>Date</th>
                                                <th>Technician</th>
                                                <th>Status</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jobasign as $customercall)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><a href="{{ route('tickets.show', $customercall->id) }}"
                                                        class="font-medium link">{{$customercall->job_title
                                                        ?? null}}</a><br />
                                                </td>
                                                {{-- <td><a href="#" class="fw-bold link">{{$customercall->job_code ??
                                                        null}}</a>
                                                </td> --}}
                                                @php
                                                $jobassign = DB::table('job_assigned')->where('job_id',
                                                $customercall->id)->first();
                                                $created_at = $jobassign ? $jobassign->created_at : null;
                                                @endphp

                                                <td>
                                                    @if ($created_at)
                                                    <div class="font-medium link">{{
                                                        \Carbon\Carbon::parse($created_at)->format('m-d-y') }}
                                                    </div>
                                                    <div style="font-size:12px;">
                                                        {{
                                                        \Carbon\Carbon::parse($jobassign->start_date_time)->format('g:ia')
                                                        }}
                                                        to {{
                                                        \Carbon\Carbon::parse($jobassign->end_date_time)->format('g:ia')
                                                        }}
                                                    </div>
                                                    @else
                                                    <div>N/A</div>
                                                    @endif
                                                </td>

                                                <td>
                                                    @php
                                                    $technician = DB::table('users')->where('id',
                                                    $customercall->technician_id)->first();
                                                    @endphp

                                                    {{ $technician ? $technician->name : 'N/A' }}
                                                </td>
                                                <td><span
                                                        class="badge bg-light-warning text-warning font-medium">{{$customercall->status
                                                        ?? null}}</span></td>


                                            </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="payment_tab" role="tabpanel" aria-labelledby="pills-payment-tab">
                            <div class="card-body">
                                <h4>Payments</h6>
                                    <div class="table-responsive mt-4">
                                        <table id="zero_config" class="table table-bordered text-nowrap">
                                            <thead>
                                                <tr>

                                                    <th>Ticket</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Technician</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($payments as $payment)
                                                <tr>
                                                    @php
                                                    $jobname = DB::table('jobs')->where('id',
                                                    $payment->job_id)->first();
                                                    @endphp

                                                    <td>
                                                        <a href=" {{ route('invoicedetail', ['id' => $payment->id]) }}"
                                                            class="font-medium link">{{ $jobname->job_title ?? 'N/A'
                                                            }}</a>
                                                    </td>
                                                    <td>{{ isset($payment->created_at) ?
                                                        \Carbon\Carbon::parse($payment->created_at)->format('m-d-Y @ g:i
                                                        a') : null }}</td>
                                                    <td>{{$payment->total ?? null}} <span
                                                            class="badge bg-success font-weight-100">{{$payment->status
                                                            ?? null}} </span>
                                                    </td>
                                                    <td>
                                                        @php
                                                        $job = DB::table('jobs')->where('id',
                                                        $payment->job_id)->first(); // Retrieve job details
                                                        if ($job) {
                                                        $technician1 = DB::table('users')->where('id',
                                                        $job->technician_id)->first(); // Retrieve technician details
                                                        $technician_name = $technician ? $technician->name : 'Unknown';
                                                        // Get technician's name or set to 'Unknown' if not found
                                                        } else {
                                                        $technician_name = 'Unknown';
                                                        }
                                                        @endphp
                                                        {{$technician1->name ?? null}} </td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <br />

                            </div>
                        </div>

                        <div class="tab-pane fade show " id="edit_profile_tab" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body">
  
                                @include('technicians.edit_profile')
                            </div>
                        </div>


                        <div class="tab-pane fade show " id="others_tab" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body">
                                <div class="profiletimeline mt-0">
                                    @foreach ($notename as $notename )
                                    <div class="sl-item">
                                        <div class="sl-left">
                                            @php
                                            $username = DB::table('users')->where('id',
                                            $notename->added_by)->first();
                                            @endphp
                                            @if($username && $username->user_image)
                                            <img src="{{ asset('public/images/Uploads/users/'. $username->id . '/' . $username->user_image) }}"
                                                class="rounded-circle" alt="user" />
                                            @else
                                            <img src="{{ asset('public/images/login_img_bydefault.png') }}" alt="user"
                                                class=" rounded-circle" />
                                            @endif

                                        </div>

                                        <div class="sl-right">
                                            <div>
                                                <a href="javascript:void(0)" class="link"> {{$username->name ??
                                                    null}}</a>
                                                <span class="sl-date">
                                                    {{ \Carbon\Carbon::parse($notename->created_at)->diffForHumans() }}
                                                </span>
                                                <p><strong> </strong><a href="javascript:void(0)">
                                                    </a></p>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12">
                                                        {{ $notename->note }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr />
                                    @endforeach
                                    {{-- <div class="sl-item">
                                        <div class="sl-left">
                                            <img src="{{ asset('public/admin/assets/images/users/1.jpg') }}" alt="user"
                                                class="rounded-circle" />
                                        </div>
                                        <div class="sl-right">
                                            <div>
                                                <span class="sl-date">2 days ago</span>
                                                <a href="javascript:void(0)" class="link">John Smith</a>
                                                <p><strong>LG AC REPAIR </strong><a href="javascript:void(0)">
                                                        View
                                                        Ticket</a></p>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12">Lorem ipsum dolor sit amet,
                                                        consectetur adipiscing elit, sed do eiusmod tempor
                                                        incididunt ut
                                                        labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                        quis
                                                        nostrud exercitation ullamco laboris nisi ut aliquip ex
                                                        ea
                                                        commodo consequat</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="sl-item">
                                        <div class="sl-left">
                                            <img src="{{ asset('public/admin/assets/images/users/1.jpg') }}" alt="user"
                                                class="rounded-circle" />
                                        </div>
                                        <div class="sl-right">
                                            <div>
                                                <a href="javascript:void(0)" class="link">James Nelson</a>
                                                <span class="sl-date">4 days ago</span>
                                                <p><strong>LG AC REPAIR </strong><a href="javascript:void(0)">
                                                        View
                                                        Ticket</a></p>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12">Lorem ipsum dolor sit amet,
                                                        consectetur adipiscing elit, sed do eiusmod tempor
                                                        incididunt ut
                                                        labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                        quis
                                                        nostrud exercitation ullamco laboris nisi ut aliquip ex
                                                        ea
                                                        commodo consequat</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade show " id="estimate_tab" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body">


                                <h4>Estimates</h6>
                                    <div class="table-responsive mt-4">
                                        <table id="zero_config" class="table table-bordered text-nowrap">
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
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><span class="badge bg-success font-weight-100"></span>
                                                    </td>
                                                    <td></td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                        </div>

                        <div class="tab-pane fade show " id="fleet_tab" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body">

                                <div class="row">

                                    <div class="col-lg-8 col-xlg-9">

                                        <h4 class="card-title">Fleet Maintenance </h4>

                                        <form class="form" method="post" action="{{route('updatefleet')}}">
                                            @csrf
                                            <input class="form-control" type="hidden" value="{{$technician->id}}"
                                                name="id">

                                            <div class="mb-3 row">
                                                <label for="oil_change" class="col-md-3 col-form-label">OIL
                                                    CHANGE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{ old('oil_change',  $oil_change) }}" name="oil_change">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="tune_up" class="col-md-3 col-form-label">TUNE UP</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text" value="{{$tune_up ?? ''}}"
                                                        name="tune_up">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="tire_rotation" class="col-md-3 col-form-label">TIRE
                                                    ROTATION</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{$tire_rotation ?? ''}}" name="tire_rotation">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="breaks" class="col-md-3 col-form-label">BREAKS</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text" value="{{$breaks ?? ''}}"
                                                        name="breaks">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="inspection_codes" class="col-md-3 col-form-label">INSPECTION
                                                    / CODES</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{$inspection_codes ?? ''}}" name="inspection_codes">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="mileage" class="col-md-3 col-form-label">MILEAGE AS OF
                                                    00/00/2024</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="date" value="{{$mileage ?? ''}}"
                                                        name="mileage">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="registration_expiration_date"
                                                    class="col-md-3 col-form-label">REGISTRATION EXPIRATION DATE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="date"
                                                        value="{{$registration_expiration_date ?? ''}}"
                                                        name="registration_expiration_date">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="vehicle_coverage" class="col-md-3 col-form-label">VEHICLE
                                                    COVERAGE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{$vehicle_coverage ?? ''}}" name="vehicle_coverage">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="license_plate" class="col-md-3 col-form-label">LICENSE
                                                    PLATE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{$license_plate ?? ''}}" name="license_plate">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="vin_number" class="col-md-3 col-form-label">VIN
                                                    NUMBER</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{$vin_number ?? ''}}" name="vin_number">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="make" class="col-md-3 col-form-label">MAKE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text" value="{{$make ?? ''}}"
                                                        name="make">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="model" class="col-md-3 col-form-label">MODEL</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text" value="{{$model ?? ''}}"
                                                        name="model">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="year" class="col-md-3 col-form-label">YEAR</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text" value="{{$year ?? ''}}"
                                                        name="year">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="color" class="col-md-3 col-form-label">COLOR</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text" value="{{$color ?? ''}}"
                                                        name="color">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="vehicle_weight" class="col-md-3 col-form-label">VEHICLE
                                                    WEIGHT</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{$vehicle_weight ?? ''}}" name="vehicle_weight">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="vehicle_cost" class="col-md-3 col-form-label">VEHICLE
                                                    COST</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{$vehicle_cost ?? ''}}" name="vehicle_cost">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="use_of_vehicle" class="col-md-3 col-form-label">USE OF
                                                    VEHICLE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{$use_of_vehicle ?? ''}}" name="use_of_vehicle">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="repair_services" class="col-md-3 col-form-label">REPAIR
                                                    SERVICES</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{$repair_services ?? ''}}" name="repair_services">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="ezpass" class="col-md-3 col-form-label">E-ZPass</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text" value="{{$ezpass ?? ''}}"
                                                        name="ezpass">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="service" class="col-md-3 col-form-label">SERVICE</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text" value="{{$service ?? ''}}"
                                                        name="service">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="additional_service_notes"
                                                    class="col-md-3 col-form-label">ADDITIONAL SERVICE NOTES</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{$additional_service_notes ?? ''}}"
                                                        name="additional_service_notes">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="last_updated" class="col-md-3 col-form-label">LAST
                                                    UPDATED</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="date"
                                                        value="{{$last_updated ?? ''}}" name="last_updated">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="epa_certification" class="col-md-3 col-form-label">EPA
                                                    CERTIFICATION</label>
                                                <div class="col-md-9">
                                                    <input class="form-control" type="text"
                                                        value="{{$epa_certification ?? ''}}" name="epa_certification">
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

                                          <div class="tab-pane fade show " id="edit_service_tab" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body">
  
                                @include('technicians.service_area')
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
</div>
</div>
@section('script')
<script>
    @foreach($userAddresscity as $location)
    var latitude = {{ $location->latitude }}; // Example latitude
    var longitude = {{ $location->longitude }}; // Example longitude

    // Construct the URL with the latitude and longitude values
    var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' + latitude
    + ',' + longitude + '&zoom=13';

    document.getElementById('map{{ $location->address_id }}').src = mapUrl;
    @endforeach
</script>
<script>
    $(document).ready(function(){
        // Select the password and new password input fields
        var passwordField = $('input[name="password"]');
        var newPasswordField = $('input[name="confirm_password"]');
        var passwordMatchMessage = $('#passwordMatchMessage');

        // Select the form and attach a submit event listener
        $('form').submit(function(event){
            // Prevent the form from submitting
            event.preventDefault();

            // Get the values of the password and new password fields
            var passwordValue = passwordField.val();
            var newPasswordValue = newPasswordField.val();

            // Check if the passwords match
            if(passwordValue === newPasswordValue){
                // If passwords match, submit the form
                this.submit();
            } else {
                // Show danger message
                passwordMatchMessage.removeClass('alert-success').addClass('alert-danger').html('Passwords do not match. Please enter matching passwords.').show();
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
    $(document).ready(function () {

        $('#state_id').change(function () {

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

                success: function (data) {

                    citySelect.html('<option selected disabled value="">Select City...</option>');

                    $.each(data, function (index, city) {

                        citySelect.append('<option value="' + city.city_id + '">' + city.city + ' - ' + city.zip + '</option>');

                    });

                },

                error: function (xhr, status, error) {

                    console.error('Error fetching cities:', error);

                }

            });

        });



        // Trigger another function to get zip code after selecting a city

        $('#city').change(function () {

            var cityId = $(this).val();

            var cityName = $(this).find(':selected').text().split(' - ')[0]; // Extract city name from option text

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
        success: function(data){
            var zipCode = data.zip_code; // Assuming the response contains the zip code
            $('#zip_code').val(zipCode); // Set the zip code in the input field
        },
        error: function(xhr, status, error){
            console.error('Error fetching zip code:', error);
        }
    });
}



</script>

@endsection

@endsection
