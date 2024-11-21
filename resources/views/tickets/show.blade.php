@if (Route::currentRouteName() != 'dash')
    @extends('home')
    @section('content')
    @endif
    <style>
        #frontier_loader {
            display: none;
        }

        .select2 {
            width: 100% !important;
        }

        /* Dim background */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
            /* Dim background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            /* Ensure it's above other elements */
        }

        /* Loader styling */
        .loader {
            width: 50px;
            aspect-ratio: 1;
            display: grid;
            border-radius: 50%;
            background:
                linear-gradient(0deg, rgb(0 0 0 / 50%) 30%, #0000 0 70%, rgb(0 0 0 / 100%) 0) 50%/8% 100%,
                linear-gradient(90deg, rgb(0 0 0 / 25%) 30%, #0000 0 70%, rgb(0 0 0 / 75%) 0) 50%/100% 8%;
            background-repeat: no-repeat;
            animation: l23 1s infinite steps(12);

        }

        .loader::before,
        .loader::after {
            content: "";
            grid-area: 1/1;
            border-radius: 50%;
            background: inherit;
            opacity: 0.915;
            transform: rotate(30deg);
        }

        .loader::after {
            opacity: 0.83;
            transform: rotate(60deg);
        }

        @keyframes l23 {
            100% {
                transform: rotate(1turn)
            }
        }
    </style>
    <div id="frontier_loader">
        <span class="loader-overlay">
            <span class="loader"></span>
        </span>
    </div>

    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-md-10">
                <h4 class="page-title">#{{ $technicians->id ?? null }} - <span
                        class="title_update">{{ $technicians->job_title ?? null }}</span> <span
                        class="mb-1 badge bg-warning">{{ $technicians->status ?? null }} </span>
                    @foreach ($jobFields as $jobField)
                        <span class="mb-1 badge bg-warning">{{ $jobField->field_name }}</span>
                    @endforeach
                </h4>
            </div>
            <div class="col-md-2">
                <a class="job_set_lnk ft14" id="job_set_lnk" href="#."><i class="far fa-sun"></i> Job Settings | </a>
                <a class="job_set_lnk ft14" id="job_flag" href="#."><i class=" far fa-flag"></i> Add Flag</a>
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

        @if (Session::has('error'))
            <div class="alert_wrap">
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            </div>
        @endif

        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- basic table -->

        <div class="row">

            <div class="col-md-4">



                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="">
                                <h5 class="card-title uppercase mt-1 mb-2"><a class="text-dark"
                                        href="{{ url('customers/show/' . $technicians->user->id) }}">{{ $technicians->user->name ?? null }}
                                        @if (!empty($technicians->user->flag_id != 0))
                                            <i class=" far fa-flag"></i>
                                        @endif
                                    </a>
                                </h5>
                                <div>Address</div>
                                <h5 class="todo-desc mb-2 fs-3 font-weight-medium">
                                    @if (isset($technicians->address) && $technicians->address !== '')
                                        {{ $technicians->address }},
                                    @endif

                                    @if (isset($technicians->city) && $technicians->city !== '')
                                        {{ $technicians->city }},
                                    @endif

                                    @if (isset($technicians->state) && $technicians->state !== '')
                                        {{ $technicians->state }},
                                    @endif

                                    @if (isset($technicians->zipcode) && $technicians->zipcode !== '')
                                        {{ $technicians->zipcode }}
                                    @endif
                                </h5>

                                <iframe id="map238" width="100%" height="150" frameborder="0" style="border: 0"
                                    allowfullscreen=""></iframe>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow text-center">
                            <div class="profile-pic mb-3 mt-3">
                                <h5 class="card-title uppercase mt-3 mb-0">Contact Details</h5>
                                @if (!empty($technicians->user->email))
                                    <a
                                        href="mailto:{{ $technicians->user->email ?? '' }}">{{ $technicians->user->email ?? null }}</a><br>
                                @endif
                                @if (!empty($technicians->user->mobile))
                                    {{ $technicians->user->mobile ?? null }}<br />
                                @endif

                            </div>
                            <div>Address</div>
                            <div class="">
                                <iframe id="map" width="100%" height="150" frameborder="0" style="border: 0"
                                    allowfullscreen=""></iframe>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="row open_items">
                                <div class="col-md-1 ">
                                    <i class="fas fas fa-tag "></i>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title uppercase">Customer Tags</h5>
                                </div>
                                <div class="col-md-2 addCustomerTags" style="cursor: pointer;">
                                    <i class="fas fa-plus "></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @foreach ($Sitetagnames as $item)
                                            {{ $item->tag_name ?? null }} ,
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-12 showCustomerTags" style="display:none; ">
                                    <form action="{{ url('add/customer_tags/' . $technicians->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <select class="select2-with-menu-bg form-control  me-sm-2"
                                                name="customer_tags[]" id="menu-bg-multiple1" multiple="multiple"
                                                data-bgcolor="light" data-bgcolor-variation="accent-3"
                                                data-text-color="blue" style="width: 100%" required>
                                                @foreach ($customer_tag as $item)
                                                    <option value="{{ $item->tag_id }}">
                                                        {{ $item->tag_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 text-end">
                                            <button type="submit" class="btn btn-primary rounded">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="row open_items">
                                <div class="col-md-1">
                                    <i class="fas fas fa-tag "></i>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title uppercase">Job Tags</h5>
                                </div>
                                <div class="col-md-1 addJobTags" style="cursor: pointer;">
                                    <i class="fas fa-plus "></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @foreach ($jobtagnames as $item)
                                            {{ $item->field_name }} ,
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-12 showJobTags" style="display:none; ">
                                    <form action="{{ url('add/job_tags/' . $technicians->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <select class="select2-with-menu-bg form-control  me-sm-2 text-uppercase" name="job_tags[]"
                                                id="menu-bg-multiple" multiple="multiple" data-bgcolor="light"
                                                data-bgcolor-variation="accent-3" data-text-color="blue"
                                                style="width: 100%" required>
                                                @foreach ($job_tag as $item)
                                                    <option value="{{ $item->field_id }}">
                                                        {{ $item->field_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 text-end">
                                            <button type="submit" class="btn btn-primary rounded">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="row open_items">
                                <div class="col-md-1">
                                    <i class="fas fa-paperclip"></i>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title uppercase">Attachments</h5>
                                </div>
                                <div class="col-md-1 addAttachment" style="cursor: pointer;">
                                    <i class="fas fa-plus "></i>
                                </div>
                            </div>
                            <div class="row">
                                <form action="{{ url('add/attachment/' . $technicians->id) }}" method="POST"
                                    enctype="multipart/form-data" class="showAttachment" style="display: none;">
                                    @csrf
                                    <input type="file" name="attachment" id="" class="form-control">
                                    <div class="mb-3 text-end">
                                        <button type="submit" class="btn btn-primary rounded mt-2">Add</button>
                                    </div>

                                </form>
                                <div>
                                    @foreach ($files as $item)
                                        <a href="{{ url('public/images/users/' . $item->user_id . '/' . $item->filename) }}"
                                            target="_blank"><img
                                                src="{{ url('public/images/users/' . $item->user_id . '/' . $item->filename) }}"
                                                alt="file" width="100px"
                                                onerror="this.onerror=null; this.src='{{ $defaultImage }}';"></a>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="row open_items">
                                <div class="col-md-1">
                                    <i class="fas fa-bullseye "></i>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title uppercase">Lead Source</h5>
                                </div>
                                <div class="col-md-1 addSource" style="cursor: pointer;">
                                    <i class="fas fa-plus "></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @foreach ($source as $item)
                                            {{ $item->source_name }} ,
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-12 showSource" style="display:none; ">
                                    <form action="{{ url('add/leadsource/' . $technicians->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <select class="select2-with-menu-bg form-control  me-sm-2"
                                                name="lead_source[]" id="menu-bg-multiple2" multiple="multiple"
                                                data-bgcolor="light" data-bgcolor-variation="accent-3"
                                                data-text-color="blue" style="width: 100%" required>
                                                @foreach ($leadsource as $item)
                                                    <option value="{{ $item->source_id }}">
                                                        {{ $item->source_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 text-end">
                                            <button type="submit" class="btn btn-primary rounded">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow text-center">
                            <h5 class="card-title uppercase">Technician Assigned</h5>
                            <div class="profile-pic mb-3 mt-3">
                                @isset($technicians->usertechnician->user_image)
                                    <img src="{{ asset('public/images/Uploads/users/' . $technicians->usertechnician->id . '/' . $technicians->usertechnician->user_image) ?? null }}"
                                        width="150" class="rounded-circle" alt="user"
                                        onerror="this.onerror=null; this.src='{{ $defaultImage }}';" />
                                @else
                                    <img src="{{ $defaultImage }}" width="150" class="rounded-circle" alt="user" />
                                @endisset
                                <h5 class="card-title uppercase mt-3 mb-0">
                                    {{ $technicians->usertechnician->name ?? null }}</h5>
                                <a
                                    href="mailto:{{ $technicians->usertechnician->email ?? '' }}">{{ $technicians->usertechnician->email ?? null }}</a><br><small
                                    class="text-muted">{{ $technicians->usertechnician->mobile ?? null }}<br />{{ $technicians->usertechnician->Locationareaname->area_name ?? null }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow text-center">
                            <h5 class="card-title uppercase">Job Creator</h5>
                            <div class="profile-pic mb-3 mt-3">
                                @isset($technicians->addedby->user_image)
                                    <img src="{{ asset('public/images/Uploads/users/' . $technicians->addedby->id . '/' . $technicians->addedby->user_image) ?? null }}"
                                        width="150" class="rounded-circle" alt="user"
                                        onerror="this.onerror=null; this.src='{{ $defaultImage }}';" />
                                @else
                                    <img src="{{ $defaultImage }}" width="150" class="rounded-circle" alt="user" />
                                @endisset
                                <h5 class="card-title mt-3 mb-0">{{ $technicians->addedby->name ?? null }}</h5>
                                <a
                                    href="mailto:{{ $technicians->addedby->email ?? '' }}">{{ $technicians->addedby->email ?? null }}</a><br><small
                                    class="text-muted">{{ $technicians->addedby->mobile ?? null }}<br>Frontier Support
                                    Staff</small>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="col-md-8">



                <div class="mb-4 flwrap" id="open_job_settings">
                    <div class="card">
                        <form action="{{ route('updateJobSettings', ['id' => $technicians->id]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="job_id" id="setting_job_id" value="{{ $technicians->id }}">
                            <div class="card-body card-border shadow">
                                <div class="row">
                                    <div class="col-md-8 d-flex">
                                        <h5 class="card-title uppercase">Job Settings</h5>
                                        <button type="submit" class="ms-3 btn btn-primary btn-xs">Save</button>
                                    </div>
                                    <div class="col-8">
                                        <div class="d-flex align-items-center justify-content-between py-3">
                                            <div>
                                                <h5 class="fs-4 fw-semibold mb-0">Job Confirmed</h5>
                                                <p class="mb-0">Whether job confirmed from customer or not</p>
                                            </div>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="job_confirmed" name="job_confirmed"
                                                    @if ($technicians->is_confirmed == 'yes') checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8">
                                        <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                            <div>
                                                <h5 class="fs-4 fw-semibold mb-0">Job Publish </h5>
                                                <p class="mb-0">Publish the job on Schedule</p>
                                            </div>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="is_published" name="is_published "
                                                    @if ($technicians->is_published == 'yes') checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8">
                                        <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                            <div>
                                                <h5 class="fs-4 fw-semibold mb-0">Show on Schedule</h5>
                                                <p class="mb-0">Display the job on Schedule</p>
                                            </div>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="job_schedule" name="job_schedule"
                                                    @if ($checkSchedule->show_on_schedule == 'yes') checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8">
                                        <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                            <div>
                                                <h5 class="fs-4 fw-semibold mb-0">Job Closed</h5>
                                                <p class="mb-0">If job is complete and verified. Mark it as close. </p>
                                            </div>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="job_closed" name="job_closed"
                                                    @if ($technicians->status == 'closed') checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mb-4 flwrap" id="open_job_flag_settings">
                    <div class="card">
                        <form id="flagCustomerForm" method="POST">
                            @csrf
                            <input type="hidden" name="technician_id" id="technician_id"
                                value="{{ $technicians->technician_id }}">
                            <input type="hidden" name="customer_id" id="customer_id"
                                value="{{ $technicians->customer_id }}">
                            <input type="hidden" name="job_id" id="job_id" value="{{ $technicians->id }}">
                            <div class="card-body card-border shadow">
                                <div class="row">
                                    <div class="col-md-12 d-flex">
                                        <h5 class="card-title uppercase"><i class="far fa-flag"></i> Flag Customer</h5>
                                    </div>
                                    <div class="col-12 pt-2">
                                        @if (!empty($notes))
                                            <div class="text-info">Flag added on {{ $notes->created_at->format('Y-m-d') }}
                                                by {{ $notes->name }}</div>
                                        @endif
                                        <label for="flag" class="pt-2">Flag</label>
                                        <select name="flag_id" id="flag" class="form-control select2" required>
                                            <option value="">-- Select Flag --</option>
                                            @foreach ($flag as $value)
                                                <option value="{{ $value->flag_id }}"> <i class="far fa-flag"
                                                        style="color: {{ $value->flag_name }};"></i>
                                                    {{ $value->flag_desc }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 pt-2 ">
                                        <label for="flag_reason">Add Reason</label>
                                        <textarea class="form-control" name="flag_reason" id="flag_reason" cols="10" rows="3" required></textarea>
                                    </div>
                                    <div class="col-12 pt-2">
                                        <button type="submit" class="btn btn-success">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mb-4 flwrap">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="brwrap">
                                <div class="flborder">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="flowchart">

                                        <div class="icwrap">
                                            <div class="ictop bg-info text-white">
                                                <i class="ri-calendar-event-line"></i>
                                            </div>
                                            <span class="cht">Schedule</span>
                                        </div>
                                        <div class="dtwrap">
                                            <div class="date">
                                                @php
                                                    use Carbon\Carbon;

                                                    // Parse the original time if it exists
                                                    $time_schedule = $jobTimings['time_schedule']
                                                        ? Carbon::parse($jobTimings['time_schedule'])
                                                        : null;
                                                    $interval = session('time_interval'); // Retrieve the time interval from the session

                                                    if ($time_schedule && $interval) {
                                                        // Add the interval to the parsed time
                                                        $time_schedule->addHours($interval);
                                                    }
                                                @endphp
                                                <span class="enr_date">
                                                    @if ($time_schedule)
                                                        {{ $time_schedule->format('M d Y g:i a') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="flowchart">

                                        <div class="icwrap">
                                            <div
                                                class="ictop @if ($jobTimings['time_omw'] !== null) bg-info text-white @else icblank @endif">
                                                <i class="ri-truck-line"></i>
                                            </div>
                                            <span class="cht"> Enroute</span>
                                        </div>
                                        <div class="dtwrap">
                                            <div class="date">
                                                @php

                                                    // Parse the original time if it exists
                                                    $newTimeOmw = $jobTimings['time_omw']
                                                        ? Carbon::parse($jobTimings['time_omw'])
                                                        : null;

                                                    if ($newTimeOmw && $interval) {
                                                        // Add the interval to the parsed time
                                                        $newTimeOmw->addHours($interval);
                                                    }
                                                @endphp
                                                @if ($newTimeOmw)
                                                    {{ $newTimeOmw->format('M d Y g:i a') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="flowchart">

                                        <div class="icwrap">
                                            <div
                                                class="ictop @if ($jobTimings['time_start'] !== null) bg-info text-white @else icblank @endif">
                                                <i class="ri-play-line"></i>
                                            </div>
                                            <span class="cht">Start</span>
                                        </div>
                                        <div class="dtwrap">
                                            <div class="date">
                                                @php
                                                    // Parse the original time if it exists
                                                    $time_start = $jobTimings['time_start']
                                                        ? Carbon::parse($jobTimings['time_start'])
                                                        : null;

                                                    if ($time_start && $interval) {
                                                        // Add the interval to the parsed time
                                                        $time_start->addHours($interval);
                                                    }
                                                @endphp
                                                @if ($time_start)
                                                    {{ $time_start->format('M d Y g:i a') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="flowchart">

                                        <div class="icwrap">
                                            <div
                                                class="ictop @if ($jobTimings['time_finish'] !== null) bg-info text-white @else icblank @endif">
                                                <i class="ri-stop-circle-line"></i>
                                            </div>
                                            <span class="cht">Finish</span>
                                        </div>
                                        <div class="dtwrap">
                                            <div class="date">
                                                @php
                                                    // Parse the original time if it exists
                                                    $time_finish = $jobTimings['time_finish']
                                                        ? Carbon::parse($jobTimings['time_finish'])
                                                        : null;

                                                    if ($time_finish && $interval) {
                                                        // Add the interval to the parsed time
                                                        $time_finish->addHours($interval);
                                                    }
                                                @endphp
                                                @if ($time_finish)
                                                    {{ $time_finish->format('M d Y g:i a') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="flowchart">
                                        <!--<button class="bl"></button>-->
                                        <div class="icwrap">
                                            <div
                                                class="ictop @if ($jobTimings['time_invoice'] !== null) bg-info text-white @else icblank @endif">
                                                <i class="ri-bill-line"></i>
                                            </div>
                                            <span class="cht">Invoice</span>
                                        </div>
                                        <div class="dtwrap">
                                            <div class="date">
                                                @php
                                                    // Parse the original time if it exists
                                                    $time_invoice = $jobTimings['time_invoice']
                                                        ? Carbon::parse($jobTimings['time_invoice'])
                                                        : null;

                                                    if ($time_invoice && $interval) {
                                                        // Add the interval to the parsed time
                                                        $time_invoice->addHours($interval);
                                                    }
                                                @endphp
                                                @if ($time_invoice)
                                                    {{ $time_invoice->format('M d Y g:i a') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="flowchart">
                                        <!--<button class="bl"></button>-->
                                        <div class="icwrap">
                                            <div
                                                class="ictop @if ($jobTimings['time_payment'] !== null) bg-info text-white @else icblank @endif">
                                                <i class="ri-currency-line"></i>
                                            </div>
                                            <span class="cht">Pay</span>
                                        </div>
                                        <div class="dtwrap">
                                            <div class="date">
                                                @php
                                                    // Parse the original time if it exists
                                                    $time_payment = $jobTimings['time_payment']
                                                        ? Carbon::parse($jobTimings['time_payment'])
                                                        : null;

                                                    if ($time_payment && $interval) {
                                                        // Add the interval to the parsed time
                                                        $time_payment->addHours($interval);
                                                    }
                                                @endphp
                                                @if ($time_payment)
                                                    {{ $time_payment->format('M d Y g:i a') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="card-title uppercase">Field Tech Status</h5>
                                </div>
                                @if (!empty($technicians->JobTechEvent))
                                    @if ($technicians->JobTechEvent->tech_completed == 'yes')
                                        <div class="col-md-4 bold text-success">JOB IS COMPLETE</div>
                                    @else
                                        <div class="col-md-4 bold text-danger">JOB IS PENDING</div>
                                    @endif
                                @else
                                    <div class="col-md-4 bold text-danger">JOB IS PENDING</div>
                                @endif
                            </div>

                            <div class="table-responsive">
                                <table class="table customize-table mb-0 v-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-bottom border-top">Technician</th>
                                            <th class="border-bottom border-top">Travel time</th>
                                            <th class="border-bottom border-top">Time on job</th>
                                            <th class="border-bottom border-top">Total Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @isset($technicians->usertechnician->user_image)
                                                        <img src="{{ asset('public/images/Uploads/users/' . $technicians->usertechnician->id . '/' . $technicians->usertechnician->user_image) }}"
                                                            class="rounded-circle" width="40"
                                                            onerror="this.onerror=null; this.src='{{ $defaultImage }}';">
                                                    @else
                                                        <img src="{{ $defaultImage }}" class="rounded-circle"
                                                            width="40">
                                                    @endisset
                                                    <span
                                                        class="ms-3 fw-normal">{{ $technicians->usertechnician->name ?? null }}</span>
                                                </div>
                                            </td>
                                            <td>&nbsp;{{ $technicians->JobAssign->driving_hours ?? '0.00' }} Hrs (Approx)
                                            </td>
                                            <td>&nbsp;{{ number_format(($technicians->JobAssign->duration ?? 0) / 60, 2) }}
                                                Hrs (Approx)
                                            </td>


                                            @php
                                                // Calculate the sum of driving_hours and duration in minutes
                                                $drivingHours = $technicians->JobAssign->driving_hours ?? 0;
                                                $duration = $technicians->JobAssign->duration ?? 0;

                                                if ($drivingHours !== null && $duration !== null) {
                                                    $totalMinutes = $drivingHours * 60 + $duration;

                                                    // Calculate hours and minutes
                                                    $hours = floor($totalMinutes / 60);
                                                    $minutes = $totalMinutes % 60;

                                                    $totalTime =
                                                        sprintf('%02d', $hours) . ':' . sprintf('%02d', $minutes);
                                                } else {
                                                    $totalTime = '0';
                                                }
                                            @endphp

                                            <td>{{ $totalTime }} Hrs (Approx)</td>

                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <?php
                                            
                                            $jobTechEvent = $technicians->JobTechEvent ?? null;
                                            
                                            // Function to convert time in HH:MM:SS format to decimal hours
                                            if (!function_exists('convertToDecimalHours')) {
                                                function convertToDecimalHours($time)
                                                {
                                                    if ($time == '00:00:00' || empty($time)) {
                                                        return '0.00';
                                                    }
                                            
                                                    [$hours, $minutes, $seconds] = explode(':', $time);
                                                    $decimalHours = $hours + $minutes / 60 + $seconds / 3600;
                                            
                                                    return number_format($decimalHours, 2);
                                                }
                                            }
                                            ?>

                                            <?php if ($jobTechEvent): ?>
                                            <td><?php echo e(convertToDecimalHours($jobTechEvent->enroute_time)); ?> Hrs</td>
                                            <td><?php echo e(convertToDecimalHours($jobTechEvent->job_time)); ?> Hrs</td>
                                            <td><?php echo e(convertToDecimalHours($jobTechEvent->total_time_on_job)); ?> Hrs</td>
                                            <?php else: ?>
                                            <td>0.00 Hrs</td>
                                            <td>0.00 Hrs</td>
                                            <td>0.00 Hrs</td>
                                            <?php endif; ?>


                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 mb-2"><strong>Repair Complete:</strong>
                                {{ $jobTechEvent->is_repair_complete ?? null }}
                            </div>
                            <div class="mb-2"><strong>Additional Details:</strong>
                                {{ $jobTechEvent->additional_details ?? null }}</div>
                            <div class="mb-2">
                                <strong>Customer Signature:</strong>
                                @if ($technicians->JobTechEvent && !empty($technicians->JobTechEvent->customer_signature))
                                    <div>
                                        <a href="{{ url('public/images/users/' . $technicians->user->id . '/' . $technicians->JobTechEvent->customer_signature) }}"
                                            target="_blank">
                                            <img src="{{ url('public/images/users/' . $technicians->user->id . '/' . $technicians->JobTechEvent->customer_signature) }}"
                                                alt="Customer Signature" width="100px"
                                                onerror="this.onerror=null; this.src='{{ $defaultImage }}';">
                                        </a>
                                    </div>
                                @else
                                    <div>No signature available.</div>
                                @endif
                            </div>

                            @if (
                                !empty($technicians->JobTechEvent) &&
                                    $technicians->JobTechEvent->tech_completed == 'yes' &&
                                    $technicians->status == 'open')
                                <form method="POST" action="{{ route('update_approval_for_pending_job') }}">
                                    @csrf

                                    <h5 class="mt-4 mb-0 card-title uppercase text-success">Admin's Remark</h5>
                                    <input type="hidden" name="job_id" value="{{ $technicians->id ?? '' }}" />
                                    <div class="row mb-2">
                                        <div class="col-sm-12 col-md-12">
                                            <label for="comment"
                                                class="control-label bold mb5 col-form-label required-field">Job Complete
                                                Comment </label>
                                            <textarea class="form-control" id="comment" name="comment" rows="2"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-12 col-md-12">
                                            <input type="checkbox" id="approve_pending_job" name="approve_pending_job"
                                                class="checkbox">
                                            <label for="approve_pending_job" class="checkbox-label">I checked and confirm
                                                that job is complete</label>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 col-md-4">
                                            <button type="submit" class="btn btn-primary"
                                                id="approve_pending_job_update"
                                                onclick="return confirmAndCheck()">Update</button>
                                        </div>
                                    </div>
                                </form>
                            @endif


                            @if (!empty($technicians->JobTechEvent) && $technicians->status == 'closed')
                                <h5 class="mt-4 mb-0 card-title uppercase text-success">Admin's Remark</h5>

                                <div class="row mb-2">

                                    <div class="mb-2">
                                        <strong>Job Complete Comment:</strong>
                                        {{ $technicians->JobTechEvent->closed_job_comment ?? '' }}
                                        <br />
                                        <span class="text-info ft12">By: {{ $technicians->close->name ?? '' }} (
                                            @if (isset($technicians->closed_date))
                                                {{ \Illuminate\Support\Carbon::parse($technicians->closed_date)->format('D n/j/y g:ia') }}
                                            @else
                                                {{ '' }}
                                            @endif
                                            )
                                        </span>
                                    </div>
                                </div>
                            @endif


                        </div>
                    </div>
                </div>

                <div class="mb-4 update-job" id="editdetails">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <form id="editJobForm"
                                action="{{ route('schedule.update_view_job', ['id' => $technicians->id]) }}"
                                method="POST">
                                @csrf
                                <div class="d-flex mb-3">
                                    <h4>Edit Job</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-sticky-note"></i>
                                                Job Title </h6>
                                            <div class="form-group">
                                                <input type="text" name="job_title" id="job_title"
                                                    class="form-control job_title" placeholder="Add Job Title Here"
                                                    aria-label="" value="{{ $technicians->job_title ?? null }}"
                                                    aria-describedby="basic-addon1" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa-user"></i> Priority
                                            </h6>
                                            <div class="form-group">
                                                <select class="form-control priority" id="exampleFormControlSelect1"
                                                    name="priority">
                                                    <option value="high"
                                                        {{ isset($technicians->priority) && $technicians->priority == 'high' ? 'selected' : '' }}>
                                                        High</option>
                                                    <option
                                                        value="low"{{ isset($technicians->priority) && $technicians->priority == 'low' ? 'selected' : '' }}>
                                                        Low</option>
                                                    <option
                                                        value="medium"{{ isset($technicians->priority) && $technicians->priority == 'medium' ? 'selected' : '' }}>
                                                        Medium</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-flex gap-2">
                                        <div>
                                            <label for="newdatetime">Date </label>
                                            <div>
                                                <input type="date" class="form-control" id="newdate" name="date"
                                                    value="{{ $date }}">
                                            </div>
                                        </div>
                                        <div>
                                            <label for="newdatetime">From </label>

                                            <select class="form-control " id="from_time" name="from_time">
                                                @foreach ($timeIntervals as $intervals)
                                                    @php
                                                        $timeDisplay = date('h:i A', strtotime($intervals));
                                                        $selected =
                                                            isset($fromDate) && $fromDate == $timeDisplay
                                                                ? 'selected'
                                                                : '';
                                                    @endphp
                                                    <option value="{{ $intervals }}" {{ $selected }}>
                                                        {{ $timeDisplay }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div>
                                            <label for="newdatetime">To </label>

                                            <select class="form-control" id="to_time" name="to_time">
                                                @foreach ($timeIntervals as $intervals)
                                                    @php
                                                        $timeDisplay = date('h:i A', strtotime($intervals));
                                                        $selected =
                                                            isset($toDate) && $toDate == $timeDisplay ? 'selected' : '';
                                                    @endphp
                                                    <option value="{{ $intervals }}" {{ $selected }}>
                                                        {{ $timeDisplay }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>



                                    <div class="col-md-6">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-check-square"></i>
                                                Warranty </h6>
                                            <div class="form-group d-flex gap-2">
                                                <select class="form-control job_type" id="check_job_type"
                                                    name="job_type">
                                                    <option value="">Please select</option>
                                                    <option value="in_warranty"
                                                        {{ isset($technicians->warranty_type) && $technicians->warranty_type == 'in_warranty' ? 'selected' : '' }}>
                                                        In Warranty</option>
                                                    <option value="out_warranty"
                                                        {{ isset($technicians->warranty_type) && $technicians->warranty_type == 'out_warranty' ? 'selected' : '' }}>
                                                        Out of Warranty</option>
                                                </select>
                                                @if (isset($technicians->warranty_type) && $technicians->warranty_type == 'in_warranty')
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter Warranty Number" name="warranty_ticket"
                                                        id="warranty_ticket"
                                                        value="{{ $technicians->warranty_ticket ?? null }}">
                                                @endif

                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i
                                                    class="fas fa fa-pencil-square-o"></i>
                                                Job Description
                                            </h6>
                                            <div class="form-group">
                                                <textarea class="form-control job_description" rows="2" placeholder="Add Description  Here..."
                                                    name="job_description" required>{{ $technicians->description ?? null }}</textarea>
                                                <small id="textHelp" class="form-text text-muted">All all details of the
                                                    job goes here</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="card-title required-field"><i class="fas fa fa-television"></i>Select
                                            Existing Appliances </h6>
                                        <div class="form-group">
                                            <select class="form-control appl_id exist_appl_id" id="appl_id"
                                                name="exist_appl_id">
                                                <option value=""> -- Select existig appliances -- </option>
                                                @foreach ($job_appliance as $appliance)
                                                    <option value="{{ $appliance->appliance_id ?? null }}"
                                                        data-appName="{{ $appliance->appliance->appliance_name ?? null }}"
                                                        data-manuName="{{ $appliance->manufacturer->manufacturer_name ?? null }}"
                                                        data-model="{{ $appliance->model_number ?? null }}"
                                                        data-serial="{{ $appliance->serial_number ?? null }}"
                                                        {{ isset($technicians->JobAppliances) && $technicians->JobAppliances->appliance_id == $appliance->appliance_id ? 'selected' : '' }}>
                                                        {{ $appliance->appliance->appliance_name ?? null }} /
                                                        {{ $appliance->manufacturer->manufacturer_name ?? null }} /
                                                        {{ $appliance->model_number ?? null }} /
                                                        {{ $appliance->serial_number ?? null }}
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="text-info"><a class="pointer" id="add_new_appl">Add New</a></div>
                                    </div>
                                </div>

                                <div class="row" style="display: none;" id="show_new_appl">
                                    <div class="col-md-3">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-television"></i>
                                                Appliances </h6>
                                            <div class="form-group">
                                                <select class="form-control appliances" id="appliances"
                                                    name="appliances">
                                                    <option value="">-- Select Appliances -- </option>
                                                    @if (isset($appliances) && !empty($appliances))
                                                        @foreach ($appliances as $value)
                                                            <option value="{{ $value->appliance_type_id }}"
                                                                data-name="{{ $value->appliance_name }}"
                                                                {{ isset($technicians->JobAppliances) && $technicians->JobAppliances->Appliances->appliance_type_id == $value->appliance_type_id ? 'selected' : '' }}>
                                                                {{ $value->appliance_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <small class="text-success" id="resp_text"></small>
                                                <div class="text-primary" style="cursor: pointer;" id="add_appliance">+
                                                    Add New</div>
                                                <div class="my-2 appliancefield" style="display:none;">
                                                    <div class="d-flex ">
                                                        <input type="text" name="new_appliance"
                                                            class="form-control rounded-0 " id="new_appliance"
                                                            placeholder="Add Appliances Here">
                                                        <button type="button" class="btn btn-cyan p-0 px-2 rounded-0"
                                                            style="cursor: pointer;" id="addAppl">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-industry"></i>
                                                Manufacturer </h6>
                                            <div class="form-group">
                                                <select class="form-control manufacturer" id="manufacturer"
                                                    name="manufacturer">
                                                    <option value="">-- Select Manufacturer -- </option>
                                                    @if (isset($manufacturers) && !empty($manufacturers))
                                                        @foreach ($manufacturers as $value)
                                                            <option value="{{ $value->id }}"
                                                                data-name="{{ $value->manufacturer_name }}"
                                                                {{ isset($technicians->JobAppliances) && $technicians->JobAppliances->Appliances->manufacturer_id == $value->id ? 'selected' : '' }}>
                                                                {{ $value->manufacturer_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <small class="text-success" id="resp_texts"></small>
                                                <div class="text-primary" style="cursor: pointer;" id="add_manufaturer">+
                                                    Add New</div>
                                                <div class="my-2 manufaturerfield" style="display:none;">
                                                    <div class="d-flex ">
                                                        <input type="text" name="new_manufacturer"
                                                            class="form-control rounded-0 " id="new_manufacturer"
                                                            placeholder="Add Manufaturer Here">
                                                        <button type="button" class="btn btn-cyan p-0 px-2 rounded-0"
                                                            style="cursor: pointer;" id="addManu">Add</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field"><i class="fas fa fa-hashtag"></i> Model
                                                Number </h6>
                                            <div class="form-group">
                                                <input type="text" class="form-control model_number"
                                                    placeholder="Model Number here" aria-label=""
                                                    aria-describedby="basic-addon1" name="model_number"
                                                    value="{{ $technicians->JobAppliances->Appliances->model_number }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mt-0 mb-3">
                                            <h6 class="card-title required-field required-field"><i
                                                    class="fas fa fa-hashtag"></i>
                                                Serial Number </h6>
                                            <div class="form-group">
                                                <input type="text" class="form-control serial_number"
                                                    placeholder="Serial Number here" aria-label=""
                                                    aria-describedby="basic-addon1" name="serial_number"
                                                    id="check_serial_number"
                                                    value="{{ $technicians->JobAppliances->Appliances->serial_number }}">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-3 w-100" id="serial_number_detail"></div>
                                </div>
                                <button id="save-close-btn" type="submit" class="ms-3 btn btn-primary btn-xs float-end"
                                    data-action="save-close">Save & Close</button>

                                <button type="submit" class="ms-3 btn btn-primary btn-xs float-end"
                                    data-action="save">Save</button>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="mb-4" id="job-details-container">
                    <div class="card">
                        <div class="card-body card-border shadow">

                            <div class="row">
                                <div class="col-md-7">
                                    <div class="mb-2">
                                        <h5 class="card-title uppercase ">#{{ $technicians->id ?? null }} -
                                            <span class="title_update"> {{ $technicians->job_title ?? null }} </span>
                                            <span class="mb-1 badge bg-info pointer edit-job"> Edit </span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-2">


                                        @php

                                            // Parse the original time if it exists
                                            $startDateTime = $technicians->schedule->start_date_time
                                                ? Carbon::parse($technicians->schedule->start_date_time)
                                                : null;

                                            if ($startDateTime && $interval) {
                                                // Add the interval to the parsed time
                                                $startDateTime->addHours($interval);
                                            }
                                        @endphp
                                        <h5 class="card-title uppercase fulldate_update"><i class="fa fa-calendar"
                                                aria-hidden="true"></i>

                                            @if ($startDateTime)
                                                {{ $startDateTime->format('jS F Y, h:i A') }}
                                            @endif

                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <p class="description_update">{{ $technicians->description ?? null }}</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>Duration:</strong>
                                        @if ($technicians->jobassignname->duration ?? null)
                                            <?php
                                            $durationInMinutes = $technicians->jobassignname->duration ?? null;
                                            $durationInHours = $durationInMinutes / 60; // Convert minutes to hours
                                            ?>
                                        @endif
                                        <span class="duration_update">{{ $durationInHours ?? null }} Hours</span>
                                    </div>
                                    <div class="mb-2"><strong>Priority:</strong> <span
                                            class="priority_update">{{ $technicians->priority ?? null }}</span>
                                    </div>
                                    <div class="mb-2"><strong>Date:
                                        </strong> <span
                                            class="date_update">{{ \Carbon\Carbon::parse($technicians->schedule->start_date_time ?? null)->format('jS F Y') }}</span>
                                    </div>
                                    <div class="mb-2"><strong>From:
                                        </strong>
                                        <span class="from_update">
                                            {{ $modifyDateTime($technicians->schedule->start_date_time ?? null, $interval, 'add', 'h:i: A') }}</span>
                                    </div>
                                    <div class="mb-2"><strong>To:
                                        </strong>
                                        <span
                                            class="to_update">{{ $modifyDateTime($technicians->schedule->end_date_time ?? null, $interval, 'add', 'h:i: A') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>Warranty Type: </strong>
                                        <span class="warranty_update"> {{ $technicians->warranty_type ?? null }}</span>
                                    </div>
                                    @if ($technicians->warranty_type === 'in_warranty')
                                        <div class="mb-2"><strong>Warranty Number: </strong>
                                            <span class="warranty_ticket_update">
                                                {{ $technicians->warranty_ticket ?? null }}</span>
                                        </div>
                                    @endif

                                    <div class="mb-2"><strong>Appliances: </strong>
                                        <span
                                            class="appliance_update">{{ $technicians->JobAppliances->Appliances->appliance->appliance_name ?? null }}</span>
                                    </div>
                                    <div class="mb-2"><strong>Manufacturer:</strong>
                                        <span
                                            class="manufacturer_update">{{ $technicians->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}</span>
                                    </div>
                                    <div class="mb-2"><strong>Model Number:
                                        </strong><span
                                            class="model_update">{{ $technicians->JobAppliances->Appliances->model_number ?? null }}</span>
                                    </div>
                                    <div class="mb-2"><strong>Serial Number: </strong>
                                        <span
                                            class="serial_update">{{ $technicians->JobAppliances->Appliances->serial_number ?? null }}</span>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>



                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <h5 class="card-title uppercase">Services & Parts (Line Items)</h5>
                            <div class="table-responsive">
                                <table class="table customize-table mb-0 v-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-bottom border-top">Line Item</th>
                                            <th class="border-bottom border-top">Unit Price</th>
                                            <th class="border-bottom border-top">Discount</th>
                                            <th class="border-bottom border-top">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($jobproduct as $product)
                                            <tr>
                                                <td>{{ $product->product->product_name ?? null }}</td>
                                                <td>${{ $product->base_price ?? null }}</td>
                                                <td>${{ $product->discount ?? null }}</td>
                                                <td>${{ $product->sub_total ?? null }}</td>
                                            </tr>
                                        @endforeach

                                        @foreach ($jobservice as $service)
                                            <tr>
                                                <td>{{ $service->service->service_name ?? null }} </td>
                                                <td>${{ $service->base_price ?? null }}</td>
                                                <td>${{ $service->discount ?? null }}</td>
                                                <td>${{ $service->sub_total ?? null }}</td>
                                            </tr>
                                        @endforeach



                                    </tbody>
                                </table>
                                <div class="row mb-2 justify-content-end" style="border-top: 1px solid #343434;">
                                    <div class="col-md-5 mt-2 text-right" style="text-align: right;padding-right: 36px;">

                                        <div class="price_h5">Subtotal:
                                            <span>${{ $technicians->subtotal ?? null }}</span>
                                        </div>
                                        <div class="price_h5">Discount:
                                            <span>${{ $technicians->discount ?? null }}</span>
                                        </div>
                                        <div class="price_h5">Tax ({{ $technicians->tax_details ?? null }}):
                                            <span>${{ $technicians->tax ?? null }}</span>
                                        </div>
                                        <div class="price_h5">Total:
                                            <span>${{ $technicians->gross_total ?? null }}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="card">
                            <div class="card-body card-border shadow">
                                <div class="row open_items">
                                    <div class="col-md-10">
                                        <h5 class="card-title uppercase"><i class="fas fa-sticky-note px-1"></i> Job Note
                                        </h5>
                                    </div>
                                    <div class="col-md-2 text-center addnotes" style="cursor: pointer;">
                                        <i class="fas fa-plus "></i>
                                    </div>
                                </div>
                                <div class="row mt-2" id="jobNotes">
                                    @foreach ($techniciansnotes as $item)
                                        <ul class="list-unstyled mt-3">
                                            <li class="d-flex align-items-start">
                                                @isset($item->user_image)
                                                    <img class="me-3 rounded"
                                                        src="{{ asset('public/images/Uploads/users/' . $item->added_by . '/' . $item->user_image) ?? null }}"
                                                        width="60" alt="image"
                                                        onerror="this.onerror=null; this.src='{{ $defaultImage }}';" />
                                                @else
                                                    <img class="me-3 rounded" src="{{ $defaultImage }}" width="60"
                                                        alt="image" />
                                                @endisset
                                                <div class="media-body">
                                                    <h5 class="mt-0 mb-1">{{ $item->name ?? 'Unknown' }}</h5>
                                                    @if($item->is_flagged == 'yes') 
                                                    <i class="far fa-flag"></i>
                                                    @endif
                                                    {!! $item->note ?? null !!}
                                                </div>
                                            </li>
                                        </ul>
                                    @endforeach
                                </div>

                                <div class="shownotes" style="display: none;">
                                    <h5 class="card-title uppercase mb-3">Add a Note</h5>


                                    <form class="row g-2" method="post" action="{{ route('techniciannote') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @if (session('success'))
                                            <div id="successMessage" class="alert alert-success" role="alert">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        <input type="hidden" name="id" value={{ $technicians->id }}>
                                        <input type="hidden" name="technician_id"
                                            value={{ $technicians->technician_id }}>
                                        <textarea id="mymce" name="note"></textarea>

                                        <div class="col-md-2">
                                            <button type="submit" id="submitBtn"
                                                class="mt-3 btn waves-effect waves-light btn-success">
                                                Send
                                            </button>
                                        </div>


                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="card">
                            <div class="card-body card-border shadow">
                                <div class="row mb-3 open_items">
                                    <div class="col-md-7">
                                        <h5 class="card-title uppercase"><i class="fas fas fa-dollar-sign px-1"></i>
                                            Payment & Invoice</h5>
                                    </div>
                                    @if ($technicians->invoice_status == 'created')
                                        <div class="col-md-5 text-center">
                                            @php
                                                $payment = \App\Models\Payment::where(
                                                    'job_id',
                                                    $technicians->id,
                                                )->first();
                                            @endphp
                                            @if ($payment)
                                                <a href="{{ route('invoicedetail', ['id' => $payment->id]) }}"
                                                    class="btn waves-effect waves-light btn-primary">View & Send
                                                    Invoice</a>
                                            @endif
                                        </div>
                                    @else
                                        <div class="col-md-5 text-center">
                                            <form action="{{ route('create.payment.invoice') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="job_id" value="{{ $technicians->id }}">
                                                <button type="submit"
                                                    class="btn waves-effect waves-light btn-primary">View &
                                                    Send
                                                    Invoice</button>
                                            </form>
                                        </div>
                                    @endif

                                </div>
                                <div class="row mb-3">
                                    @if ($technicians->invoice_status == 'created' || $technicians->invoice_status == 'complete')
                                        <div class="col-md-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Invoice Number</th>
                                                        <th>Total Payment</th>
                                                        <th>Status</th>
                                                        <th>Due Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $Payment->invoice_number ?? null }}</td>
                                                        <td>${{ $Payment->total ?? null }}</td>
                                                        <td>{{ $Payment->status ?? null }}</td>
                                                        <td>{{ $convertDateToTimezone($Payment->due_date ?? null) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif



                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <h5 class="card-title uppercase">ACTIVITY FEED</h5>

                            <div class="table-responsive">
                                <table class="table customize-table mb-0 v-middle">
                                    <tbody>
                                        @foreach ($activity as $item)
                                            <tr>
                                                <td style="width:20%">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ url('public/images/Uploads/users/' . $item->user_id . '/' . $item->user->user_image) }}"
                                                            class="rounded-circle" width="40"
                                                            onerror="this.onerror=null; this.src='{{ $defaultImage }}';"
                                                            alt="Image">
                                                        <span
                                                            class="ms-2 fw-normal">{{ $item->user->name ?? null }}</span>
                                                    </div>
                                                </td>
                                                <td style="width:60%">{{ $item->activity ?? null }}</td>
                                                <td style="width:20%">
                                                    {{ $item->created_at ? $item->created_at->format('D n/j/y g:ia') : null }}


                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>








                        </div>
                    </div>
                </div>


                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="card-title uppercase">Job Moved/Reschedule Assignments</h5>
                                </div>

                            </div>

                            <div class="table-responsive">
                                <table class="table customize-table mb-0 v-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-bottom border-top">Technician</th>
                                            <th class="border-bottom border-top">Start Time</th>
                                            <th class="border-bottom border-top">End Time</th>
                                            <th class="border-bottom border-top">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $time_interval = Session::get('time_interval');
                                        @endphp
                                        @if ($assignedJobs && count($assignedJobs) > 0)
                                            @foreach ($assignedJobs as $item)
                                                <tr>
                                                    <td>{{ $item->technician->name }} </td>
                                                    <td>{{ $modifyDateTime($item->start_date_time, $time_interval, 'add', 'M d Y g:i a') }}
                                                    </td>
                                                    <td>{{ $modifyDateTime($item->end_date_time, $time_interval, 'add', 'M d Y g:i a') }}
                                                    </td>
                                                    <td>{{ $item->assign_status }} </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4">No assigned jobs found.</td>
                                            </tr>
                                        @endif

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
    <!-- -------------------------------------------------------------- -->
    <!-- End Container fluid  -->

    @include('tickets.scriptShow')

    @if (Route::currentRouteName() != 'dash')
    @endsection
@endif
