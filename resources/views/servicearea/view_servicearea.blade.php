{{-- <input type="hidden" name="area_id" value="{{ $servicearea->area_id }}"> --}}
<div>
    <h4 class="modal-title" id="myModalLabel">{{ $servicearea->area_name }}</h4>
</div>
<div class="form-group" style="margin-top:10px;">


    <div class="row">
        <div class="col-md-12 mb-3">
            <title>Map Display</title>
            <style>
                /* Ensure the map takes up the full height of the viewport */
                #map {
                    height: 300px;
                    /* Set a specific height or adjust as needed */
                }
            </style>
            </head>

            <body>
                <script async defer
                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&callback=initMap">
                </script>

               <script>
        function initMap() {
            // Get latitude and longitude values from the Blade page
            var latitude = parseFloat("{{ $servicearea->area_latitude }}");
            var longitude = parseFloat("{{ $servicearea->area_longitude }}");
            var areaRadius = parseFloat("{{ $servicearea->area_radius }}");

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 13, // You can adjust the zoom level as needed
                center: {
                    lat: latitude,
                    lng: longitude
                }
            });

            // Add a marker for the specified location
            var marker = new google.maps.Marker({
                position: {
                    lat: latitude,
                    lng: longitude
                },
                map: map,
                title: 'Location'
            });

            // Add a circle overlay with the specified radius
            var circle = new google.maps.Circle({
                map: map,
                radius: areaRadius * 1000, // Convert from km to meters
                fillColor: '#FF0000', // Red color
                fillOpacity: 0.3,
                strokeColor: '#FF0000',
                strokeOpacity: 1,
                strokeWeight: 1
            });

            // Bind the circle to the marker
            circle.bindTo('center', marker, 'position');
        }
    </script>


                <!-- Add a div to hold the map -->
                <div id="map"></div>
        </div>

    </div>
    <div class="modal-footer">
        {{-- <button type="submit" class="btn btn-info">
            Save
        </button> --}}
        <button type="button" class="btn btn-info waves-effect" data-bs-dismiss="modal">
            Cancel
        </button>
    </div>