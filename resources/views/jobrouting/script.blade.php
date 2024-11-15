
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&callback=initMap" async
    defer></script>

<script>
    const techniciansData = {!! $responseJson !!}; 

    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 5,
            center: { lat: 40.7128, lng: -74.0060 }
        });

        const getRandomColor = () => '#' + Math.floor(Math.random() * 16777215).toString(16);

        techniciansData.forEach((techData) => {
            const { latitude, longitude, name, full_address } = techData.technician;
            if (!latitude || !longitude) return console.log(`No valid location for ${name}`);

            const techMarker = new google.maps.Marker({
                position: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
                map, title: name, label: 'T'
            });

            const infoWindow = new google.maps.InfoWindow({ content: `<h4>${name}</h4><p>${full_address}</p>` });
            techMarker.addListener("click", () => infoWindow.open(map, techMarker));

            const waypoints = techData.jobs
                .map(job => job.customer.latitude && job.customer.longitude && ({
                    location: new google.maps.LatLng(parseFloat(job.customer.latitude), parseFloat(job.customer.longitude)),
                    stopover: true
                }))
                .filter(Boolean);

            if (waypoints.length > 0) {
                const directionsService = new google.maps.DirectionsService();
                const directionsRenderer = new google.maps.DirectionsRenderer({
                    map,
                    polylineOptions: { strokeColor: getRandomColor(), strokeWeight: 5 },
                    suppressMarkers: true
                });

                directionsService.route({
                    origin: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
                    destination: waypoints[waypoints.length - 1].location,
                    waypoints,
                    travelMode: google.maps.TravelMode.DRIVING
                }, (result, status) => {
                    if (status === google.maps.DirectionsStatus.OK) directionsRenderer.setDirections(result);
                    else console.error('Directions request failed due to ' + status);
                });

                waypoints.forEach((wp, i) => {
                    const customer = techData.jobs[i].customer;
                    const customerMarker = new google.maps.Marker({
                        position: wp.location, map, title: `Customer ${i + 1}`, label: `${i + 1}`
                    });

                    const customerInfo = new google.maps.InfoWindow({
                        content: `<h4>Customer ${i + 1}</h4><p>Name: ${customer.name}</p><p>Address: ${customer.full_address}</p>`
                    });

                    customerMarker.addListener("click", () => customerInfo.open(map, customerMarker));
                });
            }
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