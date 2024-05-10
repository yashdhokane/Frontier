@extends('home')
@section('content')
<!-- Page wrapper  -->

<!-- -------------------------------------------------------------- -->
<!-- Bread crumb and right sidebar toggle -->
<!-- -------------------------------------------------------------- -->
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-6 align-self-center">
            <h4 class="page-title">{{ $multiadmin->name }}</h4>
            <!--<div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('multiadmin.index') }}">Admin</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>-->
        </div>
        <div class="col-6 text-end">
            <a href="{{ route('multiadmin.index') }}" class=""><i class="ri-contacts-line" style="margin-right: 8px;"></i> Back to Admin List </a>
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

                    <li class="nav-item" style="">
                        <a class="nav-link" id="pills-setting-tab" data-bs-toggle="pill" href="#calls_tab" role="tab"
                            aria-controls="pills-setting" aria-selected="false"><i class="fas fa-calendar-check"></i>
                            Calls</a>
                    </li>

                    <li class="nav-item">

                        <a class="nav-link" id="pills-payment-tab" data-bs-toggle="pill" href="#payment_tab" role="tab"
                            aria-controls="pills-payments" aria-selected="false"><i
                                class="ri-money-dollar-box-line"></i> Payments</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#estimate_tab"
                            role="tab" aria-controls="pills-timeline" aria-selected="false"><i
                                class="far ri-price-tag-2-line"></i> Estimates</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#edit_profile_tab"
                            role="tab" aria-controls="pills-timeline" aria-selected="false"><i class="ri-edit-fill"></i>
                            Edit Details</a>

                    </li>


                    <li class="nav-item">
                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#others_tab" role="tab"
                            aria-controls="pills-timeline" aria-selected="false"><i class="ri-draft-line"></i> Notes</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#settings_tab"
                            role="tab" aria-controls="pills-timeline" aria-selected="false">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#permission_tab"
                            role="tab" aria-controls="pills-timeline" aria-selected="false">Permission</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#activity_tab"
                            role="tab" aria-controls="pills-timeline" aria-selected="false">Activity</a>
                    </li>
                    <!--<li class="nav-item" style="">
                            <a class="nav-link" id="pills-payment-tab" data-bs-toggle="pill" href="#payment_tab"
                                role="tab" aria-controls="pills-payments" aria-selected="false">Activity</a>
                        </li>-->
                </ul>
                <!-- Tabs -->
                <div class="tab-content" id="pills-tabContent">

                    <div class="tab-pane fade show active" id="profile_tab" role="tabpanel"
                        aria-labelledby="pills-profile-tab">
                        <div class="card-body card-border shadow">
                            <div class="row">
                                <div class="col-md-3 col-xs-6 b-r">
                                    <div class="card">
                                        <div class="card-body">
                                            <center class="mt-1">
                                                @if($multiadmin->user_image)
                                                <img src="{{ asset('public/images/Uploads/users/' . $multiadmin->id . '/' . $multiadmin->user_image) }}"
                                                    class="rounded-circle" width="150" />
                                                @else
                                                <img src="{{asset('public/images/login_img_bydefault.png')}}"
                                                    alt="avatar" class="rounded-circle" width="150" />
                                                @endif <h4 class="card-title mt-1">{{ $multiadmin->name }}</h4>
                                                {{-- <h6 class="card-subtitle">{{ $multiadmin->company ?? null }}
                                                </h6> --}}
                                            </center>

                                            <div class="col-12">
                                                <h5 class="card-title uppercase mt-4">Tags</h5>
                                                <div class="mt-0">
                                                    @if($multiadmin->tags->isNotEmpty())
                                                    @foreach($multiadmin->tags as $tag)
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
                                                    @foreach($customerimage as $image)
                                                    @if($image->filename)
                                                    <a href="{{ asset('storage/app/' . $image->filename) }}" download>
                                                        <p><i class="fas fa-file-alt"></i></p>
                                                        <img src="{{ asset('storage/app/' . $image->filename) }}"
                                                            alt="Customer Image" style="width: 50px; height: 50px;">
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
                                </div>
                                <div class="col-md-3 col-xs-6 b-r">
                                    <div class="row text-left justify-content-md-left">

                                        <div class="col-12">
                                            <h5 class="card-title uppercase mt-4">Contact info</h5>

                                            <!--<h6 style="font-weight: normal;">
													<i class="fas fa-home"></i>{{ old('home_phone', $home_phone) }}
												</h6>-->
                                            <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i>
                                                {{$multiadmin->mobile}}</h6>
                                            {{-- <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i> +1
                                                123 456 7890
                                            </h6> --}}
                                            <h6 style="font-weight: normal;"><i class="fas fa-envelope"></i>
                                                {{$multiadmin->email}}
                                            </h6>
                                        </div>
                                        <div class="col-12">
                                            @foreach($userAddresscity as $location)
                                            <h5 class="card-title uppercase mt-5">Address</h5>
                                            <h6 style="font-weight: normal;"><i class="ri-map-pin-line"></i>
                                                @if(isset($location->address_line1) && $location->address_line1 !== '')
                                                {{ $location->address_line1 }},
                                                @endif

                                                @if(isset($location->address_line2) && $location->address_line2 !== '')
                                                {{ $location->address_line2 }},
                                                @endif

                                                @if(isset($user->Location->city) && $user->Location->city !== '')
                                                {{ $user->Location->city }},
                                                @endif

                                                @if(isset($location->state_name) && $location->state_name !== '')
                                                {{ $location->state_name }},
                                                @endif

                                                @if(isset($location->zipcode) && $location->zipcode !== '')
                                                {{ $location->zipcode }}
                                                @endif

                                            </h6>
                                            @endforeach
                                        </div>
                                        <h5 class="card-title uppercase mt-4">Summary</h5>
                                        <div class="col-12">
                                            <small class="text-muted pt-1 db">Last service </small>
                                            <h6>Active</h6>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted pt-1 db">Profile Created</small>
                                            <h6>{{ $multiadmin->created_at ?
                                                \Carbon\Carbon::parse($multiadmin->created_at)->format('m-d-Y') : null
                                                }}</h6>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted pt-1 db">Lifetime value</small>
                                            <h6>$0.00</h6>
                                        </div>
                                        <div class="col-12">
                                            <small class="text-muted pt-1 db">Outstanding balance</small>
                                            <h6>$0.00</h6>
                                        </div>

                                        <div class="col-12">
                                            <h5 class="card-title uppercase mt-4">Notifications</h5>
                                            <h6 style="font-weight: normal;margin-bottom: 0px;"><i
                                                    class="fas fa-check"></i> Yes
                                            </h6>
                                        </div>

                                        <div class="col-12">
                                            {{-- <h4 class=" card-title mt-4">Lead Source</h4>
                                            <div class="mt-0">
                                                <span
                                                    class="mb-1 badge bg-primary">{{$multiadmin->leadsourcename->source_name
                                                    ??
                                                    null }}</span>
                                            </div> --}}
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-6 b-r">
                                    <div class="mt-4">
                                        @foreach($userAddresscity as $location)

                                        <div>

                                            <iframe id="map{{ $location->address_id }}" width="100%" height="300"
                                                frameborder="0" style="border: 0" allowfullscreen></iframe>

                                            <small class="text-muted pt-4 db">{{ $location->address_type
                                                }}</small>
                                            <div style="display:flex;">

                                                @if(isset($location->address_line1) && $location->address_line1 !==
                                                '')
                                                {{ $location->address_line1 }},
                                                @endif

                                                @if(isset($location->address_line2) && $location->address_line2 !==
                                                '')
                                                {{ $location->address_line2 }},
                                                @endif

                                                @if(isset($user->Location->city) && $user->Location->city !== '')
                                                {{ $user->Location->city }},
                                                @endif

                                                @if(isset($location->state_name) && $location->state_name !== '')
                                                {{ $location->state_name }},
                                                @endif

                                                @if(isset($location->zipcode) && $location->zipcode !== '')
                                                {{ $location->zipcode }}
                                                @endif

                                                <br />
                                            </div>

                                            {{-- <iframe
                                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6991603.699017098!2d-100.0768425!3d31.168910300000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864070360b823249%3A0x16eb1c8f1808de3c!2sTexas%2C%20USA!5e0!3m2!1sen!2sin!4v1701086703789!5m2!1sen!2sin"
                                                width="100%" height="300" style="border:0;" allowfullscreen=""
                                                loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                            --}}

                                        </div>
                                        <hr />
                                        @endforeach




                                    </div>


                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="tab-pane fade" id="calls_tab" role="tabpanel" aria-labelledby="pills-setting-tab">
                        <div class="card-body card-border shadow">
                            <h5 class="card-title uppercase">Jobs / Calls</h5>

                            @if($tickets->where('added_by', $multiadmin->id)->isEmpty())
                            <div class="alert alert-info mt-4 col-md-12" role="alert">Calls not available for {{
                                $multiadmin->name ?? '' }}.
                                <strong><a href="{{route('schedule')}}">Add New</a></strong>
                            </div>
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
                                        @foreach ($tickets->where('added_by', $multiadmin->id) as $ticket)
                                        <tr>
                                            <td>
                                                <a href="{{ route('tickets.show', $ticket->id) }}"
                                                    class="fw-bold link"><span class="mb-1 badge bg-primary">{{
                                                        $ticket->job_code }}</span></a>
                                            </td>
                                            <td>
                                                <div class="text-wrap2">
                                                    <a href="{{ route('tickets.show', $ticket->id) }}"
                                                        class="font-medium link">{{
                                                        $ticket->job_title ??
                                                        null }}</a> <span
                                                        class="badge bg-light-warning text-warning font-medium">{{
                                                        $ticket->status
                                                        }}</span>
                                                </div>
                                                <div style="font-size:12px;">
                                                    @if ($ticket->jobdetailsinfo &&
                                                    $ticket->jobdetailsinfo->apliencename)
                                                    {{ $ticket->jobdetailsinfo->apliencename->appliance_name }}/
                                                    @endif
                                                    @if ($ticket->jobdetailsinfo &&
                                                    $ticket->jobdetailsinfo->manufacturername)
                                                    {{
                                                    $ticket->jobdetailsinfo->manufacturername->manufacturer_name
                                                    }}/
                                                    @endif
                                                    @if ($ticket->jobdetailsinfo &&
                                                    $ticket->jobdetailsinfo->model_number)
                                                    {{ $ticket->jobdetailsinfo->model_number }}/
                                                    @endif
                                                    @if ($ticket->jobdetailsinfo &&
                                                    $ticket->jobdetailsinfo->serial_number)
                                                    {{ $ticket->jobdetailsinfo->serial_number }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if ($ticket->user)
                                                <a href="{{ route('users.show', $ticket->user->id) }}" class="link">{{
                                                    $ticket->user->name
                                                    }}</a>
                                                @else
                                                Unassigned
                                                @endif
                                            </td>
                                            <td>
                                                @if ($ticket->technician)
                                                <a href="{{ route('technicians.show', $ticket->technician->id) }}"
                                                    class="link">{{
                                                    $ticket->technician->name }}</a>
                                                @else
                                                Unassigned
                                                @endif
                                            </td>
                                            <td>
                                                @if ($ticket->jobassignname &&
                                                $ticket->jobassignname->start_date_time)
                                                <div class="font-medium link">{{
                                                    $convertDateToTimezone($ticket->jobassignname->start_date_time)
                                                    }}</div>
                                                @else
                                                <div></div>
                                                @endif
                                                <div style="font-size:12px;">
                                                    {{
                                                    $convertTimeToTimezone($ticket->JobAssign->start_date_time
                                                    ?? null, 'H:i:a') }} to {{
                                                    $convertTimeToTimezone($ticket->JobAssign->end_date_time ??
                                                    null, 'H:i:a') }}
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
                            @if($payment->isEmpty())
                            <div class="alert alert-info mt-4" role="alert">
                                Payments not available for {{ $multiadmin->name ?? '' }}.
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
                                        @foreach($payment as $payment)
                                        <tr>
                                            {{-- @php
                                            $jobname = DB::table('jobs')->where('id',
                                            $payment->job_id)->first();
                                            @endphp --}}
                                            <td>
                                                <a href=" {{ route('invoicedetail', ['id' => $payment->id]) }}"
                                                    class="font-medium link">{{
                                                    $payment->id ?? 'N/A'
                                                    }}</a>
                                            </td>

                                            <td><a href=" {{ route('invoicedetail', ['id' => $payment->id]) }}"
                                                    class="font-medium link">{{
                                                    $payment->JobModel->job_title ?? 'N/A'
                                                    }}</a>
                                            </td>

                                            <td>{{ isset($payment->created_at) ?
                                                \Carbon\Carbon::parse($payment->created_at)->format('m-d-Y @ g:i
                                                a') : null }}</td>
                                            <td>{{$payment->total ?? null}}</td>
                                            <td>{{$payment->status ?? null}} </td>
                                            <td>
                                                @php
                                                if ($payment) {
                                                $technician1 = DB::table('users')->where('id',
                                                $payment->JobModel->technician_id)->first(); // Retrieve technician

                                                // $technician_name = $technician ? $technician->name : 'Unknown';
                                                // Get technician's name or set to 'Unknown' if not found
                                                } else {
                                                $technician1 = 'Unknown';
                                                }
                                                @endphp
                                                <a href="{{ route('technicians.show', ['id' => $technician1->id]) }}"
                                                    class="link">{{$technician1->name ?? null}}</a>
                                            </td>


                                            <td>@php
                                                $customer = DB::table('users')->where('id',
                                                $payment->customer_id)->first();
                                                @endphp
                                                <a href="{{ route('users.show', ['id' => $customer->id]) }}"
                                                    class="link">{{ $customer->name
                                                    ?? null }}</a>

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
                    <div class="tab-pane fade" id="estimate_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">

                        <div class="card-body card-border shadow">




                            <div class="row">



                                <h4>Estimates</h4>



                                <div class="alert alert-info mt-4 col-md-12" role="alert">

                                    Estimates details not available for {{$multiadmin->name ?? null}}. <strong><a
                                            href="{{route('schedule')}}">Add New</a></strong>

                                </div>






                            </div>

                        </div>



                    </div>
                    <div class="tab-pane fade" id="settings_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            {{-- <h5 class="card-title uppercase">Settings</h5> --}}
                            @include('multiadmin.setting_tab_file_multiadmin')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="permission_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            <h5 class="card-title uppercase">Permission</h5>
                            PERMISSION MODULE HERE
                        </div>
                    </div>
                    <div class="tab-pane fade" id="activity_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            {{-- <h5 class="card-title uppercase">Activity </h5> --}}
                            <div class="col-md-12 ">

                                <h5 class="card-title">ACTIVITY FEED</h5>
                                <div class="table-responsive">
                                    <table class="table customize-table mb-0 v-middle">
                                        <thead>
                                            <tr>
                                                <!-- <th style="width:20%">User</th> -->
                                                <th>Activity</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($activity as $record)
                                            <tr>
                                                <td>{{ $record->activity}}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($record->created_at)->format('D
                                                    n/j/y g:ia') ??
                                                    'null' }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>



                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="edit_profile_tab" role="tabpanel"
                        aria-labelledby="pills-timeline-tab">

                        <div class="card-body card-border shadow">

                            @include('multiadmin.edit')

                        </div>

                    </div>


                    <div class="tab-pane fade show " id="others_tab" role="tabpanel"
                        aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            <div class="profiletimeline mt-0">
                                @foreach ($notename as $notename )
                                <div class="sl-item">
                                    <div class="sl-left">
                                        @php
                                        $username = DB::table('users')->where('id',
                                        $notename->added_by)->first();
                                        @endphp
                                        @if($username && $username->user_image) <img
                                            src="{{ asset('public/images/Uploads/users/'. $username->id . '/' . $username->user_image) }}"
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
                            <div class="row mt-2">
                                <div class="col-lg-6 col-xlg-6">
                                    <form id="commentForm" action="{{ route('techniciancomment.store') }}"
                                        method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="tag_id" class="control-label bold col-form-label uppercase">Add
                                                New Comment</label>
                                            <input type="hidden" name="id" value="{{ $multiadmin->id }}">
                                            <textarea class="form-control" id="comment" name="note" rows="3"></textarea>
                                        </div>
                                        <div class="mb-3 d-flex align-items-center">
                                            <button type="submit" id="submitButton"
                                                class="btn btn-primary ms-2">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="settings_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">

                        <div class="card-body card-border shadow">

                            @include('multiadmin.setting')

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

                    citySelect.html('<option selected disabled value="">Select City...</option>');

                    $.each(data, function(index, city) {

                        citySelect.append('<option value="' + city.city_id + '">' + city.city + ' - ' + city.zip + '</option>');

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

            var cityName = $(this).find(':selected').text().split(' - ')[0]; // Extract city name from option text

            getZipCode(cityId, cityName); // Call the function to get the zip code

        });

    });

    // Function to get zip code
   var appendedCities = []; // Array to store already appended cities

function searchCity() {
$("#city").autocomplete({
source: function(request, response) {
$.ajax({
url: "{{ route('autocomplete.city') }}",
data: {
term: request.term
},
dataType: "json",
type: "GET",
success: function(data) {
var uniqueCities = []; // Array to store unique cities from the response
data.forEach(function(item) {
if (!appendedCities.includes(item.city)) {
uniqueCities.push(item);
appendedCities.push(item.city);
}
});
response(uniqueCities);
},
error: function(xhr, status, error) {
console.log("Error fetching city data:", error);
}
});
},
minLength: 3,
select: function(event, ui) {
$("#city").val(ui.item.city);
$("#city_id").val(ui.item.city_id);
$("#autocomplete-results").empty();
return false;
}
}).data("ui-autocomplete")._renderItem = function(ul, item) {
return $("<li>").text(item.city).appendTo("#autocomplete-results");
    };

    $("#autocomplete-results").on("click", "li", function() {
    var cityName = $(this).text();
    var cityId = $(this).data("city_id");
    $("#city_id").val(cityId);
    $("#city").val(cityName);
    $("#autocomplete-results").hide();
    });

    $("#city").click(function() {
    $("#autocomplete-results").show();
    });

    $("#city").on("input", function() {
    var inputText = $(this).val().trim();
    if (inputText === "") {
    $("#autocomplete-results").empty();
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


@endsection

@endsection
