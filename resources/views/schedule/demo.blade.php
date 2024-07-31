@extends('home')

@section('content')
    <style>
        /* div#left {
                        margin-left: 40px;
                        float: left;
                        width: 220px;
                    }

                    div#center,
                    div#right {
                        float: left;
                        width: 220px;
                        margin-left: 50px;
                    }

                    .box {
                        min-height: 100px;
                        height: auto;
                        width: 100px;
                        padding: 10px;
                        border-width: 4px;
                        border-style: solid;
                        position: absolute;
                    }

                    .day {
                        min-height: 100px;
                        height: auto;
                        width: 100px;
                        padding: 10px;
                        border-width: 4px;
                        border-style: solid;
                        position: absolute;
                    }

                    .day div {
                        cursor: move;
                        background-color: #00122f;
                        padding-top: 2px;
                        padding-bottom: 2px;
                        margin-bottom: 5px;
                        border-radius: 3px;
                        padding-right: 1px;
                        color: white;
                        padding-left: 3px;
                    }

                    #day1 {
                        border-color: orange;
                        left: 10px;
                        top: 100px;
                        width: 150px;
                    }

                    #day2 {
                        border-color: blue;
                        left: 200px;
                        top: 100px;
                        width: 150px;
                    }

                    #day3 {
                        border-color: green;
                        left: 390px;
                        top: 100px;
                        width: 150px;
                    }

                    #day4 {
                        border-color: red;
                        left: 580px;
                        top: 100px;
                        width: 150px;
                    }

                    #day5 {
                        border-color: darkturquoise;
                        left: 770px;
                        top: 100px;
                        width: 150px;
                    }

                    .instructions {
                        color: red;
                    }

                    #reorder ul {
                        margin-left: 20px;
                        width: 200px;
                        border: 1px solid black;
                        list-style: none;
                        padding: 0;
                    }

                    #reorder li {
                        padding: 2px 20px;
                        height: 25px;
                        line-height: 25px;
                    }

                    #update-button,
                    #update-message {
                        height: 30px;
                        margin-left: 20px;
                        width: 200px;
                        font-weight: bold;
                    }

                    ol.indexpage {
                        margin-top: 30px;
                        font-family: sans-serif;
                        list-style: decimal;
                        border: none;
                        margin-left: 50px;
                    }

                    .indexpage li {
                        border: none;
                        background-color: white;
                    } */

        .day div {
            cursor: move;
        }

        .schedule-container {
            display: flex;
            flex-direction: column;
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
            border: 1px solid #ddd;
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
    </style>
    <h4 class="fc-toolbar-title px-4 pt-2" id="fc-dom-1">{{ $formattedDate }}</h4>
    {{-- <div style="height:200px;">
        @foreach ($technicians as $key => $item)
            @php
                $a = $key + 1;
                $technicianSchedules = $schedules->where('technician_id', $item->id);
            @endphp

            <div class='day' id='day{{ $a }}'>
                <h4 class="technicianName" data-technician-id='{{ $item->id }}'>{{ $item->name }}</h4>
                @foreach ($technicianSchedules as $key2 => $value)
                    <div id='{{ $value->job_id }}'>
                        <h5 class="p-1 text-center"><i class="fas fa-id-badge px-2"></i>
                            <strong>{{ $value->JobModel->job_title ?? null }}
                                #{{ $value->JobModel->id ?? null }}</strong>
                        </h5>

                    </div>
                @endforeach
            </div>
        @endforeach



    </div> --}}

    <div class="mx-4 mt-5 bg-white">
        <div class="schedule-container">
            <div class="header-row">
                <div class="tech-header"></div>
                <!-- Loop through the user_array to generate technician headers -->
                @foreach ($technicians as $key => $item)
                    <div class="tech-header" style="color: #123456;">
                        <a href="#" class="link user_head_link tech_profile" style="color: #123456 !important;">
                            <img src="{{ asset('public/images/Uploads/users/' . $item->id . '/' . $item->user_image) }}"
                                alt="user" width="48" class="rounded-circle tech_profile"
                                onerror="this.onerror=null; this.src='{{ $defaultImage }}';" />
                            <span class="tech-name">{{ $item->name ?? null }}</span>
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


                            <div class="timeslot p-0 day" data-date="{{ $formattedDate }}"
                                data-slot-time="{{ formatTime($hour, $minute) }}"
                                data-technician-name="{{ $item->name }}" data-technician-id="{{ $item->id }}">
                                @foreach ($technicianSchedules as $key2 => $value)
                                    @php
                                        $duration = $value->JobModel->jobassignname->duration ?? null;
                                        $height_slot = $duration ? ($duration / 30) * 40 : 0; // Calculate height in pixels
                                    @endphp
                                    <div id='{{ $value->job_id }}' class="dts"
                                        style="max-height:{{ $height_slot }};"
                                        data-duration="{{ $value->JobModel->jobassignname->duration }}">
                                        <h5 class="p-1 text-center"><i class="fas fa-id-badge px-2"></i>
                                            <strong>{{ $value->JobModel->job_title ?? null }}
                                                #{{ $value->JobModel->id ?? null }}</strong>
                                        </h5>

                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                        <!-- Repeat the above div for each technician's time slot -->
                    </div>
                @endfor
            @endfor
            <!-- Repeat the above div for each time slot -->
        </div>
    </div>
</div>

@section('script')
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
            $('.day div').draggable({
                helper: 'clone',
                cursor: 'move'
            });

            $('.day').droppable({
                tolerance: 'pointer',
                drop: function(event, ui) {
                    var jobId = ui.draggable.attr('id');
                    var duration = ui.draggable.attr('data-duration');
                    var newTechnicianId = $(this).data('technician-id');
                    var date = $(this).data('date');
                    var time = $(this).data('slot-time');

                    console.log('Dropped job ID:', jobId);
                    console.log('New technician ID:', newTechnicianId);

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

                    // Optionally, move the job element to the new container
                    ui.draggable.remove(); // Remove the dragged element from its original position
                    $(this).append('<div id="' + jobId + '" class="dts" data-duration="' + duration +
                        '">' + ui.draggable.html() +
                        '</div>'); // Append it to the new position
                    $('div#' + jobId).draggable({
                        helper: 'clone',
                        cursor: 'move'
                    });
                }
            });
        });
    </script>
@endsection

@endsection
