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

        // Skip if no valid jobs for this date
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
            const directionsRenderer = new google.maps.DirectionsRenderer({
                map,
                polylineOptions: {
                    strokeColor: color_code || "#FF0000", // Default to red if color_code is not provided
                    strokeWeight: 4,
                },
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

                        // Add date overlay on the path
                        const midPoint =
                            result.routes[0].overview_path[
                                Math.floor(result.routes[0].overview_path.length / 2)
                            ];

                        new google.maps.Marker({
                            position: midPoint,
                            map,
                            label: {
                                text: dateDay,
                                color: "black",
                                fontSize: "12px",
                            },
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 0, // Invisible marker for label only
                            },
                        });
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
                const jobNumber = jobIndex + 1; // Correct numbering
                if (jobLat && jobLng) {
                    const customerMarker = new google.maps.Marker({
                        position: {
                            lat: parseFloat(jobLat),
                            lng: parseFloat(jobLng),
                        },
                        map,
                        label: {
                            text: `${jobNumber}`, // Correct and unique numbering
                            color: "white",
                            fontSize: "12px",
                            fontWeight: "bold",
                        },
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 15,
                            fillColor: "#007BFF", // Customize marker color
                            fillOpacity: 1,
                            strokeWeight: 1,
                            strokeColor: "#FFFFFF", // Border color
                        },
                    });

                    const customerInfo = new google.maps.InfoWindow({
                        content: `<h4>${job.job_title}</h4><p>Name: ${name}</p><p>Address: ${full_address}</p>`,
                    });

                    customerMarker.addListener("click", () =>
                        customerInfo.open(map, customerMarker)
                    );
                }
            });
        }
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
                        <li class="list-group-item" id="event_click${job.job_id}" style="cursor: pointer;"
                            data-lat="${job.customer.latitude}" data-long="${job.customer.longitude}" data-job_title="${job.job_title}" >
                              <h5 class="uppercase mb-2 text-truncate">${job.job_title}</h5>
                            <h6 class="uppercase mb-0 text-truncate"><i class="ri-user-line"></i>${job.customer.name}</h6>
                            <div class="ft14"><i class="ri-map-pin-fill"></i>
                                ${job.customer.full_address}
                            </div>
                        </li>`;

                        jobDiv.append(jobItem);
                    });
                } else {
                    // const noJobsItem = `
                    //     <li class="list-group-item mb-2" style="cursor: default;">
                    //         <span style="font-size: 15px; font-weight: 700; letter-spacing: 1px;">
                    //             No Jobs Found for Technician: ${technician.technician.name}
                    //         </span><br />
                    //     </li>`;
                    // jobDiv.append(noJobsItem);
                }
            });

            // Add click event to list items
            jobDiv.find('.list-group-item').on('click', function() {
                const lat = parseFloat($(this).data('lat'));
                const long = parseFloat($(this).data('long'));
                const job_title = $(this).data('job_title');
                if (lat && long) {
                    updateMap1([{
                        customer: {
                            latitude: lat,
                            longitude: long,
                            job_title: job_title,
                            name: $(this).find('h6').text(),
                            full_address: $(this).find('.ft14').text()
                        }
                    }]);
                }
            });
        } else {
            // const noTechniciansItem = `
            //     <li class="list-group-item mb-2" style="cursor: default;">
            //         <span style="font-size: 15px; font-weight: 700; letter-spacing: 1px;">
            //             No Technicians Found
            //         </span><br />
            //     </li>`;
            // jobDiv.append(noTechniciansItem);
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
                    <h5>${data.customer.job_title}</h5>
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
