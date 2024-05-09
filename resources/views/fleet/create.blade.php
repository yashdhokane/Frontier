<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')
    <div class="container-fluid">

        <div class="page-breadcrumb" style="padding: 0px 0px 10px 0px;">
            <div class="row">
                <div class="col-5 align-self-center">
					<h4 class="page-title">Add New Vehicles</h4>
				</div>
                <div class="col-7 text-end">
					<a href="https://dispatchannel.com/portal/vehicles" class="justify-content-center d-flex align-items-center"><i class="ri-truck-line" style="margin-right: 8px;"></i> Back to Vehicles List </a>
				</div>
             </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- basic table -->
        @if (session('success'))
			
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


		<div class="row">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body card-border shadow">

						<form action="{{ route('fleet.store') }}" method="POST" enctype="multipart/form-data">
							@csrf
							<div class="row">
								<div class="col-md-12">
									<div class="mb-2">
										<label for="vehicle_description" class="control-label bold col-form-label required-field">Vehicle Details</label>
										<textarea  rows="3" name="vehicle_description" id="vehicle_description" class="form-control" placeholder="Add Vehicle Details" required></textarea>
									</div>
									<div class="mb-2">
										<label for="vehicle_summary" class="control-label bold col-form-label required-field">Vehicle Summary</label>
										<textarea  rows="3" name="vehicle_summary" id="vehicle_summary" class="form-control" placeholder="Add complete summary about vehicle" required></textarea>
									</div>
 									<div class="mb-2">
										<label for="technician_id" class="control-label bold mb5 col-form-label required-field">Select Technician</label>
										<select name="technician_id" id="" class="form-control" required>
											<option value="">----- Select Technician -----</option>
											@foreach ($user as $item)
												<option value="{{ $item->id }}">{{ $item->name }}</option>
											@endforeach
										</select>
									</div> 
									<div class="mb-3 mt-4">	
										<button type="submit" class="btn btn-primary"> Submit</button>
									</div> 						                
								</div>
							</div>
						</form>

					</div>
				</div>
				
			</div>
		</div>
		
    </div>

     

@section('script')
    <script>
        $(document).ready(function() {
            // Select the password and new password input fields
            var passwordField = $('input[name="password"]');
            var newPasswordField = $('input[name="confirm_password"]');
            var passwordMatchMessage = $('#passwordMatchMessage');

            // Select the form and attach a submit event listener
            $('form').submit(function(event) {
                // Prevent the form from submitting
                event.preventDefault();

                // Get the values of the password and new password fields
                var passwordValue = passwordField.val();
                var newPasswordValue = newPasswordField.val();

                // Check if the passwords match
                if (passwordValue === newPasswordValue) {
                    // If passwords match, submit the form
                    this.submit();
                } else {
                    // Show danger message
                    passwordMatchMessage.removeClass('alert-success').addClass('alert-danger').html(
                        'Passwords do not match. Please enter matching passwords.').show();
                }
            });
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
        var appendedCities = []; // Array to store already appended cities

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
@endsection
@endsection
