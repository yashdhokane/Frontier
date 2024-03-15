@extends('home')
@section('content')

    <style>
        .form-control:focus {
            box-shadow: 0 0 0 0rem rgba(54, 153, 255, .25);
        }

        .popupDiv {
            position: absolute;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px;
            z-index: 1000;
            /* Ensure the popup appears above other content */
        }

        .popupDiv div {
            padding: 5px 0;
        }

        .popupDiv div span {
            margin-left: 5px;
        }

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

        .error {
            color: #ca1414;
        }

        .table-responsive {
            overflow-x: auto !important;
            width: 100% !important;
        }

        .timeslot_td {
            position: relative;
            height: 80px;
            width: 100px;
            font-size: 12px;
        }

        .timeslot_th {
            position: relative;
            width: 100px;
        }

        .flexibleslot {
            cursor: pointer;
            position: absolute;
            z-index: 1;
            width: -webkit-fill-available;
        }

        .overflow_x {
            overflow-x: auto;
        }

        .overflow_y {
            overflow-y: auto;
        }

        .pending_jobs2 {
            border: 1px solid #2962ff;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .img40 {
            width: 40px;
            height: 40px;
            line-height: 40px;
        }

        .customer_sr_box {
            padding: 10px;
            border: 1px solid #2962ff;
            border-radius: 4px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .confirm_job_box {
            margin-bottom: 20px;
        }

        .test {
            display: contents;
            font-size: 11px;
        }

        .reschedule_job {
            font-size: 12px;
        }

        .customer_sr_box:hover {
            background-color: #f3f3f3;
        }

        .pending_jobs2:hover {
            background-color: #f3f3f3;
        }

        .service_css {
            font-size: 11px;
        }

        .total_css {
            font-size: 14px;
        }

        .customers {
            height: 304px;
            overflow-y: auto;
        }

        .rescheduleJobs {
            height: 304px;
            overflow-y: auto;
        }
    </style>

    <div class="page-wrapper p-0 ms-2" style="display:flex;">
        <!-- Container fluid  -->
        <!-- -------------------------------------------------------------- -->

        <div class="bg-white p-3 mt-4 h-100" id="filterSchedule" style="display: none;">

            <div class="d-flex gap-5 justify-content-between">
                <i class="fa fa-calendar-check fs-7"></i>
                <div>
                    <a href="schedule?date={{ $TodayDate }}">
                        <h4 class="btn btn-outline-dark">Today</h4>
                    </a>

                    <i class="fa fa-arrow-alt-circle-left fs-7 ms-3 text-primary" id="leftArrow" style=""></i>
                </div>
            </div>
            <hr>
            <div>
                <h6>Schedule</h6>
                <div id="datepicker-container" class="pe-5">
                    <input type="text" id="datepicker">
                </div>

            </div>
            <hr>
            <div>
                <h4>TECHNICIAN</h4>
                <input type="text" name="searchTechnician" id="searchTechnician" class="form-control mb-4 border-black">
                @foreach ($tech as $k => $item)
                    <div class="d-flex gap-3 technician-item">
                        <input type="checkbox" class="technician_check" data-id="{{ $item->id }}"
                            id="tech{{ $k }}" style="transform: scale(1.5);"
                            {{ $item->status == 'active' ? 'checked' : '' }}>
                        <label for="tech{{ $k }}" class="fs-4">{{ $item->name }}</label>
                    </div>
                @endforeach
            </div>


        </div>
        <div id="rightArrow" class="mt-4 h-100">
            <i class="fa fa-arrow-alt-circle-right fs-7 ms-3 text-primary" title="Filter"></i>
        </div>
        <div class="container-fluid">

            <div class="row">

                <div class="card">
                    <div>
                        <div class="row gx-0">
                            <div class="col-lg-12">
                                <div class="p-4 calender-sidebar app-calendar">
                                    <div class="row">
                                        <div class="col-md-2"><a href="schedule?date={{ $previousDate }}"><i
                                                    class="fas fa-arrow-left"></i></a></div>
                                        <div class="col-md-4">
                                            <h4 class="fc-toolbar-title text-center" id="fc-dom-1">
                                                {{ $formattedDate }}
                                            </h4>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <a id="selectDates"
                                                style="margin-right: 10px; font-size: 13px;cursor: pointer;"><i
                                                    class="fas fa-calendar-alt"></i>
                                                Select Dates</a>

                                            <a href="schedule?date={{ $TodayDate }}"
                                                style="margin-right: 10px;font-size: 13px;color: #ee9d01;font-weight: bold;"><i
                                                    class="fas fa-calendar-check"></i> Today</a>
                                        </div>
                                        <div class="col-md-2" style="text-align: right;"><a
                                                href="schedule?date={{ $tomorrowDate }}"><i
                                                    class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                    <div class="table-responsive dat">
                                        <table id="demo-foo-addrow"
                                            class="table table-bordered m-t-30 table-hover contact-list text-nowrap"
                                            data-paging="true" data-paging-size="7">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    @if (isset($user_array) && !empty($user_array))
                                                        @foreach ($user_array as $value)
                                                            <th class="tech_th" data-tech-id="{{ $value }}"><a
                                                                    href="#" class="link user_head_link"
                                                                    style="color: {{ $user_data_array[$value]['color_code'] }} !important;">
                                                                    @if (isset($user_data_array[$value]['user_image']) && !empty($user_data_array[$value]['user_image']))
                                                                        <img src="{{ asset('public/images/technician/' . $user_data_array[$value]['user_image']) }}"
                                                                            alt="user" width="48"
                                                                            class="rounded-circle" /><br>
                                                                    @else
                                                                        <img src="{{ asset('public/images/login_img_bydefault.png') }}"
                                                                            alt="user" width="48"
                                                                            class="rounded-circle " /><br>
                                                                    @endif
                                                                    {{ 'EMP' . $value }} <br>
                                                                    @if (isset($user_data_array[$value]) && !empty($user_data_array[$value]))
                                                                        {{ $user_data_array[$value]['name'] }}
                                                                    @endif
                                                                </a>
                                                            </th>
                                                        @endforeach
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @for ($i = 7; $i <= 18; $i++)
                                                    @for ($minute = 0; $minute < 60; $minute += 30)
                                                        <tr>
                                                            <td class="timeslot_td">
                                                                @php
                                                                    $time = $i >= 12 ? ($i > 12 ? $i - 12 : $i) : $i;
                                                                    $minutes = '00'; // Default minutes
                                                                    if ($i % 1 !== 0) {
                                                                        $minutes = '30'; // Adjust minutes for half-hour intervals
                                                                    }
                                                                    $date = $i >= 12 ? 'pm' : 'am';

                                                                    $display_hour = $i > 12 ? $i - 12 : $i;
                                                                    $display_minute = $minute == 0 ? '00' : $minute;
                                                                    $time =
                                                                        $display_hour .
                                                                        ':' .
                                                                        $display_minute .
                                                                        ' ' .
                                                                        $date;
                                                                    $timeString =
                                                                        $time . ($minutes == '00' ? '' : ':30');
                                                                    // Format $time for comparison
                                                                    $formattedTime = date('h:i A', strtotime($time));
                                                                @endphp
                                                                {{ $time }}
                                                            </td>
                                                            @if (isset($user_array) && !empty($user_array))
                                                                @foreach ($user_array as $value)
                                                                    @php
                                                                        $assigned_data = [];
                                                                        if (
                                                                            isset(
                                                                                $assignment_arr[$value][$formattedTime],
                                                                            ) &&
                                                                            !empty(
                                                                                $assignment_arr[$value][$formattedTime]
                                                                            )
                                                                        ) {
                                                                            $assigned_data =
                                                                                $assignment_arr[$value][$formattedTime];
                                                                            // dd($assigned_data);
                                                                        }
                                                                    @endphp
                                                                    <td class="timeslot_td"
                                                                        data-slot_time="{{ $timeString }}"
                                                                        data-technician_id="{{ $value }}">
                                                                        @if (isset($assigned_data) && !empty($assigned_data))
                                                                            <div class="testclass">
                                                                                @foreach ($assigned_data as $value2)
                                                                                    @php
                                                                                        $duration = $value2->duration;
                                                                                        $height_slot = $duration / 30;
                                                                                        $height_slot_px =
                                                                                            $height_slot * 80 - 10;
                                                                                        // dd($height_slot_px);
                                                                                    @endphp
                                                                                    <div class="dts mb-1 edit_schedule updateSchedule flexibleslot"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#edit"
                                                                                        data-id="{{ $value }}"
                                                                                        data-job-id="{{ $value2->job_id }}"
                                                                                        data-time="{{ $timeString }}"
                                                                                        data-date="{{ $filterDate }}"
                                                                                        style="cursor: pointer; height: {{ $height_slot_px }}px; background: {{ $value2->color_code }};"
                                                                                        data-id="{{ $value2->main_id }}">
                                                                                        <h5
                                                                                            style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">
                                                                                            {{ $value2->customername }}
                                                                                            &nbsp;&nbsp;
                                                                                        </h5>
                                                                                        <p style="font-size: 11px;">
                                                                                            <i class="fas fa-clock"></i>
                                                                                            {{ $timeString }} --
                                                                                            {{ $value2->job_code }}<br>{{ $value2->job_title }}
                                                                                        </p>
                                                                                        <p style="font-size: 12px;">
                                                                                            {{ $value2->city }},
                                                                                            {{ $value2->state }}
                                                                                        </p>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        @else
                                                                            <div class="dts2 clickPoint"
                                                                                style="height: 100%; position: revert;"
                                                                                {{-- data-bs-toggle="modal" --}}
                                                                                data-id="{{ $value }}"
                                                                                data-time="{{ $timeString }}"
                                                                                data-date="{{ $filterDate }}"
                                                                                data-bs-target="#create">
                                                                            </div>
                                                                            <div class="popupDiv fs-4"
                                                                                style="display: none;">
                                                                                <div class="createSchedule align-items-sm-center d-flex gap-3 fw-semibold"
                                                                                    style="cursor: pointer;"
                                                                                    data-bs-toggle="modal"
                                                                                    data-id="{{ $value }}"
                                                                                    data-time="{{ $timeString }}"
                                                                                    data-date="{{ $filterDate }}"
                                                                                    data-bs-target="#create">
                                                                                    <i class="fa fa-plus-square"></i>
                                                                                    <span>Job</span>
                                                                                </div>
                                                                                <hr class="m-0">
                                                                                <div class="align-items-sm-center d-flex gap-3 fw-semibold"
                                                                                    style="cursor: pointer;"><i
                                                                                        class="fa fa-pen-square"></i>
                                                                                    <span>Estimate</span>
                                                                                </div>
                                                                                <hr class="m-0">
                                                                                <div class="eventSchedule align-items-sm-center d-flex gap-3 fw-semibold"
                                                                                    style="cursor: pointer;"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#event"
                                                                                    data-id="{{ $value }}"><i
                                                                                        class="fa fa-calendar-plus"></i>
                                                                                    <span>Event</span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </td>
                                                                @endforeach
                                                            @endif
                                                        </tr>
                                                    @endfor
                                                @endfor
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal -->
            <div class="modal fade" id="create" tabindex="-1" aria-labelledby="scroll-long-inner-modal"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable2 modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center" style="padding-bottom: 0px;">
                            <h4 class="modal-title" id="myLargeModalLabel" style="margin-left: 28px;color: #2962ff;">ADD
                                NEW
                                JOB (ADD JOB & ASSIGN TECHNICIAN)
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body createScheduleData">
                            @include('schedule.create')
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <!-- Modal -->
            <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="scroll-long-inner-modal"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable2 modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center" style="padding-bottom: 0px;">
                            <h4 class="modal-title" id="myLargeModalLabel" style="margin-left: 28px;color: #2962ff;">
                                UPDATE JOB
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body editScheduleData">
                            @include('schedule.edit')
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

            <!-- Modal -->
            <div class="modal fade" id="newCustomer" tabindex="-1" aria-labelledby="scroll-long-inner-modal"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable2 modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center" style="padding-bottom: 0px;">
                            <h4 class="modal-title" id="myLargeModalLabel" style="margin-left: 28px;color: #2962ff;">ADD
                                NEW CUSTOMER
                            </h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body createCustomerData">



                            <style>
                                .required-field::after {

                                    content: " *";

                                    color: red;

                                }
                            </style>





                            <form id="myForm" method="POST" action="{{ url('new/customer/schedule') }}"
                                enctype="multipart/form-data">

                                @csrf

                                <div class="container-fluid">
                                    <div class="row">

                                        <div class="col-lg-9 d-flex align-items-stretch">

                                            <div class="card w-100">

                                                <div class="card-body border-top">
                                                    <h4 class="card-title">Customer Information</h4>

                                                    <div class="row">

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="first_name"
                                                                    class="control-label col-form-label required-field">First
                                                                    Name</label>

                                                                <input type="text" class="form-control"
                                                                    id="first_name" name="first_name" placeholder=""
                                                                    required />
                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="last_name"
                                                                    class="control-label col-form-label required-field">Last
                                                                    Name</label>

                                                                <input type="text" class="form-control" id="last_name"
                                                                    name="last_name" placeholder="" required />

                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="display_name"
                                                                    class="control-label col-form-label required-field">Display
                                                                    Name (shown

                                                                    on invoice)</label>

                                                                <input type="text" class="form-control"
                                                                    id="display_name" name="display_name" placeholder=""
                                                                    required />

                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="email"
                                                                    class="control-label col-form-label required-field">Email</label>

                                                                <input type="email" class="form-control" id="email"
                                                                    name="email" placeholder="" required />

                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="mobile_phone"
                                                                    class="control-label col-form-label required-field">Mobile
                                                                    Phone</label>

                                                                <input type="text" class="form-control"
                                                                    id="mobile_phone" name="mobile_phone" placeholder=""
                                                                    required />

                                                            </div>

                                                        </div>


                                                        <h4 class="card-title">Address</h4>


                                                        <div class="col-sm-12 col-md-12">

                                                            <div class="mb-3">

                                                                <label for="address1"
                                                                    class="control-label col-form-label required-field">Address
                                                                    Line 1

                                                                    (Street)</label>

                                                                <input type="text" class="form-control" id="address1"
                                                                    name="address1" placeholder="" required />

                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-8">

                                                            <div class="mb-3">

                                                                <label for="address_unit"
                                                                    class="control-label col-form-label required-field">Address
                                                                    Line 2</label>

                                                                <input type="text" class="form-control"
                                                                    id="address_unit" name="address_unit"
                                                                    placeholder="" />

                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="display_name"
                                                                    class="control-label col-form-label required-field">Type</label>

                                                                <select class="form-select me-sm-2" id="address_type"
                                                                    name="address_type">

                                                                    <option value="">Select address..</option>

                                                                    <option value="home">Home Address</option>

                                                                    <option value="work">Work Address</option>

                                                                    <option value="other">Other Address</option>

                                                                </select>

                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="state_id"
                                                                    class="control-label col-form-label required-field">State</label>

                                                                <select class="form-select me-sm-2" id="state_id"
                                                                    name="state_id" required>

                                                                    <option selected disabled value="">Select
                                                                        State...</option>

                                                                    @foreach ($locationStates as $locationState)
                                                                        <option value="{{ $locationState->state_id }}">
                                                                            {{ $locationState->state_name }}

                                                                        </option>
                                                                    @endforeach

                                                                </select>

                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="city"
                                                                    class="control-label col-form-label required-field">City</label>

                                                                <select class="form-select" id="city" name="city"
                                                                    required>

                                                                    <option selected disabled value="">Select City...
                                                                    </option>

                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="zip_code"
                                                                    class="control-label col-form-label required-field">Zip</label>

                                                                <input type="text" class="form-control" id="zip_code"
                                                                    name="zip_code" placeholder="" required />

                                                            </div>

                                                        </div>

                                                        <h4 class="card-title">Other Details</h4>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="home_phone"
                                                                    class="control-label col-form-label">Home Phone</label>

                                                                <input type="text" class="form-control"
                                                                    id="home_phone" name="home_phone" placeholder="" />

                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="work_phone"
                                                                    class="control-label col-form-label">Work Phone</label>

                                                                <input type="text" class="form-control"
                                                                    id="work_phone" name="work_phone" placeholder="" />

                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="source_id"
                                                                    class="control-label col-form-label">Lead
                                                                    Source</label>

                                                                <select class="form-select me-sm-2" id="source_id"
                                                                    name="source_id">

                                                                    <option value="">Select Lead Source</option>

                                                                    @foreach ($leadSources as $leadSource)
                                                                        <option value="{{ $leadSource->source_id }}">
                                                                            {{ $leadSource->source_name }}

                                                                        </option>
                                                                    @endforeach

                                                                </select>

                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="company"
                                                                    class="control-label col-form-label">Company</label>

                                                                <input type="text" class="form-control" id="company"
                                                                    name="company" placeholder="" />

                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-4">

                                                            <div class="mb-3">

                                                                <label for="role"
                                                                    class="control-label col-form-label">Role</label>

                                                                <input type="text" class="form-control" id="role"
                                                                    name="role" placeholder="" />

                                                            </div>

                                                        </div>

                                                        <div class="col-sm-12 col-md-4">
                                                            <div class="mb-3">
                                                                <label for="inputcontact"
                                                                    class="control-label bold mb5 col-form-label">Type</label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="user_type" id="exampleRadios1"
                                                                        value="Homeowner">
                                                                    <label class="form-check-label"
                                                                        for="exampleRadios1">Homeowner</label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="user_type" id="exampleRadios2"
                                                                        value="Business">
                                                                    <label class="form-check-label"
                                                                        for="exampleRadios2">Business</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row mt-2">
                                                        <div class="col-sm-12 col-md-8">
                                                            <div class="mb-3">
                                                                <label for="image"
                                                                    class="control-label bold mb5 col-form-label">Image
                                                                    Upload</label>
                                                                <input type="file" class="form-control" id="image"
                                                                    name="image" accept="image/*">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-4">
                                                            <div class="mb-3">
                                                                <label for="tag_id"
                                                                    class="control-label bold mb5 col-form-label">Customer
                                                                    Tags</label>
                                                                <select class="form-control" id="tag_id"
                                                                    name="tag_id[]" multiple="">
                                                                    <option value="298">This is customertags1 11
                                                                    </option>
                                                                    <option value="299">This is customer tags2</option>
                                                                    <option value="302">Enim id exercitation</option>
                                                                    <option value="303">Officiis voluptatem</option>
                                                                    <option value="307">Add tag here 1 11</option>
                                                                    <option value="310">Ea tenetur aut volup</option>
                                                                    <option value="311">Quis aspernatur qui</option>
                                                                    <option value="313">Reprehenderit anim</option>
                                                                    <option value="314">Rerum cupiditate sol</option>
                                                                    <option value="315">Pariatur Et quibusd</option>
                                                                    <option value="316">Dolore doloribus qui</option>
                                                                    <option value="317">Totam omnis optio n</option>
                                                                    <option value="319">Ratione in est quibu</option>
                                                                    <option value="330">Tag Z</option>
                                                                    <option value="334">test99 99 999 99</option>
                                                                    <option value="335">test 88</option>
                                                                    <option value="336">test 5556</option>
                                                                    <option value="338">test 886</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mt-2">
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="mb-3">
                                                                <label
                                                                    class="control-label bold mb5 col-form-label">Customer
                                                                    Notes</label>
                                                                <input type="text" class="form-control"
                                                                    id="customer_notes" name="customer_notes"
                                                                    placeholder="">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>


                                            </div>
                                        </div>

                                        <div class="col-lg-3 d-flex align-items-stretch">
                                            <div class="card w-100">
                                                <div class="card-body border-top px-0">
                                                    SPACE TO SHOW RECORDS

                                                    <div class="customersSuggetions2"
                                                        style="display: none;height: 200px;
                                                            overflow-y: scroll;">
                                                        <div class="card">
                                                            <div class="card-body px-0">
                                                                <div class="">
                                                                    <h5 class="font-weight-medium mb-2">Select Customer
                                                                    </h5>
                                                                    <div class="customers2">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="p-3 border-top">

                                            <div class="action-form">

                                                <div class="mb-3 mb-0 text-center">

                                                    <button type="submit" id="submitBtn"
                                                        class="btn btn-info rounded-pill px-4 waves-effect waves-light">Save</button>



                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>



                        </div>

                    </div>



                </div>
            </div>


            </form>



        </div>
    </div>
    </div>
    </div>
    <!-- Modal -->

    <!-- Modal -->
    <div class="modal fade" id="event" tabindex="-1" aria-labelledby="scroll-long-inner-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable2 modal-dialog modal-xl">
            <form action="{{ url('store/event/') }}" method="POST" id="addEvent">
                <input type="hidden" name="event_technician_id" id="event_technician_id" value="">
                @csrf
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center" style="padding-bottom: 0px;">
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            <h4 class="modal-title" id="myLargeModalLabel" style="margin-left: 28px;">
                                New Event
                            </h4>
                        </div>
                        <button type="submit" class="btn btn-primary">SAVE EVENT</button>

                    </div>
                    <hr color="#6a737c">
                    <div class="modal-body createCustomerData pb-5">
                        <div class="row justify-content-evenly">

                            <div class="col-md-5 p-3 shadow ">
                                <h5 class="d-flex justify-content-between">
                                    <span><i class="fa fa-calendar-alt"></i> Schedule</span> <i class="fa fa-edit"></i>
                                </h5>
                                <hr>
                                <div class="col-sm-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="inputEmail3" class="control-label col-form-label">Start Time</label>

                                    </div>
                                </div>
                                <div class="d-flex gap-5 my-2">
                                    <label for="fdate">From</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-clock feather-sm">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                        </span>
                                        <input type="time" class="form-control" name="start_time" id="start_time"
                                            aria-label="Username" aria-describedby="basic-addon1">
                                    </div>

                                </div>
                                <div class="d-flex gap-5 my-2">
                                    <label for="tdate">To</label>
                                    <input type="date" name="end_date" id="end_date" class="ms-3 form-control">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-clock feather-sm">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                        </span>
                                        <input type="time" class="form-control" name="end_time" id="end_time"
                                            aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5 p-3 shadow ">
                                <h5 class="d-flex justify-content-between">
                                    <span><i class="fa fa-calendar-alt"></i> Event Details</span>
                                </h5>
                                <hr>
                                <div class="my-2">
                                    <input type="text" name="event_name" id="event_name" placeholder="Name"
                                        class="border-0 border-bottom form-control bg-light">
                                </div>
                                <div class="my-2">
                                    <textarea name="event_description" id="event_description" cols="3" rows="3" placeholder="Note"
                                        class="border-0 border-bottom form-control bg-light"></textarea>
                                </div>
                                <div class="my-2">
                                    <input type="text" name="event_location" id="event_location"
                                        placeholder="Location" class="border-0 border-bottom form-control bg-light">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <!-- Modal -->


    </div>
    </div>


@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#datepicker').hide(); // Hide the input field initially
            $('#datepicker-container').datepicker({
                format: 'yyyy-mm-dd', // Specify the format
                autoclose: true, // Close the datepicker when a date is selected
                todayHighlight: true // Highlight today's date
            }).on('changeDate', function(selected) {
                var selectedDate = new Date(selected.date);
                var formattedDate = selectedDate.getFullYear() + '-' + (selectedDate.getMonth() + 1) + '-' +
                    selectedDate.getDate();
                var scheduleLink = 'schedule?date=' + formattedDate; // Direct path
                window.location.href = scheduleLink;
            }); // Initialize the date picker on the container


            $('#searchTechnician').on('input', function() {
                var searchText = $(this).val().toLowerCase().trim();
                $('.technician-item').each(function() {
                    var technicianName = $(this).find('label').text().toLowerCase();

                    if (technicianName.includes(searchText)) {
                        $(this).removeClass('d-none');
                        $(this).addClass('d-flex');
                    } else {
                        $(this).addClass('d-none');
                        $(this).removeClass('d-flex');
                    }
                });
            });

            $(document).on('change', '.technician_check', function() {
                var isChecked = $(this).prop('checked');
                var id = $(this).data('id'); // Retrieve the value of the data-id attribute

                if (isChecked) {
                    // Hide elements with class tech_th that match the id
                    $('.tech_th[data-tech-id="' + id + '"]').show();
                    $('.timeslot_td[data-technician_id="' + id + '"]').show();
                } else {
                    // Show elements with class tech_th that match the id
                    $('.tech_th[data-tech-id="' + id + '"]').hide();
                    $('.timeslot_td[data-technician_id="' + id + '"]').hide();
                }
            });



            $('#leftArrow').on('click', function() {
                $('#filterSchedule').animate({
                    width: 'hide', // Set the width to hide to animate the element to the left
                    opacity: 'hide' // Optionally, you can hide the opacity as well
                }, 'slow');
                $('#rightArrow').show();
            });

            $('#rightArrow').on('click', function() {
                $('#filterSchedule').animate({
                    width: 'show', // Set the width to hide to animate the element to the left
                    opacity: 'show' // Optionally, you can hide the opacity as well
                }, 'slow');
                $('#rightArrow').hide();
            });



        });



        document.addEventListener('DOMContentLoaded', function() {
            const timeDropdowns = document.querySelectorAll('.time-dropdown');

            // Function to generate time options
            function generateTimeOptions(dropdown) {
                const options = [];
                const now = new Date();
                const currentHour = now.getHours();
                const currentMinute = now.getMinutes();
                const halfHour = (currentMinute < 30) ? '00' : '30';

                // Start from the current half-hour interval
                for (let hour = currentHour; hour < 24; hour++) {
                    for (let minute of ['00', '30']) {
                        // Skip if the current time has passed
                        if ((hour === currentHour && minute < halfHour) || hour < currentHour) {
                            continue;
                        }
                        const formattedHour = (hour % 12 === 0) ? 12 : hour % 12; // Convert to 12-hour format
                        const ampm = (hour < 12) ? 'AM' : 'PM'; // Determine AM or PM
                        const formattedMinute = minute;
                        const formattedTime = `${formattedHour}:${formattedMinute} ${ampm}`;
                        options.push(`<option value="${formattedTime}">${formattedTime}</option>`);
                    }
                }

                // Append options to the dropdown
                dropdown.innerHTML = options.join('');
            }

            // Call the function to generate time options for each dropdown
            timeDropdowns.forEach(function(dropdown) {
                generateTimeOptions(dropdown);
            });
        });

        $('.eventSchedule').on('click', function() {
            var id = $(this).attr('data-id');
            console.log(id);
            $('#event_technician_id').val(id);
        });

        $('#addEvent').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var formData = new FormData(this); // 'this' refers to the form DOM element

            // Make an AJAX request to submit the form data
            $.ajax({
                url: $(this).attr('action'), // Get the form action attribute
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    // Handle success response here

                    if (data.success === true) {
                        // If success is true, close the current modal
                        $('#event').modal('hide');
                        // Display a success message using SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Event added successfully.'
                        }).then(function() {
                            // Reset form fields
                            $('#addEvent')[0].reset();
                            location.reload();

                        });
                    }
                },

                error: function(xhr, status, error) {
                    console.error('Error submitting form data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Operation failed. Please try again.' + error
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Use setTimeout to wait a short period after the document is ready
            setTimeout(function() {
                // Trigger click event on the element with class .sidebartoggler
                $('.sidebartoggler').click();
            }); // Adjust the delay time as needed

            $('#myForm').submit(function(e) {
                e.preventDefault(); // Prevent default form submission

                var formData = new FormData(this); // 'this' refers to the form DOM element

                // Make an AJAX request to submit the form data
                $.ajax({
                    url: $(this).attr('action'), // Get the form action attribute
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(data) {
                        // Handle success response here
                        console.log(data.success); // Logging the value of data.success

                        if (data.success === true) {
                            // If success is true, close the current modal
                            $('#newCustomer').modal('hide');
                            // Display a success message using SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Operation completed successfully.'
                            }).then(function() {
                                // Reset form fields
                                $('#myForm')[0].reset();
                                // Open another modal
                                $('#create').modal('show');

                                var id = data.user.id;
                                var name = data.user.name;
                                $('.customer_id').val(id);
                                $('.searchCustomer').val(name);
                                // $('.searchCustomer').prop('disabled', true);
                                $('.customersSuggetions').hide();
                                $('.pendingJobsSuggetions').hide();
                                $('.CustomerAdderss').show();

                                var selectElement = $('.customer_address');
                                selectElement.empty();

                                var option = $('<option>', {
                                    value: '',
                                    text: '-- Select Address --'
                                });

                                selectElement.append(option);

                                $.ajax({
                                    url: "{{ route('customer.details') }}",
                                    data: {
                                        id: id,
                                    },
                                    type: 'GET',
                                    success: function(data) {
                                        if (data) {
                                            $('.customer_number_email')
                                                .text(data.mobile + ' / ' +
                                                    data.email);
                                            $('.show_customer_name').text(
                                                data.name);
                                        }
                                        if (data.address && $.isArray(data
                                                .address)) {
                                            $.each(data.address, function(
                                                index, element) {
                                                var addressString =
                                                    $.ucfirst(
                                                        element
                                                        .address_type
                                                    ) + ':  ' +
                                                    element
                                                    .address_line1 +
                                                    ', ' + element
                                                    .city +
                                                    ', ' + element
                                                    .state_name +
                                                    ', ' + element
                                                    .zipcode;
                                                var option = $(
                                                    '<option>', {
                                                        value: element
                                                            .address_type,
                                                        text: addressString
                                                    });

                                                option.attr(
                                                    'data-city',
                                                    element.city
                                                );

                                                selectElement
                                                    .append(option);
                                            });
                                        }
                                    }
                                });
                            });
                        }
                        if (data.success === false) {
                            // Display an error message using SweetAlert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Operation failed. Please try again.'
                            });
                        }
                    },

                    error: function(xhr, status, error) {
                        console.error('Error submitting form data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Operation failed. Please try again.' + error
                        });
                    }
                });
            });

            $('#selectDates').datepicker({
                format: 'yyyy-mm-dd', // Specify the format
                autoclose: true, // Close the datepicker when a date is selected
                todayHighlight: true // Highlight today's date
            }).on('changeDate', function(selected) {
                var selectedDate = new Date(selected.date);
                var formattedDate = selectedDate.getFullYear() + '-' + (selectedDate.getMonth() + 1) + '-' +
                    selectedDate.getDate();
                var scheduleLink = 'schedule?date=' + formattedDate; // Direct path
                window.location.href = scheduleLink;
            });

            $('#mobile_phone').keyup(function() {
                var phone = $(this).val();
                if (phone.length >= 10) {
                    $('.customersSuggetions2').show();
                } else {
                    $('.customersSuggetions2').hide();
                }

                $.ajax({
                    url: 'get/user/by_number',
                    method: 'GET',
                    data: {
                        phone: phone
                    }, // send the phone number to the server
                    success: function(data) {
                        // Handle the response from the server here
                        console.log(data);
                        $('.rescheduleJobs').empty();

                        $('.customers2').empty();

                        if (data.customers) {
                            $('.customers2').append(data.customers);
                        } else {
                            $('.customers2').html(
                                '<div class="customer_sr_box"><div class="row"><div class="col-md-12" style="text-align: center;"><h6 class="font-weight-medium mb-0">No Data Found</h6></div></div></div>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors here
                        console.error(xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.selectCustomer2', function() {
                $('#newCustomer').modal('hide');
                $('#create').modal('show');
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');
                $('.customer_id').val(id);
                $('.searchCustomer').val(name);
                // $('.searchCustomer').prop('disabled', true);
                $('.customersSuggetions').hide();
                $('.pendingJobsSuggetions').hide();
                $('.CustomerAdderss').show();

                var selectElement = $('.customer_address');
                selectElement.empty();

                var option = $('<option>', {
                    value: '',
                    text: '-- Select Address --'
                });

                selectElement.append(option);

                $.ajax({
                    url: "{{ route('customer.details') }}",
                    data: {
                        id: id,
                    },
                    type: 'GET',
                    success: function(data) {
                        if (data) {
                            $('.customer_number_email').text(data.mobile + ' / ' + data.email);
                            $('.show_customer_name').text(data.name);
                        }
                        if (data.address && $.isArray(data.address)) {
                            $.each(data.address, function(index, element) {
                                var addressString = $.ucfirst(element.address_type) +
                                    ':  ' +
                                    element.address_line1 + ', ' + element.city +
                                    ', ' + element.state_name + ', ' + element
                                    .zipcode;
                                var option = $('<option>', {
                                    value: element.address_type,
                                    text: addressString
                                });

                                option.attr('data-city', element.city);

                                selectElement.append(option);
                            });
                        }
                    }
                });
            });

            $(document).on('click', '#jobdetail', function() {
                setTimeout(function() {
                    var nextAnchor = $('a[href="#previous"]');

                    // Trigger click event on the anchor tag with href="#next" three times
                    for (var i = 0; i < 2; i++) {
                        nextAnchor.trigger('click');
                    }
                }); // Adjust the delay value as needed

            });

            $(document).on('click', '#service_parts', function() {
                setTimeout(function() {
                    var nextAnchor = $('a[href="#previous"]');

                    // Trigger click event on the anchor tag with href="#next" three times

                    nextAnchor.trigger('click');

                }); // Adjust the delay value as needed

            });

            $(document).on('click', '.updateSchedule', function() {

                var id = $(this).attr('data-id');
                var job_id = $(this).attr('data-job-id');
                var time = $(this).attr('data-time');
                var date = $(this).attr('data-date');

                $.ajax({
                    method: 'get',
                    url: "{{ route('schedule.edit') }}",
                    data: {
                        id: id,
                        job_id: job_id,
                        time: time,
                        date: date
                    },
                    beforeSend: function() {
                        $('.editScheduleData').html('Processing Data...');
                    },
                    success: function(data) {

                        $('.editScheduleData').empty();
                        $('.editScheduleData').html(data);
                        // Introduce a delay after the AJAX call to ensure content is fully loaded
                        setTimeout(function() {
                            var nextAnchor = $('a[href="#next"]');
                            console.log(nextAnchor); // Verify if nextAnchor is found

                            // Trigger click event on the anchor tag with href="#next" three times
                            for (var i = 0; i < 3; i++) {
                                nextAnchor.trigger('click');
                            }
                        }); // Adjust the delay value as needed

                        $('.tab-wizard').steps({
                            headerTag: 'h6',
                            bodyTag: 'section',
                            transitionEffect: 'fade',
                            titleTemplate: '<span class="step">#index#</span> #title#',
                            labels: {
                                finish: 'Submit Job',
                            },
                            onStepChanging: function(event, currentIndex, newIndex) {

                                if (newIndex === 0) {
                                    // This condition prevents navigation to step 1
                                    // Adjust the condition as needed based on your logic
                                    if (someConditionIsMet) {
                                        return false; // Prevent navigation to step 1
                                    }
                                }

                                if (currentIndex === 1) {
                                    // Check if all required fields are filled
                                    var isValid = validateStep2Fields();
                                    if (!isValid) {
                                        // Required fields are not filled, prevent navigation
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Please fill in all required fields before proceeding.'
                                        });
                                        return false; // Prevent navigation to the next step
                                    }
                                }
                                if (currentIndex === 2) {
                                    // Check if all required fields are filled
                                    var isValid = validateStep3Fields();
                                    if (!isValid) {
                                        // Required fields are not filled, prevent navigation
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Please fill in warranty fields before proceeding.'
                                        });
                                        return false; // Prevent navigation to the next step
                                    }
                                }
                                // end new chages 

                                if (newIndex < currentIndex) {
                                    return true;
                                }

                                if (newIndex == 3) {
                                    showAllInformation(newIndex);
                                }

                                return true;
                            },
                            onFinished: function(event, currentIndex) {

                                var form = $('#updateScheduleForm')[0];
                                var params = new FormData(form);

                                $.ajax({
                                    url: "{{ route('schedule.update.post') }}",
                                    data: params,
                                    method: 'post',
                                    processData: false,
                                    contentType: false,
                                    headers: {
                                        'X-CSRF-TOKEN': $(
                                                'meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    success: function(data) {



                                        $('a[href="#finish"]:eq(0)')
                                            .text('Submit Job');

                                        if (data.status == true) {

                                            $('.btn-close').trigger(
                                                'click');

                                            Swal.fire({
                                                title: "Success!",
                                                text: "Job Has Been Updated",
                                                icon: "success"
                                            }).then((
                                                result) => {
                                                if (result
                                                    .isConfirmed ||
                                                    result
                                                    .dismiss ===
                                                    Swal
                                                    .DismissReason
                                                    .backdrop
                                                ) {
                                                    location
                                                        .reload();
                                                }
                                            });


                                        } else if (data.status ==
                                            false) {

                                            $('.btn-close').trigger(
                                                'click');

                                            Swal.fire({
                                                icon: "error",
                                                title: "Error",
                                                text: "Something went wrong !",
                                            });

                                        }
                                    }


                                });
                            },
                        });


                    }
                });
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $('.clickPoint').click(function(e) {
                e.stopPropagation();
                var popupDiv = $(this).next('.popupDiv');

                // Hide any previously displayed popupDiv elements
                $('.popupDiv').not(popupDiv).hide();

                // Position and show the clicked popupDiv
                var mouseX = e.clientX;
                var mouseY = e.clientY;

                // Calculate the distance from the clicked point to the edges of the window
                var distanceTop = mouseY;
                var distanceBottom = $(window).height();
                var distanceLeft = mouseX;
                var distanceRight = $(window).width();

                // Calculate the margin values in pixels
                var topMargin = 30;
                var bottomMargin = 30;
                var leftMargin = 20;
                var rightMargin = 20;

                // Calculate the position of the popupDiv based on margins and distances
                var position = {};
                if (distanceTop > distanceBottom && distanceTop > popupDiv.outerHeight() + bottomMargin) {
                    position.top = popupDiv.outerHeight() - bottomMargin;
                } else {
                    position.top = topMargin;
                }

                if (distanceLeft > distanceRight && distanceLeft > popupDiv.outerWidth() + rightMargin) {
                    position.left = popupDiv.outerWidth() - rightMargin;
                } else {
                    position.left = leftMargin;
                }

                // Set the position and show the popupDiv
                popupDiv.css(position).toggle();
            });

            // Hide the popup div when clicking outside of it
            $(document).click(function() {
                $('.popupDiv').hide();
            });
        });
    </script>

    <script>
        var ajaxRequestForCustomer;

        var ajaxRequestForService;

        var ajaxRequestForProduct;

        $.ucfirst = function(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        };

        function showAllInformation(params) {

            var customer_address = $('.customer_address').find(':selected');
            var cityValue = customer_address.data('city');
            var selectedText = customer_address.text();
            $('.show_customer_area').text(cityValue + ' Area');
            $('.show_customer_adderss').text(selectedText);

            $('.show_job_title').text($('.job_title').val());
            $('.show_job_code').text($('.job_code').val());
            $('.show_job_information').text($('.appliances').val() + ' / Model No.: ' + $('.model_number').val() +
                ' / Serial Number: ' + $('.serial_number').val());
            $('.show_job_description').text($('.job_description').val());
            $('.show_job_duration').text('Duration: ' + $('.duration option:selected').text());

            var services = $('.services').find(':selected');
            var services_code = services.data('code');
            var servicesText = services.text();
            var service_cost = $('.service_cost').val();
            var service_discount = $('.service_discount').val();
            var service_tax = $('.service_tax').val();
            var service_total = $('.service_total').val();

            $('.show_service_code_name').text(services_code + ' - ' + servicesText);
            $('.show_warranty').text(+$('.job_type option:selected').text());
            $('.show_service_cost').text('$' + service_cost);
            $('.show_service_discount').text('$' + service_discount);
            $('.show_service_tax').text('$' + service_tax);
            $('.show_service_total').text('$' + service_total);

            var products = $('.products').find(':selected');
            var products_code = products.data('code');
            var productsText = products.text();
            var product_cost = $('.product_cost').val();
            var product_discount = $('.product_discount').val();
            var product_tax = $('.product_tax').val();
            var product_total = $('.product_total').val();

            $('.show_product_code_name').text(products_code + ' - ' + productsText);
            $('.show_product_cost').text('$' + product_cost);
            $('.show_product_discount').text('$' + product_discount);
            $('.show_product_tax').text('$' + product_tax);
            $('.show_product_total').text('$' + product_total);

            var getDiscount = $('.discount').val().trim();
            var getTax = parseInt(service_tax) - parseInt(product_tax);
            var getTotal = $('.total').val().trim();

            $('.show_total_discount').text('$' + getDiscount);
            $('.show_total_tax').text('$' + getTax);
            $('.show_total').text('$' + getTotal);

        }

        //   new changes  
        // on clicking on pendingJobs 

        $(document).on('click', '.pending_jobs2', function() {

            var job_id = $(this).attr('data-id');
            var address = $(this).attr('data-address');

            // Append the address value as a selected option to the select element
            $('.customer_address').append('<option value="' + address + '" selected>' + address + '</option>');


            $.ajax({
                url: "get/pending_jobs",
                data: {
                    job_id: job_id,
                },
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    $('a[href="#next"]').click();

                    $('.job_id').val(data.id);
                    $('.customer_id').val(data.customer_id);
                    $('.job_title').val(data.job_title);
                    $('.job_code').val(data.job_code);
                    $('.address_type').val(data.address_type);

                    var appliances_id = data.appliances_id;

                    // Iterate through each option in the select element
                    $('.appliances option').each(function() {
                        // Check if the value of the current option matches the appliances_id
                        if ($(this).val() == appliances_id) {
                            // Set the selected attribute for the matching option
                            $(this).prop('selected', true);
                        }
                    });

                    var manufaturer_id = data.job_details.manufacturer_id;

                    // Iterate through each option in the select element
                    $('.manufaturer option').each(function() {
                        // Check if the value of the current option matches the manufaturer_id
                        if ($(this).val() == manufaturer_id) {
                            // Set the selected attribute for the matching option
                            $(this).prop('selected', true);
                        }
                    });

                    var priority = data.priority;

                    // Iterate through each option in the select element
                    $('.priority option').each(function() {
                        // Check if the value of the current option matches the manufaturer_id
                        if ($(this).val() == priority) {
                            // Set the selected attribute for the matching option
                            $(this).prop('selected', true);
                        }
                    });

                    $('.model_number').val(data.job_details.model_number);
                    $('.serial_number').val(data.job_details.serial_number);

                    var duration = data.job_assign.duration;

                    // Iterate through each option in the select element
                    $('.duration option').each(function() {
                        // Check if the value of the current option matches the manufaturer_id
                        if ($(this).val() == duration) {
                            // Set the selected attribute for the matching option
                            $(this).prop('selected', true);
                        }
                    });

                    $('.job_description').val(data.description);
                    $('.technician_notes').val(data.job_note.note);
                    $('.tags').val(data.tag_ids);

                    // step 3 

                    var warranty_type = data.warranty_type;

                    // Iterate through each option in the select element
                    $('.job_type option').each(function() {
                        // Check if the value of the current option matches the manufaturer_id
                        if ($(this).val() == warranty_type) {
                            // Set the selected attribute for the matching option
                            $(this).prop('selected', true);
                        }
                    });

                    var service_id = data.jobserviceinfo.service_id;

                    // Iterate through each option in the select element
                    $('.services option').each(function() {
                        // Check if the value of the current option matches the manufaturer_id
                        if ($(this).val() == service_id) {
                            // Set the selected attribute for the matching option
                            $(this).prop('selected', true);
                        }
                    });

                    $('.pre_service_id').val(data.jobserviceinfo.service_id);
                    $('.pre_service_cost').val(data.jobserviceinfo.base_price);
                    $('.service_cost').val(data.jobserviceinfo.base_price);
                    $('.pre_service_discount').val(data.jobserviceinfo.discount);
                    $('.service_discount').val(data.jobserviceinfo.discount);
                    $('.service_tax_text').text('$' + data.jobserviceinfo.tax);
                    $('.service_tax').val(data.jobserviceinfo.tax);
                    $('.service_total_text').text('$' + data.jobserviceinfo.sub_total);
                    $('.service_total').val(data.jobserviceinfo.sub_total);

                    var product_id = data.jobproductinfo.product_id;

                    // Iterate through each option in the select element
                    $('.products option').each(function() {
                        // Check if the value of the current option matches the manufaturer_id
                        if ($(this).val() == product_id) {
                            // Set the selected attribute for the matching option
                            $(this).prop('selected', true);
                        }
                    });

                    $('.pre_product_id').val(data.jobproductinfo.product_id);
                    $('.pre_product_cost').val(data.jobproductinfo.base_price);
                    $('.product_cost').val(data.jobproductinfo.base_price);
                    $('.pre_product_discount').val(data.jobproductinfo.discount);
                    $('.product_discount').val(data.jobproductinfo.discount);
                    $('.product_tax_text').text('$' + data.jobproductinfo.tax);
                    $('.product_tax').val(data.jobproductinfo.tax);
                    $('.product_total_text').text('$' + data.jobproductinfo.sub_total);
                    $('.product_total').val(data.jobproductinfo.sub_total);

                    // for total section  services
                    var service_cost = $('.service_cost').val();

                    var service_discount = $('.service_discount').val();

                    var service_tax = $('.service_tax').val();

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var subTotal = parseInt(getSubTotalVal) - parseInt(service_cost);
                    $('.subtotal').val(subTotal);
                    $('.subtotaltext').text('$' + subTotal);

                    var getDiscount = $('.discount').val().trim();
                    var discount = parseInt(getDiscount) - parseInt(service_discount);
                    $('.discount').val(discount);
                    $('.discounttext').text('$' + discount);

                    var getTotal = $('.total').val().trim();
                    var total = parseInt(getTotal) - parseInt(service_cost) + parseInt(
                        service_discount) - parseInt(
                        service_tax);
                    $('.total').val(total);
                    $('.totaltext').text('$' + total);

                    // product 

                    var product_cost = $('.product_cost').val();

                    var product_discount = $('.product_discount').val();

                    var product_tax = $('.product_tax').val();

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var subTotal = parseInt(getSubTotalVal) - parseInt(product_cost);
                    $('.subtotal').val(subTotal);
                    $('.subtotaltext').text('$' + subTotal);

                    var getDiscount = $('.discount').val().trim();
                    var discount = parseInt(getDiscount) - parseInt(product_discount);
                    $('.discount').val(discount);
                    $('.discounttext').text('$' + discount);

                    var getTotal = $('.total').val().trim();
                    var total = parseInt(getTotal) - parseInt(product_cost) + parseInt(
                        product_discount) - parseInt(
                        product_tax);
                    $('.total').val(total);
                    $('.totaltext').text('$' + total);

                    // customer detail step 4 
                    $('.customer_number_email').text(data.user.mobile + ' / ' + data.user.email);
                    $('.show_customer_name').text(data.user.name);
                    $('.show_customer_area').text(data.city + ' Area');
                    $('.c_address').text(data.address_type + ': ' + data.address + ' ' + data.city +
                        ' ' + data.state + ' ' + data.zipcode);

                }
            });

        });


        $(document).on('click', '.createSchedule', function() {

            var id = $(this).attr('data-id');
            var time = $(this).attr('data-time');
            var date = $(this).attr('data-date');

            $.ajax({
                method: 'get',
                url: "{{ route('schedule.create') }}",
                data: {
                    id: id,
                    time: time,
                    date: date
                },
                beforeSend: function() {
                    $('.createScheduleData').html('Processing Data...');
                },
                success: function(data) {

                    $('.createScheduleData').empty();
                    $('.createScheduleData').html(data);

                    $('.tab-wizard').steps({
                        headerTag: 'h6',
                        bodyTag: 'section',
                        transitionEffect: 'fade',
                        titleTemplate: '<span class="step">#index#</span> #title#',
                        labels: {
                            finish: 'Submit Job',
                        },
                        onStepChanging: function(event, currentIndex, newIndex) {

                            // new changes 
                            if (newIndex > currentIndex) {
                                // Assuming the user address step index is 3 (adjust if necessary)
                                if (currentIndex === 0) {
                                    // Check if user address is selected
                                    var selectedAddress = $('.customer_address').val();
                                    console.log(selectedAddress);
                                    if (!selectedAddress) {
                                        // User address is not selected, prevent navigation
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Please select a user address before proceeding.'
                                        });
                                        return false; // Prevent navigation to the next step
                                    }
                                }
                            }
                            if (currentIndex === 1) {
                                // Check if all required fields are filled
                                var isValid = validateStep2Fields();
                                if (!isValid) {
                                    // Required fields are not filled, prevent navigation
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Please fill in all required fields before proceeding.'
                                    });
                                    return false; // Prevent navigation to the next step
                                }
                            }
                            if (currentIndex === 2) {
                                // Check if all required fields are filled
                                var isValid = validateStep3Fields();
                                if (!isValid) {
                                    // Required fields are not filled, prevent navigation
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Please fill in warranty fields before proceeding.'
                                    });
                                    return false; // Prevent navigation to the next step
                                }
                            }
                            // end new chages 

                            if (newIndex < currentIndex) {
                                return true;
                            }

                            if (newIndex == 3) {
                                showAllInformation(newIndex);
                            }

                            return true;
                        },
                        onFinished: function(event, currentIndex) {

                            var form = $('#createScheduleForm')[0];
                            var params = new FormData(form);

                            $.ajax({
                                url: "{{ route('schedule.create.post') }}",
                                data: params,
                                method: 'post',
                                processData: false,
                                contentType: false,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                        .attr('content')
                                },
                                success: function(data) {



                                    $('a[href="#finish"]:eq(0)').text(
                                        'Submit Job');

                                    if (data.start_date) {

                                        $('.btn-close').trigger(
                                            'click');

                                        Swal.fire({
                                            title: "Success!",
                                            text: "Job Has Been Reschedule",
                                            icon: "success"
                                        }).then((
                                            result) => {
                                            if (result.isConfirmed ||
                                                result.dismiss === Swal
                                                .DismissReason.backdrop
                                            ) {
                                                location
                                                    .reload();
                                            }
                                        });

                                        var elements = $('[data-slot_time="' +
                                            data.start_date +
                                            '"][data-technician_id="' + data
                                            .technician_id + '"]');

                                        elements.empty();

                                        elements.append(data
                                            .html)

                                    } else if (data ==
                                        'false') {

                                        $('.btn-close').trigger(
                                            'click');

                                        Swal.fire({
                                            icon: "error",
                                            title: "Error",
                                            text: "Something went wrong !",
                                        });

                                    } else {

                                        $('.btn-close').trigger(
                                            'click');

                                        Swal.fire({
                                            title: "Success!",
                                            text: "Job Has Been Created",
                                            icon: "success"
                                        }).then((result) => {
                                            // Reload the page after the user clicks the 'OK' button on the success message
                                            if (result.isConfirmed ||
                                                result.isDismissed) {
                                                location
                                                    .reload(); // Reload the current page
                                            }
                                        });

                                        // scheduleButton.empty();
                                        // scheduleButton.html(
                                        //     data.html);
                                    }

                                }
                            });
                        },
                    });

                    $('.searchCustomer').keyup(function() {

                        var name = $(this).val().trim();

                        $('.customersSuggetions').show();

                        $('.pendingJobsSuggetions').show();

                        $('.customers').empty();

                        $('.rescheduleJobs').empty();

                        if (ajaxRequestForCustomer) {
                            ajaxRequestForCustomer.abort();
                        }

                        if (name.length != 0) {

                            ajaxRequestForCustomer = $.ajax({
                                url: "{{ route('autocomplete.customer') }}",
                                data: {
                                    name: name,
                                },
                                beforeSend: function() {

                                    $('.rescheduleJobs').text('Processing...');

                                    $('.customers').text('Processing...');
                                },
                                type: 'GET',
                                success: function(data) {

                                    $('.rescheduleJobs').empty();

                                    $('.customers').empty();

                                    if (data.customers) {
                                        $('.customers').append(data.customers);
                                    } else {
                                        $('.customers').html(
                                            '<div class="customer_sr_box"><div class="row"><div class="col-md-12" style="text-align: center;"><h6 class="font-weight-medium mb-0">No Data Found</h6></div></div></div>'
                                        );
                                    }
                                    if (data.pendingJobs) {
                                        $('.rescheduleJobs').append(data
                                            .pendingJobs);
                                    } else {
                                        $('.rescheduleJobs').html(
                                            '<div class="pending_jobs2"><div class="row"><div class="col-md-12" style="text-align: center;"><h6 class="font-weight-medium mb-0">No Data Found</h6></div></div></div>'
                                        );
                                    }
                                }
                            });

                        } else {

                            $('.customersSuggetions').hide();

                            $('.pendingJobsSuggetions').hide();

                        }

                    });

                }
            });
        });

        $(document).on('click', '.selectCustomer', function() {

            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            $('.customer_id').val(id);
            $('.searchCustomer').val(name);
            // $('.searchCustomer').prop('disabled', true);
            $('.customersSuggetions').hide();
            $('.pendingJobsSuggetions').hide();
            $('.CustomerAdderss').show();

            var selectElement = $('.customer_address');
            selectElement.empty();

            var option = $('<option>', {
                value: '',
                text: '-- Select Address --'
            });

            selectElement.append(option);

            $.ajax({
                url: "{{ route('customer.details') }}",
                data: {
                    id: id,
                },
                type: 'GET',
                success: function(data) {
                    if (data) {
                        $('.customer_number_email').text(data.mobile + ' / ' + data.email);
                        $('.show_customer_name').text(data.name);
                    }
                    if (data.address && $.isArray(data.address)) {
                        $.each(data.address, function(index, element) {
                            var addressString = $.ucfirst(element.address_type) + ':  ' +
                                element.address_line1 + ', ' + element.city +
                                ', ' + element.state_name + ', ' + element
                                .zipcode;
                            var option = $('<option>', {
                                value: element.address_type,
                                text: addressString
                            });

                            option.attr('data-city', element.city);

                            selectElement.append(option);
                        });
                    }
                }
            });
        });

        $(document).on('change', '.services', function(event) {

            event.stopPropagation();

            var id = $(this).val().trim();

            if ($('.pre_service_id').val() != '') {

                var service_cost = $('.service_cost').val();

                var service_discount = $('.service_discount').val();

                var service_tax = $('.service_tax').val();

                var getSubTotalVal = $('.subtotal').val().trim();
                var subTotal = parseInt(getSubTotalVal) - parseInt(service_cost);
                $('.subtotal').val(subTotal);
                $('.subtotaltext').text('$' + subTotal);

                var getDiscount = $('.discount').val().trim();
                var discount = parseInt(getDiscount) - parseInt(service_discount);
                $('.discount').val(discount);
                $('.discounttext').text('$' + discount);

                var getTotal = $('.total').val().trim();
                var total = parseInt(getTotal) - parseInt(service_cost) + parseInt(service_discount) - parseInt(
                    service_tax);
                $('.total').val(total);
                $('.totaltext').text('$' + total);

                $('.service_cost').val(0);

                $('.service_discount').val(0);

                $('.service_tax_text').text('$0');

                $('.service_total_text').text('$0');

                $('.pre_service_id').val('');
            }

            if (id.length != 0) {

                if (ajaxRequestForService) {
                    ajaxRequestForService.abort();
                }

                ajaxRequestForService = $.ajax({
                    url: "{{ route('services.details') }}",
                    data: {
                        id: id,
                    },
                    type: 'GET',
                    success: function(data) {

                        if (data) {

                            $('.pre_service_id').val(id);

                            $('.service_cost').val(data.service_cost);
                            $('.pre_service_cost').val(data.service_cost);

                            $('.service_discount').val(data.service_discount);
                            $('.pre_service_discount').val(data.service_discount);

                            $('.service_tax_text').text('$' + data.service_tax);
                            $('.service_tax').val(data.service_tax);

                            $('.service_total_text').text('$' + data.service_cost);
                            $('.service_total').val(data.service_cost);

                            var getSubTotalVal = $('.subtotal').val().trim();
                            var subTotal = parseInt(getSubTotalVal) + parseInt(data.service_cost);
                            $('.subtotal').val(subTotal);
                            $('.subtotaltext').text('$' + subTotal);

                            var getDiscount = $('.discount').val().trim();
                            var discount = parseInt(getDiscount) + parseInt(data.service_discount);
                            $('.discount').val(discount);
                            $('.discounttext').text('$' + discount);

                            var getTotal = $('.total').val().trim();
                            var total = parseInt(getTotal) + parseInt(data.service_cost) - parseInt(data
                                .service_discount) + parseInt(data.service_tax);
                            $('.total').val(total);
                            $('.totaltext').text('$' + total);

                        }

                    }
                });
            }

        });

        $(document).on('change', '.service_cost', function() {

            var pre_service_cost = parseInt($('.pre_service_cost').val());

            var service_cost = $(this).val();

            if ((/-/.test(service_cost) || /\./.test(service_cost) || isNaN(service_cost))) {
                $(this).val(pre_service_cost);
                return true;
            }

            $('.service_cost').val(service_cost);
            $('.pre_service_cost').val(service_cost);

            $('.service_total_text').text('$' + service_cost);
            $('.service_total').val(service_cost);

            var getSubTotalVal = $('.subtotal').val().trim();
            var subTotal = parseInt(getSubTotalVal) - parseInt(pre_service_cost);
            var currentSubTotal = parseInt(subTotal) + parseInt(service_cost);
            $('.subtotal').val(currentSubTotal);
            $('.subtotaltext').text('$' + currentSubTotal);

            var getTotal = $('.total').val().trim();
            var total = parseInt(getTotal) - parseInt(pre_service_cost);
            var currentTotal = parseInt(total) + parseInt(service_cost);
            $('.total').val(currentTotal);
            $('.totaltext').text('$' + currentTotal);

        });

        $(document).on('change', '.service_discount', function() {

            var service_discount = $(this).val();

            var pre_service_discount = parseInt($('.pre_service_discount').val());

            if ((/-/.test(service_discount) || /\./.test(service_discount) || isNaN(service_discount))) {
                $(this).val(pre_service_discount);
                return true;
            }

            var getDiscount = parseInt($('.discount').val());
            var totalDiscount = parseInt(getDiscount) - parseInt(pre_service_discount);
            var finnalDiscount = parseInt(totalDiscount) + parseInt(service_discount);
            $('.discount').val(finnalDiscount);
            $('.discounttext').text('$' + finnalDiscount);

            var getPreTotal = parseInt($('.total').val());
            var getTotal = parseInt(getPreTotal) + parseInt(pre_service_discount);
            var total = parseInt(getTotal) - parseInt(service_discount);
            $('.total').val(total);
            $('.totaltext').text('$' + total);

            $('.pre_service_discount').val(service_discount);

        });

        $(document).on('change', '.products', function(event) {

            event.stopPropagation();

            var id = $(this).val().trim();

            if ($('.pre_product_id').val() != '') {

                var product_cost = $('.product_cost').val();

                var product_discount = $('.product_discount').val();

                var product_tax = $('.product_tax').val();

                var getSubTotalVal = $('.subtotal').val().trim();
                var subTotal = parseInt(getSubTotalVal) - parseInt(product_cost);
                $('.subtotal').val(subTotal);
                $('.subtotaltext').text('$' + subTotal);

                var getDiscount = $('.discount').val().trim();
                var discount = parseInt(getDiscount) - parseInt(product_discount);
                $('.discount').val(discount);
                $('.discounttext').text('$' + discount);

                var getTotal = $('.total').val().trim();
                var total = parseInt(getTotal) - parseInt(product_cost) + parseInt(product_discount) - parseInt(
                    product_tax);
                $('.total').val(total);
                $('.totaltext').text('$' + total);

                $('.product_cost').val(0);

                $('.product_discount').val(0);

                $('.product_tax_text').text('$0');

                $('.product_total_text').text('$0');

                $('.pre_product_id').val('');
            }

            if (id.length != 0) {

                if (ajaxRequestForProduct) {
                    ajaxRequestForProduct.abort();
                }

                ajaxRequestForProduct = $.ajax({
                    url: "{{ route('product.details') }}",
                    data: {
                        id: id,
                    },
                    type: 'GET',
                    success: function(data) {

                        if (data) {

                            $('.pre_product_id').val(id);

                            $('.product_cost').val(data.base_price);
                            $('.pre_product_cost').val(data.base_price);

                            $('.product_discount').val(data.discount);
                            $('.pre_product_discount').val(data.discount);

                            $('.product_tax_text').text('$' + data.tax);
                            $('.product_tax').val(data.tax);

                            $('.product_total_text').text('$' + data.base_price);
                            $('.product_total').val(data.base_price);

                            var getSubTotalVal = $('.subtotal').val().trim();
                            var subTotal = parseInt(getSubTotalVal) + parseInt(data
                                .base_price);
                            $('.subtotal').val(subTotal);
                            $('.subtotaltext').text('$' + subTotal);

                            var getDiscount = $('.discount').val().trim();
                            var discount = parseInt(getDiscount) + parseInt(data.discount);
                            $('.discount').val(discount);
                            $('.discounttext').text('$' + discount);

                            var getTotal = $('.total').val().trim();
                            var total = parseInt(getTotal) + parseInt(data.base_price) -
                                parseInt(data.discount) + parseInt(data.tax);
                            $('.total').val(total);
                            $('.totaltext').text('$' + total);

                        }

                    }
                });
            }
        });

        $(document).on('change', '.product_cost', function() {

            var pre_product_cost = parseInt($('.pre_product_cost').val());

            var product_cost = $(this).val();

            if ((/-/.test(product_cost) || /\./.test(product_cost) || isNaN(product_cost))) {
                $(this).val(pre_product_cost);
                return true;
            }

            $('.product_cost').val(product_cost);
            $('.pre_product_cost').val(product_cost);

            $('.product_total_text').text('$' + product_cost);
            $('.product_total').val(product_cost);

            var getSubTotalVal = $('.subtotal').val().trim();
            var subTotal = parseInt(getSubTotalVal) - parseInt(pre_product_cost);
            var currentSubTotal = parseInt(subTotal) + parseInt(product_cost);
            $('.subtotal').val(currentSubTotal);
            $('.subtotaltext').text('$' + currentSubTotal);

            var getTotal = $('.total').val().trim();
            var total = parseInt(getTotal) - parseInt(pre_product_cost);
            var currentTotal = parseInt(total) + parseInt(product_cost);
            $('.total').val(currentTotal);
            $('.totaltext').text('$' + currentTotal);


        });

        $(document).on('change', '.product_discount', function() {

            var product_discount = $(this).val();

            var pre_product_discount = parseInt($('.pre_product_discount').val());

            if ((/-/.test(product_discount) || /\./.test(product_discount) || isNaN(product_discount))) {
                $(this).val(pre_product_discount);
                return true;
            }

            var getDiscount = parseInt($('.discount').val());
            var totalDiscount = parseInt(getDiscount) - parseInt(pre_product_discount);
            var finnalDiscount = parseInt(totalDiscount) + parseInt(product_discount);
            $('.discount').val(finnalDiscount);
            $('.discounttext').text('$' + finnalDiscount);

            var getPreTotal = parseInt($('.total').val());
            var getTotal = parseInt(getPreTotal) + parseInt(pre_product_discount);
            var total = parseInt(getTotal) - parseInt(product_discount);
            $('.total').val(total);
            $('.totaltext').text('$' + total);

            $('.pre_product_discount').val(product_discount);

        });

        // new changes 
        function validateStep2Fields() {
            // Validate all fields in Step 2
            var isValid = true;

            // Check if job title is filled
            var jobTitle = $('.job_title').val().trim();
            if (jobTitle === '') {
                isValid = false;
            }

            // Check if ticket number is filled
            var ticketNumber = $('.job_code').val().trim();
            if (ticketNumber === '') {
                isValid = false;
            }

            // Check if appliances is selected
            var appliances = $('.appliances').val();
            if (!appliances) {
                isValid = false;
            }

            // Check if manufacturer is selected
            var manufacturer = $('select[name="manufacturer"]').val();
            if (!manufacturer) {
                isValid = false;
            }

            // Check if priority is selected
            var priority = $('select[name="priority"]').val();
            if (!priority) {
                isValid = false;
            }

            // Check if model number is filled
            var modelNumber = $('.model_number').val().trim();
            if (modelNumber === '') {
                isValid = false;
            }

            // Check if serial number is filled
            var serialNumber = $('.serial_number').val().trim();
            if (serialNumber === '') {
                isValid = false;
            }

            // Check if job description is filled
            var jobDescription = $('.job_description').val().trim();
            if (jobDescription === '') {
                isValid = false;
            }
            // Check if technician notes is filled
            var technicianNotes = $('.technician_notes').val().trim();
            if (technicianNotes === '') {
                isValid = false;
            }


            return isValid;
        }

        function validateStep3Fields() {
            // Validate all fields in Step 3
            var isValid = true;

            // Check if job type is selected
            var jobType = $('.job_type').val();
            if (jobType === '') {
                isValid = false;
            }



            return isValid;
        }
        //  end new changes 
    </script>

    <script>
        $(document).ready(function() {

            $('#state_id').change(function() {

                var stateId = $(this).val();
                console.log(stateId);

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
        $(document).ready(function() {

            $('#anotherstate_id').change(function() {

                var stateId = $(this).val();

                var citySelect = $('#anothercity');

                citySelect.html('<option selected disabled value="">Loading...</option>');



                // Make an AJAX request to fetch the cities based on the selected state

                $.ajax({

                    url: "{{ route('getcitiesanother') }}", // Correct route URL

                    type: 'GET',

                    data: {

                        anotherstate_id: stateId

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

            $('#anothercity').change(function() {

                var cityId = $(this).val();

                var cityName = $(this).find(':selected').text().split(' - ')[
                    0]; // Extract city name from option text

                getZipCodeanother(cityId, cityName); // Call the function to get the zip code

            });

        });



        // Function to get zip code

        function getZipCodeanother(cityId, cityName) {

            $.ajax({

                url: "{{ route('getZipCodeanother') }}", // Adjust route URL accordingly

                type: 'GET',

                data: {

                    anothercity_id: cityId,

                    anothercity_name: cityName

                },

                dataType: 'json',

                success: function(data) {

                    var anotherzip_code = data.anotherzip_code; // Assuming the response contains the zip code

                    $('#anotherzip_code').val(anotherzip_code); // Set the zip code in the input field

                },

                error: function(xhr, status, error) {

                    console.error('Error fetching zip code:', error);

                }

            });

        }
    </script>

    <script>
        function addNewAddress() {

            var addressCardTwo = document.getElementById("adresscardtwo");

            if (addressCardTwo.style.display === "none") {

                addressCardTwo.style.display = "block";

            } else {

                addressCardTwo.style.display = "none";

            }

            var addressCardTwoone = document.getElementById("adresscardtwo1");

            if (addressCardTwoone.style.display === "none") {

                addressCardTwoone.style.display = "block";

            } else {

                addressCardTwoone.style.display = "none";

            }

        }
    </script>
@endsection

@endsection
