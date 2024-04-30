@extends('home')
@section('content')
    <link rel="stylesheet" href="{{ url('public/admin/schedule/style.css') }}">

    <style>
        .dts2 {
            min-height: 36px;
        }

        .timeslot_td_time {
            padding: 0px;
            font-weight: 600;
            font-size: 10px;
            letter-spacing: 0.5px;
        }
 

        .container-schedule {
            padding: 0px !important;
        }

        .schedule_section_box {
            overflow-x: scroll;
        }

        .popupContainer {
            position: absolute;
            z-index: 999;
            background-color: #444445;
            color: white;
            border: 1px solid #cccccc;
            padding: 10px;
            display: none;
        }

        .popup-option {
            display: block;
            margin-bottom: 5px;
            color: white;
            font-family: 'Font Awesome 5 Free';
            font-size: 12px;
            text-decoration: none;
        }

        .tech_th {
            position: relative;
        }

        .user_head_link {
            color: inherit;
            text-decoration: none;
        }

        .user_head_link img {
            width: 48px;
            border-radius: 50%;
            margin-bottom: 5px;
        }

        .popupContainer::after {
            content: "";
            position: absolute;
            bottom: 100%;
            /* Change top to bottom */
            left: 50%;
            margin-left: -5px;
            border-width: 6px;
            border-style: solid;
            border-color: transparent transparent #444445 transparent;
            /* Change border-color */
        }

        .smscontainer {
            position: absolute;
            z-index: 999;
            background-color: #444445;
            color: white;
            border: 1px solid #cccccc;
            padding: 10px;
            display: none;
            width: 300px;
        }

        .smscontainer::after {
            content: "";
            position: absolute;
            top: 50%;
            /* Adjust top position as needed */
            right: 100%;
            /* Change left to right */
            margin-top: -5px;
            /* Adjust margin-top as needed */
            border-width: 6px;
            border-style: solid;
            border-color: transparent #444445 transparent transparent;
            /* Change border-color */
        }
        .settingcontainer {
            position: absolute;
            z-index: 999;
            background-color: #444445;
            color: white;
            border: 1px solid #cccccc;
            padding: 10px;
            display: none;
        }

        .settingcontainer::after {
            content: "";
            position: absolute;
            top: 50%;
            /* Adjust top position as needed */
            right: 100%;
            /* Change left to right */
            margin-top: -5px;
            /* Adjust margin-top as needed */
            border-width: 6px;
            border-style: solid;
            border-color: transparent #444445 transparent transparent;
            /* Change border-color */
        }

        /* Add more styles as needed */
    </style>

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
 											<div class="cal_title_left text-start"><a href="schedule?date={{ $previousDate }}"><i class="fas fa-arrow-left"></i></a></div>
                                            <div class="cal_title_center text-center"><h4 class="fc-toolbar-title" id="fc-dom-1">{{ $formattedDate }}</h4></div>
											<div class="cal_title_right text-end"><a href="schedule?date={{ $tomorrowDate }}"><i class="fas fa-arrow-right"></i></a></div>
                                        </div>
										<div class="col-md-4"></div>
										<div class="col-md-4 text-start">
                                            <a id="selectDates" style="margin-right: 10px; font-size: 13px;cursor: pointer;"><i
                                                    class="fas fa-calendar-alt"></i>Select Dates</a>

                                            <a href="schedule?date={{ $TodayDate }}"
                                                style=" margin-right: 10px;font-size: 13px;color: #ee9d01;font-weight: bold;"><i
                                                    class="fas fa-calendar-check"></i> Today</a>
                                        </div>
                                        
                                    </div>
                                    <div class="dat schedule_section_box">
                                        <table id="demo-foo-addrow"
                                            class="table table-bordered m-t-30 table-hover contact-list text-nowrap"
                                            data-paging="true" data-paging-size="7" style="width: max-content;">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    @if (isset($user_array) && !empty($user_array))
                                                        @foreach ($user_array as $value)
                                                            <th class="tech_th" data-tech-id="{{ $value }}" style="width:100px">
                                                                <a href="#" class="link user_head_link tech_profile"
                                                                    style="color: {{ $user_data_array[$value]['color_code'] }} !important;">
                                                                    @if (isset($user_data_array[$value]['user_image']) && !empty($user_data_array[$value]['user_image']))
                                                                        <img src="{{ asset('public/images/technician/' . $user_data_array[$value]['user_image']) }}"
                                                                            alt="user" width="48"
                                                                            class="rounded-circle tech_profile" onerror="this.onerror=null; this.src='{{ $defaultImage }}';" /><br>
                                                                    @else
                                                                        <img src="{{ $defaultImage }}"
                                                                            alt="user" width="48"
                                                                            class="rounded-circle tech_profile" /><br>
                                                                    @endif
                                                                     
                                                                   @if (isset($user_data_array[$value]) && !empty($user_data_array[$value]))
                                                                        @php
                                                                            $name = $user_data_array[$value]['name'];
                                                                            $nameParts = explode(' ', $name);
                                                                            $firstName = $nameParts[0];
                                                                            $lastInitial = count($nameParts) > 1 ? strtoupper($nameParts[count($nameParts) - 1][0]) : '';
                                                                            $formattedName = $firstName . ' ' . $lastInitial;
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
																	<div style="width:150px; height:150px;">
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
                                                @for ($i = $start_time; $i <= $end_time; $i++)
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
                                                                    <td class="timeslot_td"
                                                                        data-slot_time="{{ $timeString }}"
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
                                                                                        @endphp
                                                                                        <a class="show_job_details"
                                                                                            href="{{ $value2->job_id ? route('tickets.show', $value2->job_id) : '#' }}">

                                                                                            <div class="dts mb-1  flexibleslot"
                                                                                                {{-- data-bs-toggle="modal" --}}
                                                                                                {{-- data-bs-target="#edit" --}}
                                                                                                data-id="{{ $value }}"
                                                                                                data-job-id="{{ $value2->job_id }}"
                                                                                                data-time="{{ $timeString }}"
                                                                                                data-date="{{ $filterDate }}"
                                                                                                style="cursor: pointer; height: {{ $height_slot_px }}px; background: {{ $value2->JobModel->technician->color_code ?? null }};"
                                                                                                data-id="{{ $value2->job_id }}">
																								@if($value2->JobModel && $value2->JobModel->is_confirmed == 'yes')
                                                                                                    
																								<div class="cls_is_confirmed">
																									<i class="ri-thumb-up-fill"></i>
                                                                                                </div>
																								
                                                                                                @endif
                                                                                                 <div class="cls_slot_title">
                                                                                                    <i class="ri-tools-line"></i>
                                                                                                    {{ $value2->JobModel->user->name ?? null }}
                                                                                                </div>
                                                                                                <div class="cls_slot_time"><i class="ri-truck-line"></i> {{ $timeString }}
                                                                                                </div>
                                                                                                <div class="cls_slot_job_card">{{ $value2->JobModel->job_title ?? null }}
                                                                                                </div>
                                                                                                <div class="cls_slot_job_card">
                                                                                                    {{ $value2->JobModel->city ?? null }},
                                                                                                    {{ $value2->JobModel->state ?? null }}
                                                                                                </div>
																								
																								
                                                                                            </div>
                                                                                        </a>
                                                                                        <div
                                                                                            class="open_job_details rounded shadow py-3 px-2" style="background: {{ $value2->JobModel->technician->color_code ?? null }};">
                                                                                            <div class="popup-content">
                                                                                                <h5><i class="fas fa-id-badge px-2"></i>
                                                                                                    <strong>Job
                                                                                                        #{{ $value2->JobModel->job_code ?? null }}</strong>
                                                                                                </h5>
                                                                                                <p class="ps-4 m-0 ms-2">
                                                                                                    {{ $value2->start_date_time ? date('M d Y g:i a', strtotime($value2->start_date_time)) : null }}
                                                                                                    -
                                                                                                    {{ \Carbon\Carbon::parse($value2->end_date_time)->format('g:i A') }}
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
                                                                                                <div class="py-1"><i class="fas fa-user-secret px-2"></i>
                                                                                                    <strong>{{ $value2->JobModel->technician->name ?? null }}</strong>
                                                                                                    <div class="py-1"><i class="fas fa-tag px-2"></i>
                                                                                                        <span class="mb-1 badge bg-primary">{{ $value2->JobModel->status ?? null }}</span>
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
                                                                                            <p style="font-size: 11px;">
                                                                                                <i
                                                                                                    class="fas fa-clock"></i>
                                                                                                {{ $timeString }} --
                                                                                                {{ $value2->JobModel->job_code ?? null }}<br>{{ $value2->JobModel->job_title ?? null }}
                                                                                            </p>
                                                                                            <p style="font-size: 12px;">
                                                                                                {{ $value2->JobModel->city ?? null }},
                                                                                                {{ $value2->JobModel->state ?? null }}
                                                                                            </p>
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

                            @include('schedule.new_customer')

                        </div>

                    </div>



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



@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script src="{{ url('public/admin/schedule/script.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('.event_start_date').hide();
            $('.event_start_time').hide();
            $('.event_end_date').hide();
            $('.event_end_time').hide();

            $(document).on('change', '.event_type', function() {
                 var event_type = $(this).val();
                if (event_type == 'full') {
                    $('.event_start_date').show();
                    $('.event_end_date').show();
                    $('.event_start_time').hide();
                    $('.event_end_time').hide();
                }else{
                 $('.event_start_date').show();
                $('.event_start_time').show();
                $('.event_end_date').hide();
                $('.event_end_time').show();
                }
            });
        });

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

        $('#cancelBtn').on('click', function() {

            $('#newCustomer').modal('hide');
            $('#create').modal('show');

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


    {{-- this for the schedule page --}}
    <script>
        $(document).ready(function() {

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
    {{-- end this  --}}



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
