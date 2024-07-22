<div class="mt-3 mb-4 calender-sidebar app-calendar" id="calender2">
    <div class="row" id="screen-date2" data-screen2-date="{{ $formattedDate }}">
        <div class="col-md-4">
            <div class="cal_title_left text-start"><a href="#" id="preDate2"
                    data-previous-date="{{ $previousDate }}"><i class="fas fa-arrow-left"></i></a></div>
            <div class="cal_title_center text-center">
                <h4 class="fc-toolbar-title" id="fc-dom-1">{{ $formattedDate }}</h4>
            </div>
            <div class="cal_title_right text-end"><a href="#" id="tomDate2"
                    data-tomorrow-date="{{ $tomorrowDate }}"><i class="fas fa-arrow-right"></i></a></div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4 text-start">
            <a id="selectDates2" style="margin-right: 10px; font-size: 13px;cursor: pointer;"><i
                    class="fas fa-calendar-alt"></i>Select Dates</a>

            {{-- <a href="#" id="todayDate2" data-today-date="{{ $TodayDate }}"
                style=" margin-right: 10px;font-size: 13px;color: #ee9d01;font-weight: bold;"><i
                    class="fas fa-calendar-check"></i> Today</a> --}}
        </div>

    </div>
    <div class="dat schedule_section_box" id="table-container">
        <table id="demo-foo-addrow" class="table table-bordered m-t-30 table-hover contact-list text-nowrap"
            data-paging="true" data-paging-size="7" style="width: max-content;">
            <thead>
                <tr>
                    <th></th>
                    @php

                        use Carbon\Carbon;
                    @endphp
                    @if (isset($user_array) && !empty($user_array))
                        @foreach ($user_array as $value)
                            <th class="tech_th" data-tech-id="{{ $value }}" style="width:100px">
                                <a href="#" class="link user_head_link tech_profile"
                                    style="color: {{ $user_data_array[$value]['color_code'] }} !important;">
                                    @if (isset($user_data_array[$value]['user_image']) && !empty($user_data_array[$value]['user_image']))
                                        <img src="{{ asset('public/images/Uploads/users/' . $value . '/' . $user_data_array[$value]['user_image']) }}"
                                            alt="user" width="48" class="rounded-circle tech_profile"
                                            onerror="this.onerror=null; this.src='{{ $defaultImage }}';" /><br>
                                    @else
                                        <img src="{{ $defaultImage }}" alt="user" width="48"
                                            class="rounded-circle tech_profile" /><br>
                                    @endif

                                    @if (isset($user_data_array[$value]) && !empty($user_data_array[$value]))
                                        @php
                                            $name = $user_data_array[$value]['name'];
                                            $nameParts = explode(' ', $name);
                                            $firstName = $nameParts[0];
                                            $lastInitial =
                                                count($nameParts) > 1
                                                    ? strtoupper($nameParts[count($nameParts) - 1][0])
                                                    : '';
                                            $formattedName = $firstName . ' ' . $lastInitial;
                                        @endphp
                                        {{ $formattedName }}
                                    @endif
                                </a>
                                <div class="popupContainer text-start" style="display: none;">
                                    <!-- Popup content for profile link -->
                                    <a href="{{ url('technicians/show/' . $value) }}" class="popup-option"><i
                                            class="fa fa-user pe-2"></i>View Profile</a>
                                    </hr>
                                    <!-- Popup content for message option -->
                                    <a href="#" class="popup-option message-popup"><i
                                            class="fa fa-list-alt pe-2"></i>Message</a></hr>
                                    <a href="#" class="popup-option setting-popup"><i
                                            class="fa fa-wrench pe-2"></i>Settings</a>
                                </div>

                                <div class="smscontainer">

                                    <input type="text" class="message_content form-control p-1 my-1"
                                        placeholder="Type Something....">
                                    <button class="btn btn-info p-0 px-1 my-1 float-end">Send</button>

                                </div>
                                <div class="settingcontainer">
                                    <div style="width:150px; height:100px;">
                                    </div>
                                </div>

                            </th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody class="slot_time_60_span" id="draggable-area">
                @php
                    $start_time = (int) $hours->start_time;
                    $end_time = (int) $hours->end_time;
                @endphp
                @for ($i = $start_time; $i < $end_time; $i++)
                    @for ($minute = 0; $minute < 60; $minute += 30)
                        <tr class="draggable-area">
                            <td class="timeslot_td">
                                <div class="timeslot_td_time">
                                    @php
                                        $time = $i >= 12 ? ($i > 12 ? $i - 12 : $i) : $i;
                                        $minutes = '00'; // Default minutes
                                        if ($i % 1 !== 0) {
                                            $minutes = '30'; // Adjust minutes for half-hour intervals
                                        }
                                        $date = $i >= 12 ? 'pm' : 'am';

                                        $display_hour = $i > 12 ? $i - 12 : $i;
                                        $display_minute = $minute == 0 ? '00' : $minute;
                                        $time = $display_hour . ':' . $display_minute . ' ' . $date;
                                        $timeString = $time . ($minutes == '00' ? '' : ':30');
                                        // Format $time for comparison
                                        $formattedTime = date('h:i A', strtotime($time));
                                    @endphp
                                    {{ $time }}
                                </div>
                            </td>
                            @if (isset($user_array) && !empty($user_array))
                                @foreach ($user_array as $value)
                                    @php
                                        $assigned_data = [];
                                        if (
                                            isset($schedule_arr[$value][$formattedTime]) &&
                                            !empty($schedule_arr[$value][$formattedTime])
                                        ) {
                                            $assigned_data = $schedule_arr[$value][$formattedTime];
                                        }
                                    @endphp
                                    <td class="timeslot_td slot_refresh_jobs draggable-items"
                                        data-slot_time="{{ $timeString }}"
                                        data-drag-date="{{ $formattedDate }}"
                                        data-technician-name="{{ $value }}"
                                        data-technician_id="{{ $value }}">
                                        @if (isset($assigned_data) && !empty($assigned_data))
                                            <div class="testclass">
                                                @php
                                                    // Ensure $assigned_data is a collection
                                                    $groupedJobs = collect($assigned_data)->groupBy(function ($item) {
                                                        return $item->start_date_time;
                                                    });
                                                @endphp

                                                @foreach ($groupedJobs as $startDateTime => $jobs)
                                                    @php
                                                        $jobCount = count($jobs);
                                                        $jobWidth = 90 / $jobCount;
                                                        $jobWidth2 = 85 / $jobCount;
                                                        $additionalClass = $jobCount > 1 ? 'schedulJob' : '';
                                                    @endphp
                                                    <div class="job-group" style="display: flex; width:100%;">
                                                        @foreach ($jobs as $value2)
                                                            {{-- For schedule type job --}}
                                                            @if ($value2->schedule_type == 'job')
                                                                @php
                                                                    $duration =
                                                                        $value2->JobModel->jobassignname->duration ??
                                                                        null;
                                                                    $height_slot = $duration / 30;
                                                                    $height_slot_px =
                                                                        $height_slot * 36 + $height_slot - 1;
                                                                    $overfloHeight = $height_slot_px - 5;
                                                                @endphp

                                                                <a class="show_job_details pb-5"
                                                                    href="{{ $value2->job_id ? route('tickets.show', $value2->job_id) : '#' }}"
                                                                    style="width: {{ $jobWidth }}%;">
                                                                    <div class="dts stretchJob mb-1 flexibleslot {{ $additionalClass }}"
                                                                        data-id="{{ $value2->job_id }}"
                                                                        data-duration="{{ $value2->JobModel->jobassignname->duration }}"
                                                                        data-technician-name="{{ $value2->technician->name }}"
                                                                        data-timezone-name="{{ $value2->technician->TimeZone->timezone_name }}"
                                                                        data-time="{{ $timeString }}"
                                                                        data-date="{{ $filterDate }}"
                                                                        style="max-width: {{ $jobWidth2 }}%;cursor: pointer; height: {{ $height_slot_px }}px; background: {{ $value2->JobModel->technician->color_code ?? null }};">
                                                                        @if ($value2->JobModel && $value2->JobModel->is_confirmed == 'yes')
                                                                            <div class="cls_is_confirmed">
                                                                                <i class="ri-thumb-up-fill"></i>
                                                                            </div>
                                                                        @endif

                                                                        <div class="start-drag-div">
                                                                            <i class="bi-arrows-move start-drag"></i>
                                                                        </div>
                                                                        <div
                                                                            style="overflow:hidden;height: {{ $overfloHeight }}px;">
                                                                            <div class="cls_slot_title">
                                                                                <i class="ri-tools-line"></i>
                                                                                {{ $value2->JobModel->user->name ?? null }}
                                                                            </div>
                                                                            <div class="cls_slot_time">
                                                                                <i class="ri-truck-line"></i>
                                                                                {{ $timeString }}
                                                                            </div>
                                                                            <div class="cls_slot_job_card">
                                                                                {{ $value2->JobModel->job_title ?? null }}
                                                                            </div>
                                                                            <div class="cls_slot_job_card">
                                                                                {{ $value2->JobModel->city ?? null }},
                                                                                {{ $value2->JobModel->state ?? null }}
                                                                            </div>
                                                                            <div class="round bg-cyan">
                                                                                @php
                                                                                    $name =
                                                                                        $value2->technician->name ??
                                                                                        null;
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

                                                                <div class="open_job_details rounded shadow py-3 px-2"
                                                                    style="background: {{ $value2->JobModel->technician->color_code ?? null }};">
                                                                    <div class="popup-content">
                                                                        <h5><i class="fas fa-id-badge px-2"></i>
                                                                            <strong>Job
                                                                                #{{ $value2->JobModel->id ?? null }}</strong>
                                                                        </h5>
                                                                        <p class="ps-4 m-0 ms-2">
                                                                            @php
                                                                                $startDateTime = $value2->start_date_time
                                                                                    ? Carbon::parse(
                                                                                        $value2->start_date_time,
                                                                                    )
                                                                                    : null;
                                                                                $endDateTime = $value2->end_date_time
                                                                                    ? Carbon::parse(
                                                                                        $value2->end_date_time,
                                                                                    )
                                                                                    : null;
                                                                                $interval = session('time_interval'); // Retrieve the time interval from the session

                                                                                if (
                                                                                    $startDateTime &&
                                                                                    $endDateTime &&
                                                                                    $interval
                                                                                ) {
                                                                                    $startDateTime->addHours($interval);
                                                                                    $endDateTime->addHours($interval);
                                                                                }
                                                                            @endphp
                                                                            @if ($startDateTime && $endDateTime)
                                                                                {{ $startDateTime->format('M d Y g:i a') }}
                                                                                -
                                                                                {{ $endDateTime->format('g:i A') }}
                                                                            @endif
                                                                        </p>
                                                                        <div class="py-1">
                                                                            <i class="fas fa-ticket-alt px-2"></i>
                                                                            <strong>{{ $value2->JobModel->job_title ?? null }}</strong>
                                                                        </div>
                                                                        <div class="py-1">
                                                                            <i class="fas fa-user px-2"></i>
                                                                            <strong>{{ $value2->JobModel->user->name ?? null }}</strong>
                                                                            <p class="ps-4 m-0 ms-2">
                                                                                {{ $value2->JobModel->addresscustomer->address_line1 ?? null }}
                                                                                {{ $value2->JobModel->addresscustomer->zipcode ?? null }}
                                                                            </p>
                                                                            <p class="ps-4 m-0 ms-2">
                                                                                {{ $value2->JobModel->user->mobile ?? null }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="py-1">
                                                                            <i class="fas fa-user-secret px-2"></i>
                                                                            <strong>{{ $value2->JobModel->technician->name ?? null }}</strong>
                                                                        </div>
                                                                        <div class="py-1">
                                                                            <i class="fas fa-tag px-2"></i>
                                                                            <span
                                                                                class="mb-1 badge bg-primary">{{ $value2->JobModel->status ?? null }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            {{-- For schedule type event --}}
                                                            @if ($value2->schedule_type == 'event')
                                                                @php
                                                                    $to = $value2->event->start_date_time ?? null;
                                                                    $from = $value2->event->end_date_time ?? null;
                                                                    $toDateTime = new DateTime($to);
                                                                    $fromDateTime = new DateTime($from);
                                                                    $interval = $toDateTime->diff($fromDateTime);
                                                                    $duration = $interval->h * 60 + $interval->i;
                                                                    $height_slot = $duration / 30;
                                                                    $height_slot_px = $height_slot * 40.5 - 7;
                                                                @endphp
                                                                <div class="dts mb-1 flexibleslot"
                                                                    data-id="{{ $value2->job_id }}"
                                                                    data-time="{{ $timeString }}"
                                                                    data-date="{{ $filterDate }}"
                                                                    style="cursor: pointer; height: {{ $height_slot_px }}px; background: #dadad6; width: {{ $jobWidth }}%;">
                                                                    <h5
                                                                        style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">
                                                                        {{ $value2->event->event_name ?? null }}
                                                                    </h5>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="dts2 clickPoint" style="height: 100%; position: revert;"
                                                {{-- data-bs-toggle="modal" --}} data-id="{{ $value }}"
                                                data-time="{{ $timeString }}" data-date="{{ $filterDate }}"
                                                data-bs-target="#create">
                                            </div>
                                            <div class="popupDiv fs-4" style="display: none;">
                                                <a href="{{ url('create_job', [$value, $timeString, $filterDate]) }}">
                                                    <div class="createSchedule align-items-sm-center d-flex gap-3 fw-semibold"
                                                        style="cursor: pointer;" data-id="{{ $value }}"
                                                        data-time="{{ $timeString }}"
                                                        data-date="{{ $filterDate }}">
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
                                                    style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#event" data-id="{{ $value }}"><i
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
