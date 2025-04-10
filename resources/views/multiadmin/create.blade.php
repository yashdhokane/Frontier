@extends('home')
@section('content')

<style>
    .required-field::after {

        content: " *";

        color: red;

    }

    #autocomplete-results {
        position: absolute;
        background-color: #fff;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        width: calc(30% - 2px);
        /* Subtract border width from input width */
    }

    #autocomplete-results ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    #autocomplete-results li {
        padding: 8px 12px;
        cursor: pointer;
    }

    #autocomplete-results li:hover {
        background-color: #f0f0f0;
    }

    #autocomplete-results li:hover::before {
        content: "";
        display: block;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        z-index: -1;
    }

    /* Ensure hover effect covers the entire autocomplete result */
    #autocomplete-results li:hover::after {
        content: "";
        display: block;
        position: absolute;
    }
</style>
<form id="myForm" action="{{ route('multiadmin.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
   
    
        <!-- -------------------------------------------------------------- -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
    <div class="row withoutthreedottest">
        <div class="col-6 align-self-center">
            <h4 class="page-title">NEW ADMIN</h4>
             <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#.">Admin Management</a></li>
                        <li class="breadcrumb-item"><a href="#">Admin New</a></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-6 text-end px-4">
            <a href="{{ route('multiadmin.index') }}" class="btn btn-secondary text-white">
                <i class="ri-contacts-line me-2"></i> Back to Admin List
            </a>
        </div>
    </div>
</div>

        <!-- -------------------------------------------------------------- -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- -------------------------------------------------------------- -->
        <style>
            .custom-alert {
                width: 98%;
                /* Adjust the width as needed */
                margin: 0 auto;
                /* Center the alert horizontally */
            }
        </style>
        <div class="custom-alert">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>


    
    @if(Session::has('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    @endif
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->
    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->


        <!-- row -->

        <div class="row mt-3">

            <div class="col-lg-9 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body border-top">
                        <div id="passwordMatchMessage" class="alert" style="display:none; margin-bottom:5px;"></div>

                        <h4 class="card-title">Account Info</h4>

                        <div class="row">
						    <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="control-label bold mb5 col-form-label required-field">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder=""
                                        required />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="password"
                                        class="control-label bold mb5 col-form-label required-field">Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="" required />


                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="confirm_password"
                                        class="control-label bold mb5 col-form-label required-field">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" placeholder="" required />
                                </div>
                            </div>
                        </div>

                        {{-- --}}
                        <h4 class="card-title mt-4">Admin Details</h4>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="first_name" class="control-label bold mb5 col-form-label required-field">First
                                        Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="" required />


                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="last_name" class="control-label bold mb5 col-form-label required-field">Last
                                        Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        placeholder="" required />
                                </div>
                            </div>
							<div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="display_name"
                                        class="control-label bold mb5 col-form-label required-field">Display Name (shown
                                        on invoice)</label>
                                    <input type="text" class="form-control" id="display_name" name="display_name"
                                        placeholder="" required />
                                </div>
                            </div>
                        </div>
                        
						<div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="mobile_phone" class="control-label bold mb5 col-form-label required-field">Mobile
                                        Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="mobile_phone"
                                        name="mobile_phone" placeholder="" required />
                                    <small id="name" class="form-text text-muted">Don’t add +1. Only add number
                                        without space.</small>
                                </div>
                            </div>
							<div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="home_phone" class="control-label bold mb5 col-form-label">Home Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="home_phone"
                                        name="home_phone" placeholder="" />
                                </div>
                            </div>
							<div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="work_phone" class="control-label bold mb5 col-form-label">Work Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="work_phone"
                                        name="work_phone" placeholder="" />
                                </div>
                            </div>
                        </div>
						
						<h4 class="card-title mt-4">Address Details</h4>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="address1" class="control-label bold mb5 col-form-label required-field">Address
                                        Line 1
                                        (Street)</label>
                                    <input type="text" class="form-control" id="address1" name="address1" placeholder=""
                                        required />
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="mb-3">
                                <label for="address_unit" class="control-label bold mb5 col-form-label">Address Line 2</label>
                                <input type="text" class="form-control" id="address_unit" name="address_unit"
                                    placeholder="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="city"
                                        class="control-label bold mb5 col-form-label required-field">City</label>
                                    {{-- <select class="form-select" id="city" name="city" required>
                                        <option selected disabled value="">Select City...</option>
                                    </select> --}}
                                    <input type="text" class="form-control" id="city" name="city" oninput="searchCity()"
                                        required />
                                    {{-- <input type="text" class="form-control" id="city_id" name="city_id"
                                        oninput="searchCity1()" required />
                                    --}}
                                    <div id="autocomplete-results"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="state_id"
                                        class="control-label bold mb5 col-form-label required-field">State</label>
                                    <select class="form-select me-sm-2" id="state_id" name="state_id" required>
                                        <option selected disabled value="">Select State...</option>
                                        @foreach($locationStates as $locationState)
                                        <option value="{{ $locationState->state_id }}">{{ $locationState->state_name
                                            }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="zip_code"
                                        class="control-label bold mb5 col-form-label required-field">Zip</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder=""
                                        required />
                                </div>
                            </div>
                        </div>
						
                        {{-- <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="email" class="control-label bold mb5 col-form-label required-field">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder=""
                                        required />
                                </div>
                            </div>
                        </div> --}}
						
						<h4 class="card-title mt-4">Other Details</h4>
						<div class="row">
							<div class="col-sm-12 col-md-4">
								<div class="mb-3">
									<label for="dob" class="control-label bold mb5 col-form-label required-field">DOB</label>
									<!-- Change the input type to "date" -->
									<input type="date" class="form-control" id="dob" name="dob" placeholder=""
										required />
								</div>
							</div>
 							<div class="col-sm-12 col-md-4">
								<div class="mb-3">
									<label for="license_number"
										class="control-label bold mb5 col-form-label required-field">License Number</label>
									<input type="text" class="form-control" id="license_number" name="license_number"
										placeholder="" required />
								</div>
							</div>
							<div class="col-sm-12 col-md-4">
								<div class="mb-3">
									<label for="ssn" class="control-label bold mb5 col-form-label ">SSN(Social Security
										Number)</label>
									<input type="text" class="form-control" id="ssn" name="ssn" placeholder="" />
								</div>
							</div>
 						</div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="control-label bold mb5 col-form-label">Image Upload</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*" />
                                </div>
                            </div>
							<div class="col-sm-12 col-md-4">
                                    <div class="mb-3">
                                        <label for="tag_id" class="control-label bold mb5 col-form-label">Tags</label>
                                        <select class="form-control select2-hidden-accessible" id="select2-with-tags"
                                            name="tag_id[]" multiple="multiple" data-bgcolor="light"
                                            data-select2-id="select2-data-select2-with-tags" tabindex="-1"
                                            aria-hidden="true" style="width: 100%">
                                            @foreach($tags as $tag)
                                            <option value="{{ $tag->tag_id }}">{{ $tag->tag_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-3 d-flex align-items-stretch">
                <!--<div class="card w-100">
                    <div class="card-body border-top">
                        <h4 class="card-title mb-3">&nbsp;</h4>
                        <div class="row">
                            <div class="row">
                                
                            </div>
                        </div>
                        <div class="row" style="display:none;">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="role" class="control-label col-form-label">Role</label>
                                    <input type="text" class="form-control" id="role" name="role" placeholder=""
                                        value="admin" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>-->
            </div>

        </div>
        <!-- End row -->


        <!-- row -->
        <div class="row">

           

            {{-- <div class="col-lg-3 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body border-top">
                        <h4 class="card-title mb-3">&nbsp;</h4>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6991603.699017098!2d-100.0768425!3d31.168910300000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864070360b823249%3A0x16eb1c8f1808de3c!2sTexas%2C%20USA!5e0!3m2!1sen!2sin!4v1701086703789!5m2!1sen!2sin"
                                    width="100%" height="auto" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            --}}

        </div>
        <!-- End row -->

        <!-- row -->

        <!-- End row -->

        <!-- row -->
        <div class="row">
            <div class="p-3 border-top">
                <div class="action-form">
                    <div class="mb-3 mb-0 text-center">
                        <button id="submitBtn" type="submit"
                            class="btn btn-info rounded-pill px-4 waves-effect waves-light">Save</button>
                        <a href="{{route('multiadmin.index')}}"> <button type="button"
                                class="btn btn-dark rounded-pill px-4 waves-effect waves-light">Cancel</button> </a>
                    </div>
                </div>
            </div>
        </div>


        <!-- End row -->




        <!-- -------------------------------------------------------------- -->
        <!-- End PAge Content -->
        <!-- -------------------------------------------------------------- -->
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Container fluid  -->

  </div>

    </div>
</form>
@section('script')
<script>
    $(document).ready(function(){
        // Select the password and new password input fields
        var passwordField = $('input[name="password"]');
        var newPasswordField = $('input[name="confirm_password"]');
        var passwordMatchMessage = $('#passwordMatchMessage');

        // Select the form and attach a submit event listener
        $('form').submit(function(event){
            // Prevent the form from submitting
            event.preventDefault();

            // Get the values of the password and new password fields
            var passwordValue = passwordField.val();
            var newPasswordValue = newPasswordField.val();

            // Check if the passwords match
            if(passwordValue === newPasswordValue){
                // If passwords match, submit the form
                this.submit();
            } else {
                // Show danger message
                passwordMatchMessage.removeClass('alert-success').addClass('alert-danger').html('Passwords do not match. Please enter matching passwords.').show();
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

                    citySelect.html('<option selected disabled value="">Select City...</option>');

                    $.each(data, function(index, city) {

                        citySelect.append('<option value="' + city.city_id + '">' + city.city + ' - ' + city.zip + '</option>');

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

            var cityName = $(this).find(':selected').text().split(' - ')[0]; // Extract city name from option text

            getZipCode(cityId, cityName); // Call the function to get the zip code

        });

    });

    // Function to get zip code
   var appendedCities = []; // Array to store already appended cities

function searchCity() {
$("#city").autocomplete({
source: function(request, response) {
$.ajax({
url: "{{ route('autocomplete.city') }}",
data: {
term: request.term
},
dataType: "json",
type: "GET",
success: function(data) {
var uniqueCities = []; // Array to store unique cities from the response
data.forEach(function(item) {
if (!appendedCities.includes(item.city)) {
uniqueCities.push(item);
appendedCities.push(item.city);
}
});
response(uniqueCities);
},
error: function(xhr, status, error) {
console.log("Error fetching city data:", error);
}
});
},
minLength: 3,
select: function(event, ui) {
$("#city").val(ui.item.city);
$("#city_id").val(ui.item.city_id);
$("#autocomplete-results").empty();
return false;
}
}).data("ui-autocomplete")._renderItem = function(ul, item) {
return $("<li>").text(item.city).appendTo("#autocomplete-results");
    };

    $("#autocomplete-results").on("click", "li", function() {
    var cityName = $(this).text();
    var cityId = $(this).data("city_id");
    $("#city_id").val(cityId);
    $("#city").val(cityName);
    $("#autocomplete-results").hide();
    });

    $("#city").click(function() {
    $("#autocomplete-results").show();
    });

    $("#city").on("input", function() {
    var inputText = $(this).val().trim();
    if (inputText === "") {
    $("#autocomplete-results").empty();
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
        success: function(data){
            var zipCode = data.zip_code; // Assuming the response contains the zip code
            $('#zip_code').val(zipCode); // Set the zip code in the input field
        },
        error: function(xhr, status, error){
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