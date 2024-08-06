@extends('home')

@section('content')
    <style>
        .day div {
            cursor: move;
        }

        .schedule-container {
            display: flex;
            flex-direction: column;
            width: fit-content;
        }

        .header-row,
        .time-row {
            display: flex;
        }

        .timeslot {

            height: 40px;
        }

        .tech-header,
        .timeslot {
            width: 100px;
            padding: 5px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
        }

        .time-slot {
            display: flex;
            flex-direction: column;
            height: 490px;
            overflow-y: scroll;
        }

        .time-slot div {
            text-align: center;
        }

        .tech-profile-img {
            width: 48px;
            border-radius: 50%;
        }

        .tech-name {
            display: block;
            margin-top: 5px;
        }

        .ui-draggable-dragging {
            z-index: 1000 !important;
            /* Ensure the helper has a high z-index during dragging */
        }
    </style>
    <h4 class="fc-toolbar-title px-4 pt-2 mt-3" id="fc-dom-1">{{ $formattedDate }}</h4>


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
            <!-- Repeat the above div for each technician -->
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

                            // Debug: Output timeSlot and start_date_time format
                            $technicianSchedules = $schedules->filter(function ($schedule) use ($item, $timeSlot) {
                                $scheduleTime = Carbon\Carbon::parse($schedule->start_date_time)->format('H:i');

                                return $schedule->technician_id == $item->id && $scheduleTime == $timeSlot;
                            });
                        @endphp

                        @php
                            $groupedJobs = collect($technicianSchedules)->groupBy('start_date_time');
                            $timeString = formatTime($hour, $minute);
                        @endphp
                        <div class="timeslot p-0 day clickPoint1" data-date="{{ $formattedDate }}" 
                            data-slot-time="{{ formatTime($hour, $minute) }}" data-technician-name="{{ $item->name }}"
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
                                            data-duration="{{ $job->JobModel->jobassignname->duration }}">

                                            <a class="show_job_details text-white"
                                                href="{{ $job->job_id ? route('tickets.show', $job->job_id) : '#' }}"
                                                style="width: {{ $jobWidth }}px;">
                                                <div class="mb-1" data-id="{{ $job->job_id }}"
                                                    data-duration="{{ $job->JobModel->jobassignname->duration }}"
                                                    data-technician-name="{{ $job->technician->name }}"
                                                    data-timezone-name="{{ $job->technician->TimeZone->timezone_name }}"
                                                    data-time="{{ $timeString }}" data-date="{{ $formattedDate }}"
                                                    style="max-width: {{ $jobWidth }}%;cursor: pointer;">
                                                    @if ($job->JobModel && $job->JobModel->is_confirmed == 'yes')
                                                        <div class="cls_is_confirmed">
                                                            <i class="ri-thumb-up-fill"></i>
                                                        </div>
                                                    @endif
                                                    <div style="height: {{ $overflow_height }}px;overflow:hidden;">
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
                                                                        $initials .= strtoupper(substr($part, 0, 1));
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

                                            $to = $job->event->start_date_time ?? null;
                                            $from = $job->event->end_date_time ?? null;

                                            if ($to && $from) {
                                                $toDateTime = Carbon\Carbon::parse($to);
                                                $fromDateTime = Carbon\Carbon::parse($from);
                                                $durationInMinutes = $toDateTime->diffInMinutes($fromDateTime);

                                                $height_slot = $durationInMinutes / 30;
                                                $height_slot_px = $height_slot * 40.5 - 7;
                                            } else {
                                                $height_slot_px = 0; // Default value in case $to or $from is null
                                            }
                                        @endphp

                                        <div class="mb-1" data-id="{{ $job->job_id }}"
                                            data-time="{{ formatTime($hour, $minute) }}"
                                            data-date="{{ $formattedDate }}"
                                            style="cursor: pointer; height: {{ $height_slot_px }}px; background: #dadad6; width: {{ $jobWidth }}px;">
                                            <h5
                                                style="font-size: 15px; padding-bottom: 0px; margin-bottom: 5px; margin-top: 3px;">
                                                {{ $job->event->event_name ?? null }}
                                            </h5>
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
                    <!-- Repeat the above div for each technician's time slot -->
                </div>
            @endfor
        @endfor
        <!-- Repeat the above div for each time slot -->
    </div>

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
        <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://unpkg.com/tippy.js@6.3.1/dist/tippy.css" />
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>

        <script>
            $(function() {
                $(".day").sortable({
                    connectWith: ".day",
                    cursor: "move",
                    helper: "clone",
                    items: "> .dragDiv",
                    stop: function(event, ui) {
                        var $item = ui.item;
                        var eventLabel = $item.text();
                        var newDay = $item.parent().attr("id");

                        console.log($item[0].id, eventLabel, newDay);

                        // Here's where am ajax call will go

                    }
                }).disableSelection();
            });

            $(document).ready(function() {

                var isDragging = false;
                var isResizing = false;

                // Initialize draggable elements
                $('.day .dragDiv').draggable({
                    helper: 'clone',
                    cursor: 'move',
                    start: function(event, ui) {
                        if (isResizing) {
                            return false; // Prevent dragging if resizing
                        }
                        isDragging = true;
                    },
                    stop: function(event, ui) {
                        isDragging = false;
                    }
                });

                // Initialize droppable elements
                $('.day').droppable({
                    tolerance: 'pointer',
                    drop: function(event, ui) {
                        var jobId = ui.draggable.attr('id');
                        var duration = ui.draggable.attr('data-duration');
                        var newTechnicianId = $(this).data('technician-id');
                        var date = $(this).data('date');
                        var time = $(this).data('slot-time');

                        var height_slot = duration ? (duration / 30) * 40 : 0; // Calculate height in pixels

                        // Perform the AJAX request to update the technician_id for the job
                        $.ajax({
                            url: '{{ route('updateJobTechnician') }}', // Replace with your actual endpoint URL
                            method: 'POST',
                            data: {
                                job_id: jobId,
                                duration: duration,
                                date: date,
                                time: time,
                                technician_id: newTechnicianId,
                                _token: '{{ csrf_token() }}' // CSRF token for Laravel
                            },
                            success: function(response) {
                                console.log('Job updated successfully:', response);
                            },
                            error: function(xhr) {
                                console.error('Failed to update job:', xhr.responseText);
                            }
                        });

                        ui.draggable.remove(); // Remove the dragged element from its original position
                        // Calculate the number of jobs in the target container
                        var jobCount = $(this).children('.dts').length + 1; // Including the dropped job
                        var jobWidth = 100 / jobCount;

                        // Set the width of existing jobs
                        $(this).children('.dts').each(function() {
                            $(this).css('width', jobWidth + 'px');
                        });

                        // Append the new job with the calculated width
                        var newJobElement = $('<div id="' + jobId +
                            '" class="dts dragDiv stretchJob border" style="height:' + height_slot +
                            'px; position: relative; width:' + jobWidth + 'px;" data-duration="' +
                            duration + '">' + ui.draggable.html() + '</div>');

                        $(this).append(newJobElement);

                        // Make the new job element draggable
                        newJobElement.draggable({
                            helper: 'clone',
                            cursor: 'move',
                            start: function(event, ui) {
                                if (isResizing) {
                                    return false; // Prevent dragging if resizing
                                }
                                isDragging = true;
                            },
                            stop: function(event, ui) {
                                isDragging = false;
                            }
                        });

                        // Update the width of the original container if any jobs remain
                        var originalContainer = ui.draggable.parent();
                        var originalJobCount = originalContainer.children('.dts').length;

                        if (originalJobCount > 0) {
                            var originalJobWidth = 100 / originalJobCount;
                            originalContainer.children('.dts').each(function() {
                                $(this).css('width', originalJobWidth + 'px');
                            });
                        }
                    }
                });

                // Prevent tooltip from showing while dragging
                $(document).on('mouseenter', '.stretchJob', function() {
                    if (!isDragging) {
                        var template = $(this).find('.template');
                        if (!this._tippy) {
                            tippy(this, {
                                content: template.html(),
                                allowHTML: true,
                            });
                        }
                    }
                });

                // Initialize resizable elements with interact.js
                interact('.stretchJob').resizable({
                        edges: {
                            left: false,
                            right: false,
                            bottom: true,
                            top: false
                        }
                    })
                    .on('resizestart', function(event) {
                        // Disable dragging while resizing
                        isResizing = true;

                        // Set original height if not already set
                        if (!event.target.dataset.originalHeight) {
                            event.target.dataset.originalHeight = event.target.style.height;
                        }
                    })
                    .on('resizemove', function(event) {
                        let target = event.target;
                        let originalHeight = parseFloat(target.dataset.originalHeight) || parseFloat(target.style
                            .height) || 0;
                        let heightChange = event.rect.height - originalHeight;

                        // Update the height directly with the cursor movement
                        let newHeight = originalHeight + heightChange;

                        // Set a minimum height to prevent collapsing too much
                        let minHeight = 40; // Equivalent to 30 minutes
                        if (newHeight < minHeight) {
                            newHeight = minHeight;
                        }

                        // Update the element's height
                        target.style.height = `${newHeight}px`;

                        // Calculate and update the new duration
                        let heightPer30Min = 40; // height for 30 minutes
                        let newDuration = Math.round(newHeight / heightPer30Min) * 30;
                        target.dataset.duration = newDuration;
                    })
                    .on('resizeend', function(event) {
                        // Re-enable dragging after resizing
                        isResizing = false;

                        // Get the updated duration
                        let newDuration = parseInt(event.target.dataset.duration);

                        // Get the job ID
                        let jobId = event.target.id; // Assuming the element's ID is the job ID

                        // AJAX request to update duration in database
                        updateDurationInDatabase(jobId, newDuration, event.target);
                    });

                function updateDurationInDatabase(jobId, newDuration, target) {
                    Swal.fire({
                        title: 'Do you want to change time?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // AJAX POST request to your Laravel endpoint
                            $.ajax({
                                url: "{{ route('schedule.update_job_duration') }}",
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token if using Laravel CSRF protection
                                },
                                data: {
                                    duration: newDuration,
                                    jobId: jobId
                                },
                                success: function(response) {
                                    console.log(response);
                                    // Handle success if needed
                                    Swal.fire('Success', 'Duration updated successfully', 'success')
                                        .then(() => {});
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error updating duration:', error);
                                    // Handle error if needed
                                    Swal.fire('Error',
                                        'Failed to update duration. Please try again.', 'error');
                                }
                            });
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            // Reset the height to the original height
                            let originalHeight = parseFloat(target.dataset.originalHeight);
                            console.log('Reverting to Original Height:', originalHeight);
                            target.style.height = `${originalHeight}px`;
                        }
                    });
                }

                $(document).on('click', '.clickPoint1', function(e) {
                    if (!isResizing) {
                        e.stopPropagation();
                        var popupDiv = $(this).find('.popupDiv1');

                        // Hide any previously displayed popupDiv elements
                        $('.popupDiv1').not(popupDiv).hide();

                        // Calculate the position of the popupDiv based on the clicked point
                        var mouseX = e.pageX - 180;
                        var mouseY = e.pageY - 100;

                        // Get the dimensions of the popupDiv and the window
                        var popupWidth = popupDiv.outerWidth();
                        var popupHeight = popupDiv.outerHeight();
                        var windowWidth = $(window).width();
                        var windowHeight = $(window).height();

                        // Calculate the position for the popupDiv, ensuring it stays within the window
                        var topPosition = mouseY;
                        var leftPosition = mouseX;

                        // Adjust the position if the popupDiv overflows the window
                        if (topPosition + popupHeight > windowHeight) {
                            topPosition = windowHeight - popupHeight - 10; // Add a margin of 10px
                        }
                        if (leftPosition + popupWidth > windowWidth) {
                            leftPosition = windowWidth - popupWidth - 10; // Add a margin of 10px
                        }

                        // Set the position and show the popupDiv
                        popupDiv.css({
                            position: 'absolute',
                            top: topPosition + 'px',
                            left: leftPosition + 'px',
                            zIndex: 1000 // Ensure the popupDiv is above other elements
                        }).toggle();
                    }
                });

                // Hide the popup div when clicking outside of it
                $(document).click(function() {
                    $('.popupDiv').hide();
                });


                $('.eventSchedule').on('click', function() {
                    var id = $(this).attr('data-id');
                    console.log(id);
                    $('#event_technician_id').val(id);
                });

                $('.event_start_time').hide();
                $('.event_end_time').hide();
                $('.f_start').hide();
                $('.s_to').hide();

                $(document).on('change', '.event_type', function() {
                    var event_type = $(this).val();
                    if (event_type == 'full') {
                        $('.event_start_date').show();
                        $('.event_end_date').show();
                        $('.event_start_time').hide();
                        $('.event_end_time').hide();
                        $('.f_start').hide();
                        $('.s_to').hide();
                    } else {
                        $('.event_start_date').show();
                        $('.event_start_time').show();
                        $('.event_end_date').hide();
                        $('.event_end_time').show();
                        $('.f_start').show();
                        $('.s_to').show();
                    }
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

               $(document).on('change', '.technician_check', function() {
                    var isChecked = $(this).prop('checked');
                    var id = $(this).data('id'); // Retrieve the value of the data-id attribute

                    if (isChecked) {
                        // Show elements with class tech-header and day that match the id
                        $('.tech-header[data-tech-id="' + id + '"]').show();
                        $('.clickPoint1[data-technician-id="' + id + '"]').show();
                    } else {
                        // Hide elements with class tech-header and day that match the id
                        $('.tech-header[data-tech-id="' + id + '"]').hide();
                        $('.clickPoint1[data-technician-id="' + id + '"]').hide();
                    }
                });



            });
        </script>
    @endsection

@endsection
