@extends('home')
@section('content')
    <link rel="stylesheet" href="{{ url('public/admin/schedule/style.css') }}">

    <div class="page-wrapper p-0 ms-2" style="display:flex;">
        <!-- Container fluid  -->
        <!-- -------------------------------------------------------------- -->

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
                                                style=" margin-right: 10px;font-size: 13px;color: #ee9d01;font-weight: bold;"><i
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
                                                                                                $height_slot * 80 - 10;
                                                                                            // dd($height_slot_px);
                                                                                        @endphp
                                                                                        <a
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
                                                                                                <h5
                                                                                                    style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">
                                                                                                    {{ $value2->JobModel->user->name ?? null }}
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
                                                                                        </a>
                                                                                    @endif

                                                                                    {{-- for schedule type evnt  --}}
                                                                                    @if ($value2->schedule_type == 'event')
                                                                                        @php

                                                                                           $to = $value2->event->start_date_time ?? null;
                                                                                            $from = $value2->event->end_date_time ?? null;

                                                                                            // Convert strings to DateTime objects
                                                                                            $toDateTime = new DateTime($to);
                                                                                            $fromDateTime = new DateTime($from);

                                                                                            // Calculate the difference in seconds
                                                                                            $interval = $toDateTime->diff($fromDateTime);

                                                                                            // Calculate duration in minutes
                                                                                            $duration = $interval->h * 60 + $interval->i;

                                                                                            $height_slot =
                                                                                                $duration / 30;
                                                                                            $height_slot_px =
                                                                                                $height_slot * 80 - 10;
                                                                                        @endphp
                                                                                        

                                                                                            <div class="dts mb-1  flexibleslot"
                                                                                                {{-- data-bs-toggle="modal" --}}
                                                                                                {{-- data-bs-target="#edit" --}}
                                                                                                data-id="{{ $value }}"
                                                                                                data-time="{{ $timeString }}"
                                                                                                data-date="{{ $filterDate }}"
                                                                                                style="cursor: pointer; height: {{ $height_slot_px }}px; background: {{ $value2->technician->color_code ?? null }};">
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

    <script>
        $(document).ready(function() {
            // Use setTimeout to wait a short period after the document is ready


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


            $('.show_total_discount').text(getDiscount >= 0 ? '$' + getDiscount : '$' + Math.abs(getDiscount));
            $('.show_total_tax').text(getTax >= 0 ? '$' + getTax : '$' + Math.abs(getTax));
            $('.show_total').text(getTotal >= 0 ? '$' + getTotal : '$' + Math.abs(getTotal));

        }

        //   new changes  
        // on clicking on pendingJobs 

        $(document).on('click', '.pending_jobs2', function() {

            var job_id = $(this).attr('data-id');
            var address = $(this).attr('data-address');
            var customerId = $(this).data('customer-id');

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
                    $('.subtotal').val(Math.abs(subTotal));
                    $('.subtotaltext').text('$' + Math.abs(subTotal));

                    var getDiscount = $('.discount').val().trim();
                    var discount = parseInt(getDiscount) - parseInt(service_discount);
                    $('.discount').val(Math.abs(discount));
                    $('.discounttext').text('$' + Math.abs(discount));

                    var getTotal = $('.total').val().trim();
                    var total = parseInt(getTotal) - parseInt(service_cost) + parseInt(
                        service_discount) - parseInt(
                        service_tax);
                    $('.total').val(Math.abs(total));
                    $('.totaltext').text('$' + Math.abs(total));

                    // product 

                    var product_cost = $('.product_cost').val();

                    var product_discount = $('.product_discount').val();

                    var product_tax = $('.product_tax').val();

                    var getSubTotalVal = $('.subtotal').val().trim();
                    var subTotal = parseInt(getSubTotalVal) - parseInt(product_cost);
                    $('.subtotal').val(Math.abs(subTotal));
                    $('.subtotaltext').text('$' + Math.abs(subTotal));

                    var getDiscount = $('.discount').val().trim();
                    var discount = parseInt(getDiscount) - parseInt(product_discount);
                    $('.discount').val(Math.abs(discount));
                    $('.discounttext').text('$' + Math.abs(discount));

                    var getTotal = $('.total').val().trim();
                    var total = parseInt(getTotal) - parseInt(product_cost) + parseInt(
                        product_discount) - parseInt(
                        product_tax);
                    $('.total').val(Math.abs(total));

                    $('.totaltext').text('$' + Math.abs(total));

                    // customer detail step 4 
                    $('.customer_number_email').text(data.user.mobile + ' / ' + data.user.email);
                    $('.show_customer_name').text(data.user.name);
                    $('.show_customer_area').text(data.city + ' Area');
                    $('.c_address').text(data.address_type + ': ' + data.address + ' ' + data.city +
                        ' ' + data.state + ' ' + data.zipcode);

                    $.ajax({
                        url: "get/usertax",
                        data: {
                            customerId: customerId,
                        },
                        type: 'GET',
                        success: function(data) {
                            $('.taxcodetext').empty();

                            $('.taxcodetext').append('' + data.state_tax + '% for ' + data
                                .state_code + '');
                        },
                    });

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
                            // Check if navigating forward to the next step
                            if (newIndex > currentIndex) {
                                // Assuming the user address step index is 3 (adjust if necessary)
                                if (currentIndex === 0) {
                                    // Check if user address is selected
                                    var selectedAddress = $('.customer_address').val();
                                    if (!selectedAddress) {
                                        // User address is not selected, prevent navigation
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: 'Please select a user address before proceeding.'
                                        });
                                        return false; // Prevent navigation to the next step
                                    }
                                } else if (currentIndex === 1) {
                                    // Check if all required fields are filled for step 2
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
                                } else if (currentIndex === 2) {
                                    // Check if all required fields are filled for step 3
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
                            }
                            if (newIndex < currentIndex) {
                                return true;
                            }

                            if (newIndex == 3) {
                                showAllInformation(newIndex);
                            }

                            // Allow navigation to the previous step or to step 3
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

                                        // Hide elements initially

                                        var newyork = $('#newyork');
                                        var techAll = $('#techall');

                                        // Initial check if 'newyork' checkbox is checked
                                        if (newyork.prop('checked')) {
                                            $('.pending_jobs2').addClass('d-none');
                                        }

                                        // Function to toggle the order of elements
                                        function toggleOrder() {
                                            var ascendingOrder =
                                                true; // Flag to track sorting order
                                            // Get the list of .pending_jobs2 elements
                                            var $pendingJobs = $('.pending_jobs2');
                                            // Toggle the sorting order flag
                                            ascendingOrder = !ascendingOrder;

                                            // Sort the elements based on their positions in the DOM
                                            $pendingJobs.sort(function(a, b) {
                                                if (ascendingOrder) {
                                                    return $(a).index() - $(
                                                        b).index();
                                                } else {
                                                    return $(b).index() - $(
                                                        a).index();
                                                }
                                            });

                                            // Append the sorted elements to their parent
                                            $pendingJobs.appendTo($pendingJobs
                                                .parent());
                                        }

                                        // Event handler for clicking on #makedescending
                                        $('#makedescending').on('click',
                                            toggleOrder);

                                        // Iterate over each .pending_jobs2 element
                                        $('.pending_jobs2').each(function() {
                                            var $element = $(
                                                this
                                            ); // Store reference to the element
                                            var technicianId = $element
                                                .data('technician-id');
                                            $.ajax({
                                                method: 'get',
                                                url: "get/userstate",
                                                data: {
                                                    technicianId: technicianId
                                                },
                                                success: function(
                                                    values) {
                                                    var code =
                                                        values
                                                        .state_code;

                                                    // Function to update visibility based on checkboxes
                                                    function updateVisibility() {
                                                        if (newyork
                                                            .prop(
                                                                'checked'
                                                            ) &&
                                                            code ===
                                                            'NY'
                                                        ) {
                                                            $element
                                                                .removeClass(
                                                                    'd-none'
                                                                );
                                                        } else if (
                                                            techAll
                                                            .prop(
                                                                'checked'
                                                            )
                                                        ) {
                                                            $element
                                                                .removeClass(
                                                                    'd-none'
                                                                );
                                                        } else {
                                                            $element
                                                                .addClass(
                                                                    'd-none'
                                                                );
                                                        }
                                                    }

                                                    // Update visibility initially
                                                    updateVisibility
                                                        ();

                                                    // Event handler for 'newyork' checkbox change
                                                    $('#newyork')
                                                        .change(
                                                            function() {
                                                                updateVisibility
                                                                    ();
                                                            });

                                                    // Event handler for 'techall' checkbox change
                                                    $('#techall')
                                                        .change(
                                                            function() {
                                                                updateVisibility
                                                                    ();
                                                            });
                                                },
                                                error: function(xhr,
                                                    status,
                                                    error) {
                                                    console.log(
                                                        'Error occurred during AJAX request:',
                                                        error
                                                    );
                                                }
                                            });
                                        });


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
            var customerId = $('.selectCustomer').data('customer-id');
            console.log(customerId);

            var id = $(this).val().trim();

            if ($('.pre_service_id').val() != '') {

                var service_cost = $('.service_cost').val();

                var service_discount = $('.service_discount').val();

                var service_tax = $('.service_tax').val();

                var getSubTotalVal = $('.subtotal').val().trim();
                var subTotal = parseInt(getSubTotalVal) - parseInt(service_cost);
                $('.subtotal').val(Math.abs(subTotal));
                $('.subtotaltext').text('$' + Math.abs(subTotal));

                var getDiscount = $('.discount').val().trim();
                var discount = parseInt(getDiscount) - parseInt(service_discount);
                $('.discount').val(Math.abs(discount));
                $('.discounttext').text('$' + Math.abs(discount));

                var getTotal = $('.total').val().trim();
                var total = parseInt(getTotal) - parseInt(service_cost) + parseInt(service_discount) - parseInt(
                    service_tax);
                $('.total').val(Math.abs(total));
                $('.totaltext').text('$' + Math.abs(total));

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
                            $('.subtotal').val(Math.abs(subTotal));
                            $('.subtotaltext').text('$' + Math.abs(subTotal));

                            var getDiscount = $('.discount').val().trim();
                            var discount = parseInt(getDiscount) + parseInt(data.service_discount);
                            $('.discount').val(Math.abs(discount));
                            $('.discounttext').text('$' + Math.abs(discount));

                            var getTotal = $('.total').val().trim();
                            var total = parseInt(getTotal) + parseInt(data.service_cost) - parseInt(data
                                .service_discount) + parseInt(data.service_tax);
                            $('.total').val(Math.abs(total));
                            $('.totaltext').text('$' + Math.abs(total));

                        }

                    }
                });
            }

            $.ajax({
                url: "get/usertax",
                data: {
                    customerId: customerId,
                },
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    $('.taxcodetext').empty();
                    console.log(data);
                    $('.taxcodetext').append('' + data.state_tax + '% for ' + data.state_code + '');
                },
            });

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
            var customerId = $('.selectCustomer').data('customer-id');

            var id = $(this).val().trim();

            if ($('.pre_product_id').val() != '') {

                var product_cost = $('.product_cost').val();

                var product_discount = $('.product_discount').val();

                var product_tax = $('.product_tax').val();

                var getSubTotalVal = $('.subtotal').val().trim();
                var subTotal = parseInt(getSubTotalVal) - parseInt(product_cost);
                $('.subtotal').val(Math.abs(subTotal));
                $('.subtotaltext').text('$' + Math.abs(subTotal));

                var getDiscount = $('.discount').val().trim();
                var discount = parseInt(getDiscount) - parseInt(product_discount);
                $('.discount').val(Math.abs(discount));
                $('.discounttext').text('$' + Math.abs(discount));

                var getTotal = $('.total').val().trim();
                var total = parseInt(getTotal) - parseInt(product_cost) + parseInt(product_discount) - parseInt(
                    product_tax);
                $('.total').val(Math.abs(total));
                $('.totaltext').text('$' + Math.abs(total));

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
                            $('.subtotal').val(Math.abs(subTotal));
                            $('.subtotaltext').text('$' + Math.abs(subTotal));

                            var getDiscount = $('.discount').val().trim();
                            var discount = parseInt(getDiscount) + parseInt(data.discount);
                            $('.discount').val(Math.abs(discount));
                            $('.discounttext').text('$' + Math.abs(discount));

                            var getTotal = $('.total').val().trim();
                            var total = parseInt(getTotal) + parseInt(data.base_price) -
                                parseInt(data.discount) + parseInt(data.tax);
                            $('.total').val(Math.abs(total));
                            $('.totaltext').text('$' + Math.abs(total));

                        }

                    }
                });
            }
            $.ajax({
                url: "get/usertax",
                data: {
                    customerId: customerId,
                },
                type: 'GET',
                success: function(data) {
                    $('.taxcodetext').empty();

                    $('.taxcodetext').append('' + data.state_tax + '% for ' + data.state_code + '');
                },
            });

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
