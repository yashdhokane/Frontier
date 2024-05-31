
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&loading=async&callback=initMap&libraries=marker">
    </script>
    <script>
        $(document).ready(function() {

            var openInfoWindowpop = null;

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

            async function initMap(userLat, userLng, areaZoom, radius) {

                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: areaZoom,
                    center: {
                        lat: userLat,
                        lng: userLng
                    }
                });

                setMarkers(map);
            }

            setTimeout(function() {

                var selectedOption = $('#territory').find('option:selected');
                var area_id = selectedOption.val();

                if (area_id == '') {
                    initMap(40.73061, -73.935242, 5, 2000000);
                } else {
                    var lat = parseFloat(selectedOption.data('lat'));
                    var lag = parseFloat(selectedOption.data('lag'));
                    var radius = 2000000;
                    initMap(lat, lag, 5, radius);
                }
            }, 1500);

            var jobIds = [];
            var count = 0;

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
                
                 // Validation check for technician selection
                if (!technician) {
                    // Display validation message if technician is not selected
                    if (!$('.technician').next('.validation-error').length) {
                        $('.technician').after('<small class="validation-error text-danger">Please select a technician.</small>');
                    }
                    return; // Prevent form submission
                } else {
                    // Remove validation message if technician is selected
                    $('.technician').next('.validation-error').remove();
                }

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

                            initMap(lat, lag, 5, radius);
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