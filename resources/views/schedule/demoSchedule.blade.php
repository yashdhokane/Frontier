<div class="row ps-5">

    <div class="col-md-3 mt-3 ps-5">
        <div class="cal_title_left text-start ms-3"><a href="#" id="preDate1"
                data-previous-date="{{ $previousDate }}"><i class="fas fa-arrow-left"></i></a>
        </div>
        <div class="cal_title_center text-center">
            <h4 class="fc-toolbar-title" id="fc-dom-1">{{ $formattedDate }}</h4>
        </div>
        <div class="cal_title_right text-end"><a href="#" id="tomDate1"
                data-tomorrow-date="{{ $tomorrowDate }}"><i class="fas fa-arrow-right"></i></a>
        </div>
        
    </div>

    <div class="col-md-5 mt-3">
        <a href="#" id="todayDate" class="me-1 text-decoration-underline" data-today-date="{{ $TodayDate }}">Today </a> 
        <a id="selectDates1" class="btn btn-outline-dark py-0" ><i
                class="fas fa-calendar-alt"></i> Select Dates</a>
                </div>
    <div class="col-md-4 text-end">
        <div class="btn-group my-2" role="group" aria-label="Button group with nested dropdown"
            style="margin-right:30px;">
            <a href="#navCalendar1" class="btn btn-info cbtn1">Calendar</a>
            <a href="#navMap1" class="btn btn-secondary text-white mbtn1">Map</a>
        </div>
    </div>

</div>

<div class="row ps-5">
    <div class="col-md-12" id="scheduleSection1" data-map-date="{{ $formattedDate }}" style="overflow-x: scroll;">
        <div class="schedule-container mx-4 bg-white">
            <div class="header-row">
                <div class="tech-header"></div>
                <!-- Loop through the user_array to generate technician headers -->
                @foreach ($technicians as $key => $item)
                    <div class="tech-header  tech_width_{{ $item->id }}" style="color: #123456;"
                        data-tech-id="{{ $item->id }}">
                        <a href="#" class="link user_head_link tech_profile" style="color: #123456 !important;">
                            <img src="{{ asset('public/images/Uploads/users/' . $item->id . '/' . $item->user_image) }}"
                                alt="user" width="48"
                                class="rounded-circle tech_extend_width JobOpenModalButtonschedule"
                                data-tech-id="{{ $item->id }}" data-tech-name="{{ $item->name }}"
                                onerror="this.onerror=null; this.src='{{ $defaultImage }}';"
                                data-class-name="tech_width_{{ $item->id }}"
                                data-jobClass-name="width_job_{{ $item->id }}"
                                data-max-width="max_width_job{{ $item->id }}" data-date="{{ $formattedDate }}" />
                            <span class="tech-name tech_profile">
                                @php
                                    $name = $item->name;
                                    $nameParts = explode(' ', $name);
                                    $firstName = $nameParts[0];
                                    $lastInitial =
                                        count($nameParts) > 1 ? strtoupper($nameParts[count($nameParts) - 1][0]) : '';
                                    $formattedName = $firstName . ' ' . $lastInitial;
                                @endphp
                                {{ $formattedName }}
                            </span>
                        </a>
                        <div class="popupContainer text-start" style="display: none;">
                            <!-- Popup content for profile link -->
                            <a href="{{ url('technicians/show/' . $item->id) }}" target="_blank"
                                class="popup-option"><i class="fa fa-user pe-2"></i>View Profile</a>
                            </hr>
                            <!-- Popup content for message option -->
                            <a href="#" class="popup-option message-popup"><i
                                    class="fa fa-list-alt pe-2"></i>Message</a></hr>
                            <a href="#" class="popup-option setting-popup"><i
                                    class="fa fa-wrench pe-2"></i>Settings</a>
                            <!-- Anchor Tag to Open Modal -->





                        </div>

                        <div class="smscontainer">
                            <form id="sendSmsForm" class="conversation_form" method="post">
                                @csrf
                                <input type="hidden" name="tech_id" value="{{ $item->id }}">
                                <textarea name="message" class="message_content form-control p-1 my-1 w-100" rows="3"
                                    placeholder="Type Something...."></textarea>
                                <div>
                                    <button type="button" id="sendSmsButton"
                                        class="btn btn-info p-0 px-1 my-1 float-end">Send</button>
                                </div>
                            </form>

                        </div>
                        <div class="settingcontainer">
                            <div style="width:150px; height:100px;">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Time slots and technician schedule rows -->
            <div class="time-slot">
                @php
                    $start_time = (int) $hours->start_time;
                    $end_time = (int) $hours->end_time;
                    $startTime = $start_time; // 8 AM
                    $endTime = $end_time; // 7 PM
                    $interval = 30; // 30 minutes

                    // Function to format time in 12-hour format
                    function formatTime($hour, $minute)
                    {
                        $ampm = $hour >= 12 ? 'PM' : 'AM';
                        $formattedHour = $hour % 12 == 0 ? 12 : $hour % 12;
                        $formattedMinute = str_pad($minute, 2, '0', STR_PAD_LEFT);
                        return "$formattedHour:$formattedMinute $ampm";
                    }
                @endphp
                <!-- Loop through the time slots -->
                @for ($hour = $startTime; $hour <= $endTime; $hour++)
                    @for ($minute = 0; $minute < 60; $minute += $interval)
                        @if ($hour == $endTime && $minute > 0)
                        @break
                    @endif
                    <div class="time-row">
                        <div class="timeslot">{{ formatTime($hour, $minute) }}</div>
                        @foreach ($technicians as $key => $item)
                            @php
                                $timeSlot = Carbon\Carbon::createFromTime($hour, $minute)->format('H:i');
                                $technicianSchedules = $schedules->filter(function ($schedule) use ($item, $timeSlot) {
                                    $scheduleTime = Carbon\Carbon::parse($schedule->start_date_time)->format('H:i');
                                    return $schedule->technician_id == $item->id && $scheduleTime == $timeSlot;
                                });
                                $groupedJobs = collect($technicianSchedules)->groupBy('start_date_time');
                                $timeString = formatTime($hour, $minute);
                            @endphp
                            <div class="timeslot p-0 day clickPoint1 tech_width_{{ $item->id }}"
                                data-slot-time="{{ formatTime($hour, $minute) }}"
                                data-technician-name="{{ $item->name }}" data-date="{{ $formattedDate }}"
                                data-technician-id="{{ $item->id }}" style="display: flex;">

                                @foreach ($groupedJobs as $key2 => $jobs)
                                    @php
                                        $jobCount = count($jobs);
                                        $jobWidth = 100 / $jobCount;
                                    @endphp
                                    @foreach ($jobs as $job)
                                        {{-- For schedule type event --}}
                                        @if ($job->schedule_type == 'event')
                                            @php
                                                $to = $job->event->end_date_time
                                                    ? Carbon\Carbon::parse($job->event->end_date_time)
                                                    : null;
                                                $from = $job->event->start_date_time
                                                    ? Carbon\Carbon::parse($job->event->start_date_time)
                                                    : null;
                                                $duration = $to && $from ? $to->diffInMinutes($from) : null;
                                                $height_slot = $duration ? ($duration / 30) * 40 : 0;
                                                $overflow_height = $height_slot + 40;
                                            @endphp

                                            <a href="#" class="eventNoClick">
                                                <div id='{{ $job->job_id }}' class="dts border"
                                                    style="height:{{ $overflow_height }}px; position: relative; width:100px; background-color:#d8dcdf;"
                                                    data-duration="{{ $duration }}">
                                                    <h6>{{ $job->event->event_name }}</h6>

                                                </div>
                                            </a>
                                        @else
                                            @if ($job->schedule_type == 'job')
                                                @php
                                                    $duration = $job->JobModel->jobassignname->duration ?? null;
                                                    $height_slot = $duration ? ($duration / 30) * 40 : 0;
                                                    $overflow_height = $height_slot - 10;
                                                @endphp
                                                <div id='{{ $job->job_id }}'
                                                    class="dts dragDiv stretchJob border width_job_{{ $job->technician_id }} {{ $job->JobModel->is_published === 'yes' ? 'is_published_bg' : '' }} {{ $job->JobModel->status === 'closed' ? 'is_closed_bg' : '' }}"
                                                    style="height:{{ $height_slot }}px; position: relative; width:{{ $jobWidth }}px;"
                                                    data-duration="{{ $job->JobModel->jobassignname->duration ?? '' }}"
                                                    data-technician-name="{{ $job->technician->name }}"
                                                    data-timezone-name="{{ $job->technician->TimeZone->timezone_name }}">

                                                    <a class="show_job_details text-white"
                                                        href="{{ $job->job_id ? route('tickets.show', $job->job_id) : '#' }}"
                                                        style="width: {{ $jobWidth }}px;">
                                                        <div class="mb-1 max_width_job{{ $job->technician_id }}"
                                                            data-duration="{{ $job->JobModel->jobassignname->duration ?? '' }}"
                                                            data-technician-name="{{ $job->technician->name }}"
                                                            data-timezone-name="{{ $job->technician->TimeZone->timezone_name }}"
                                                            data-time="{{ $timeString }}"
                                                            data-date="{{ $formattedDate }}"
                                                            style="max-width: {{ $jobWidth }}px;cursor: pointer;">
                                                            @if ($job->JobModel && $job->JobModel->is_confirmed == 'yes')
                                                                <div class="cls_is_confirmed">
                                                                    <i class="ri-thumb-up-fill"></i>
                                                                </div>
                                                            @endif
                                                            <div
                                                                style="height: {{ $overflow_height }}px;overflow:hidden;">
                                                                <div class="cls_slot_title">
                                                                    <i class="ri-tools-line"></i>
                                                                    {{ $job->JobModel->user->name ?? null }}
                                                                </div>
                                                                <div class="cls_slot_time">
                                                                    <i class="ri-truck-line"></i>
                                                                    {{ $timeString }}
                                                                </div>
                                                                <div class="cls_slot_job_card text-truncate">
                                                                    {{ $job->JobModel->job_title ?? null }}
                                                                </div>
                                                                <div class="cls_slot_job_card hide_address">
                                                                    {{ $job->JobModel->city ?? null }},
                                                                    {{ $job->JobModel->state ?? null }}
                                                                </div>
                                                                <div class="cls_slot_job_card show_address"
                                                                    style="display: none;">
                                                                    {{ $job->JobModel->address }},
                                                                    {{ $job->JobModel->city ?? null }},
                                                                    {{ $job->JobModel->state ?? null }},
                                                                    {{ $job->JobModel->zipcode }}
                                                                    <div style="font-size:12px;">
                                                                        @if ($job->JobModel->JobAppliances && $job->JobModel->JobAppliances->Appliances)
                                                                            {{ $job->JobModel->JobAppliances->Appliances->appliance->appliance_name ?? null }}
                                                                            /
                                                                        @endif
                                                                        @if ($job->JobModel->JobAppliances && $job->JobModel->JobAppliances->Appliances)
                                                                            {{ $job->JobModel->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}
                                                                            /
                                                                        @endif
                                                                        @if ($job->JobModel->JobAppliances && $job->JobModel->JobAppliances->Appliances->model_number)
                                                                            {{ $job->JobModel->JobAppliances->Appliances->model_number ?? null }}
                                                                            /
                                                                        @endif
                                                                        @if ($job->JobModel->JobAppliances && $job->JobModel->JobAppliances->Appliances->serial_number)
                                                                            {{ $job->JobModel->JobAppliances->Appliances->serial_number ?? null }}
                                                                        @endif
                                                                    </div>
                                                                    <p>{{ $job->JobModel->description ?? null }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>

                                                    <div class="template" style="display: none;">
                                                        <div class="popup-content">
                                                            <h5><i class="fas fa-id-badge px-2"></i>
                                                                <strong>Job
                                                                    #{{ $job->JobModel->id ?? null }}</strong>
                                                            </h5>
                                                            <p class="ps-4 m-0 ms-2">
                                                                @php
                                                                    $startDateTime1 = $job->start_date_time
                                                                        ? Carbon\Carbon::parse($job->start_date_time)
                                                                        : null;
                                                                    $endDateTime1 = $job->end_date_time
                                                                        ? Carbon\Carbon::parse($job->end_date_time)
                                                                        : null;
                                                                    $interval1 = session('time_interval'); // Retrieve the time interval from the session

                                                                    if (
                                                                        $startDateTime1 &&
                                                                        $endDateTime1 &&
                                                                        $interval1
                                                                    ) {
                                                                        $startDateTime1->addHours($interval1);
                                                                        $endDateTime1->addHours($interval1);
                                                                    }
                                                                @endphp
                                                                @if ($startDateTime1 && $endDateTime1)
                                                                    {{ $startDateTime1->format('M d Y g:i a') }}
                                                                    -
                                                                    {{ $endDateTime1->format('g:i A') }}
                                                                @endif
                                                            </p>
                                                            <div class="py-1 text-truncate">
                                                                <i class="fas fa-ticket-alt px-2"></i>
                                                                <strong
                                                                    class="">{{ $job->JobModel->job_title ?? null }}</strong>
                                                            </div>
                                                            <div class="py-1">
                                                                <i class="fas fa-user px-2"></i>
                                                                <strong>{{ $job->JobModel->user->name ?? null }}</strong>
                                                                <p class="ps-4 m-0 ms-2">
                                                                    {{ $job->JobModel->addresscustomer->address_line1 ?? null }}
                                                                    {{ $job->JobModel->addresscustomer->zipcode ?? null }}
                                                                </p>
                                                                <p class="ps-4 m-0 ms-2">
                                                                    {{ $job->JobModel->user->mobile ?? null }}
                                                                </p>
                                                            </div>
                                                            <div class="py-1">
                                                                <i class="fas fa-user-secret px-2"></i>
                                                                <strong>{{ $job->JobModel->technician->name ?? null }}</strong>
                                                            </div>
                                                            <div class="py-1">
                                                                <i class="fas fa-tag px-2"></i>
                                                                <span
                                                                    class="mb-1 badge bg-primary">{{ $job->JobModel->status ?? null }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                @endforeach
                                <div class="popupDiv1 fs-4  bg-white shadow p-2" style="display: none;">
                                    <a
                                        href="{{ url('create_job', [$item->id, formatTime($hour, $minute), $formattedDate]) }}">
                                        <div class="createSchedule align-items-sm-center d-flex gap-3 fw-semibold"
                                            style="cursor: pointer;" data-id="{{ $item->id }}"
                                            data-time="{{ formatTime($hour, $minute) }}"
                                            data-date="{{ $formattedDate }}">
                                            <i class="fa fa-plus-square"></i>
                                            <span>Job</span>
                                        </div>
                                    </a>
                                    <hr class="m-0">
                                    <div class="align-items-sm-center d-flex gap-3 fw-semibold"
                                        style="cursor: pointer;"><i class="fa fa-pen-square"></i>
                                        <span>Estimate</span>
                                    </div>
                                    <hr class="m-0">
                                    <div class="eventSchedule align-items-sm-center d-flex gap-3 fw-semibold"
                                        style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#event"
                                        data-id="{{ $item->id }}"><i class="fa fa-calendar-plus"></i>
                                        <span>Event</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endfor
            @endfor
        </div>
    </div>
</div>

<div class="col-12 bg-light py-2 px-3 mt-3 card-border mapStyle" id="mapSection1">
    <div id="mapScreen1" style="height: 550px; width: 100%;"></div>
</div>

<!-- Modal -->
<div class="modal fade" id="allJobsTechnician" tabindex="-1" aria-labelledby="allJobsTechnicianLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="allJobsTechnicianLabel46"></h4>


                <button style="width:150px!important;" class=" popup-option123 togglebutton btn btn-outline-primary btn-sm">Best Route</button>



            </div>
            <div class="modal-body row openJobTechDetailsSchedule ">
                <!-- Original content -->


            </div>
            <!-- <div class="modal-body row mapbestroute" style="display:none;">

<div id="map" style="height: 500px; width: 100%;"></div>

</div> -->




            <div class="row">
                <!-- Modal -->
                <div class="modal fade" id="allJobsTechnician" tabindex="-1"
                    aria-labelledby="allJobsTechnicianLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center mod-head">
                                <h4 class="modal-title" id="allJobsTechnicianLabel46"></h4>
                            </div>
                            <div class="modal-body row openJobTechDetails">
                                <!-- Original content -->
                            </div>
                        </div>
                    </div>
                </div>


                {{-- @include('jobrouting.modal') --}}

                <div class="modal-body row mapbestroute" style="display:none;">
                    <!-- Buttons -->


                    <link href="{{ asset('public/admin/routing/style.css') }}" rel="stylesheet" />

                    <div class=" col-md-12">
                        <div class="d-flex justify-content-between align-items-center" id="menu">
                            <div class="col-md-12 row">
                                <div class="col-md-3">
                                    <select id="dateDay" name="dateDay" class="form-select select ms-1">
                                        <option value="today">Today</option>
                                        <option value="tomorrow">Tomorrow</option>
                                        <option value="nextdays">Next 3 Days</option>
                                        <option value="week">Next 7 Days</option>
                                        <option value="chooseDate">Choose Date</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select id="routing" name="routing" class="form-select select">
                                        <option value="bestroute">Best Route</option>
                                        <option value="shortestroute">Shortest Route</option>
                                        <option value="customizedroute">Customized Route</option>
                                    </select>
                                </div>
                                <div class="col-md-2" style="display:none!important;">

                                    <select id="priorityDropdown12" name="priority12"
                                        class="form-select form-control-sm" style="">


                                        <option value="">All</option>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="urgent">Urgent</option>
                                    </select>
                                </div>
                                <div class="col-md-3"  style="display:none!important;">

                                    <select id="priorityDropdown12" name="priority12"
                                        class="form-select form-control-sm" style="">


                                        <option value="">Priority</option>
                                        <option value="">Is Published</option>
                                    </select>
                                </div>

                                <div class="col-md-2 ">
                                    {{-- <a href="javascript:void(0);" id="setNewButton1"
                            class="text-decoration-none text-primary" style="color:black;"><i
                                class="ri-settings-2-line"></i> Routing Setting</a><span
                            style="margin-left:5px;">|</span> --}}
                                    <a href="javascript:void(0);" id="fullview"
                                        class="text-decoration-none  text-warning"
                                        style="color:black; margin-left:10px;"><i class="ri-zoom-in-line"></i>
                                        Full
                                        View</a>
                                </div>



                            </div>
                            <select id="routingTriggerSelect" name="routing_id" class="form-select  select selectedone"
                                multiple="multiple" style="display: none!important;">

                                @foreach ($tech as $routing)
                                    <option value="{{ $routing->id }}">
                                        {{ $routing->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="col-md-4"></div>


                        </div>
                    </div>
                    <div class=" col-md-12" id="showChooseDate">
                        <div class="row ps-1 pt-2">
                            <div class=" col-md-2">
                                <input type="date" class="form-control" name="chooseFrom" id="chooseFrom">
                            </div>
                            <div class=" col-md-2">
                                <input type="date" class="form-control" name="chooseTo" id="chooseTo">

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @include('jobrouting.map')
                        @include('jobrouting.job_details')
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-light-danger text-danger font-medium waves-effect text-start"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- map best root model -->
        <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mapModalLabel">Best Route</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div id="map3" style="height: 500px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
