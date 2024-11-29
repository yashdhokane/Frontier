@extends('home')
@section('content')
    <!-- -------------------------------------------------------------- -->

    <!-- ------------------------------------------------- -->


    <!-- Bread crumb and right sidebar toggle -->

    <!-- -------------------------------------------------------------- -->


    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->

    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb pt-0">
        <div class="row">
            <div class="col-9 align-self-center">
                <h4 class="page-title">Service Area</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Settings</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Service Area</li>
                        </ol>
                    </nav>
                </div>
            </div>
           <!-- <div class="col-3 align-self-center">
                <a href="{{ route('servicearea.create') }}" id="btn-add-contact" class="btn btn-info"><i
                        class="ri-map-pin-line"> </i> Add New Service Area</a>
            </div> -->
            <div class="col-3 align-self-center">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#service-area-modal">Add Service Area</button>
           </div>
        </div>
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->

    <div class="container-fluid pt-2">

        <!-- -------------------------------------------------------------- -->

        <!-- Start Page Content -->

        <!-- -------------------------------------------------------------- -->

        <!-- 1. card with img -->




        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <div class="row card card-border shadow mr-0">

            <!-- Add Popup Model -->
            <div id="service-area" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" style="max-width:500px;">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center">
                            <h4 class="modal-title" id="myModalLabel">Edit Service Area</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                fdprocessedid="k7jfjv"></button>
                        </div>
                        <div class="modal-body" id="appendbodyservicearea">

                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <!-- End Popup Model -->

            <!-- view popup model -->
            <div id="service-area-view" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" style="max-width:800px;">
                    <div class="modal-content">
                        <div class="modal-header d-flex align-items-center">
                            {{--   <h4 class="modal-title" id="myModalLabel">Service Area Details</h4> --}}
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                fdprocessedid="k7jfjv"></button>
                        </div>
                        <div class="modal-body" id="appendbodyserviceareaview">

                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <!--end -->


            <!-- Row -->

            <div class="row mt-2" id="service-area-container">

                <!-- column -->
                @foreach ($servicearea as $index => $item)
                    <div class="col-lg-4 col-md-6 col-xl-2">

                        <!-- Card -->



                        <div class="card card-border shadow mb-4">

                            <div class="mparea">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <title>Map Display</title>
                                        <style>
                                            /* Ensure the map takes up the full height of the viewport */
                                            #map1{{ $index }} {
                                                height: 200px;
                                                /* Set a specific height or adjust as needed */
                                            }
                                        </style>
                                        </head>

                                        <body>
                                            <!-- Add a div to hold the map -->
                                            <div id="map1{{ $index }}"></div>
                                        </body>
                                    </div>
                                </div>
                            </div>

                            <div class="card-bodyX mx-3 mb-3">
                                <h5 class="card-title uppercase text-info">{{ $item->area_name ?? null }}</h5>
                                <p class="card-text mb-2">{{ $item->area_description ?? null }}</p>
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#service-area"
                                    class="btn btn-xs btn-primary serviceareaedit" id="{{ $item->area_id }}">Edit</a>
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#service-area-view"
                                    class="btn btn-xs btn-primary serviceareaview mx-2" id="{{ $item->area_id }}">View</a>
                            </div>

                        </div>

                        <!-- Card -->

                    </div>
                @endforeach
<div class="modal fade" id="service-area-modal" tabindex="-1" aria-labelledby="serviceAreaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceAreaModalLabel">Add Service Area</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="service-area-form" action="{{ route('servicearea.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="area_name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="area_name" id="area_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="area_description" class="form-label">Description</label>
                        <textarea class="form-control" name="area_description" id="area_description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="area_radius" class="form-label">Radius</label>
                        <select class="form-select" name="area_radius" id="area_radius" required>
                            <option value="1">1KM</option>
                            <option value="2">2KM</option>
                            <option value="5">5KM</option>
                            <option value="10">10KM</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="area_latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" name="area_latitude" id="area_latitude" required>
                    </div>
                    <div class="mb-3">
                        <label for="area_longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" name="area_longitude" id="area_longitude" required>
                    </div>
                    <button type="button" id="submit-service-area" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

                <script async defer
                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&callback=initMap"></script>
                <script>
                    function initMap() {
                        @foreach ($servicearea as $index => $item)
                            // Get latitude and longitude values from the Blade page
                            var latitude = parseFloat("{{ $item->area_latitude }}");
                            var longitude = parseFloat("{{ $item->area_longitude }}");
                            var areaRadius = parseFloat("{{ $item->area_radius }}");

                            var map = new google.maps.Map(document.getElementById('map1{{ $index }}'), {
                                zoom: 10, // You can adjust the zoom level as needed
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
                        @endforeach
                    }
                </script>


            </div>
            <!-- -------------------------------------------------------------- -->
        </div>
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Page wrapper  -->
    <!-- -------------------------------------------------------------- -->


@section('script')
    <script>
        $(document).on('click', '.serviceareaedit', function() {
            $("#service-area").modal({

                backdrop: "static",
                keyboard: false,
            });
            var entry_id = $(this).attr('id');
            $("#appendbodyservicearea").empty();
            $.ajax({
                url: 'editservicearea',
                type: 'get',
                data: {
                    entry_id: entry_id

                },
                dataType: 'json',
                success: function(data) {
                    $("#appendbodyservicearea").html(data.html);
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.serviceareaview', function() {
            $("#service-area-view").modal({

                backdrop: "static",
                keyboard: false,
            });
            var entry_id = $(this).attr('id');
            $("#appendbodyserviceareaview").empty();
            $.ajax({
                url: 'viewservicearea',
                type: 'get',
                data: {
                    entry_id: entry_id

                },
                dataType: 'json',
                success: function(data) {
                    $("#appendbodyserviceareaview").html(data.html);
                }
            });
        });
    </script>

    <script>
   $(document).ready(function() {
    // Handle form submission via AJAX
    $('#submit-service-area').on('click', function(e) {
        e.preventDefault(); // Prevent default behavior

        // Use FormData to get the form fields
        const formData = new FormData($('#service-area-form')[0]);

        $.ajax({
            url: $('#service-area-form').attr('action'), // Form action URL
            method: 'POST',
            data: formData,
            processData: false, // Prevent automatic data processing
            contentType: false, // Set content type to false for FormData
            success: function(response) {
                if (response.success) {
                    // Dynamically add new card to the page
                    const newCard = `
                        <div class="col-lg-4 col-md-6 col-xl-2">
                            <div class="card card-border shadow mb-4">
                                <div class="mparea">
                                    <div id="map1${response.data.id}" style="height: 200px;"></div>
                                </div>
                                <div class="card-bodyX mx-3 mb-3">
                                    <h5 class="card-title uppercase text-info">${response.data.area_name}</h5>
                                    <p class="card-text mb-2">${response.data.area_description}</p>
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#service-area" class="btn btn-xs btn-primary serviceareaedit" id="${response.data.area_id}">Edit</a>
                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#service-area-view" class="btn btn-xs btn-primary serviceareaview mx-2" id="${response.data.area_id}">View</a>
                                </div>
                            </div>
                        </div>
                    `;
                    $('#service-area-container').append(newCard);

                    // Initialize Google Map for the new card
                    initializeMap(response.data.id, response.data.area_latitude, response.data.area_longitude, response.data.area_radius);

                    // Close modal and reset form
                    $('#service-area-modal').modal('hide');
                    $('#service-area-form')[0].reset();
                } else {
                    alert('Failed to add service area.');
                }
            },
            error: function(xhr) {
                alert('Please Fill all required fields.');
            }
        });
    });

    // Reset form when modal is closed
    $('#service-area-modal').on('hidden.bs.modal', function() {
        $('#service-area-form')[0].reset();
    });
});

// Function to initialize Google Map for a specific map container
function initializeMap(mapId, latitude, longitude, radius) {
    const map = new google.maps.Map(document.getElementById('map1' + mapId), {
        zoom: 10, // You can adjust the zoom level as needed
        center: { lat: parseFloat(latitude), lng: parseFloat(longitude) }
    });

    // Add a marker for the specified location
    const marker = new google.maps.Marker({
        position: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
        map: map,
        title: 'Location'
    });

    // Add a circle overlay with the specified radius
    const circle = new google.maps.Circle({
        map: map,
        radius: radius * 1000, // Convert from km to meters
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
@endsection
@endsection
