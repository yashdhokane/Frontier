@extends('home')
@section('content')
    <!-- -------------------------------------------------------------- -->
   
        <!-- ------------------------------------------------- -->


        <!-- Bread crumb and right sidebar toggle -->

        <!-- -------------------------------------------------------------- -->


        <!-- Page wrapper  -->
        <!-- -------------------------------------------------------------- -->
   
            <!-- -------------------------------------------------------------- -->
            <div class="page-breadcrumb">
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
                    <div class="col-3 align-self-center">
						<a href="{{ route('servicearea.create') }}" id="btn-add-contact" class="btn btn-info"><i class="ri-map-pin-line"> </i> Add New Service Area</a>
					</div>
                </div>
            </div>
            <!-- -------------------------------------------------------------- -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- -------------------------------------------------------------- -->
            <!-- -------------------------------------------------------------- -->
            <!-- Container fluid  -->
            <!-- -------------------------------------------------------------- -->

            <div class="container-fluid">

                <!-- -------------------------------------------------------------- -->

                <!-- Start Page Content -->

                <!-- -------------------------------------------------------------- -->

                <!-- 1. card with img -->




                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                


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
                <div id="service-area-view" class="modal fade in" tabindex="-1" role="dialog"
                    aria-labelledby="myModalLabel" aria-hidden="true">
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

                <div class="row mt-2">

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
                                                #map1{{$index}} {
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

                    <script async defer
                        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&callback=initMap"></script>
                    <script>
                        function initMap() {
                            @foreach ($servicearea as $index => $item)
                                // Get latitude and longitude values from the Blade page
                                var latitude = parseFloat("{{ $item->area_latitude }}");
                                var longitude = parseFloat("{{ $item->area_longitude }}");
                                var areaRadius = parseFloat("{{ $item->area_radius }}");

                                var map = new google.maps.Map(document.getElementById('map1{{$index}}'), {
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
                    <!-- column -->

                    <!-- column -->



                    <!-- column -->

                    <!-- column -->


                    <!-- column -->

                    <!-- column -->



                    <!-- column -->

                    <!-- column -->



                    <!-- column -->

                    <!-- column -->

                    <!-- column -->

                </div>

                <!-- Row -->

                <!-- 1. end card with img -->



                <!-- 2. card with body -->



                <!-- -------------------------------------------------------------- -->
                <!-- End Container fluid  -->
                <!-- -------------------------------------------------------------- -->
                <!-- -------------------------------------------------------------- -->
                <!-- footer -->
                <!-- -------------------------------------------------------------- -->

                <!-- -------------------------------------------------------------- -->
                <!-- End footer -->
                <!-- -------------------------------------------------------------- -->
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
@endsection
@endsection
