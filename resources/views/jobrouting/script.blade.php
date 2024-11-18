<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&callback=initMap" async
    defer></script>

<script>
    // const techniciansData = {!! $responseJson !!};

    $(document).ready(function() {
        fetchFilteredData();
    });

    function initMap(techniciansData) {
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 5,
            center: {
                lat: 40.7128,
                lng: -74.0060
            } // Center the map to a default location
        });

        const getRandomColor = () => '#' + Math.floor(Math.random() * 16777215).toString(16);

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
                        strokeColor: getRandomColor(),
                        strokeWeight: 5
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
        // Get selected values from the filters
        const dateDay = $('#dateDay').val();
        const routing = $('#routing').val();
        const technicians = $('#routingTriggerSelect').val();

        // Make an AJAX request to fetch filtered data
        $.ajax({
            url: "{{ route('jobrouting.filter') }}", // Replace with your backend route
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
                } else {
                    console.log(response.message || "No data found for the selected filters.");
                }
            },
            error: function() {
                console.log("An error occurred while fetching data. Please try again.");
            }
        });
    }

    $(document).on('change', '#dateDay, #routing, #routingTriggerSelect', function() {
        fetchFilteredData();
    });

    function updateMap(techniciansData) {
        initMap(techniciansData);
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
