@extends('home')
@section('content')
    <style>
        .scroll-container {
            height: 750px;
            overflow-y: auto;
        }

        .error {
            color: rgb(218, 45, 45);
        }
    </style>
    <div class="page-wrapper" style="display:inline;">
        <!-- Container fluid  -->
        <!-- -------------------------------------------------------------- -->
        <div class="container-fluid" style="padding-top: 0px;">
            <div class="row">
                <div class="container">
                    <div class="row mb-3">
                      
                        <div class="col-12 reschedulejob" style="display: none">
                            <form class="rescheduleForm" method="post">
                                <div class="row">
                                    <div class="col-12 rescheduleList"> </div>
                                </div>
                            </form>
                            <div class="row">
                                 <div class="col-12  py-2 px-3">
                                    <button type="button" class="btn waves-effect waves-light btn-info reschedulebutton"><i class="ri-calendar-check-line"></i> Reschedule Jobs
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col bg-light py-2 px-3 card-border">
                            <ul class="list-group scroll-container reschedule_user_list">
                                <li class="list-group-item">
                                    <div class="form-group mb-4">
                                        <label class="me-sm-2 py-2 bold" for="inlineFormCustomSelect">Select Territory</label>
                                        <select class="form-select me-sm-2 territory" id="territory" onchange="reloadPage()">
                                            <option value="">-- Select Territory --</option>
                                            @php
                                            $selectedAreaId = request()->input('area_id') ?? ($locationServiceSouthWest->area_id ?? null);
                                        @endphp
                                        
                                        @foreach ($locationServiceArea as $value)
                                            <option 
                                                data-lat="{{ $value->area_latitude }}" 
                                                value="{{ $value->area_id }}"
                                                @if ($selectedAreaId == $value->area_id)
                                                    selected
                                                @endif
                                                data-lag="{{ $value->area_longitude }}"
                                                data-radius="{{ $value->area_radius }}"
                                            >
                                                {{ $value->area_name }}
                                            </option>
                                        @endforeach
                                        

                                        
                                        
                                        </select>
                                        <span class="error territory_error"></span>
                                    </div>
                                </li>
                                @if (isset($data) && !empty($data->count()))
                                    @foreach ($data as $key => $value)
                                        <li class="list-group-item" id="event_click{{ $value->assign_id }}"
                                            style="cursor: pointer;"> 
											<h6 class="uppercase mb-0">{{ $value->subject }}</h6>
                                            <div class="ft14"><i  class="ri-user-location-fill"></i> {{ $value->name }}<br/> {{ $value->address . ', ' . $value->city . ', ' . $value->state }}
											</div>
                                         </li>
                                    @endforeach
                                @else
                                    <li class="list-group-item mb-2" id="event_click" style="cursor: pointer;">
                                        <span style="font-size: 15px;font-weight: 700;letter-spacing: 1px;">
                                            No Data Found</span><br />
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-9 bg-light py-2 px-3 card-border">
                            <div id="map" style="height: 750px !important; width: 100% !important;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&callback=initMap"></script>
    <script>
        $(document).ready(function() {

            var openInfoWindowpop = null;

            function initMap(userLat, userLng, areaZoom,radius) {

                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: areaZoom,
                    center: {
                        lat: userLat,
                        lng: userLng
                    }
                });

                setMarkers(map);
            }

            function setMarkers(map) {

                <?php
                if (isset($data) && !empty($data)) {
                    foreach ($data as $key => $val) {
                        if (!empty($val->latitude) && !empty($val->longitude)) {
                            echo 'google.maps.event.addDomListener(event_click' . $val->assign_id . ", 'click', function() {map.setCenter(new google.maps.LatLng(" . $val->latitude . ',' . $val->longitude . '));map.setZoom(15);google.maps.event.trigger(marker' . $val->assign_id . ' , "click");});';
                        }
                    }
                }
                ?>


                @if (isset($data) && !empty($data))
                    @foreach ($data as $marker)
                        @if (!empty($marker->latitude) && !empty($marker->longitude))

                            var marker{{ $marker->assign_id }} = new google.maps.Marker({
                                position: {
                                    lat: {{ $marker->latitude }},
                                    lng: {{ $marker->longitude }}
                                },
                                map: map,
                                title: '{{ $marker->name }}'
                            });

                            marker{{ $marker->assign_id }}.addListener('click', function() {
                                $.ajax({
                                    url: '{{ route('map.getMarkerDetails') }}',
                                    method: 'GET',
                                    data: {
                                        id: {{ $marker->job_id }}
                                    },
                                    success: function(response) {
                                        if (response.content) {
                                            if (openInfoWindowpop) {
                                                openInfoWindowpop.close();
                                            }
                                            openInfoWindowpop = openInfoWindow(
                                                marker{{ $marker->assign_id }},
                                                response.content);
                                        } else {
                                            console.error('Error fetching marker content.');
                                        }
                                    },
                                    error: function() {
                                        console.error('Error fetching marker content.');
                                    }
                                });
                            });
                        @endif
                    @endforeach
                @endif

                function openInfoWindow(marker, content) {
                    var infoWindow = new google.maps.InfoWindow({
                        content: content
                    });
                    infoWindow.open(map, marker);
                }

            }

            $(document).ready(function() {

                var selectedOption = $('#territory').find('option:selected');
                var area_id = selectedOption.val();

                if (area_id == '') {
                    initMap(40.73061, -73.935242, 5, 2000000);
                } else {
                    var lat = parseFloat(selectedOption.data('lat'));
                    var lag = parseFloat(selectedOption.data('lag'));
                    var radius = 2000000; // 2,000 km
                    initMap(lat, lag, 5, radius);
                }
            });

           

            var count = 0;
            var jobIds = [];

            $("body").on('click', '.reschedule', function(e) {
                var selectedOption = $('#territory').find('option:selected');
                var area_id = selectedOption.val();

                var error = 0;

                // if ($('.technician option:selected').val() == '') {
                //     $('.technicians_error').text('Please select Technicaian for reschedule job.');
                //     error++;
                // }

                if ($('.territory option:selected').val() == '') {
                    $('.territory_error').text('Please select Territory for reschedule job.');
                    error++;
                }

                if (error == 0) {

                    if (jobIds.includes($(this).attr('data-job_id'))) {

                        Swal.fire({
                            icon: 'warning',
                            title: 'Reschedule Process',
                            text: 'This job is already undergoing the rescheduling process.',
                        });

                    } else {

                        jobIds.push($(this).attr('data-job_id'));

                        $.ajax({
                            url: "{{ route('get.jobDetails') }}",
                            data: {
                                job_id: $(this).attr('data-job_id'),
                                count: count,
                                area_id: area_id,
                            },
                            type: 'GET',
                            success: function(data) {

                                $('.reschedulejob').show();
                                $('.rescheduleList').append(data);
                                count++;
                                $('.reschedulebutton').text('Reschedule (' + count + ') Jobs');


                            }
                        });
                    }


                }

            });

            $("body").on('change', '.click', function(e) {
                jobIds.length = 0

            });

            $("body").on('change', '.start_date_time', function(e) {

                var initialValue = $(this).val();
                var minAttributeValue = $(this).attr("min");
                if ($(this).val() === "") {
                    $(this).val(minAttributeValue);
                }

            });

            $("body").on('click', '.reschedulebutton', function(e) {

                var form = $('.rescheduleForm')[0];
                var params = new FormData(form);
                var technician = $('.technician option:selected').val();

                params.append('technician_id', technician);

                $('.reschedulebutton').prop('disabled', true);
                $('.reschedulebutton').text('processing...');

                $.ajax({
                    url: "{{ route('reschedule.job') }}",
                    data: params,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {

                        if (data == 'true') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Reschedule Successful',
                                text: 'The reschedule job successful!',
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Something went wrong !",
                            });
                        }

                        $('.reschedulebutton').prop('disabled', false);
                        $('.reschedulejob').hide();
                        $('.rescheduleList').empty();

                        var selectedOption = $('.territory').find('option:selected');
                        var area_id = selectedOption.val();
                        jobIds.length = 0;
                        count = 0;

                        if (area_id == '') {

                            initMap(40.73061, -73.935242, 5, 2000000);

                        } else {

                            var lat = parseFloat(selectedOption.data('lat'));
                            var lag = parseFloat(selectedOption.data('lag'));
                            var radius = parseFloat(selectedOption.data('radius'));

                            initMap(lat, lag,5, radius);
                        }
                    }
                });
            });

        });
    </script>
    <script>
     function reloadPage() {

                var selectedValue = document.getElementById("territory").value;

                if (selectedValue) {
                    var url = "{{ route('map') }}" + "?area_id=" + selectedValue;
                    window.location.href = url;
                } else {
                    var url = "{{ route('map') }}";
                    window.location.href = url;
                }
            }
    </script>
@endsection
@endsection
