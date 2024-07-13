@extends('home')
@section('content')


    <div class="page-wrapper p-0 ms-2" style="display:flex;">
        <!-- Container fluid  -->
        <!-- -------------------------------------------------------------- -->

        <div class="container-fluid container-schedule">

            <div class="row">

                <div class="card">
                    <div>
                        <div class="row gx-0 px-3">
                            <div class="col-lg-12">
                                <div class="mt-3 mb-4 calender-sidebar app-calendar">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="cal_title_left text-start"><a
                                                    href="schedule?date={{ $previousDate }}"><i
                                                        class="fas fa-arrow-left"></i></a></div>
                                            <div class="cal_title_center text-center">
                                                <h4 class="fc-toolbar-title" id="fc-dom-1">{{ $formattedDate }}</h4>
                                            </div>
                                            <div class="cal_title_right text-end"><a
                                                    href="schedule?date={{ $tomorrowDate }}"><i
                                                        class="fas fa-arrow-right"></i></a></div>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4 text-start">
                                            <a id="selectDates"
                                                style="margin-right: 10px; font-size: 13px;cursor: pointer;"><i
                                                    class="fas fa-calendar-alt"></i>Select Dates</a>

                                            <a href="schedule?date={{ $TodayDate }}"
                                                style=" margin-right: 10px;font-size: 13px;color: #ee9d01;font-weight: bold;"><i
                                                    class="fas fa-calendar-check"></i> Today</a>
                                        </div>

                                    </div>
                                    <div class="dat schedule_section_box" id="table-container">
                                        <table id="demo-foo-addrow"
                                            class="table table-bordered m-t-30 table-hover contact-list text-nowrap"
                                            data-paging="true" data-paging-size="7" style="width: max-content;">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    @php

                                                        use Carbon\Carbon;
                                                    @endphp
                                                    @if (isset($user_array) && !empty($user_array))
                                                        @foreach ($user_array as $value)
                                                            <th class="tech_th" data-tech-id="{{ $value }}"
                                                                style="width:100px">
                                                                <a href="#" class="link user_head_link tech_profile"
                                                                    style="color: {{ $user_data_array[$value]['color_code'] }} !important;">
                                                                    @if (isset($user_data_array[$value]['user_image']) && !empty($user_data_array[$value]['user_image']))
                                                                        <img src="{{ asset('public/images/Uploads/users/' . $value . '/' . $user_data_array[$value]['user_image']) }}"
                                                                            alt="user" width="48"
                                                                            class="rounded-circle tech_profile"
                                                                            onerror="this.onerror=null; this.src='{{ $defaultImage }}';" /><br>
                                                                    @else
                                                                        <img src="{{ $defaultImage }}" alt="user"
                                                                            width="48"
                                                                            class="rounded-circle tech_profile" /><br>
                                                                    @endif

                                                                    @if (isset($user_data_array[$value]) && !empty($user_data_array[$value]))
                                                                        @php
                                                                            $name = $user_data_array[$value]['name'];
                                                                            $nameParts = explode(' ', $name);
                                                                            $firstName = $nameParts[0];
                                                                            $lastInitial =
                                                                                count($nameParts) > 1
                                                                                    ? strtoupper(
                                                                                        $nameParts[
                                                                                            count($nameParts) - 1
                                                                                        ][0],
                                                                                    )
                                                                                    : '';
                                                                            $formattedName =
                                                                                $firstName . ' ' . $lastInitial;
                                                                        @endphp
                                                                        {{ $formattedName }}
                                                                    @endif
                                                                </a>
                                                                <div class="popupContainer text-start"
                                                                    style="display: none;">
                                                                    <!-- Popup content for profile link -->
                                                                    <a href="{{ url('technicians/show/' . $value) }}"
                                                                        class="popup-option"><i
                                                                            class="fa fa-user pe-2"></i>View Profile</a>
                                                                    </hr>
                                                                    <!-- Popup content for message option -->
                                                                    <a href="#" class="popup-option message-popup"><i
                                                                            class="fa fa-list-alt pe-2"></i>Message</a></hr>
                                                                    <a href="#" class="popup-option setting-popup"><i
                                                                            class="fa fa-wrench pe-2"></i>Settings</a>
                                                                </div>

                                                                <div class="smscontainer">

                                                                    <input type="text"
                                                                        class="message_content form-control p-1 my-1"
                                                                        placeholder="Type Something....">
                                                                    <button
                                                                        class="btn btn-info p-0 px-1 my-1 float-end">Send</button>

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
                                            <tbody class="slot_time_60_span">
                                                @php
                                                    $start_time = (int) $hours->start_time;
                                                    $end_time = (int) $hours->end_time;
                                                @endphp
                                                @for ($i = $start_time; $i < $end_time; $i++)
                                                    @for ($minute = 0; $minute < 60; $minute += 30)
                                                        <tr>
                                                            <td class="timeslot_td">
                                                                <div class="timeslot_td_time">
                                                                    @php
                                                                        $time =
                                                                            $i >= 12 ? ($i > 12 ? $i - 12 : $i) : $i;
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
                                                                        $formattedTime = date(
                                                                            'h:i A',
                                                                            strtotime($time),
                                                                        );
                                                                    @endphp
                                                                    {{ $time }}
                                                                </div>
                                                            </td>
                                                            @if (isset($user_array) && !empty($user_array))
                                                                @foreach ($user_array as $value)
                                                                    @php
                                                                        $assigned_data = [];
                                                                        if (
                                                                            isset(
                                                                                $schedule_arr[$value][$formattedTime],
                                                                            ) &&
                                                                            !empty(
                                                                                $schedule_arr[$value][$formattedTime]
                                                                            )
                                                                        ) {
                                                                            $assigned_data =
                                                                                $schedule_arr[$value][$formattedTime];
                                                                            // dd($assigned_data);
                                                                        }
                                                                    @endphp
                                                                    <td class="timeslot_td slot_refresh_jobs draggable-items"
                                                                        data-slot_time="{{ $timeString }}"
                                                                        data-technician-name="{{ $value }}"
                                                                        data-technician_id="{{ $value }}">
                                                                        @if (isset($assigned_data) && !empty($assigned_data))
                                                                            <div class="testclass">
                                                                                @foreach ($assigned_data as $value2)
                                                                                    {{-- for schedule type job  --}}
                                                                                    @if ($value2->schedule_type == 'job')
                                                                                        @php
                                                                                            $duration =
                                                                                                $value2->JobModel
                                                                                                    ->jobassignname
                                                                                                    ->duration ?? null;
                                                                                            $height_slot =
                                                                                                $duration / 30;
                                                                                            $height_slot_px =
                                                                                                $height_slot * 36 +
                                                                                                $height_slot -
                                                                                                1;
                                                                                            $overfloHeight =
                                                                                                $height_slot_px - 5;
                                                                                        @endphp
                                                                                        <a class="show_job_details"
                                                                                            href="{{ $value2->job_id ? route('tickets.show', $value2->job_id) : '#' }}">

                                                                                            <div class="dts mb-1  flexibleslot"
                                                                                                {{-- data-bs-toggle="modal" --}}
                                                                                                {{-- data-bs-target="#edit" --}}
                                                                                                data-id="{{ $value2->job_id }}"
                                                                                                data-duration="{{ $value2->JobModel->jobassignname->duration }}"
                                                                                                data-technician-name="{{ $value2->technician->name }}"
                                                                                                data-timezone-name="{{ $value2->technician->TimeZone->timezone_name }}"
                                                                                                data-time="{{ $timeString }}"
                                                                                                data-date="{{ $filterDate }}"
                                                                                                style="cursor: pointer; height: {{ $height_slot_px }}px; background: {{ $value2->JobModel->technician->color_code ?? null }};"
                                                                                                data-id="{{ $value2->job_id }}">
                                                                                                @if ($value2->JobModel && $value2->JobModel->is_confirmed == 'yes')
                                                                                                    <div
                                                                                                        class="cls_is_confirmed">
                                                                                                        <i
                                                                                                            class="ri-thumb-up-fill"></i>
                                                                                                    </div>
                                                                                                @endif
                                                                                                <div
                                                                                                    style="overflow:hidden;height: {{ $overfloHeight }}px;">
                                                                                                    <div
                                                                                                        class="cls_slot_title">
                                                                                                        <i
                                                                                                            class="ri-tools-line"></i>
                                                                                                        {{ $value2->JobModel->user->name ?? null }}
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="cls_slot_time">
                                                                                                        <i
                                                                                                            class="ri-truck-line"></i>
                                                                                                        {{ $timeString }}
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="cls_slot_job_card">
                                                                                                        {{ $value2->JobModel->job_title ?? null }}
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="cls_slot_job_card">
                                                                                                        {{ $value2->JobModel->city ?? null }},
                                                                                                        {{ $value2->JobModel->state ?? null }}
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="round bg-cyan2" style="color: #000; width: 24px; height: 24px; display: inline-block; font-weight: 400; text-align: center; border-radius: 100%; background: #dbdbdb; line-height: 24px; font-size: 10px !important;">
                                                                                                        @php
                                                                                                            $name =
                                                                                                                $value2
                                                                                                                    ->technician
                                                                                                                    ->name ??
                                                                                                                null;
                                                                                                            $initials =
                                                                                                                '';
                                                                                                            if ($name) {
                                                                                                                $names = explode(
                                                                                                                    ' ',
                                                                                                                    $name,
                                                                                                                );
                                                                                                                foreach (
                                                                                                                    $names
                                                                                                                    as $part
                                                                                                                ) {
                                                                                                                    $initials .= strtoupper(
                                                                                                                        substr(
                                                                                                                            $part,
                                                                                                                            0,
                                                                                                                            1,
                                                                                                                        ),
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
                                                                                                <h5><i
                                                                                                        class="fas fa-id-badge px-2"></i>
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
                                                                                                        $interval = session(
                                                                                                            'time_interval',
                                                                                                        ); // Retrieve the time interval from the session

                                                                                                        if (
                                                                                                            $startDateTime &&
                                                                                                            $endDateTime &&
                                                                                                            $interval
                                                                                                        ) {
                                                                                                            // Add the interval to both start and end times
                                                                                                            $startDateTime->addHours(
                                                                                                                $interval,
                                                                                                            );
                                                                                                            $endDateTime->addHours(
                                                                                                                $interval,
                                                                                                            );
                                                                                                        }
                                                                                                    @endphp

                                                                                                    @if ($startDateTime && $endDateTime)
                                                                                                        {{ $startDateTime->format('M d Y g:i a') }}
                                                                                                        -
                                                                                                        {{ $endDateTime->format('g:i A') }}
                                                                                                    @endif
                                                                                                </p>
                                                                                                <div class="py-1">
                                                                                                    <i
                                                                                                        class="fas fa-ticket-alt px-2"></i>
                                                                                                    <strong>{{ $value2->JobModel->job_title ?? null }}</strong>
                                                                                                </div>
                                                                                                <div class="py-1">
                                                                                                    <i
                                                                                                        class="fas fa-user px-2"></i>
                                                                                                    <strong>{{ $value2->JobModel->user->name ?? null }}</strong>
                                                                                                    <p
                                                                                                        class="ps-4 m-0 ms-2">
                                                                                                        {{ $value2->JobModel->addresscustomer->address_line1 ?? null }}
                                                                                                        {{ $value2->JobModel->addresscustomer->zipcode ?? null }}
                                                                                                    </p>
                                                                                                    <p
                                                                                                        class="ps-4 m-0 ms-2">
                                                                                                        {{ $value2->JobModel->user->mobile ?? null }}
                                                                                                    </p>
                                                                                                </div>
                                                                                                <div class="py-1"><i
                                                                                                        class="fas fa-user-secret px-2"></i>
                                                                                                    <strong>{{ $value2->JobModel->technician->name ?? null }}</strong>
                                                                                                    <div class="py-1"><i
                                                                                                            class="fas fa-tag px-2"></i>
                                                                                                        <span
                                                                                                            class="mb-1 badge bg-primary">{{ $value2->JobModel->status ?? null }}</span>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                    @endif

                                                                                    {{-- for schedule type evnt  --}}
                                                                                    @if ($value2->schedule_type == 'event')
                                                                                        @php

                                                                                            $to =
                                                                                                $value2->event
                                                                                                    ->start_date_time ??
                                                                                                null;
                                                                                            $from =
                                                                                                $value2->event
                                                                                                    ->end_date_time ??
                                                                                                null;

                                                                                            // Convert strings to DateTime objects
                                                                                            $toDateTime = new DateTime(
                                                                                                $to,
                                                                                            );
                                                                                            $fromDateTime = new DateTime(
                                                                                                $from,
                                                                                            );

                                                                                            // Calculate the difference in seconds
                                                                                            $interval = $toDateTime->diff(
                                                                                                $fromDateTime,
                                                                                            );

                                                                                            // Calculate duration in minutes
                                                                                            $duration =
                                                                                                $interval->h * 60 +
                                                                                                $interval->i;

                                                                                            $height_slot =
                                                                                                $duration / 30;
                                                                                            $height_slot_px =
                                                                                                $height_slot * 40.5 - 7;
                                                                                        @endphp


                                                                                        <div class="dts mb-1  flexibleslot"
                                                                                            {{-- data-bs-toggle="modal" --}}
                                                                                            {{-- data-bs-target="#edit" --}}
                                                                                            data-id="{{ $value }}"
                                                                                            data-time="{{ $timeString }}"
                                                                                            data-date="{{ $filterDate }}"
                                                                                            style="cursor: pointer; height: {{ $height_slot_px }}px; background: #dadad6;">
                                                                                            <h5
                                                                                                style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">
                                                                                                {{ $value2->event->event_name ?? null }}
                                                                                                &nbsp;&nbsp;
                                                                                            </h5>
                                                                                        </div>
                                                                                    @endif
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
                                                                                <a
                                                                                    href="{{ url('create_job', [$value, $timeString, $filterDate]) }}">
                                                                                    <div class="createSchedule align-items-sm-center d-flex gap-3 fw-semibold"
                                                                                        style="cursor: pointer;"
                                                                                        data-id="{{ $value }}"
                                                                                        data-time="{{ $timeString }}"
                                                                                        data-date="{{ $filterDate }}">
                                                                                        <i class="fa fa-plus-square"></i>
                                                                                        <span>Job</span>
                                                                                    </div>
                                                                                </a>
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





        </div>

    </div>
    <!-- Modal -->

    <!-- Modal -->
    <div class="modal fade" id="event" tabindex="-1" aria-labelledby="scroll-long-inner-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable2 modal-dialog modal-xl">
            <form action="{{ url('store/event/') }}" method="POST" id="addEvent">
                <input type="hidden" name="event_technician_id" id="event_technician_id" value="">
                <input type="hidden" name="scheduleType" id="scheduleType" value="event">
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
                        @include('schedule.event')

                    </div>

                </div>
            </form>
        </div>
    </div>
    <!-- Modal -->
    @include('schedule.indexScript')

@endsection
