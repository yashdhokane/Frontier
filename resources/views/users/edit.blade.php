@if(Route::currentRouteName() != 'dash')


@extends('home')

@section('content')
@endif




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



{{-- model change password --}}

<div id="changePasswordModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change Password</h4>
                <button type="button" class="close btn btn-danger" data-dismiss="modal" style="opacity: 1;" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="passwordMatchMessage" class="alert" style="display:none; margin-bottom:5px;"></div>
                <div id="passwordStrengthMessage" class="alert" style="display:none; margin-bottom:5px;"></div>
                <form id="changePasswordForm" method="get" action="{{ route('update-customer-password') }}">
                    @csrf
                    <input type="hidden" class="form-control" name="id" value="{{ $commonUser->id }}" placeholder="" required />
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="password" required>
                    </div>
                    <div class="form-group" style="margin-top:15px;">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                    </div>
                    <button style="margin-top:15px;" type="submit" class="btn btn-primary btn-block">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- -- end model --}}





<form method="POST" action="{{route('users.update', $user->id) }}" enctype="multipart/form-data">

    @csrf

    @method('PUT')

    <!-- End Left Sidebar - style you can find in sidebar.scss  -->

    <!-- -------------------------------------------------------------- -->

    <!-- -------------------------------------------------------------- -->

    <!-- Page wrapper  -->

    <!-- -------------------------------------------------------------- -->

    <div class="page-wrapper" style="display:inline;">

        <!-- -------------------------------------------------------------- -->

        <!-- Bread crumb and right sidebar toggle -->

        <!-- -------------------------------------------------------------- -->

        <div class="page-breadcrumb">

            <div class="row">

                <div class="col-5 align-self-center">

                    <h4 class="page-title">{{old('display_name', $user->name )}}</h4>

                    <div class="d-flex align-items-center">

                        <nav aria-label="breadcrumb">

                            <ol class="breadcrumb">

                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>

                                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Customers</a></li>

                                <li class="breadcrumb-item active" aria-current="page">Update Customer</li>

                            </ol>

                        </nav>

                    </div>

                </div>

                <div class="col-7 align-self-center">

                    <div class="d-flex no-block justify-content-end align-items-center">

                        <div class="me-2">

                            <div class="lastmonth"></div>

                        </div>

                        <div class="">

                            <small>LAST MONTH</small>

                            <h4 class="text-info mb-0 font-medium">$58,256</h4>

                        </div>

                    </div>

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



        <div class="row">



            <div class="col-lg-9 d-flex align-items-stretch">

                <div class="card w-100">

                    <div class="card-body border-top">

                        <h4 class="card-title">Customer Information</h4>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">

                                    <label for="first_name" class="control-label col-form-label required-field">First
                                        Name</label>

                                    <input type="text" class="form-control" name="first_name" id="first_name"
                                        value="{{ old('first_name', $first_name) }}" placeholder="" required />

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">

                                    <label for="last_name" class="control-label col-form-label required-field">Last
                                        Name</label>

                                    <input type="text" class="form-control" name="last_name" id="last_name"
                                        value="{{ old('last_name', $last_name) }}" placeholder="" />

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">

                                    <label for="display_name"
                                        class="control-label col-form-label required-field">Display Name (shown

                                        on invoice)</label>

                                    <input type="text" class="form-control" name="display_name" id="display_name"
                                        value="{{old('display_name', $user->name )}}" placeholder="" required />

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="control-label  col-form-label ">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{old('email', $user->email )}}" placeholder="" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">

                                    <label for="mobile_phone" class="control-label col-form-label required-field">Mobile
                                        Phone</label>

                                    <input type="number" maxlength="10" class="form-control" name="mobile_phone"
                                        value="{{old('mobile_phone', $user->mobile )}}" placeholder="" required />
                                    <small id="name" class="form-text text-muted">Don’t add +1. Only add mobile number
                                        without space.</small>

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                            </div>
                        </div>

                        <h4 class="card-title">Address</h4>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">

                                    <label for="address1" class="control-label col-form-label required-field">Address
                                        Line 1

                                        (Street)</label>

                                    <input type="text" class="form-control" id="address1" name="address1"
                                        value="{{ old('address1', $location->address_line1 ?? null) }}" placeholder=""
                                        required />

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-8">
                                <div class="mb-3">

                                    <label for="address_unit" class="control-label col-form-label ">Address
                                        Line2</label>

                                    <input type="text" class="form-control" id="address_unit" name="address_unit"
                                        value="{{ old('address_unit', $location->address_line2 ?? null) }}"
                                        placeholder="" />

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">

                                <div class="mb-3">

                                    <label for="anotheraddress_type"
                                        class="control-label col-form-label required-field">Type</label>

                                    <select class="form-select me-sm-2" id="address_type" name="address_type">
                                        <option value="">Select Address Type...</option>
                                        <option value="home" {{ ($location->address_type ?? null) == 'home' ? 'selected'
                                            : '' }}>Home Address</option>
                                        <option value="work" {{ ($location->address_type ?? null) == 'work' ? 'selected'
                                            : '' }}>Work Address</option>
                                        <option value="other" {{ ($location->address_type ?? null) == 'other' ?
                                            'selected' : '' }}>Other Address</option>
                                    </select>


                                </div>
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
                                    <input type="text" class="form-control" id="city" name="city"
                                        value="{{ $location->city ?? null }}" oninput="searchCity()" required />
                                    {{-- <input type="hidden" class="form-control" id="city_id" name="city_id"
                                        oninput="searchCity1()" required /> --}}
                                    <div id="autocomplete-results"></div>
                                </div>

                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">

                                    <label for="state_id"
                                        class="control-label col-form-label required-field">State</label>

                                    <select class="form-select me-sm-2" id="state_id" name="state_id" required>

                                        <option selected disabled value="">Select State...</option>

                                        @foreach($locationStates as $locationState)

                                        <option value="{{ $locationState->state_id }}" {{ ($location->state_id ?? null)
                                            == $locationState->state_id ? 'selected' : '' }}>

                                            {{ $locationState->state_name }}

                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">

                                    <label for="zip_code"
                                        class="control-label col-form-label required-field">Zip</label>

                                    <input type="text" class="form-control" id="zip_code" name="zip_code"
                                        value="{{ old('zip_code', $location->zipcode ?? null) }}" placeholder=""
                                        required />

                                </div>
                            </div>
                        </div>

                        <h4 class="card-title mt-4">Other Details</h4>
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">

                                    <label for="home_phone" class="control-label col-form-label">Home Phone</label>

                                    <input type="number" maxlength="10" class="form-control" name="home_phone"
                                        placeholder="" value="{{ old('home_phone', $home_phone) }}" />

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">

                                    <label for="work_phone" class="control-label col-form-label">Work Phone</label>

                                    <input type="number" maxlength="10" class="form-control" name="work_phone"
                                        value="{{ old('work_phone', $work_phone) }}" placeholder="" />

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">

                                    <label for="source_id" class="control-label col-form-label">Lead Source</label>

                                    <select class="form-select me-sm-2" id="source_id" name="source_id">

                                        <option value="">Select Lead Source</option>

                                        @foreach($leadSources as $leadSource)

                                        <option value="{{ $leadSource->source_id }}" {{ ($user->source_id ?? null) ==

                                            $leadSource->source_id ?

                                            'selected' : '' }}>

                                            {{ $leadSource->source_name }}

                                        </option>

                                        @endforeach

                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">

                                    <label for="company" class="control-label col-form-label">Company</label>

                                    <input type="text" class="form-control" name="company"
                                        value="{{ old('company', $user->company) }}" placeholder="" />

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">

                                    <label for="role" class="control-label col-form-label">Role</label>

                                    <input type="text" class="form-control" id="role" name="role"
                                        value="{{ old('role', $user->position) }}" placeholder="" />

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">

                                    <label for="inputcontact" class="control-label col-form-label">Type</label>

                                    <div class="form-check">

                                        <input class="form-check-input" type="radio" name="user_type"
                                            id="exampleRadios1" value="Homeowner" {{ ($user->user_type ?? null) ==

                                        'Homeowner' ? 'checked' : '' }}>

                                        <label class="form-check-label" for="exampleRadios1">Homeowner</label>

                                    </div>

                                    <div class="form-check">

                                        <input class="form-check-input" type="radio" name="user_type"
                                            id="exampleRadios2" value="Business" {{ ($user->user_type ?? null) ==

                                        'Business' ? 'checked' : '' }}>

                                        <label class="form-check-label" for="exampleRadios2">Business</label>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-8">

                                <div class="mb-3">

                                    <label for="image" class="control-label col-form-label">Image Upload</label>

                                    <input type="file" class="form-control" name="image"
                                        value="{{old('user_image', $user->image )}}" accept="image/*" />

                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">

                                <div class="mb-3">

                                    <label for="tag_id" class="control-label bold mb5 col-form-label">Customer
                                        Tags</label>
                                    <select class="form-control select2-hidden-accessible" id="select2-with-tags"
                                        name="tag_id[]" multiple="multiple" data-bgcolor="light"
                                        data-select2-id="select2-data-select2-with-tags" tabindex="-1"
                                        aria-hidden="true" style="width: 100%">

                                        @foreach($tags as $tag)

                                        <option value="{{ $tag->tag_id }}" {{ (in_array($tag->tag_id, old('tag_id',
                                            $userTags->pluck('tag_id')->toArray()))) ? 'selected' : '' }}>

                                            {{ $tag->tag_name }}

                                        </option>

                                        @endforeach

                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-12">

                                <div class="mb-3">

                                    <label class="control-label col-form-label">Customer Notes</label>

                                    <textarea class="form-control" id="customer_notes" name="customer_notes"
                                        placeholder=""
                                        rows="1">{{ old('customer_notes', $Note->note ?? null) }}</textarea>

                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-lg-3 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body border-top">


                        <div>
                            <span id="mobile_error" style="margin-top: 5px; color: red;"></span>
                        </div>

                    </div>
                </div>

            </div>



        </div>

        <!-- End row -->


        <!-- row -->

        <div class="row">

            <div class="p-3 border-top">

                <div class="action-form">

                    <div class="mb-3 mb-0 text-center">

                        <button type="submit" id="submitBtn"
                            class="btn btn-info rounded-pill px-4 waves-effect waves-light">Save</button>

                        <a href="{{ route('users.index') }}"> <button type="button"
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

    </div>



    <!-- End row -->



    <!-- row -->

    {{-- <div class="row">

        <div class="p-3 border-top">

            <div class="action-form">

                <div class="mb-3 mb-0 text-center">

                    <button type="submit" class="btn btn-info rounded-pill px-4 waves-effect waves-light">Save</button>

                    <a href="{{ route('users.index') }}"> <button type="button"
                            class="btn btn-dark rounded-pill px-4 waves-effect waves-light">Cancel</button> </a>

                </div>

            </div>

        </div>

    </div> --}}





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
    $(document).ready(function () {

        $('#state_id').change(function () {

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

                success: function (data) {

                    citySelect.html('<option selected disabled value="">Select City...</option>');

                    $.each(data, function (index, city) {

                        citySelect.append('<option value="' + city.city_id + '">' + city.city + ' - ' + city.zip + '</option>');

                    });

                },

                error: function (xhr, status, error) {

                    console.error('Error fetching cities:', error);

                }

            });

        });



        // Trigger another function to get zip code after selecting a city

        $('#city').change(function () {

            var cityId = $(this).val();

            var cityName = $(this).find(':selected').text().split(' - ')[0]; // Extract city name from option text

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

            success: function (data) {

                var zipCode = data.zip_code; // Assuming the response contains the zip code

                $('#zip_code').val(zipCode); // Set the zip code in the input field

            },

            error: function (xhr, status, error) {

                console.error('Error fetching zip code:', error);

            }

        });

    }







</script>



<script>
    $(document).ready(function () {

        $('#anotherstate_id').change(function () {

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

                success: function (data) {

                    citySelect.html('<option selected disabled value="">Select City...</option>');

                    $.each(data, function (index, city) {

                        citySelect.append('<option value="' + city.city_id + '">' + city.city + ' - ' + city.zip + '</option>');

                    });

                },

                error: function (xhr, status, error) {

                    console.error('Error fetching cities:', error);

                }

            });

        });



        // Trigger another function to get zip code after selecting a city

        $('#anothercity').change(function () {

            var cityId = $(this).val();

            var cityName = $(this).find(':selected').text().split(' - ')[0]; // Extract city name from option text

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

            success: function (data) {

                var anotherzip_code = data.anotherzip_code; // Assuming the response contains the zip code

                $('#anotherzip_code').val(anotherzip_code); // Set the zip code in the input field

            },

            error: function (xhr, status, error) {

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



<script>
    document.getElementById('openChangePasswordModal').addEventListener('click', function (event) {

        event.preventDefault();

        $('#changePasswordModal').modal('show');

    });



    // Close modal when close button is clicked

    $('.close').on('click', function () {

        $('#changePasswordModal').modal('hide');

    });

</script>

<script>
    $(document).ready(function () {

        // Select the password and new password input fields

        var passwordField = $('input[name="password"]');

        var newPasswordField = $('input[name="confirm_password"]');

        var passwordMatchMessage = $('#passwordMatchMessage');



        // Select the form and attach a submit event listener

        $('form').submit(function (event) {

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

                passwordMatchMessage.removeClass('alert-success').addClass('alert-danger').html('Passwords do not match. Please enter matching passwords.').show();

            }

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
    document.addEventListener('DOMContentLoaded', function () {
        const mobileInput = document.getElementById('mobile_phone');
        const mobileError = document.getElementById('mobile_error');
        const submitBtn = document.getElementById('submitBtn');

        mobileInput.addEventListener('blur', function () {
            const mobileNumber = this.value.trim();
            if (mobileNumber !== '') {
                // Send AJAX request to check if mobile number exists
                fetch('{{ route("check-mobile") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ mobile_number: mobileNumber })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            const userName = data.user.name;
                            mobileError.textContent = `${userName} already used this mobile number`;
                            submitBtn.disabled = true;
                        } else {
                            mobileError.textContent = '';
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    });

</script>

@endsection
    @if(Route::currentRouteName() != 'dash')

@endsection
@endif