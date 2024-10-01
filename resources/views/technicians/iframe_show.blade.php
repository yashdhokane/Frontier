    @if (Route::currentRouteName() != 'dash')


@extends('home')
@section('content')
<!-- Page wrapper  -->
@endif
       <style>
            #main-wrapper[data-layout="vertical"][data-sidebartype="mini-sidebar"] .page-wrapper {
                margin-left: 0px !important;
            }

            /* .card-body {
                                                    padding: 0px !important;
                                                } */

            .container-fluid {
                padding: 0px !important;
            }

            #main-wrapper[data-layout=vertical][data-header-position=fixed] .topbar {
                display: none !important;
            }

            #main-wrapper[data-layout=vertical][data-sidebar-position=fixed] .left-sidebar {
                display: none !important;
            }

            #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
                margin-left: 0px !important;
            }

            #main-wrapper[data-layout=vertical][data-header-position=fixed] .page-wrapper {
                padding-top: 0px !important;
            }

            .page-wrapper {
                padding: 0px !important;
            }

            /* Make iframe content scrollable */
            html,
            body {
                overflow: auto !important;
                /* Allow scrolling */
                margin: 0;
                /* Remove default margins */
                padding: 0;
                /* Remove default padding */
            }

            .page-breadcrumb{
                padding:0px !important;
            }
        </style>
@php
$address = '';
if (isset($location->address_line1) && $location->address_line1 !== '') {
$address .= $location->address_line1 . ', ';
}
if (isset($location->address_line2) && $location->address_line2 !== '') {
$address .= $location->address_line2 . ', ';
}
if (isset($user->Location->city) && $user->Location->city !== '') {
$address .= $user->Location->city . ', ';
}
if (isset($location->state_name) && $location->state_name !== '') {
$address .= $location->state_name . ', ';
}
if (isset($location->zipcode) && $location->zipcode !== '') {
$address .= $location->zipcode;
}
@endphp


<!-- -------------------------------------------------------------- -->
<!-- Bread crumb and right sidebar toggle -->
<!-- -------------------------------------------------------------- -->


         <div class="page-breadcrumb search-breadcrumb">
        <div class="card searched-card">
            <div class="row card-body  searched-card-body">
                <!-- Search Input on the Left -->
                <div class="col-8 align-self-center searched-card-body-div">
                    <h4 class="page-title1" >
            {{ $commonUser->name }}
            <small class="text-muted" style="font-size: 12px;">Technician</small>
        </h4>
                </div>

                <!-- Dropdown Group on the Right -->
                <div class="col-4 align-self-center">
                    <div class="d-flex justify-content-end">
                        <!-- Dropdown Menu for Actions -->
                          <div class="dropdown">
            <a href="#" class="link" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <!-- Three vertical dots icon -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical feather-sm">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="12" cy="5" r="1"></circle>
                    <circle cx="12" cy="19" r="1"></circle>
                </svg>
            </a>
            <!-- Dropdown menu -->
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <li>
                    <a class="dropdown-item" href="{{ route('iframe_index') }}">
                        <i class="ri-contacts-line"></i> Technician List
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('iframe_create') }}">
                        <i class="ri-user-add-line"></i> Add New Technician
                    </a>
                </li>
              
            </ul>
        </div>
    </div>
                    </div>
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

    @if (Session::has('success'))
    <div class="alert_wrap">
        <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
            {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
        </div>
    </div>
    @endif

    <!-- -------------------------------------------------------------- -->
    <!-- Start Page Content -->
    <!-- -------------------------------------------------------------- -->
    <!-- Row -->
    <div class="row">
        <!-- Column -->

        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-12 col-xlg-9 col-md-7">
            <!-- ---------------------
                                                    start Timeline
                                                ---------------- -->
            <div class="card">
                <!-- Tabs -->
                <ul class="nav nav-pills custom-pills nav-fill flex-column flex-sm-row user_profile_tabs" id="pills-tab"
                    role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" href="#profile_tab"
                            role="tab" aria-controls="pills-profile" aria-selected="true"><i
                                class="ri-contacts-line"></i> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-setting-tab" data-bs-toggle="pill" href="#calls_tab" role="tab"
                            aria-controls="pills-setting" aria-selected="false"><i class="fas fa-calendar-check"></i>
                            Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-payment-tab" data-bs-toggle="pill" href="#payment_tab" role="tab"
                            aria-controls="pills-payments" aria-selected="false"><i
                                class="ri-money-dollar-box-line"></i> Payments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#estimate_tab"
                            role="tab" aria-controls="pills-timeline" aria-selected="false"><i
                                class="far ri-price-tag-2-line"></i> Estimates</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#fleet_tab" role="tab"
                            aria-controls="pills-timeline" aria-selected="false"><i class="ri-truck-line"></i>
                            Vehicle</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#edit_profile_tab"
                            role="tab" aria-controls="pills-timeline" aria-selected="false"><i class="ri-edit-fill"></i>
                            Edit Details</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#others_tab" role="tab"
                            aria-controls="pills-timeline" aria-selected="false"><i class="ri-draft-line"></i> Notes</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                            aria-expanded="false"> <span>More</span> </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" id="pills-timeline-tab" data-bs-toggle="pill"
                                    href="#edit_service_tab" role="tab" aria-controls="pills-timeline"
                                    aria-selected="false"><i class="ri-service-line fas"></i> Service Area</a></li>
                            <li><a class="dropdown-item" data-bs-toggle="pill" href="#parts_tab" role="tab"
                                    aria-controls="pills-timeline" aria-selected="false"><i
                                        class="mdi mdi-book-multiple"></i> Parts</a></li>
                            <li><a class="dropdown-item" data-bs-toggle="pill" href="#settings_tab" role="tab"
                                    aria-controls="pills-timeline" aria-selected="false"><i
                                        class="fas ri-settings-2-line"></i> Settings</a></li>
                            <li><a class="dropdown-item" data-bs-toggle="pill" href="#activity_tab" role="tab"
                                    aria-controls="pills-timeline" aria-selected="false"><i
                                        class="fas fa-calendar-check"></i> Schedule</a></li>
                        </ul>
                    </li>

                </ul>



                <!-- Tabs -->
                <div class="tab-content" id="pills-tabContent">

                    <div class="card-border shadow tab-pane fade show active" id="profile_tab" role="tabpanel"
                        aria-labelledby="pills-profile-tab">
                        <div class="card-body">

                         @include('technicians.profile_tab')



                        </div>
                    </div>


                    <div class="tab-pane fade" id="calls_tab" role="tabpanel" aria-labelledby="pills-setting-tab">
                        <div class="card-body card-border shadow">
                            @include('commonfiles.calls_for_profiles')

                        </div>
                    </div>

                    <div class="tab-pane fade" id="payment_tab" role="tabpanel" aria-labelledby="pills-payment-tab">
                        <div class="card-body card-border shadow">
                            @include('commonfiles.payment_for_profiles')

                        </div>
                    </div>


                    <div class="tab-pane fade" id="fleet_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                      @include('technicians.fleet_tab')
                    </div>
                    <script>
                        document.getElementById('submitBtnfleet').addEventListener('click', function(event) {
                             // Custom form submission logic
                             event.preventDefault();
                             var form = document.getElementById('fleetForm');
                            
                             // Additional validation or processing can go here
                            
                              // Submit the form
                              form.submit();
                            });
                    </script>

                    <div class="tab-pane fade" id="edit_profile_tab" role="tabpanel"
                        aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            @include('technicians.edit_profile')
                        </div>
                    </div>


                    <div class="tab-pane fade" id="others_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            @include('commonfiles.notes_for_profiles')

                        </div>
                    </div>

                    <div class="tab-pane fade" id="edit_service_tab" role="tabpanel"
                        aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            @include('technicians.service_area')
                        </div>
                    </div>

                    <div class="tab-pane fade" id="estimate_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">

                            @include('commonfiles.estimate_for_profiles')
                        </div>

                    </div>



                    <div class="tab-pane fade" id="parts_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            @include('technicians.parts_view_and_assign')
                        </div>
                    </div>

                    <div class="tab-pane fade" id="settings_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            @include('commonfiles.setting_for_profiles')
                        </div>
                    </div>

                    <div class="tab-pane fade" id="activity_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">

                     
                    @include('technicians.schedule_tab')

                    </div>





                </div>
            </div>
            <!-- ---------------------
                                                    end Timeline
                                                ---------------- -->
        </div>
        <!-- Column -->
    </div>
    <!-- Row -->
    <!-- -------------------------------------------------------------- -->
    <!-- End PAge Content -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Right sidebar -->
    <!-- -------------------------------------------------------------- -->
    <!-- .right-sidebar -->
    <!-- -------------------------------------------------------------- -->
    <!-- End Right sidebar -->
    <!-- -------------------------------------------------------------- -->
</div>
<!-- -------------------------------------------------------------- -->
<!-- End Container fluid  -->
@section('script')
<script>
    @foreach ($userAddresscity as $location)
            var latitude = {{ $location->latitude }}; // Example latitude
            var longitude = {{ $location->longitude }}; // Example longitude

            // Construct the URL with the latitude and longitude values
            var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' +
                latitude +
                ',' + longitude + '&zoom=13';

            document.getElementById('map{{ $location->address_id }}').src = mapUrl;
        @endforeach
</script>
<script>
    $(document).ready(function() {
          $('#toggleNewPassword').click(function() {
        var newPasswordInput = $('#newPassword');
        var type = newPasswordInput.attr('type') === 'password' ? 'text' : 'password';
        newPasswordInput.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    $('#toggleConfirmPassword').click(function() {
        var confirmPasswordInput = $('#confirmPassword');
        var type = confirmPasswordInput.attr('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });
        var passwordField = $('input[name="password"]');
        var confirmPasswordField = $('input[name="confirm_password"]');
        var passwordMatchMessage = $('#passwordMatchMessage');
        var passwordStrengthMessage = $('#passwordStrengthMessage');

        function checkPasswordStrength(password) {
            var strongPasswordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#^$!%*?&])[A-Za-z\d@#^$!%*?&]{8,}$/;
            return strongPasswordPattern.test(password);
        }

        function showMessage(element, type, message) {
            element.removeClass('alert-success alert-danger').addClass('alert-' + type).html(message).show();
            setTimeout(function() {
                element.hide();
            }, 5000);
        }

        passwordField.on('keyup', function() {
            var passwordValue = passwordField.val();
            if (checkPasswordStrength(passwordValue)) {
                showMessage(passwordStrengthMessage, 'success', 'Strong password.');
            } else {
                showMessage(passwordStrengthMessage, 'danger', 'Weak password.');
            }
        });

        $('form').submit(function(event) {
            event.preventDefault();
            var passwordValue = passwordField.val();
            var confirmPasswordValue = confirmPasswordField.val();

            if (passwordValue === confirmPasswordValue) {
                if (checkPasswordStrength(passwordValue)) {
                    this.submit();
                } else {
                    showMessage(passwordStrengthMessage, 'danger', 'Weak password.');
                }
            } else {
                showMessage(passwordMatchMessage, 'danger', 'Passwords do not match.');
            }
        });

        $('#changePasswordModal').on('hidden.bs.modal', function() {
            passwordField.val('');
            confirmPasswordField.val('');
            passwordMatchMessage.hide();
            passwordStrengthMessage.hide();
        });
    });
</script>
<script>
    const firstNameInput = document.getElementById('first_name');
        const lastNameInput = document.getElementById('last_name');
        const displayNameInput = document.getElementById('display_name');

        // Function to update the display name field
        function updateDisplayName() {
            const firstName = firstNameInput.value.trim();
            const lastName = lastNameInput.value.trim();

            // Concatenate first and last name
            const displayName = firstName + ' ' + lastName;

            // Set the display name input value
            displayNameInput.value = displayName;
        }

        // Listen for input changes on first and last name fields
        firstNameInput.addEventListener('input', updateDisplayName);
        lastNameInput.addEventListener('input', updateDisplayName);
</script>
<script>
    document.getElementById('openChangePasswordModal').addEventListener('click', function(event) {
            event.preventDefault();
            $('#changePasswordModal').modal('show');
        });

        // Close modal when close button is clicked
        $('.close').on('click', function() {
            $('#changePasswordModal').modal('hide');
        });
</script>
<script>
    $(document).ready(function() {

            $('#state_id').change(function() {

                var stateId = $(this).val();

                var citySelect = $('#city');

                citySelect.html('<option selected disabled value="">Loading...</option>');



                // Make an AJAX request to fetch the cities based on the selected state

                $.ajax({

                    url: "{{ route('getcities') }}", // Correct route URL

                    type: 'GET',

                    data: {

                        state_id: stateId

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

            $('#city').change(function() {

                var cityId = $(this).val();

                var cityName = $(this).find(':selected').text().split(' - ')[
                    0]; // Extract city name from option text

                getZipCode(cityId, cityName); // Call the function to get the zip code

            });

        });


        // Function to get zip code
        function searchCity() {
            // Initialize autocomplete
            $("#city").autocomplete({
                source: function(request, response) {
                    // Clear previous autocomplete results
                    $("#autocomplete-results").empty();

                    $.ajax({
                        url: "{{ route('autocomplete.city') }}",
                        data: {
                            term: request.term
                        },
                        dataType: "json",
                        type: "GET",
                        success: function(data) {
                            response(data);
                        },
                        error: function(response) {
                            console.log("Error fetching city data:", response);
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $("#city").val(ui.item.city);
                    $("#city_id").val(ui.item.city_id);
                    return false;
                }
            }).data("ui-autocomplete")._renderItem = function(ul, item) {
                // Render each item
                var listItem = $("<li>").text(item.city).appendTo("#autocomplete-results");
                listItem.data("city_id", item.city_id);
                return listItem;
            };

            // Handle click on autocomplete results
            $("#autocomplete-results").on("click", "li", function() {
                var cityName = $(this).text();
                var cityId = $(this).data("city_id");

                // Check if cityId is retrieved properly
                console.log("Selected City ID:", cityId);

                // Set the city ID
                $("#city_id").val(cityId);

                // Set the city name
                $("#city").val(cityName);

                // Hide autocomplete results
                $("#autocomplete-results").hide();
            });

            // Handle input field click
            $("#city").click(function() {
                // Show autocomplete results box
                $("#autocomplete-results").show();
            });

            // Clear appended city when input is cleared
            $("#city").on("input", function() {
                var inputVal = $(this).val();
                if (inputVal === "") {
                    // If input is cleared, re-initialize autocomplete
                    $("#autocomplete-results").empty(); // Clear appended cities
                    searchCity(); // Re-initialize autocomplete
                }
            });
        }

        // Function to get zip code
        function getZipCode(cityId, cityName) {
            $.ajax({
                url: "{{ route('getZipCode') }}", // Adjust route URL accordingly
                type: 'GET',
                data: {
                    city_id: cityId,
                    city_name: cityName
                },
                dataType: 'json',
                success: function(data) {
                    var zipCode = data.zip_code; // Assuming the response contains the zip code
                    $('#zip_code').val(zipCode); // Set the zip code in the input field
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching zip code:', error);
                }
            });
        }
</script>
<script>
    document.getElementById("submitButton").addEventListener("click", function(event) {
            var comment = document.getElementById("comment").value.trim();
            if (comment === "") {
                event.preventDefault(); // Prevent form submission
                alert("Please add a comment before submitting.");
            }
        });
</script>
@endsection
    @if (Route::currentRouteName() != 'dash')

@endsection
@endif
