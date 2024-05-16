@extends('home')
@section('content')
<!-- Page wrapper  -->


<!-- -------------------------------------------------------------- -->
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
            <h4 class="page-title">{{ $dispatcher->name }} <small class="text-muted"
                    style="font-size: 10px;">Dispatcher</small></h4>
        </div>
        <div class="col-3 text-end">
            <a href="https://dispatchannel.com/portal/dispatcher-index"
                class="btn btn-primary font-weight-medium shadow"><i class="ri-contacts-line"
                    style="margin-right: 8px;"></i>Dispatcher List</a>
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
                            aria-controls="pills-timeline" aria-selected="false">Notes</a>
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


                </ul>
                <!-- Tabs -->
                <div class="tab-content" id="pills-tabContent">

                    <div class="tab-pane fade show active" id="profile_tab" role="tabpanel"
                        aria-labelledby="pills-profile-tab">
                        <div class="card-body card-border shadow">
                            <div class="row">
                                <div class="col-lg-3 col-xlg-9">

                                    <center class="mt-1">
                                        @if($dispatcher->user_image)
                                        <img src="{{ asset('public/images/Uploads/users/'. $dispatcher->id . '/' . $dispatcher->user_image) }}"
                                            class="rounded-circle" width="150" />
                                        @else
                                        <img src="{{asset('public/images/login_img_bydefault.png')}}" alt="avatar"
                                            class="rounded-circle" width="150" />
                                        @endif
                                        <h4 class="card-title mt-1">{{ $dispatcher->name }}</h4>
                                        {{-- <h6 class="card-subtitle">{{ $dispatcher->company ?? 'null' }}
                                        </h6> --}}
                                    </center>

                                    <div class="col-12">
                                        <h5 class="card-title uppercase mt-4">Tags</h5>
                                        <div class="mt-0">
                                            @if($dispatcher->tags->isNotEmpty())
                                            @foreach($dispatcher->tags as $tag)
                                            <span class="badge bg-dark">{{ $tag->tag_name }}</span>
                                            @endforeach
                                            @else
                                            <span class="badge bg-dark">No tags available</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
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

                                <div class="col-lg-9 col-xlg-9">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-6 b-r">

                                            <div class="row text-left justify-content-md-left">

                                                <div class="col-12">
                                                    <h5 class="card-title uppercase mt-1">Contact info</h5>

                                                    <!--<h6 style="font-weight: normal;">
															<i class="fas fa-home"></i>{{ old('home_phone', $home_phone) }}
														</h6>-->
                                                    <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i>
                                                        {{$dispatcher->mobile}}</h6>
                                                    {{-- <h6 style="font-weight: normal;"><i
                                                            class="fas fa-mobile-alt"></i> +1
                                                        123 456 7890
                                                    </h6> --}}
                                                    <h6 style="font-weight: normal;"><i class="fas fa-envelope"></i>
                                                        {{$dispatcher->email}}
                                                    </h6>
                                                </div>
                                                <div class="col-12">
                                                    <h5 class="card-title uppercase mt-5">Address</h5>
                                                    <h6 style="font-weight: normal;"><i class="ri-map-pin-line"></i>
                                                        {{ $address ?? ''}}
                                                    </h6>

                                                </div>

                                                <h5 class="card-title uppercase mt-4">Summary</h5>
                                                <div class="col-12">
                                                    <small class="text-muted pt-1 db">Last service</small>
                                                    <h6>Active</h6>
                                                </div>
                                                <div class="col-12">
                                                    <small class="text-muted pt-1 db">Profile Created</small>
                                                    <h6>{{ $dispatcher->created_at ?
                                                        \Carbon\Carbon::parse($dispatcher->created_at)->format('m-d-Y')
                                                        : null
                                                        }}</h6>
                                                </div>




                                            </div>

                                        </div>
                                        <div class="col-md-1 col-xs-6 b-r">&nbsp;</div>

                                        <div class="col-md-8 col-xs-6 b-r">
                                            <div>

                                                <div class="mt-2">

                                                    <iframe id="map{{ $location->address_id }}" width="100%"
                                                        height="300" frameborder="0" style="border: 0"
                                                        allowfullscreen></iframe>


                                                    <div style="display:flex;">


                                                        <h6>
                                                            {{ $address ?? ''}}
                                                        </h6>

                                                        <br />
                                                    </div>
                                                    {{-- <iframe
                                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6991603.699017098!2d-100.0768425!3d31.168910300000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864070360b823249%3A0x16eb1c8f1808de3c!2sTexas%2C%20USA!5e0!3m2!1sen!2sin!4v1701086703789!5m2!1sen!2sin"
                                                        width="100%" height="300" style="border:0;" allowfullscreen=""
                                                        loading="lazy"
                                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                    --}}

                                                </div>
                                                <hr />




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

                            @if($tickets->where('added_by', $dispatcher->id)->isEmpty())
                            <div class="alert alert-info mt-4 col-md-12" role="alert">Calls not available for {{
                                $dispatcher->name ?? '' }}.
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
                                        @foreach ($tickets->where('added_by', $dispatcher->id) as $ticket)
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
                                Payments not available for {{ $dispatcher->name ?? '' }}.
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


                        </div>
                    </div>

                    <div class="tab-pane fade" id="estimate_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            <h5 class="card-title uppercase">Estimates</h5>
                            <div class="alert alert-info mt-4 col-md-12" role="alert">Estimates details not available
                                for {{$dispatcher->name ?? null}}. <strong><a href="{{route('schedule')}}">Add
                                        New</a></strong>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="settings_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            {{-- <h5 class="card-title uppercase">Settings</h5> --}}
                            @include('dispatcher.setting_tab_file')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="permission_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            <h5 class="card-title uppercase">User Permission</h5>

                            <div class="row mt-3 mb-3">
                                @php
                                use App\Models\UserPermission;
                                use App\Models\PermissionModel;

                                $access_array = UserPermission::where('user_id', $dispatcher->id)
                                ->where('permission', 1)
                                ->pluck('module_id')
                                ->toArray();

                                $parentModules = PermissionModel::where('parent_id', 0)
                                ->orderBy('module_id', 'ASC')
                                ->get();
                                @endphp
                                <div class="col-md-8">
                                    <form id="permissionsForm" action="{{ route('update.permissions') }}" method="POST">
                                        @csrf
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input info" type="radio" name="radio-solid-info"
                                                id="permissions_type_all" value="all" {{ $dispatcher->permissions_type
                                            == 'all' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permissions_type_all">All</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input info" type="radio" name="radio-solid-info"
                                                id="permissions_type_selected" value="selected" {{
                                                $dispatcher->permissions_type == 'selected' ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="permissions_type_selected">Selected</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input info" type="radio" name="radio-solid-info"
                                                id="permissions_type_block" value="block" {{
                                                $dispatcher->permissions_type == 'block' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="permissions_type_block">Block</label>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                @foreach($parentModules as $parentModule)
                                                @php

                                                $childModules = PermissionModel::where('parent_id',
                                                $parentModule->module_id)
                                                ->orderBy('module_id', 'ASC')
                                                ->get();
                                                @endphp

                                                <h6>{{ $loop->iteration }}: {{ $parentModule->module_name }}</h6>

                                                @foreach($childModules as $childModule)
                                                <div class="mb-2">
                                                    <label class="form-check-label"
                                                        for="p_mod_{{ $childModule->module_id }}">
                                                        <input class="form-check-input permission-checkbox updatevalue"
                                                            type="checkbox" id="p_mod_{{ $childModule->module_id }}"
                                                            name="{{ $childModule->module_id }}[]" value="1" {{
                                                            in_array($childModule->module_id, $access_array) ? 'checked'
                                                        : '' }}>
                                                        {{ $childModule->module_name }}
                                                    </label>
                                                    <!-- Hidden input to ensure the value is always submitted -->
                                                    <input type="hidden" name="{{ $childModule->module_id }}[]"
                                                        value="0">
                                                </div>
                                                @endforeach

                                                <br><br>
                                                @endforeach
                                                <input type="hidden" name="user_id" value="{{ $dispatcher->id }}">
                                            </div>
                                        </div>
                                         @if($auth->role == 'dispatcher')
                                           <button type="button" class="btn btn-primary" disabled>Save Permissions</button>
                                            <br><small class="bg-danger text-white px-2"> Dispatcher can't change the permission.</small>
                                         @else
                                         <button type="submit" class="btn btn-primary">Save Permissions</button>
                                         @endif
                                    </form>
                                </div>
                            </div>
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

                            @include('dispatcher.edit')

                        </div>

                    </div>


                    <div class="tab-pane fade show " id="others_tab" role="tabpanel"
                        aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            <h5 class="card-title uppercase">Notes </h5>
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
                                            <input type="hidden" name="id" value="{{ $dispatcher->id }}">
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
    document.addEventListener('DOMContentLoaded', function() {
        var allCheckbox = document.querySelectorAll('.permission-checkbox');
        var allRadio = document.querySelectorAll('input[name="radio-solid-info"]');

        allRadio.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.value === 'all') {
                    allCheckbox.forEach(function(checkbox) {
                        checkbox.checked = true;
                        checkbox.value = 1;
                        checkbox.disabled = false; // Set value to 1 for all checkboxes when 'All' is selected
                    });
                } else if (this.value === 'block') {
                    allCheckbox.forEach(function(checkbox) {
                        checkbox.checked = false;
                        checkbox.value = 0;
                        checkbox.disabled = true; // Set value to 0 for all checkboxes when 'Block' is selected
                    });
                }
                else if (this.value === 'selected') {
                allCheckbox.forEach(function(checkbox) {
            //  checkbox.checked = true;
            // checkbox.value = 1;
                checkbox.disabled = false; // Set value to 0 for all checkboxes when 'Block' is selected
                });
                }
            });
        });

        // Update the state of the 'All' radio button based on checkbox states
        allCheckbox.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var allChecked = true;
                allCheckbox.forEach(function(cb) {
                    if (!cb.checked) {
                        allChecked = false;
                    }
                });
                document.getElementById('permissions_type_all').checked = allChecked;
            });
        });
    });

</script>
<script>
    $(document).ready(function() {


        $(document).on('click', '.updatevalue', function() {
            var updatevalue = $(this).val();
            if (this.checked) {
                $(this).val(1);
            } else {
                $(this).val(0);
            }
        });


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
    function searchCity() {
        // Initialize autocomplete
        $("#city").autocomplete({
            source: function(request, response) {
                // Clear previous autocomplete results
                $("#autocomplete-results").empty();

                $.ajax({
                    url: "{{ route('autocomplete.city') }}"
                    , data: {
                        term: request.term
                    }
                    , dataType: "json"
                    , type: "GET"
                    , success: function(data) {
                        response(data);
                    }
                    , error: function(response) {
                        console.log("Error fetching city data:", response);
                    }
                });
            }
            , minLength: 2
            , select: function(event, ui) {
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
    @foreach($userAddresscity as $location)
    var latitude = {
        {
            $location - > latitude
        }
    }; // Example latitude
    var longitude = {
        {
            $location - > longitude
        }
    }; // Example longitude

    // Construct the URL with the latitude and longitude values
    var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' + latitude +
        ',' + longitude + '&zoom=13';

    document.getElementById('map{{ $location->address_id }}').src = mapUrl;
    @endforeach

</script>


@endsection

@endsection