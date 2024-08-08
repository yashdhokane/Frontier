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
    <div id="newdemodata">
        @include('schedule.demoSchedule')
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&loading=async&callback=initMap&libraries=marker"
        async></script>

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

                    // Add keydown event listener to hide popupDiv when Esc is pressed
                    $(document).on('keydown', function(e) {
                        if (e.key === "Escape") { // Check if the pressed key is "Esc"
                            popupDiv.hide();
                        }
                    });
                       // Hide the popup div when clicking outside of it
                    $(document).on('click',function(e) {
                        popupDiv.hide();
                    });

                }
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



            // Function to initialize draggable elements
            function initializeDraggable() {
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
            }

            // Function to revert drag operation
            function revertDrag(ui) {
                ui.helper.animate(ui.originalPosition, "slow");
            }

            // Function to initialize droppable elements
            function initializeDroppable() {
                $('.day').droppable({
                    tolerance: 'pointer',
                    drop: function(event, ui) {
                        var jobId = ui.draggable.attr('id');
                        var duration = ui.draggable.attr('data-duration');
                        var newTechnicianId = $(this).data('technician-id');
                        var techName = ui.draggable.attr('data-technician-name');
                        var timezone = ui.draggable.attr('data-timezone-name');
                        var date = $(this).data('date');
                        var time = $(this).data('slot-time');
                        let name;
                        let zoneName;

                        var height_slot = duration ? (duration / 30) * 40 : 0;

                        // Temporarily move the job to the new position
                        var originalContainer = ui.draggable.parent();
                        var newContainer = $(event.target);
                        var originalJobCount = originalContainer.children('.dts').length;
                        var newJobCount = newContainer.children('.dts').length + 1;
                        var newJobWidth = 100 / newJobCount;
                        var originalJobWidth = 100 / (originalJobCount - 1);

                        // Remove the draggable element from its original container
                        ui.draggable.remove();

                        // Set the width of existing jobs in the new container
                        newContainer.children('.dts').each(function() {
                            $(this).css('width', newJobWidth + 'px');
                        });

                        // Append the new job with the calculated width
                        var newJobElement = $('<div>', {
                            id: jobId,
                            class: 'dts dragDiv stretchJob border',
                            css: {
                                height: height_slot + 'px',
                                position: 'relative',
                                width: newJobWidth + 'px'
                            },
                            'data-duration': duration,
                            'data-technician-name': techName,
                            'data-timezone-name': timezone,
                            html: ui.draggable.html()
                        });

                        newContainer.append(newJobElement);

                        // Update the width of the original container if any jobs remain
                        if (originalJobCount > 1) {
                            originalContainer.children('.dts').each(function() {
                                $(this).css('width', originalJobWidth + 'px');
                            });
                        }

                        // Make the new job element draggable
                        newJobElement.draggable({
                            helper: 'clone',
                            cursor: 'move',
                            start: function(event, ui) {
                                if (isResizing) {
                                    return false;
                                }
                                isDragging = true;
                            },
                            stop: function(event, ui) {
                                isDragging = false;
                            }
                        });

                        // Ask for confirmation to move the job
                        $.ajax({
                            url: "{{ route('get.techName') }}",
                            type: 'GET',
                            data: {
                                techId: newTechnicianId,
                            },
                            success: function(response) {
                                name = response.name;
                                zoneName = response.time_zone.timezone_name;

                                Swal.fire({
                                    title: `Do you want to move job from ${techName} to ${name}?`,
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes',
                                    cancelButtonText: 'No',
                                    reverseButtons: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        if (timezone == zoneName) {
                                            updateJobTechnician(jobId, duration,
                                                date, time, newTechnicianId, ui,
                                                name, zoneName, newJobElement,
                                                originalContainer,
                                                originalJobCount);
                                        } else {
                                            Swal.fire({
                                                title: `Do you want to change the Job from ${timezone} to ${zoneName}?`,
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonText: 'Yes',
                                                cancelButtonText: 'No',
                                                reverseButtons: true
                                            }).then((innerResult) => {
                                                if (innerResult
                                                    .isConfirmed) {
                                                    updateJobTechnician(
                                                        jobId, duration,
                                                        date, time,
                                                        newTechnicianId,
                                                        ui, name,
                                                        zoneName,
                                                        newJobElement,
                                                        originalContainer,
                                                        originalJobCount
                                                        );
                                                } else {
                                                    revertTempMove(
                                                        newJobElement,
                                                        originalContainer,
                                                        originalJobCount
                                                        );
                                                }
                                            });
                                        }
                                    } else {
                                        revertTempMove(newJobElement,
                                            originalContainer, originalJobCount);
                                    }
                                });
                            },
                            error: function(error) {
                                revertTempMove(newJobElement, originalContainer,
                                    originalJobCount);
                                console.error(error);
                            }
                        });

                        function updateJobTechnician(jobId, duration, date, time, newTechnicianId, ui,
                            name, zoneName, newJobElement, originalContainer, originalJobCount) {
                            $.ajax({
                                url: '{{ route('updateJobTechnician') }}',
                                method: 'POST',
                                data: {
                                    job_id: jobId,
                                    duration: duration,
                                    date: date,
                                    time: time,
                                    technician_id: newTechnicianId,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    console.log('Job updated successfully:', response);
                                    if (response.success) {
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: 'success',
                                            title: 'Job moved successfully',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    } else {
                                        revertTempMove(newJobElement, originalContainer,
                                            originalJobCount);
                                        console.error('Error:', response.error);
                                    }
                                },
                                error: function(error) {
                                    revertTempMove(newJobElement, originalContainer,
                                        originalJobCount);
                                    console.error(error);
                                }
                            });
                        }

                        function revertTempMove(newJobElement, originalContainer, originalJobCount) {
                            newJobElement.remove();
                            var originalJobWidth = 100 / originalJobCount;

                            // Append the job back to the original container
                            originalContainer.append(ui.draggable);

                            // Update the width of the original container
                            originalContainer.children('.dts').each(function() {
                                $(this).css('width', originalJobWidth + 'px');
                            });

                            // Update the width of the new container
                            var newJobWidth = 100 / newContainer.children('.dts').length;
                            newContainer.children('.dts').each(function() {
                                $(this).css('width', newJobWidth + 'px');
                            });
                        }
                    }
                });
            }


            // Function to initialize resizable elements
            function initializeResizable() {
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
                        let originalHeight = parseFloat(target.dataset.originalHeight) || parseFloat(target
                            .style.height) || 0;
                        let heightChange = event.rect.height - originalHeight;

                        // Update the height directly with the cursor movement
                        let newHeight = originalHeight + heightChange;

                        // Set a minimum height to prevent collapsing too much
                        let minHeight = 40; // Equivalent to 30 minutes
                        if (newHeight < minHeight) {
                            newHeight = minHeight;
                        }

                        // Update the element's height
                        target.style.height = newHeight + 'px';

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
            }

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
                        target.style.height = originalHeight + 'px';
                    }
                });
            }

            // Function to initialize all necessary components
            function initializeComponents() {
                initializeDraggable();
                initializeDroppable();
                initializeResizable();
            }

            // Call the initialization function once at the start
            initializeComponents();

            // Fetch new schedule data and reinitialize components

            $(document).on('click', '#preDate1, #tomDate1', function(e) {
                e.preventDefault(); // Prevent the default anchor behavior

                var date = $(this).data('previous-date') || $(this).data('tomorrow-date');
                fetchSchedule(date);
            });

            // Event listener for showing the map
            $('#mapSection1').hide();

            $(document).on('click', 'a[href="#navMap1"]', function(e) {
                e.preventDefault();
                $('#scheduleSection1').hide();
                $('.cbtn1').removeClass('btn-info').addClass('btn-light-info text-info');
                $('.mbtn1').removeClass('btn-light-info text-info').addClass('btn-info');
                $('#mapSection1').show();
                initMap('mapScreen1', '#scheduleSection1');
            });

            // Event listener for hiding the map
            $(document).on('click', 'a[href="#navCalendar1"]', function(e) {
                e.preventDefault();
                $('#mapSection1').hide();
                $('.mbtn1').removeClass('btn-info').addClass('btn-light-info text-info');
                $('.cbtn1').removeClass('btn-light-info text-info').addClass('btn-info');
                $('#scheduleSection1').show();
            });

            function fetchSchedule(date) {
                $.ajax({
                    url: "{{ route('schedule.demoScheduleupdate') }}",
                    method: "GET",
                    data: {
                        date: date
                    },
                    success: function(response) {
                        $('#newdemodata').empty().html(response.tbody);
                        initializeComponents();
                        initializeDatepicker('#selectDates1', fetchSchedule);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }


            var openInfoWindowpop = null;
            var maps = {};

            function initMap(mapElementId, scheduleSectionId) {

                if (maps[mapElementId]) {
                    destroyMap(mapElementId); // Destroy the existing map instance
                }

                maps[mapElementId] = new google.maps.Map(document.getElementById(mapElementId), {
                    zoom: 5,
                    center: {
                        lat: 39.8283,
                        lng: -98.5795
                    }
                });

                var selectedDate = $(scheduleSectionId).data('map-date');
                if (!selectedDate) {
                    selectedDate = new Date().toISOString().split('T')[0];
                    $(scheduleSectionId).data('map-date', selectedDate);
                    console.log("No date provided. Using current date:", selectedDate);
                } else {
                    console.log("Using provided date:", selectedDate);
                }

                fetchJobData(mapElementId, selectedDate);
            }


            function fetchJobData(mapElementId, date) {
                $.ajax({
                    url: '{{ route('schedule.getJobsByDate') }}',
                    method: 'GET',
                    data: {
                        date: date
                    },
                    success: function(response) {
                        if (response.data) {
                            console.log("Job data received:", response.data);
                            setMarkers(mapElementId, response.data);
                        } else {
                            console.error('Error: No job data returned.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: AJAX request failed. Status:', status, 'Error:', error);
                    }
                });
            }

            function setMarkers(mapElementId, markersData) {
                clearMarkers(mapElementId);

                const markers = markersData.filter(marker => marker.latitude && marker.longitude);
                var bounds = new google.maps.LatLngBounds();

                markers.forEach(marker => {
                    console.log("Creating marker for:", marker.name, "at position:", marker.latitude, marker
                        .longitude);
                    var markerInstance = new google.maps.Marker({
                        position: {
                            lat: parseFloat(marker.latitude),
                            lng: parseFloat(marker.longitude)
                        },
                        map: maps[mapElementId],
                        title: marker.name
                    });

                    markerInstance.addListener('click', function() {
                        console.log("Marker clicked for job ID:", marker.job_id);
                        fetchMarkerDetails(markerInstance, marker.job_id);
                    });

                    bounds.extend(markerInstance.position);
                });

                if (markers.length > 0) {
                    console.log("Fitting map to marker bounds.");
                    maps[mapElementId].fitBounds(bounds);
                } else {
                    console.log("No markers to set on map.");
                }
            }

            function fetchMarkerDetails(markerInstance, jobId) {
                $.ajax({
                    url: '{{ route('schedule.getMarkerDetails') }}',
                    method: 'GET',
                    data: {
                        id: jobId
                    },
                    success: function(response) {
                        if (response.content) {
                            console.log("Marker details received:", response.content);
                            if (openInfoWindowpop) {
                                openInfoWindowpop.close();
                            }
                            openInfoWindowpop = openInfoWindow(markerInstance, response.content);
                        } else {
                            console.error('Error: No content returned.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: AJAX request failed. Status:', status, 'Error:', error);
                    }
                });
            }

            function openInfoWindow(marker, content) {
                var infoWindow = new google.maps.InfoWindow({
                    content: content
                });
                infoWindow.open(maps[marker.map.getDiv().id], marker);
                return infoWindow;
            }

            function clearMarkers(mapElementId) {
                if (maps[mapElementId].markers) {
                    maps[mapElementId].markers.forEach(marker => marker.setMap(null));
                }
                maps[mapElementId].markers = [];
            }

            window.onload = function() {
                initMap('mapScreen1', '#scheduleSection1');
            };

            function destroyMap(mapElementId) {
                if (maps[mapElementId]) {
                    // Clear any existing markers or overlays if applicable
                    clearMarkers(mapElementId);

                    // Clear event listeners associated with the map
                    google.maps.event.clearInstanceListeners(maps[mapElementId]);

                    // Set the map instance to null, effectively "destroying" it
                    maps[mapElementId] = null;
                    console.log("Map instance destroyed for:", mapElementId);
                }
            }

            function initializeDatepicker(selector, fetchFunction) {
                $(selector).datepicker({
                    format: 'yyyy-mm-dd', // Specify the format
                    autoclose: true, // Close the datepicker when a date is selected
                    todayHighlight: true // Highlight today's date
                }).on('changeDate', function(selected) {
                    var selectedDate = new Date(selected.date);
                    var date = selectedDate.getFullYear() + '-' +
                        (selectedDate.getMonth() + 1).toString().padStart(2, '0') + '-' +
                        selectedDate.getDate().toString().padStart(2, '0');
                    fetchFunction(date);
                });
            }
            initializeDatepicker('#selectDates1', fetchSchedule);


        });
    </script>
@endsection

@endsection
