<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&callback=initMap" async
    defer></script>

<script>
    $(document).ready(function() {

        fetchFilteredData();
        $('#showChooseDate').hide();
        $('#routingTriggerSelect').select2('destroy');
        $('#routingTriggerSelect').select2();
        let isProcessing = false; // Flag to prevent recursive calls

        $(document).on('change', '#dateDay', function() {
            var datevalue = $(this).val();
            if (datevalue == 'chooseDate') {
                $('#showChooseDate').show();
            } else {
                $('#showChooseDate').hide();
            }
        });

        $(document).on('change', '#routingTriggerSelect', function() {
            if (isProcessing) return; // Prevent recursive execution
            isProcessing = true;

            fetchFilteredData();

            const selectedValues = $(this).val();
            const selectAllValue = 'all';

            if (selectedValues.includes(selectAllValue)) {
                // If "All" is selected, deselect others
                $(this).val([selectAllValue]).trigger('change');
            } else {
                // If individual technicians are selected, deselect "All"
                const filteredValues = selectedValues.filter(value => value !== selectAllValue);
                $(this).val(filteredValues).trigger('change');
            }

            isProcessing = false; // Reset the flag after execution
        });


    });

    function initMap(techniciansData) {

        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 5,
            center: {
                lat: 40.7128,
                lng: -74.0060
            }
        });
        if (!Array.isArray(techniciansData)) {
            console.error("Invalid techniciansData:", techniciansData);
            return;
        }
        techniciansData.forEach((techData) => {
            const {
                latitude,
                longitude,
                name,
                full_address,
                color_code,
                dateDay
            } = techData.technician;


            if (!latitude || !longitude) {
                console.log(`No valid location for ${name}`);
                return;
            }

            const initials = name.split(" ")
                .map(word => word.charAt(0).toUpperCase())
                .join("");

            // Add technician marker
            const techMarker = new google.maps.Marker({
                position: {
                    lat: parseFloat(latitude),
                    lng: parseFloat(longitude)
                },
                map,
                title: name,
                label: {
                    text: initials,
                    color: "white",
                    fontWeight: "bold"
                },
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 15,
                    fillColor: color_code,
                    fillOpacity: 1,
                    strokeWeight: 2,
                    strokeColor: "#FFFFFF"
                }
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `<h4 class="pointer JobOpenModalButton" data-dateDay="${ techData.technician.dateDay }" data-tech-name="${name}" data-tech-id="${ techData.technician.id }">${name}</h4><p>${full_address}</p>`
            });

            techMarker.addListener("click", () => infoWindow.open(map, techMarker));

            // Check if dateDay is "nextdays" and process jobs
            if (dateDay === "nextdays") {
                // Group jobs by individual dates
                const jobsByDate = techData.jobs.reduce((acc, job) => {
                    const jobDate = job
                        .start_date_time; // Assuming each job has a `start_date_time` property
                    if (!acc[jobDate]) acc[jobDate] = [];
                    acc[jobDate].push(job);
                    return acc;
                }, {});


                // Pass jobsByDate directly to renderRouteForDate
                renderRouteForDate(map, techData.technician, jobsByDate, dateDay);
            } else {
                // For single-day routes, create a similar grouped structure
                const singleDayJobs = {
                    [dateDay]: techData.jobs
                };
                renderRouteForDate(map, techData.technician, singleDayJobs, dateDay);
            }

        });
    }
    var popupUrl = "{{ route('jobs.popup', ['jobId' => '__JOB_ID__']) }}";

    function renderRouteForDate(map, technician, jobsByDate, dateDay) {
        const {
            latitude,
            longitude,
            color_code
        } = technician;

        // Ensure valid technician coordinates
        if (!latitude || !longitude || isNaN(latitude) || isNaN(longitude)) {
            console.error("Invalid technician coordinates. Skipping route rendering.");
            return;
        }

        // Iterate through each date and its jobs
        Object.entries(jobsByDate).forEach(([jobDate, jobs], index) => {
            // Filter jobs with valid customer locations
            const validJobs = jobs.filter(
                (job) =>
                job.customer.latitude &&
                job.customer.longitude &&
                !isNaN(parseFloat(job.customer.latitude)) &&
                !isNaN(parseFloat(job.customer.longitude))
            );
            if (validJobs.length === 0) {
                // console.log(`No jobs for date ${jobDate}. Skipping path rendering.`);
                return;
            }

            // Generate waypoints
            const waypoints = validJobs.map((job) => ({
                location: new google.maps.LatLng(
                    parseFloat(job.customer.latitude),
                    parseFloat(job.customer.longitude)
                ),
                stopover: true,
            }));

            if (waypoints.length > 0) {
                const directionsService = new google.maps.DirectionsService();
                const strokeStyle = getPolylineStyle(jobDate, color_code);

                const directionsRenderer = new google.maps.DirectionsRenderer({
                    map,
                    polylineOptions: strokeStyle, // Apply dynamic stroke style
                    suppressMarkers: true,
                });

              directionsService.route({
                origin: {
                    lat: parseFloat(latitude),
                    lng: parseFloat(longitude)
                },
                destination: waypoints[waypoints.length - 1].location,
                optimizeWaypoints: false, // Disable waypoints optimization
                waypoints,
                travelMode: google.maps.TravelMode.DRIVING, // Try WALKING or BICYCLING if needed
            }, (result, status) => {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);

                    // Extract polyline path from directions
                    const route = result.routes[0].legs;
                    let polylinePath = [];

                    route.forEach(leg => {
                        leg.steps.forEach(step => {
                            step.path.forEach(point => {
                                polylinePath.push(point);
                            });
                        });
                    });

                    // Create new Polyline with event listeners
                    const polyline = new google.maps.Polyline({
                        path: polylinePath,
                        strokeColor: strokeStyle,
                        map: map
                    });

                    // InfoWindow for showing the date
                    const infoWindow = new google.maps.InfoWindow();

                    // Hover event to show date
                    google.maps.event.addListener(polyline, "mouseover", (event) => {
                        const firstJob = validJobs[0];
                        infoWindow.setContent(`Start Date: ${firstJob.start_date_time}`);
                        infoWindow.setPosition(event.latLng);
                        infoWindow.open(map);
                    });

                    // Mouseout event to close InfoWindow
                    google.maps.event.addListener(polyline, "mouseout", () => {
                        infoWindow.close();
                    });

                    // Click event for additional actions
                    google.maps.event.addListener(polyline, "click", (event) => {
                        const allJobsContent = validJobs.map(job => `
                            <div>
                                <strong>Job Title:</strong> ${job.job_title}<br>
                                <strong>Start Date:</strong> ${job.start_date_time}<br>
                                <strong>Customer:</strong> ${job.customer.name}<br>
                                <strong>Address:</strong> ${job.customer.full_address}<br>
                            </div>
                        `).join('<hr>');

                        const allJobsInfoWindow = new google.maps.InfoWindow({
                            content: allJobsContent
                        });

                        allJobsInfoWindow.setPosition(event.latLng);
                        allJobsInfoWindow.open(map);
                    });

                } else {
                    console.error("Directions request failed due to " + status);
                }
            });


                // Add numbered customer markers
                validJobs.forEach((job, jobIndex) => {
                    const {
                        latitude: jobLat,
                        longitude: jobLng,
                        name,
                        full_address
                    } = job.customer;
                    const jobNumber = (dateDay === "nextdays") ? index + 1 : jobIndex + 1;
                    if (jobLat && jobLng) {
                        const customerMarker = new google.maps.Marker({
                            position: {
                                lat: parseFloat(jobLat),
                                lng: parseFloat(jobLng),
                            },
                            map,
                            label: {
                                text: `${jobNumber}`, // Unique job number
                                color: "white",
                                fontSize: "12px",
                            },
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 9,
                                fillColor: "#007BFF",
                                fillOpacity: 1,
                                strokeWeight: 1,
                                strokeColor: "#FFFFFF",
                            },
                        });
                       
                        const popupRequestUrl = popupUrl.replace('__JOB_ID__', job.job_id);

                        $.ajax({
                            url: popupRequestUrl,
                            method: 'GET',
                            success: function(response) {
                                if (response.popupHtml) {
                                    const customerInfo = new google.maps.InfoWindow({
                                        content: response.popupHtml,
                                    });

                                    customerMarker.addListener("click", () =>
                                        customerInfo.open(map, customerMarker)
                                    );
                                }
                            },
                            error: function(xhr, status, error) {
                                // Log detailed error information
                                console.error("Failed to fetch popup HTML.");
                             }
                        });
                    }
                });

                
            }
        });
    }


    function getPolylineStyle(dateDay, color_code) {
        const today = new Date().setHours(0, 0, 0, 0); // Normalize to midnight for consistent comparison
        const targetDate = new Date(dateDay).setHours(0, 0, 0, 0);

        // Calculate the difference in days
        const diffDays = Math.round((targetDate - today) / (1000 * 60 * 60 * 24));

        let strokePattern;
        switch (diffDays) {
            case 0: // Today - Solid Line
                strokePattern = null; // No pattern for solid line
                break;
            case 1: // Tomorrow - Triangle Pattern with Gaps
                strokePattern = [{
                    icon: {
                        path: "M 0,0 L 2,-3 L -2,-3 Z", // Small triangle
                        scale: 2, // Size of the triangle
                        strokeOpacity: 1, // Border visibility
                        fillOpacity: 1, // Fill visibility
                        fillColor: color_code || "#FF0000", // Triangle color
                    },
                    repeat: "15px", // Distance between triangles to create gaps
                }, ];
                break;


            case 2: // Day After Tomorrow - Dotted Line
                strokePattern = [{
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE, // Dotted line
                        scale: 3, // Larger dot size
                        strokeOpacity: 1,
                        fillOpacity: 1,
                        fillColor: color_code || "#FF0000", // Match stroke color
                    },
                    repeat: "15px", // Distance between dots
                }, ];
                break;
            default: // Future Days - Default Solid Line
                strokePattern = null; // Default to solid line
        }

        return {
            strokeColor: color_code || "#FF0000", // Default color
            strokeWeight: 4, // Increased line thickness
            strokeOpacity: 1, // Full opacity
            icons: strokePattern || [], // Apply stroke pattern or solid
        };
    }

    // Utility function to calculate the distance between two coordinates
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth's radius in km
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) *
            Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) *
            Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }

    // Utility function to assign unique colors
    function getColorByIndex(index) {
        const colors = ["#FF5733", "#33FF57", "#3357FF"]; // Add more colors if needed
        return colors[index % colors.length];
    }

    function fetchFilteredData() {

        const dateDay = $('#dateDay').val();
        const chooseFrom = $('#chooseFrom').val();
        const chooseTo = $('#chooseTo').val();
        const routing = $('#routing').val();
        const technicians = $('#routingTriggerSelect').val();

        // Make an AJAX request to fetch filtered data
        $.ajax({
            url: "{{ route('jobrouting.filter') }}",
            type: "GET",
            data: {
                dateDay: dateDay,
                chooseFrom: chooseFrom,
                chooseTo: chooseTo,
                routing: routing,
                technicians: technicians
            },

            success: function(response) {

                if (response.success) {
                    const techniciansData = response.data;
            
                    // Update the map with the filtered data
                    updateMap(techniciansData);
                    // Update the job list with new data
                    updateJobDiv(techniciansData);

                } else {
                    console.log(response.message || "No data found for the selected filters.");
                }
            },
            error: function() {
                console.log("An error occurred while fetching data. Please try again.");
            }
        });
    }

    $(document).on('change', '#dateDay, #routing, #chooseFrom, #chooseTo', function() {
        fetchFilteredData();
    });

    function updateMap(techniciansData) {
        initMap(techniciansData);
    }


    function updateJobDiv(techniciansData) {
        const jobDiv = $('#jobdiv .list-group');
        jobDiv.empty();
        let hasCustomizedRoute = false;

        if (techniciansData.length > 0) {
            const jobsByDate = {};

            techniciansData.forEach(technician => {
                const routingType = technician.technician.routing;
                const techId = technician.technician.id;
                //  console.log(technician);

                if (technician.jobs && technician.jobs.length > 0) {
                    technician.jobs.forEach(job => {
                        const jobDate = job.start_date_time.split(' ')[0];
                        if (!jobsByDate[jobDate]) {
                            jobsByDate[jobDate] = [];
                        }
                        jobsByDate[jobDate].push({
                            ...job,
                            routingType,
                            techId
                        });

                        if (routingType === "customizedroute") {
                            hasCustomizedRoute = true;
                        }
                    });
                }
            });

            // console.log(jobsByDate);

            // Append jobs grouped by date
            Object.keys(jobsByDate).sort().forEach(date => {
                const rawDate = date; // Your input date
                const datenew = new Date(rawDate);

                const formattedDate = new Intl.DateTimeFormat('en-US', {
                    weekday: 'short',
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                }).format(datenew);
                const dateHeading = `<li class="list-group-item active date-heading">${formattedDate}</li>`;
                jobDiv.append(dateHeading);

                jobsByDate[date].forEach(job => {
                    const jobItem = `
                    <li class="list-group-item sortable-job" id="event_click${job.job_id}" style="cursor: pointer;" data-jobid="${job.job_id}"
                    data-description="${job.description}" data-lat="${job.customer.latitude}" data-long="${job.customer.longitude}" 
                    data-job_title="${job.job_title}" data-routing="${job.routingType}" data-techid="${job.techId}" data-date="${job.start_date_time}">
                        <h5 class="uppercase text-truncate pb-0 mb-0">#${job.job_id}-${job.job_title}</h5>
                        <p class="text-truncate pb-0 mb-0 ft13">${job.description}</p>
                        <p class="ft13 uppercase mb-0 text-truncate"><strong><i class="ri-user-line"></i> ${job.customer.name}</strong></p>
                        <div class="ft12"><i class="ri-map-pin-fill"></i>
                            ${job.customer.full_address}
                        </div>
                    </li>`;
                    jobDiv.append(jobItem);
                });
            });

            // Append Save Button if "customizedroute" exists
            if (hasCustomizedRoute) {
                if ($('#saveRoute1').length === 0) {
                    $('#jobdiv').append(
                        '<button id="saveRoute1" class="btn btn-primary mt-2" disabled>Save Routes</button>');
                }
            } else {
                $('#saveRoute1').remove();
            }

            // Add click event to list items
            jobDiv.find('.list-group-item').on('click', function() {
                const lat = parseFloat($(this).data('lat'));
                const long = parseFloat($(this).data('long'));
                const job_title = $(this).data('job_title');
                const description = $(this).data('description');
                const job_id = $(this).data('jobid');
                if (lat && long) {
                    updateMap1([{
                        customer: {
                            latitude: lat,
                            longitude: long,
                            job_title: job_title,
                            description: description,
                            job_id: job_id,
                            name: $(this).find('h6').text(),
                            full_address: $(this).find('.ft14').text()
                        }
                    }]);
                }
            });
        }
    }

    function updateMap1(techniciansData) {
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 5,
            center: {
                lat: 40.7128,
                lng: -74.0060
            } // Default center
        });

        techniciansData.forEach((data, index) => {

            const {
                latitude,
                longitude,
                name,
                full_address
            } = data.customer;

            if (!latitude || !longitude) {
                console.log(`No valid location for ${name}`);
                return;
            }

            const marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(latitude),
                    lng: parseFloat(longitude)
                },
                map,
                title: name,
                // label: `${index + 1}`
            });

            const popupRequestUrl = popupUrl.replace('__JOB_ID__', data.customer.job_id);

            $.ajax({
                url: popupRequestUrl,
                method: 'GET',
                success: function(response) {
                    if (response.popupHtml) {
                        const infoWindow = new google.maps.InfoWindow({
                            content: response.popupHtml,
                        });
                         infoWindow.open(map, marker);
                        marker.addListener("click", () => {
                            infoWindow.open(map, marker);

                            // Close info window when the button is clicked
                            google.maps.event.addListenerOnce(infoWindow, "domready", () => {
                                document.getElementById("close-info-window").addEventListener("click",
                                    () => {
                                        infoWindow.close();
                                    });
                            });
                        });

                        // Center map on the marker
                        map.setCenter(marker.getPosition());
                        map.setZoom(12);
                    }
                },
                error: function(xhr, status, error) {
                    // Log detailed error information
                    console.error("Failed to fetch popup HTML.");
                    }
            });

           
           
        });
    }
</script>

<script>
    $(document).ready(function() {
        $('#fullview').on('click', function() {
            $('#mapdiv').toggleClass('col-md-9 col-md-12');
            $('#jobdiv').toggle();
        });

        $(".list-group").sortable({
            items: '.sortable-job[data-routing="customizedroute"]',
            start: function(event, ui) {
                // Logic before sorting starts
                $(".day").droppable("disable");

            },
            stop: function(event, ui) {
                // Enable save button after sorting
                $(".day").droppable("enable");

                $('#saveRoute1').prop('disabled', false);

                let reorderedJobs = [];

                // Extract data into the array
                $('.list-group .sortable-job').each(function() {
                    reorderedJobs.push({
                        job_id: $(this).data('jobid'),
                        techid: $(this).data('techid'),
                        date: $(this).data('date'),
                        job_title: $(this).data('job_title'),
                        description: $(this).data('description'),
                        latitude: $(this).data('lat'),
                        longitude: $(this).data('long'),
                    });
                });

                // Group jobs by technician ID
                const groupedByTechId = reorderedJobs.reduce((acc, job) => {
                    if (!acc[job.techid]) {
                        acc[job.techid] = {
                            jobs: [],
                            date: job.date, // Store the date outside of jobs array
                        };
                    }
                    acc[job.techid].jobs.push(job);
                    return acc;
                }, {});

                // Convert object to array with technician IDs and associated jobs, and date outside jobs
                const finalArray = Object.keys(groupedByTechId).map(techid => ({
                    techid: techid,
                    date: groupedByTechId[techid].date, // Add date outside jobs
                    jobs: groupedByTechId[techid].jobs
                }));

                // Set the transformed data as an attribute or use it further
              $('#saveRoute1').removeAttr('data-reorderedJobIds').attr('data-reorderedJobIds', JSON.stringify(finalArray));


            }
        });

        $(document).on('click', '#saveRoute1', function() {
            let reorderedJobs = $(this).attr('data-reorderedJobIds');
            if (reorderedJobs && reorderedJobs.length > 0) {
                // Parse JSON string to an array before sending
                reorderedJobs = JSON.parse(reorderedJobs);

                $.ajax({
                    url: '{{ route('save-reordered-jobs') }}',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        jobIds: reorderedJobs, // Now sending an actual array
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }),
                    success: function(response) {
                        alert('Jobs reordered successfully!');
                    },
                    error: function(error) {
                        console.error("Error saving reordered jobs:", error);
                        alert('An error occurred while saving.');
                    }
                });
            } else {
                alert("No changes to save.");
            }
        });



        $(document).on('click', '.JobOpenModalButton', function(event) {
            event.preventDefault();
            var tech_id = $(this).data('tech-id');
            var dateDay = $(this).data('dateday');
            var routing = $('#routing').val();
            var $jobDetailsDiv = $('.openJobTechDetails');
            var chooseFrom;
            var chooseTo;

            if (dateDay === 'chooseDate') {
                chooseFrom = $('#chooseFrom').val();
                chooseTo = $('#chooseTo').val();
            }

            $jobDetailsDiv.show();
            $('#allJobsTechnician .popup-option123').attr('data-id', tech_id);
            var tech_name = $(this).data('tech-name');
            var date = $(this).data('date');
            $.ajax({
                url: '{{ route('schedule.getALlRoutingJob') }}',
                method: 'GET',
                data: {
                    tech_id: tech_id,
                    dateDay: dateDay,
                    routing: routing,
                    date: date,
                    chooseFrom: chooseFrom,
                    chooseTo: chooseTo,
                },
                success: function(response) {
                    var jobs = response;

                    var ticketShowRoute = "{{ route('tickets.show', ':id') }}";
                    $('#allJobsTechnicianLabel46').empty();
                    $('#allJobsTechnicianLabel46').append(tech_name +
                        ' - Dispatch Schedule');

                    $('.openJobTechDetails').empty();

                    if (jobs.length === 0) {
                        $('.openJobTechDetails').append(
                            '<div class="col-12"><p>There is no job available.</p></div>'
                        );
                    } else {
                        // Group jobs by date
                        var groupedJobs = jobs.reduce(function(groups, job) {
                            var dateKey = new Date(job.start_date_time)
                                .toLocaleDateString('en-US', {
                                    weekday: 'short', // Tue
                                    day: '2-digit', // 24
                                    month: 'short', // Dec
                                    year: 'numeric' // 2024
                                });
                            if (!groups[dateKey]) {
                                groups[dateKey] = [];
                            }
                            groups[dateKey].push(job);
                            return groups;
                        }, {});

                        // Generate HTML for each date group
                        Object.keys(groupedJobs).forEach(function(date) {
                            // Add a date heading
                            $('.openJobTechDetails').append(
                                `<div class="col-12">
                                        <h4 class="date-heading">${date}</h4>
                                    </div>`
                            );

                            // Add jobs for the current date
                            groupedJobs[date].forEach(function(job, index) {
                                var fieldNames = '';

                                if (
                                    job.job_model &&
                                    Array.isArray(job.job_model.fieldids) &&
                                    job.job_model.fieldids.length > 0
                                ) {
                                    fieldNames = job.job_model.fieldids
                                        .map(function(f) {
                                            return f.field_name;
                                        })
                                        .join(', ');
                                }

                                var fieldNamesBadge = fieldNames ?
                                    `<span class="badge bg-primary">${fieldNames}</span>` :
                                    '';

                                var jobHtml = `<div class="col-md-4 mb-3">
                                        <div class="card shadow-sm h-100 pp_job_info_full">
                                            <div class="card-body card-border card-shadow">
                                                <!-- Job ID and Badge -->
                                                <h5 class="card-title py-1">
                                                    <strong class="text-uppercase">
                                                        #${job.job_model ? job.job_model.id : ''}  ${fieldNamesBadge}
                                                        ${job.job_model && job.job_model.warranty_type === 'in_warranty' ? `<span class="badge bg-warning">In Warranty</span>` : ''}
                                                        ${job.job_model && job.job_model.warranty_type === 'out_warranty' ? `<span class="badge bg-danger">Out of Warranty</span>` : ''}
                                                    </strong>
                                                </h5>
                                                <div class="cls_job_order_number"><span>${index + 1}</span></div>
                                                
                                                <!-- Job Title and Description -->
                                                <div class="pp_job_info pp_job_info_box">
                                                    <h6 class="text-uppercase"> ${job.job_model ? (job.job_model.job_title.length > 20 ? job.job_model.job_title.substring(0, 20) + '...' : job.job_model.job_title) : ''} </h6>
                                                    <div class="description_info">${job.job_model ? job.job_model.description : ''}</div>
                                                    <div class="pp_job_date text-primary">
                                                        ${job.start_date_time && job.end_date_time ? formatDateRange(job.start_date_time, job.end_date_time, job.interval) : ''}
                                                    </div>
                                                </div>
                                                
                                                <!-- User Info -->
                                                <div class="pp_user_info pp_job_info_box">
                                                    <h6 class="text-uppercase"><i class="fas fa-user pe-2 fs-2"></i> ${job.job_model && job.job_model.user ? job.job_model.user.name : ''}</h6>
                                                    <div>
                                                        ${job.job_model && job.job_model.addresscustomer ? job.job_model.addresscustomer.address_line1 : ''},
                                                        ${job.job_model && job.job_model.addresscustomer ? job.job_model.addresscustomer.zipcode : ''}
                                                    </div>
                                                    <div>
                                                        ${job.job_model && job.job_model.user ? job.job_model.user.mobile : ''}
                                                    </div>
                                                </div>
                                                
                                                <!-- Equipment Info -->
                                                <div class="pp_job_info_box">
                                                    <h6 class="text-uppercase">Equipment</h6>
                                                    <div> 
                                                        ${job.job_model && job.job_model.job_appliances && job.job_model.job_appliances.appliances 
                                                            ? job.job_model.job_appliances.appliances.appliance.appliance_name 
                                                            : ''} /  
                                                        ${job.job_model && job.job_model.job_appliances && job.job_model.job_appliances.appliances && job.job_model.job_appliances.appliances.manufacturer 
                                                            ? job.job_model.job_appliances.appliances.manufacturer.manufacturer_name 
                                                            : ''} /  
                                                        ${job.job_model && job.job_model.job_appliances && job.job_model.job_appliances.appliances 
                                                            ? job.job_model.job_appliances.appliances.model_number 
                                                            : ''} / 
                                                        ${job.job_model && job.job_model.job_appliances && job.job_model.job_appliances.appliances 
                                                            ? job.job_model.job_appliances.appliances.serial_number 
                                                            : ''}
                                                    </div>
                                                </div>
                                                
                                                <div class="pp_job_info_box">
                                                    <h6 class="text-uppercase">Parts & Services</h6>
                                                    <div> 
                                                        <!-- Check and display parts -->
                                                        ${job.job_model && job.job_model.jobproductinfohasmany && job.job_model.jobproductinfohasmany.length > 0 
                                                            ? job.job_model.jobproductinfohasmany.map(product => `
                                                                    ${product.product && product.product.product_name ? `${product.product.product_name}, ` : ''}
                                                                `).join('') 
                                                            : ''
                                                        }

                                                        <!-- Check and display services -->
                                                        ${job.job_model && job.job_model.jobserviceinfohasmany && job.job_model.jobserviceinfohasmany.length > 0 
                                                            ? job.job_model.jobserviceinfohasmany
                                                                .map(service => service.service && service.service.service_name ? service.service.service_name : '')
                                                                .filter(serviceName => serviceName !== '') // Filter out any empty service names
                                                                .join(', ')  // Join with comma
                                                                .replace(/,\s*$/, '')  // Remove the last comma if present
                                                            : ''
                                                        }
                                                    </div>
                                                </div>
                                                
                                                <!-- Edit and View Buttons -->
                                                <div class="d-flex justify-content-between pt-2">
                                                    <a href="${ticketShowRoute.replace(':id', job.job_model ? job.job_model.id : '#')}?mode=edit#editdetails" target="_blank">
                                                        <button class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                    </a>
                                                    <a href="${ticketShowRoute.replace(':id', job.job_model ? job.job_model.id : '#')}" target="_blank">
                                                        <button class="btn btn-outline-primary btn-sm">
                                                            View
                                                        </button>
                                                    </a>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>`;

                                $('.openJobTechDetails').append(jobHtml);
                            });
                        });
                    }

                    $('#allJobsTechnician').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Error: AJAX request failed. Status:', status, 'Error:',
                        error);
                }

            });
        });

        function formatDateRange(startDate, endDate, interval) {
            var startDateTime = moment(
                startDate); // Assuming moment.js is available
            var endDateTime = moment(endDate);

            // Add the interval if provided
            if (interval) {
                startDateTime.add(interval, 'hours');
                endDateTime.add(interval, 'hours');
            }

            // Format the dates
            return startDateTime.format('MMM D YYYY h:mm A') + ' - ' +
                endDateTime.format('h:mm A');
        }

    });
</script>
