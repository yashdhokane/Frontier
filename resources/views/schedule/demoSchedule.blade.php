<div class="row">

        <div class="col-md-4 mt-4">
            <div class="cal_title_left text-start ms-3"><a href="#" id="preDate1" data-previous-date="{{ $previousDate }}"><i
                        class="fas fa-arrow-left"></i></a>
            </div>
            <div class="cal_title_center text-center">
                <h4 class="fc-toolbar-title" id="fc-dom-1">{{ $formattedDate }}</h4>
            </div>
            <div class="cal_title_right text-end"><a href="#" id="tomDate1"
                    data-tomorrow-date="{{ $tomorrowDate }}"><i class="fas fa-arrow-right"></i></a>
            </div>
        </div>

        <div class="col-md-4"></div>
        <div class="col-md-4 text-start">
            <a id="selectDates1" style="margin-right: 10px; font-size: 13px;cursor: pointer;"><i
                    class="fas fa-calendar-alt"></i>Select Dates</a>
            <div class="btn-group my-2" role="group" aria-label="Button group with nested dropdown"
                style="margin-right:30px;">
                <a href="#navCalendar1" class="btn btn-info cbtn1">Calendar</a>
                <a href="#navMap1" class="btn btn-light-info text-info mbtn1">Map</a>
            </div>
        </div>

        <div class="col-md-12" id="scheduleSection1" data-map-date="{{ $formattedDate }}">
            <div class="schedule-container mx-4 bg-white">
                <div class="header-row">
                    <div class="tech-header"></div>
                    <!-- Loop through the user_array to generate technician headers -->
                    @foreach ($technicians as $key => $item)
                        <div class="tech-header" style="color: #123456;" data-tech-id="{{ $item->id }}">
                            <a href="#" class="link user_head_link tech_profile" style="color: #123456 !important;">
                                <img src="{{ asset('public/images/Uploads/users/' . $item->id . '/' . $item->user_image) }}"
                                    alt="user" width="48" class="rounded-circle tech_profile"
                                    onerror="this.onerror=null; this.src='{{ $defaultImage }}';" />
                                <span class="tech-name">
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
                                    <div class="timeslot p-0 day clickPoint1" data-date="{{ $formattedDate }}"
                                        data-slot-time="{{ formatTime($hour, $minute) }}"
                                        data-technician-name="{{ $item->name }}"
                                        data-technician-id="{{ $item->id }}" style="display: flex;">

                                        @foreach ($groupedJobs as $key2 => $jobs)
                                            @php
                                                $jobCount = count($jobs);
                                                $jobWidth = 100 / $jobCount;
                                            @endphp
                                            @foreach ($jobs as $job)
                                                @if ($job->schedule_type == 'job')
                                                    @php
                                                        $duration = $job->JobModel->jobassignname->duration ?? null;
                                                        $height_slot = $duration ? ($duration / 30) * 40 : 0;
                                                        $overflow_height = $height_slot - 10;
                                                    @endphp
                                                    <div id='{{ $job->job_id }}' class="dts dragDiv stretchJob border"
                                                        style="height:{{ $height_slot }}px; position: relative; width:{{ $jobWidth }}px;"
                                                        data-duration="{{ $job->JobModel->jobassignname->duration }}" data-technician-name="{{ $job->technician->name }}"  data-timezone-name="{{ $job->technician->TimeZone->timezone_name }}">

                                                        <a class="show_job_details text-white"
                                                            href="{{ $job->job_id ? route('tickets.show', $job->job_id) : '#' }}"
                                                            style="width: {{ $jobWidth }}px;">
                                                            <div class="mb-1" data-id="{{ $job->job_id }}"
                                                                data-duration="{{ $job->JobModel->jobassignname->duration }}"
                                                                data-technician-name="{{ $job->technician->name }}"
                                                                data-timezone-name="{{ $job->technician->TimeZone->timezone_name }}"
                                                                data-time="{{ $timeString }}"
                                                                data-date="{{ $formattedDate }}"
                                                                style="max-width: {{ $jobWidth }}%;cursor: pointer;">
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
                                                                    <div class="cls_slot_job_card">
                                                                        {{ $job->JobModel->job_title ?? null }}
                                                                    </div>
                                                                    <div class="cls_slot_job_card">
                                                                        {{ $job->JobModel->city ?? null }},
                                                                        {{ $job->JobModel->state ?? null }}
                                                                    </div>
                                                                    <div class="round-init">
                                                                        @php
                                                                            $name = $job->technician->name ?? null;
                                                                            $initials = '';
                                                                            if ($name) {
                                                                                $names = explode(' ', $name);
                                                                                foreach ($names as $part) {
                                                                                    $initials .= strtoupper(
                                                                                        substr($part, 0, 1),
                                                                                    );
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        {{ $initials }}
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

                                                                        if ($startDateTime1 && $endDateTime1 && $interval1) {
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
                                                                <div class="py-1">
                                                                    <i class="fas fa-ticket-alt px-2"></i>
                                                                    <strong>{{ $job->JobModel->job_title ?? null }}</strong>
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

                                                {{-- For schedule type event --}}
                                                @if ($job->schedule_type == 'event')
                                                    @php
                                                        $to = $job->event->end_time
                                                            ? Carbon\Carbon::parse($job->event->end_time)
                                                            : null;
                                                        $from = $job->event->start_time
                                                            ? Carbon\Carbon::parse($job->event->start_time)
                                                            : null;
                                                        $duration = $to && $from ? $to->diffInMinutes($from) : null;
                                                        $height_slot = $duration ? ($duration / 30) * 40 : 0;
                                                        $overflow_height = $height_slot - 10;
                                                    @endphp

                                                    <div id='{{ $job->job_id }}' class="dts dragDiv stretchJob border"
                                                        style="height:{{ $height_slot }}px; position: relative; width:{{ $jobWidth }}px;"
                                                        data-duration="{{ $duration }}">

                                                        <a class="show_job_details text-white"
                                                            href="{{ $job->job_id ? route('tickets.show', $job->job_id) : '#' }}"
                                                            style="width: {{ $jobWidth }}px;">
                                                            <div class="mb-1" data-id="{{ $job->job_id }}"
                                                                data-duration="{{ $duration }}"
                                                                data-technician-name="{{ $job->technician->name }}"
                                                                data-timezone-name="{{ $job->technician->TimeZone->timezone_name }}"
                                                                data-time="{{ $timeString }}"
                                                                data-date="{{ $formattedDate }}"
                                                                style="max-width: {{ $jobWidth }}%;cursor: pointer;">
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
                                                                    <div class="cls_slot_job_card">
                                                                        {{ $job->JobModel->job_title ?? null }}
                                                                    </div>
                                                                    <div class="cls_slot_job_card">
                                                                        {{ $job->JobModel->city ?? null }},
                                                                        {{ $job->JobModel->state ?? null }}
                                                                    </div>
                                                                    <div class="round-init">
                                                                        @php
                                                                            $name = $job->technician->name ?? null;
                                                                            $initials = '';
                                                                            if ($name) {
                                                                                $names = explode(' ', $name);
                                                                                foreach ($names as $part) {
                                                                                    $initials .= strtoupper(
                                                                                        substr($part, 0, 1),
                                                                                    );
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        {{ $initials }}
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

                                                                        if ($startDateTime1 && $endDateTime1 && $interval1) {
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
                                                                <div class="py-1">
                                                                    <i class="fas fa-ticket-alt px-2"></i>
                                                                    <strong>{{ $job->JobModel->job_title ?? null }}</strong>
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
                                            @endforeach
                                        @endforeach
                                        <div class="popupDiv1 fs-4  bg-white shadow p-2" style="display: none;">
                                            <a
                                                href="{{ url('create_job', [$item->id, formatTime($hour, $minute), $formattedDate]) }}">
                                                <div class="createSchedule align-items-sm-center d-flex gap-3 fw-semibold"
                                                    style="cursor: pointer;" data-id="{{ $item->id }}"
                                                    data-time="{{ formatTime($hour, $minute) }}" data-date="{{ $formattedDate }}">
                                                    <i class="fa fa-plus-square"></i>
                                                    <span>Job</span>
                                                </div>
                                            </a>
                                            <hr class="m-0">
                                            <div class="align-items-sm-center d-flex gap-3 fw-semibold" style="cursor: pointer;"><i
                                                    class="fa fa-pen-square"></i>
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

</div>