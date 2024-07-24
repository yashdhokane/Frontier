@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script src="{{ url('public/admin/schedule/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&loading=async&callback=initMap&libraries=marker"
        async></script>

    <script>
        $(document).ready(function() {
            // section 1
            $('#mapSection1').hide();
            $(document).on('click', 'a[href="#navMap1"]', function(e) {
                e.preventDefault();

                $('#scheduleSection1').hide();
                $('.cbtn1').removeClass('btn-info').addClass('btn-light-info text-info');
                $('.mbtn1').removeClass('btn-light-info text-info').addClass('btn-info');

                $('#mapSection1').show();
                var date = $('#scheduleSection1').data('map-date');
                var mapElementId = 'mapSection1'; // Get the map element ID from data attribute
                initMap(mapElementId, '#scheduleSection1'); // Ensure the map is initialized
                fetchJobData(mapElementId, date); // Fetch job data for the new date

            });

            $(document).on('click', 'a[href="#navCalendar1"]', function(e) {
                e.preventDefault();

                $('#mapSection1').hide();
                $('.mbtn1').removeClass('btn-info').addClass('btn-light-info text-info');
                $('.cbtn1').removeClass('btn-light-info text-info').addClass('btn-info');
                $('#scheduleSection1').show();
            });
            // section 2 
            $('#mapSection2').hide();
            $(document).on('click', 'a[href="#navMap2"]', function(e) {
                e.preventDefault();

                $('#scheduleSection2').hide();
                $('.cbtn2').removeClass('btn-info').addClass('btn-light-info text-info');
                $('.mbtn2').removeClass('btn-light-info text-info').addClass('btn-info');
                $('#mapSection2').show();
                var date = $('#scheduleSection2').data('map-date');
                var mapElementId = 'mapSection2'; // Get the map element ID from data attribute
                initMap(mapElementId, '#scheduleSection2'); // Ensure the map is initialized
                fetchJobData(mapElementId, date); // Fetch job data for the new date
            });

            $(document).on('click', 'a[href="#navCalendar2"]', function(e) {
                e.preventDefault();

                $('#mapSection2').hide();
                $('.mbtn2').removeClass('btn-info').addClass('btn-light-info text-info');
                $('.cbtn2').removeClass('btn-light-info text-info').addClass('btn-info');
                $('#scheduleSection2').show();
            });
            // section 3 
            $('#mapSection3').hide();
            $(document).on('click', 'a[href="#navMap3"]', function(e) {
                e.preventDefault();

                $('#scheduleSection3').hide();
                $('.cbtn3').removeClass('btn-info').addClass('btn-light-info text-info');
                $('.mbtn3').removeClass('btn-light-info text-info').addClass('btn-info');
                $('#mapSection3').show();
                var date = $('#scheduleSection3').data('map-date');
                var mapElementId = 'mapSection3'; // Get the map element ID from data attribute
                initMap(mapElementId, '#scheduleSection3'); // Ensure the map is initialized
                fetchJobData(mapElementId, date); // Fetch job data for the new date
            });

            $(document).on('click', 'a[href="#navCalendar3"]', function(e) {
                e.preventDefault();

                $('#mapSection3').hide();
                $('.mbtn3').removeClass('btn-info').addClass('btn-light-info text-info');
                $('.cbtn3').removeClass('btn-light-info text-info').addClass('btn-info');
                $('#scheduleSection3').show();
            });

            //  for split screen 
            // Function to toggle active screens based on tab selection
            $(document).on('click', 'a[href="#navpill-1"]', function(e) {
                e.preventDefault();

                // Hide all screens
                $('.screen2, .screen3').hide();
                $('.screen1, .screen2, .screen3').removeClass('col-lg-4').addClass('col-lg-12');
                $('.screen1').show();
            });
            $(document).on('click', 'a[href="#navpill-2"]', function(e) {
                e.preventDefault();

                // Hide all screens
                $('.screen1, .screen3').hide();
                $('.screen1, .screen2, .screen3').removeClass('col-lg-4').addClass('col-lg-12');
                $('.screen2').show();
            });
            $(document).on('click', 'a[href="#navpill-3"]', function(e) {
                e.preventDefault();

                // Hide all screens
                $('.screen2, .screen1').hide();
                $('.screen1, .screen2, .screen3').removeClass('col-lg-4').addClass('col-lg-12');
                $('.screen3').show();
            });

            // Function to handle "Expand All" functionality
            $(document).on('click', 'a[href="#navpill-4"]', function(e) {
                e.preventDefault();

                // Remove col-lg-12 and add col-lg-4 to all screen containers
                $('.screen1, .screen2, .screen3').removeClass('col-lg-12').addClass('col-lg-4');

                // Show all screens
                $('.screen1, .screen2, .screen3').show();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var isDragulaInitialized = false;
            if (!isDragulaInitialized) {
                initializeDragula();
                isDragulaInitialized = true;
            }
            interact('.stretchJob').resizable({
                    edges: {
                        left: false,
                        right: false,
                        bottom: true,
                        top: false
                    }
                })
                .on('resizestart', function(event) {
                    // Disable pointer events on the parent <a> tag when resizing starts
                    let parentAnchor = event.target.closest('.show_job_details');
                    if (parentAnchor) {
                        parentAnchor.style.pointerEvents = 'none';
                    }
                })
                .on('resizemove', function(event) {
                    let target = event.target;
                    let originalHeight = parseFloat(target.dataset.originalHeight) || parseFloat(target.style
                        .height) || 0;
                    let heightChange = event.deltaRect.height;
                    let heightPer30Min = 36; // height for 30 minutes
                    let minDuration = 30; // min duration in minutes
                    let maxDuration = 240; // max duration in minutes

                    // Calculate the new height and duration
                    let newHeight = originalHeight + heightChange;
                    let newDuration = Math.round(newHeight / heightPer30Min) * 30;

                    // Restrict the duration within the allowed range
                    if (newDuration < minDuration) newDuration = minDuration;
                    // if (newDuration > maxDuration) newDuration = maxDuration;

                    // Update the height based on the new duration
                    target.style.height = `${(newDuration / 30) * heightPer30Min}px`;

                    // Update the data-duration attribute
                    target.dataset.duration = newDuration;
                })
                .on('resizeend', function(event) {
                    // Re-enable pointer events on the parent <a> tag when resizing ends
                    let parentAnchor = event.target.closest('.show_job_details');
                    if (parentAnchor) {
                        parentAnchor.style.pointerEvents = 'auto';
                    }

                    // Get the updated duration
                    let newDuration = parseInt(event.target.dataset.duration);

                    // Get the job ID
                    let jobId = event.target.dataset.id; // Assuming you have job ID stored in data-id attribute
                    let target = event.target;
                    // AJAX request to update duration in database
                    updateDurationInDatabase(jobId, newDuration, target);
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

                        var screen1Date = $('#screen-date1').data('screen1-date');
                        var screen2Date = $('#screen-date2').data('screen2-date');
                        var screen3Date = $('#screen-date3').data('screen3-date');
                        // AJAX POST request to your Laravel endpoint
                        $.ajax({
                            url: "{{ route('schedule.update_job_duration') }}",
                            type: 'POST', // Changed from 'GET' to 'POST'
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
                                    .then(() => {

                                        fetchSchedule1(screen1Date);
                                        fetchSchedule2(screen2Date);
                                        fetchSchedule3(screen3Date);
                                    });
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

            var drake;

            function initializeDragula() {
                drake = dragula(Array.from(document.getElementsByClassName('draggable-items')), {
                    moves: function(el, container, handle) {
                        return handle.classList.contains('start-drag');
                    }
                });

                var originalParent, originalNextSibling;

                drake.on('drag', function(el) {
                    el.classList.remove('card-moved');
                    originalParent = el.parentElement;
                    originalNextSibling = el.nextElementSibling;
                });

                drake.on('drop', function(el, target, source, sibling) {
                    var time = $(el).closest('.draggable-items').data('slot_time');
                    var techId = $(el).closest('.draggable-items').data('technician_id');
                    var dragDate = $(el).closest('.draggable-items').data('drag-date');
                    var jobId = $(el).find('.flexibleslot').data('id');
                    var duration = $(el).find('.flexibleslot').data('duration');
                    var techName = $(el).find('.flexibleslot').data('technician-name');
                    var timezone = $(el).find('.flexibleslot').data('timezone-name');
                    var screen1Date = $('#screen-date1').data('screen1-date');
                    var screen2Date = $('#screen-date2').data('screen2-date');
                    var screen3Date = $('#screen-date3').data('screen3-date');

                    // Update the schedule
                    $.ajax({
                        url: "{{ route('get.techName') }}",
                        type: 'GET',
                        data: {
                            techId: techId
                        },
                        success: function(response) {
                            var name = response.name;
                            var zoneName = response.time_zone.timezone_name;

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
                                        $.ajax({
                                            url: "{{ route('schedule.drag_update') }}",
                                            type: 'GET',
                                            data: {
                                                jobId: jobId,
                                                techId: techId,
                                                time: time,
                                                dragDate: dragDate,
                                                duration: duration
                                            },
                                            success: function(response) {
                                                if (response.success ==
                                                    true) {
                                                    fetchSchedule1(
                                                        screen1Date);
                                                    fetchSchedule2(
                                                        screen2Date);
                                                    fetchSchedule3(
                                                        screen3Date);
                                                    Swal.fire({
                                                        position: 'top-end',
                                                        icon: 'success',
                                                        title: 'Job moved successfully',
                                                        showConfirmButton: false,
                                                        timer: 1500
                                                    });


                                                } else {
                                                    console.log(response
                                                        .error);
                                                    revertDrag(
                                                        el
                                                    ); // Revert the drag operation
                                                }
                                            },
                                            error: function(error) {
                                                console.error(error);
                                                revertDrag(
                                                    el
                                                ); // Revert the drag operation
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            title: `Do you want to change the Job from ${timezone} to ${zoneName}?`,
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonText: 'Yes',
                                            cancelButtonText: 'No',
                                            reverseButtons: true
                                        }).then((innerResult) => {
                                            if (innerResult.isConfirmed) {
                                                $.ajax({
                                                    url: "{{ route('schedule.drag_update') }}",
                                                    type: 'GET',
                                                    data: {
                                                        jobId: jobId,
                                                        techId: techId,
                                                        time: time,
                                                        dragDate: dragDate,
                                                        duration: duration
                                                    },
                                                    success: function(
                                                        response) {
                                                        if (response
                                                            .success ==
                                                            true) {
                                                            fetchSchedule1
                                                                (
                                                                    screen1Date
                                                                );
                                                            fetchSchedule2
                                                                (
                                                                    screen2Date
                                                                );
                                                            fetchSchedule3
                                                                (
                                                                    screen3Date
                                                                );
                                                            Swal.fire({
                                                                position: 'top-end',
                                                                icon: 'success',
                                                                title: 'Job moved successfully',
                                                                showConfirmButton: false,
                                                                timer: 1500
                                                            });


                                                        } else {
                                                            console
                                                                .log(
                                                                    response
                                                                    .error
                                                                );
                                                            revertDrag
                                                                (
                                                                    el
                                                                ); // Revert the drag operation
                                                        }
                                                    },
                                                    error: function(
                                                        error) {
                                                        console
                                                            .error(
                                                                error
                                                            );
                                                        revertDrag(
                                                            el
                                                        ); // Revert the drag operation
                                                    }
                                                });
                                            } else {
                                                revertDrag(
                                                    el
                                                ); // Revert the drag operation
                                            }
                                        });
                                    }
                                } else {
                                    revertDrag(el); // Revert the drag operation
                                }
                            });
                        },
                        error: function(error) {
                            console.error(error);
                            revertDrag(el); // Revert the drag operation
                        }
                    });
                });

                // Function to revert the drag operation
                function revertDrag(el) {
                    if (originalParent && originalNextSibling) {
                        originalParent.insertBefore(el, originalNextSibling);
                    } else if (originalParent) {
                        originalParent.appendChild(el);
                    }
                }
            }

            function fetchSchedule1(date) {
                $.ajax({
                    url: "{{ route('schedule.calender1') }}",
                    method: "GET",
                    data: {
                        date: date
                    },
                    success: function(response) {
                        $('#calender1').empty().html(response.tbody);
                        reinitializeDatepickers();
                        initializeDragula();

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            $(document).on('click', '#preDate1, #tomDate1', function(e) {
                e.preventDefault(); // Prevent the default anchor behavior

                var date = $(this).data('previous-date') || $(this).data('tomorrow-date')


                fetchSchedule1(date);

            });



            function fetchSchedule2(date) {
                $.ajax({
                    url: "{{ route('schedule.calender2') }}",
                    method: "GET",
                    data: {
                        date: date
                    },
                    success: function(response) {
                        $('#calender2').empty().html(response.tbody);
                        reinitializeDatepickers();
                        initializeDragula();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            $(document).on('click', '#preDate2, #tomDate2', function() {
                var date = $(this).data('previous-date') || $(this).data('tomorrow-date');
                fetchSchedule2(date);
            });

            function fetchSchedule3(date) {
                $.ajax({
                    url: "{{ route('schedule.calender3') }}",
                    method: "GET",
                    data: {
                        date: date
                    },
                    success: function(response) {
                        $('#calender3').empty().html(response.tbody);
                        reinitializeDatepickers();
                        initializeDragula();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            $(document).on('click', '#preDate3, #tomDate3', function() {
                var date = $(this).data('previous-date') || $(this).data('tomorrow-date');
                fetchSchedule3(date);
            });

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

            function reinitializeDatepickers() {
                initializeDatepicker('#selectDates1', fetchSchedule1);
                initializeDatepicker('#selectDates2', fetchSchedule2);
                initializeDatepicker('#selectDates3', fetchSchedule3);

            }

            // Initial initialization
            reinitializeDatepickers();
        });


        // map section 
        var openInfoWindowpop = null;
        var maps = {};

        $(document).ready(function() {
            // Initialize the maps
            initMap('mapScreen1', '#scheduleSection1');
            initMap('mapScreen2', '#scheduleSection2');
            initMap('mapScreen3', '#scheduleSection3');


        });

        function fetchJobData(mapElementId, date) {
            $.ajax({
                url: '{{ route('schedule.getJobsByDate') }}', // Adjust the route accordingly
                method: 'GET',
                data: {
                    date: date
                },
                success: function(response) {
                    console.log(response);
                    if (response.data) {
                        setMarkers(mapElementId, response.data);
                    } else {
                        console.error('Error: No job data returned.');
                    }
                },
                error: function() {
                    console.error('Error: AJAX request failed.');
                }
            });
        }

        function setMarkers(mapElementId, markersData) {
            // Clear existing markers
            clearMarkers();

            const markers = markersData.filter(marker => marker.latitude && marker.longitude);

            markers.forEach(marker => {
                var markerInstance = new google.maps.Marker({
                    position: {
                        lat: parseFloat(marker.latitude),
                        lng: parseFloat(marker.longitude)
                    },
                    map: maps[mapElementId],
                    title: marker.name
                });

                markerInstance.addListener('click', function() {
                    fetchMarkerDetails(markerInstance, marker.job_id);
                });
            });

            // Calculate bounds based on the markers
            var bounds = new google.maps.LatLngBounds();
            markers.forEach(marker => {
                bounds.extend(new google.maps.LatLng(parseFloat(marker.latitude), parseFloat(marker.longitude)));
            });

            if (markers.length > 0) {
                maps[mapElementId].fitBounds(bounds);
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
                        if (openInfoWindowpop) {
                            openInfoWindowpop.close();
                        }
                        openInfoWindowpop = openInfoWindow(markerInstance, response.content);
                    } else {
                        console.error('Error: No content returned.');
                    }
                },
                error: function() {
                    console.error('Error: AJAX request failed.');
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

        function clearMarkers() {
            // Implement this function to clear existing markers from the map
        }

        function initMap(mapElementId, scheduleSectionId) {
            maps[mapElementId] = new google.maps.Map(document.getElementById(mapElementId), {
                zoom: 5,
                center: {
                    lat: 0,
                    lng: 0
                }
            });

            var selectedDate = $(scheduleSectionId).data('map-date');
            if (!selectedDate) {
                selectedDate = new Date().toISOString().split('T')[0];
                $(scheduleSectionId).data('map-date', selectedDate);
            }

            fetchJobData(mapElementId, selectedDate);
        }

        // Initialize the maps when the window loads
        window.onload = function() {
            initMap('mapScreen1', '#scheduleSection1');
            initMap('mapScreen2', '#scheduleSection2');
            initMap('mapScreen3', '#scheduleSection3');
        };
    </script>


    <script>
        $(document).ready(function() {
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
            var timezoneName = '{{ $timezoneName }}'
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

            $(document).on('click', '.clickPoint', function(e) {
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
