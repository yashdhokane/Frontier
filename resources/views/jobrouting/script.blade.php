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
            } // Center the map to a default location
        });

        // const getRandomColor = () => '#' + Math.floor(Math.random() * 16777215).toString(16);

        techniciansData.forEach((techData) => {

            const {
                latitude,
                longitude,
                name,
                full_address
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
                label: 'T'
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `<h4>${name}</h4><p>${full_address}</p>`
            });

            techMarker.addListener("click", () => infoWindow.open(map, techMarker));

            // Sort jobs in ascending order based on the array index in the best route
            const sortedJobs = techData.jobs;

            // Process waypoints and markers
            const waypoints = sortedJobs.map((job, index) => {
                if (job.customer.latitude && job.customer.longitude) {
                    return {
                        location: new google.maps.LatLng(
                            parseFloat(job.customer.latitude),
                            parseFloat(job.customer.longitude)
                        ),
                        stopover: true
                    };
                }
                return null;
            }).filter(Boolean);

            if (waypoints.length > 0) {
                const directionsService = new google.maps.DirectionsService();
                const directionsRenderer = new google.maps.DirectionsRenderer({
                    map,
                    polylineOptions: {
                        strokeColor: techData.technician.color_code,
                        strokeWeight: 2
                    },
                    suppressMarkers: true
                });

                directionsService.route({
                    origin: {
                        lat: parseFloat(latitude),
                        lng: parseFloat(longitude)
                    },
                    destination: waypoints[waypoints.length - 1].location,
                    waypoints,
                    travelMode: google.maps.TravelMode.DRIVING
                }, (result, status) => {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(result);
                    } else {
                        console.error('Directions request failed due to ' + status);
                    }
                });

                // Add customer markers with sequential numbering
                sortedJobs.forEach((job, index) => {
                    const {
                        latitude,
                        longitude,
                        name,
                        full_address
                    } = job.customer;
                    if (latitude && longitude) {
                        const customerMarker = new google.maps.Marker({
                            position: {
                                lat: parseFloat(latitude),
                                lng: parseFloat(longitude)
                            },
                            map,
                            title: `Customer ${index + 1}`,
                            label: `${index + 1}` // Sequential numbering
                        });

                        const customerInfo = new google.maps.InfoWindow({
                            content: `<h4>Customer ${index + 1}</h4><p>Name: ${name}</p><p>Address: ${full_address}</p>`
                        });

                        customerMarker.addListener("click", () => customerInfo.open(map,
                            customerMarker));
                    }
                });
            }
        });
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
                            data-lat="${job.customer.latitude}" data-long="${job.customer.longitude}">
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
                if (lat && long) {
                    updateMap1([{
                        customer: {
                            latitude: lat,
                            longitude: long,
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
                    <h4>${name}</h4>
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
