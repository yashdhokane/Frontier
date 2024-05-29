


@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet">

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script src="{{ url('public/admin/schedule/script.js') }}"></script>


      <script>
        document.addEventListener("DOMContentLoaded", function() {
            function getDateFromUrl() {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get('date');
            }

            function isCurrentDate(dateStr) {
                const currentDate = new Date();
                const urlDate = new Date(dateStr);
                return currentDate.toISOString().split('T')[0] === urlDate.toISOString().split('T')[0];
            }

            function fetchAndAppendTable() {
                fetch("{{ route('get.table.content') }}")
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("table-container").innerHTML = data;
                        console.log('done');
                    })
                    .catch(error => console.error("Error fetching table content:", error));
            }

            const dateFromUrl = getDateFromUrl();
            const currentDateStr = new Date().toISOString().split('T')[0];

            if (!dateFromUrl || isCurrentDate(dateFromUrl)) {
                // Fetch and append the table every 30 seconds
                setInterval(fetchAndAppendTable, 30000);

                // Initial fetch
                fetchAndAppendTable();
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.event_start_time').hide();
            $('.event_end_time').hide();
            $('.f_start').hide();
            $('.s_to').hide();

            $(document).on('change', '.event_type', function() {
                var event_type = $(this).val();
                if (event_type == 'full') {
                    $('.event_start_date').show();
                    $('.event_end_date').show();
                    $('.event_start_time').hide();
                    $('.event_end_time').hide();
                    $('.f_start').hide();
                    $('.s_to').hide();
                } else {
                    $('.event_start_date').show();
                    $('.event_start_time').show();
                    $('.event_end_date').hide();
                    $('.event_end_time').show();
                    $('.f_start').show();
                    $('.s_to').show();
                }
            });
        });

        $(document).ready(function() {

            $('#datepicker').hide(); // Hide the input field initially
            $('#datepicker-container').datepicker({
                format: 'yyyy-mm-dd', // Specify the format
                autoclose: true, // Close the datepicker when a date is selected
                todayHighlight: true // Highlight today's date
            }).on('changeDate', function(selected) {
                var selectedDate = new Date(selected.date);
                var formattedDate = selectedDate.getFullYear() + '-' + (selectedDate.getMonth() + 1) + '-' +
                    selectedDate.getDate();
                var scheduleLink = 'schedule?date=' + formattedDate; // Direct path
                window.location.href = scheduleLink;
            }); // Initialize the date picker on the container


            $('#searchTechnician').on('input', function() {
                var searchText = $(this).val().toLowerCase().trim();
                $('.technician-item').each(function() {
                    var technicianName = $(this).find('label').text().toLowerCase();

                    if (technicianName.includes(searchText)) {
                        $(this).removeClass('d-none');
                        $(this).addClass('d-flex');
                    } else {
                        $(this).addClass('d-none');
                        $(this).removeClass('d-flex');
                    }
                });
            });

            $(document).on('change', '.technician_check', function() {
                var isChecked = $(this).prop('checked');
                var id = $(this).data('id'); // Retrieve the value of the data-id attribute

                if (isChecked) {
                    // Hide elements with class tech_th that match the id
                    $('.tech_th[data-tech-id="' + id + '"]').show();
                    $('.timeslot_td[data-technician_id="' + id + '"]').show();
                } else {
                    // Show elements with class tech_th that match the id
                    $('.tech_th[data-tech-id="' + id + '"]').hide();
                    $('.timeslot_td[data-technician_id="' + id + '"]').hide();
                }
            });



        });



        document.addEventListener('DOMContentLoaded', function() {
            const timeDropdowns = document.querySelectorAll('.time-dropdown');

            // Function to generate time options
            function generateTimeOptions(dropdown) {
                const options = [];
                const now = new Date();
                const currentHour = now.getHours();
                const currentMinute = now.getMinutes();
                const halfHour = (currentMinute < 30) ? '00' : '30';

                // Start from the current half-hour interval
                for (let hour = currentHour; hour < 24; hour++) {
                    for (let minute of ['00', '30']) {
                        // Skip if the current time has passed
                        if ((hour === currentHour && minute < halfHour) || hour < currentHour) {
                            continue;
                        }
                        const formattedHour = (hour % 12 === 0) ? 12 : hour % 12; // Convert to 12-hour format
                        const ampm = (hour < 12) ? 'AM' : 'PM'; // Determine AM or PM
                        const formattedMinute = minute;
                        const formattedTime = `${formattedHour}:${formattedMinute} ${ampm}`;
                        options.push(`<option value="${formattedTime}">${formattedTime}</option>`);
                    }
                }

                // Append options to the dropdown
                dropdown.innerHTML = options.join('');
            }

            // Call the function to generate time options for each dropdown
            timeDropdowns.forEach(function(dropdown) {
                generateTimeOptions(dropdown);
            });
        });

        $('.eventSchedule').on('click', function() {
            var id = $(this).attr('data-id');
            console.log(id);
            $('#event_technician_id').val(id);
        });

        $('#cancelBtn').on('click', function() {

            $('#newCustomer').modal('hide');
            $('#create').modal('show');

        });

        $('#addEvent').submit(function(e) {
            e.preventDefault(); // Prevent default form submission

            var formData = new FormData(this); // 'this' refers to the form DOM element

            // Make an AJAX request to submit the form data
            $.ajax({
                url: $(this).attr('action'), // Get the form action attribute
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(data) {
                    // Handle success response here

                    if (data.success === true) {
                        // If success is true, close the current modal
                        $('#event').modal('hide');
                        // Display a success message using SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Event added successfully.'
                        }).then(function() {
                            // Reset form fields
                            $('#addEvent')[0].reset();
                            location.reload();

                        });
                    }
                },

                error: function(xhr, status, error) {
                    console.error('Error submitting form data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Operation failed. Please try again.' + error
                    });
                }
            });
        });
    </script>


    {{-- this for the schedule page --}}
    <script>
        $(document).ready(function() {
            var timezoneName = '{{ $timezoneName }}'
            $('#selectDates').datepicker({
                format: 'yyyy-mm-dd', // Specify the format
                autoclose: true, // Close the datepicker when a date is selected
                todayHighlight: true // Highlight today's date
            }).on('changeDate', function(selected) {
                var selectedDate = new Date(selected.date);
                var formattedDate = selectedDate.getFullYear() + '-' + (selectedDate.getMonth() + 1) + '-' +
                    selectedDate.getDate();
                var scheduleLink = 'schedule?date=' + formattedDate; // Direct path
                window.location.href = scheduleLink;
            });

              $(document).on('click', '.clickPoint', function (e) {
                e.stopPropagation();
                var popupDiv = $(this).next('.popupDiv');

                // Hide any previously displayed popupDiv elements
                $('.popupDiv').not(popupDiv).hide();

                // Position and show the clicked popupDiv
                var mouseX = e.clientX;
                var mouseY = e.clientY;

                // Calculate the distance from the clicked point to the edges of the window
                var distanceTop = mouseY;
                var distanceBottom = $(window).height();
                var distanceLeft = mouseX;
                var distanceRight = $(window).width();

                // Calculate the margin values in pixels
                var topMargin = 30;
                var bottomMargin = 30;
                var leftMargin = 20;
                var rightMargin = 20;

                // Calculate the position of the popupDiv based on margins and distances
                var position = {};
                if (distanceTop > distanceBottom && distanceTop > popupDiv.outerHeight() + bottomMargin) {
                    position.top = popupDiv.outerHeight() - bottomMargin;
                } else {
                    position.top = topMargin;
                }

                if (distanceLeft > distanceRight && distanceLeft > popupDiv.outerWidth() + rightMargin) {
                    position.left = popupDiv.outerWidth() - rightMargin;
                } else {
                    position.left = leftMargin;
                }

                // Set the position and show the popupDiv
                popupDiv.css(position).toggle();
            });

            // Hide the popup div when clicking outside of it
            $(document).click(function() {
                $('.popupDiv').hide();
            });
        });
    </script>
    {{-- end this  --}}



    <script>
        $(document).ready(function() {

            $('#anotherstate_id').change(function() {

                var stateId = $(this).val();

                var citySelect = $('#anothercity');

                citySelect.html('<option selected disabled value="">Loading...</option>');



                // Make an AJAX request to fetch the cities based on the selected state

                $.ajax({

                    url: "{{ route('getcitiesanother') }}", // Correct route URL

                    type: 'GET',

                    data: {

                        anotherstate_id: stateId

                    },

                    dataType: 'json',

                    success: function(data) {

                        citySelect.html(
                            '<option selected disabled value="">Select City...</option>');

                        $.each(data, function(index, city) {

                            citySelect.append('<option value="' + city.city_id + '">' +
                                city.city + ' - ' + city.zip + '</option>');

                        });

                    },

                    error: function(xhr, status, error) {

                        console.error('Error fetching cities:', error);

                    }

                });

            });



            // Trigger another function to get zip code after selecting a city

            $('#anothercity').change(function() {

                var cityId = $(this).val();

                var cityName = $(this).find(':selected').text().split(' - ')[
                    0]; // Extract city name from option text

                getZipCodeanother(cityId, cityName); // Call the function to get the zip code

            });

        });



        // Function to get zip code

        function getZipCodeanother(cityId, cityName) {

            $.ajax({

                url: "{{ route('getZipCodeanother') }}", // Adjust route URL accordingly

                type: 'GET',

                data: {

                    anothercity_id: cityId,

                    anothercity_name: cityName

                },

                dataType: 'json',

                success: function(data) {

                    var anotherzip_code = data.anotherzip_code; // Assuming the response contains the zip code

                    $('#anotherzip_code').val(anotherzip_code); // Set the zip code in the input field

                },

                error: function(xhr, status, error) {

                    console.error('Error fetching zip code:', error);

                }

            });

        }
    </script>

    <script>
        function addNewAddress() {

            var addressCardTwo = document.getElementById("adresscardtwo");

            if (addressCardTwo.style.display === "none") {

                addressCardTwo.style.display = "block";

            } else {

                addressCardTwo.style.display = "none";

            }

            var addressCardTwoone = document.getElementById("adresscardtwo1");

            if (addressCardTwoone.style.display === "none") {

                addressCardTwoone.style.display = "block";

            } else {

                addressCardTwoone.style.display = "none";

            }

        }
    </script>
@endsection