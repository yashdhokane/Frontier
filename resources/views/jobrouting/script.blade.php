<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&callback=initMap" async
    defer></script>

<script>
    // const techniciansData = {!! $responseJson !!};

    $(document).ready(function() {
        fetchFilteredData();
        $('#routingTriggerSelect').select2('destroy');
        $('#routingTriggerSelect').select2();
        let isProcessing = false; // Flag to prevent recursive calls

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

            // Add technician marker
            const techMarker = new google.maps.Marker({
                position: {
                    lat: parseFloat(latitude),
                    lng: parseFloat(longitude)
                },
                map,
                title: name,
                label: {
                    text: "T",
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
                content: `<h4>${name}</h4><p>${full_address}</p>`
            });

            techMarker.addListener("click", () => infoWindow.open(map, techMarker));

            // Check if dateDay is "nextdays" and process jobs
            if (dateDay === "nextdays") {
                // Group jobs by individual dates
                const jobsByDate = techData.jobs.reduce((acc, job) => {
                    console.log(job);
                    const jobDate = job.start_date_time; // Assuming each job has a `date` property
                    if (!acc[jobDate]) acc[jobDate] = [];
                    acc[jobDate].push(job);
                    return acc;
                }, {});

                // Render routes for each individual date
                Object.entries(jobsByDate).forEach(([jobDate, jobs], index) => {
                    renderRouteForDate(map, techData.technician, jobs, jobDate, index);
                });
            } else {
                // Render a single route for the technician if dateDay is not "nextdays"
                renderRouteForDate(map, techData.technician, techData.jobs, dateDay, 0);
            }
        });
    }

    function renderRouteForDate(map, technician, jobs, dateDay, index) {
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

        // Filter jobs with valid customer locations
        const validJobs = jobs.filter(
            (job) =>
            job.customer.latitude &&
            job.customer.longitude &&
            !isNaN(parseFloat(job.customer.latitude)) &&
            !isNaN(parseFloat(job.customer.longitude))
        );

        if (validJobs.length === 0) {
            console.log(`No jobs for date ${dateDay}. Skipping path rendering.`);
            return;
        }

        // Sort jobs by proximity to technician's location
        const sortedJobs = validJobs.sort((a, b) => {
            const distanceA = calculateDistance(
                parseFloat(latitude),
                parseFloat(longitude),
                parseFloat(a.customer.latitude),
                parseFloat(a.customer.longitude)
            );
            const distanceB = calculateDistance(
                parseFloat(latitude),
                parseFloat(longitude),
                parseFloat(b.customer.latitude),
                parseFloat(b.customer.longitude)
            );
            return distanceA - distanceB;
        });

        // Generate waypoints
        const waypoints = sortedJobs.map((job) => ({
            location: new google.maps.LatLng(
                parseFloat(job.customer.latitude),
                parseFloat(job.customer.longitude)
            ),
            stopover: true,
        }));

        if (waypoints.length > 0) {
            const directionsService = new google.maps.DirectionsService();
            const strokeStyle = getPolylineStyle(dateDay, color_code);

            const directionsRenderer = new google.maps.DirectionsRenderer({
                map,
                polylineOptions: strokeStyle, // Apply dynamic stroke style
                suppressMarkers: true,
            });

            directionsService.route({
                    origin: {
                        lat: parseFloat(latitude),
                        lng: parseFloat(longitude),
                    },
                    destination: waypoints[waypoints.length - 1].location,
                    waypoints,
                    travelMode: google.maps.TravelMode.DRIVING,
                },
                (result, status) => {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(result);
                    } else {
                        console.error("Directions request failed due to " + status);
                    }
                }
            );

            // Add numbered customer markers based on proximity
            sortedJobs.forEach((job, jobIndex) => {
                const {
                    latitude: jobLat,
                    longitude: jobLng,
                    name,
                    full_address
                } = job.customer;
                const jobNumber = jobIndex + 1; // Use 1-based numbering
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

                    const customerInfo = new google.maps.InfoWindow({
                        content: `<h4><span class="btn btn-primary badge">#${job.job_id}</span> ${job.job_title}</h4>
                <p class="mb-2"><i class="ri-user-line"></i> ${name}</p>
                <p class="mb-2"><i class="ri-map-pin-fill"></i> ${full_address}</p>`,
                    });

                    customerMarker.addListener("click", () => customerInfo.open(map, customerMarker));
                }
            });

        }
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
        const routing = $('#routing').val();
        const technicians = $('#routingTriggerSelect').val();

        // Make an AJAX request to fetch filtered data
        $.ajax({
            url: "{{ route('jobrouting.filter') }}",
            type: "GET",
            data: {
                dateDay: dateDay,
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

    $(document).on('change', '#dateDay, #routing', function() {
        fetchFilteredData();
    });

    function updateMap(techniciansData) {
        initMap(techniciansData);
    }

    function updateJobDiv(techniciansData) {
        const jobDiv = $('#jobdiv .list-group');
        jobDiv.empty(); // Clear existing list items

        if (techniciansData.length > 0) {
            techniciansData.forEach(technician => {
                if (technician.jobs && technician.jobs.length > 0) {
                    technician.jobs.forEach(job => {
                        const jobItem = `
                        <li class="list-group-item" id="event_click${job.job_id}" style="cursor: pointer;" data-jobid="${job.job_id}"
                            data-lat="${job.customer.latitude}" data-long="${job.customer.longitude}" data-job_title="${job.job_title}" >
                              <h5 class="uppercase text-truncate"><span class="btn btn-primary badge">#${job.job_id}</span> ${job.job_title}</h5>
                            <h6 class="uppercase mb-0 text-truncate"><i class="ri-user-line"></i> ${job.customer.name}</h6>
                            <div class="ft14"><i class="ri-map-pin-fill"></i>
                                ${job.customer.full_address}
                            </div>
                        </li>`;

                        jobDiv.append(jobItem);
                    });
                } else {

                }
            });

            // Add click event to list items
            jobDiv.find('.list-group-item').on('click', function() {
                const lat = parseFloat($(this).data('lat'));
                const long = parseFloat($(this).data('long'));
                const job_title = $(this).data('job_title');
                const job_id = $(this).data('jobid');
                if (lat && long) {
                    updateMap1([{
                        customer: {
                            latitude: lat,
                            longitude: long,
                            job_title: job_title,
                            job_id: job_id,
                            name: $(this).find('h6').text(),
                            full_address: $(this).find('.ft14').text()
                        }
                    }]);
                }
            });
        } else {

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

            const infoWindow = new google.maps.InfoWindow({
                content: `
                <div>
                    <h5><span class="btn btn-primary badge">#${data.customer.job_id}</span> ${data.customer.job_title}</h5>
                    <h6>${name}</h6>
                    <p>${full_address}</p>
                </div>`
            });

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
        });
    }
</script>


<script>
    $(document).ready(function() {
        $('#fullview').on('click', function() {
            $('#mapdiv').toggleClass('col-md-9 col-md-12');
            $('#jobdiv').toggle();
        });
    });
</script>
