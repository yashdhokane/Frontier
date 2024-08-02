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
        }

        .time-slot {
            display: flex;
            flex-direction: column;
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
                <div class="tech-header" style="color: #123456;">
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
                        @endphp
                        <div class="timeslot p-0 day d-flex" data-date="{{ $formattedDate }}"
                            data-slot-time="{{ formatTime($hour, $minute) }}" data-technician-name="{{ $item->name }}"
                            data-technician-id="{{ $item->id }}">

                            @foreach ($groupedJobs as $key2 => $jobs)
                                @php
                                    $jobCount = count($jobs);
                                    $jobWidth = 100 / $jobCount;
                                @endphp
                                @foreach ($jobs as $job)
                                    @php
                                        $duration = $job->JobModel->jobassignname->duration ?? null;
                                        $height_slot = $duration ? ($duration / 30) * 40 : 0;
                                    @endphp
                                    <div id='{{ $job->job_id }}' class="dts stretchJob border"
                                        style="height:{{ $height_slot }}px; position: relative; width:{{ $jobWidth }}px;"
                                        data-duration="{{ $job->JobModel->jobassignname->duration }}">
                                        <p class="p-1 text-center"><i class="fas fa-id-badge px-2"></i>
                                            <strong>{{ $job->JobModel->job_title ?? null }}
                                                #{{ $job->JobModel->id ?? null }}</strong>
                                        </p>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endforeach
                    <!-- Repeat the above div for each technician's time slot -->
                </div>
            @endfor
        @endfor
        <!-- Repeat the above div for each time slot -->
    </div>

    @section('script')
        <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(function() {
                $(".day").sortable({
                    connectWith: ".day",
                    cursor: "move",
                    helper: "clone",
                    items: "> div",
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
                var isResizing = false;

                // Initialize draggable elements
                $('.day div').draggable({
                    helper: 'clone',
                    cursor: 'move',
                    start: function(event, ui) {
                        if (isResizing) {
                            return false; // Prevent dragging if resizing
                        }
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
                            '" class="dts stretchJob border" style="height:' + height_slot +
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
            });
        </script>
    @endsection

@endsection
