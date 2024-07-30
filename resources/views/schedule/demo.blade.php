@extends('home')

@section('content')
    <style>
        div#left {
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

        /* ul,
                            ol {
                                list-style: none;
                                border: 1px solid black;
                                padding: 0;
                            }

                            li {
                                padding: 0 10px;
                                height: 25px;
                                line-height: 25px;
                            }

                            li:nth-child(odd) {
                                background-color: #CCC;
                            }

                            li:nth-child(even) {
                                background-color: white;
                            }

                            li:hover {
                                cursor: move;
                            } */

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
        }
    </style>
 <h4 class="fc-toolbar-title px-4 pt-2" id="fc-dom-1">{{ $formattedDate }}</h4>
    <div style="height:500px;">
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
                    var jobId = ui.draggable.attr('id'); // ID of the dragged job
                    var newTechnicianId = $(this).find('.technicianName').data(
                        'technician-id'); // ID of the technician where the job is dropped

                    console.log('Dropped job ID:', jobId);
                    console.log('New technician ID:', newTechnicianId);

                    // Perform the AJAX request to update the technician_id for the job
                    $.ajax({
                        url: '{{ route('updateJobTechnician') }}', // Replace with your actual endpoint URL
                        method: 'POST',
                        data: {
                            job_id: jobId,
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
                    $(this).append('<div id="' + jobId + '" class="job-item">' + ui.draggable.html() +
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
