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

{{-- model change password --}}
<div id="changePasswordModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change Password</h4>
                <button type="button" class="close btn btn-danger" data-dismiss="modal" style="opacity: 1; "
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="passwordMatchMessage" class="alert" style="display:none; margin-bottom:5px;"></div>
                <form id="changePasswordForm" method="get" action="{{route('update-customer-password')}}">
                    @csrf
                    <input type="hidden" class="form-control" name="id" value="{{$technician->id}}" placeholder=""
                        required />
                    <div class="form-group">
                        <label for="newPassword">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="password" required>
                    </div>
                    <div class="form-group " style="margin-top:15px;">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password"
                            required>
                    </div>
                    <button style="margin-top:15px;" type="submit" class="btn btn-primary btn-block">Change
                        Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- -- end model --}}
<form action="{{ route('technicians.update', $technician->id) }}" method="POST" enctype="multipart/form-data">
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
                    <h4 class="page-title">NEW TECHNICIAN</h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('technicians.index') }}">Technicians</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Add New </li>
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

            <div class="col-lg-6 d-flex align-items-stretch">
                <div class="card w-100">

                    <div class="card-body border-top">



                        <h4 class="card-title">Contact Info</h4>
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="control-label col-form-label required-field">First
                                        Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="{{ old('first_name', $first_name) }}" placeholder="" required />


                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="control-label col-form-label required-field">Last
                                        Name</label>
                                    <input type="text" class="form-control" id="last_name"
                                        value="{{ old('last_name', $last_name) }}" name="last_name" placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="display_name"
                                        class="control-label col-form-label required-field">Display Name (shown
                                        on invoice)</label>
                                    <input type="text" class="form-control" id="display_name" name="display_name"
                                        value="{{ old('display_name', $technician->name) }}" placeholder="" required />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="image" class="control-label col-form-label">Image Upload</label>
                                    <input type="file" class="form-control" id="image"
                                        value="{{ old('image', $technician->user_image) }}" name="image"
                                        accept="image/*" />
                                </div>
                            </div>
                        </div>
                        <a href="#" id="openChangePasswordModal">Click here to change password</a>

                    </div>

                </div>
            </div>

            <div class="col-lg-3 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body border-top">
                        <h4 class="card-title mb-3">Other Info&nbsp;</h4>

                        <!-- Date of Birth -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="dob" class="control-label col-form-label required-field">DOB</label>
                                    <!-- Date input field with preselected value -->
                                    <input type="date" class="form-control" id="dob" name="dob" placeholder=""
                                        value="{{ old('dob', $dob) }}" required />
                                </div>
                            </div>
                        </div>

                        <!-- License Number -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="license_number"
                                        class="control-label col-form-label required-field">License Number</label>
                                    <!-- Text input field for license number with preselected value -->
                                    <input type="text" class="form-control" id="license_number" name="license_number"
                                        placeholder="" value="{{ old('license_number',$license_number) }}" required />
                                </div>
                            </div>
                        </div>

                        <!-- SSN (Social Security Number) -->
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="ssn" class="control-label col-form-label">SSN(Social Security
                                        Number)</label>
                                    <!-- Text input field for SSN with preselected value -->
                                    <input type="text" class="form-control" id="ssn" name="ssn" placeholder=""
                                        value="{{ old('ssn', $ssn) }}" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="mobile_phone" class="control-label col-form-label required-field">Mobile
                                        Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="mobile_phone"
                                        name="mobile_phone" value="{{ old('mobile_phone', $technician->mobile) }}"
                                        placeholder="" required />


                                    <small id="name" class="form-text text-muted">Don’t add +1. Only add mobile number
                                        without space.</small>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="home_phone" class="control-label col-form-label">Home Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="home_phone"
                                        name="home_phone" value="{{ old('home_phone', $home_phone) }}" placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="work_phone" class="control-label col-form-label">Work Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="work_phone"
                                        name="work_phone" value="{{ old('work_phone', $work_phone) }}" placeholder="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body border-top">
                        <h4 class="card-title mb-3">&nbsp;</h4>

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-sm-12 col-md-12" style="margin-top: 10px;">
                                <div class="mb-3">
                                    <label class="control-label col-form-label">Service Area</label><br>
                                    @php
                                    if (isset($technician->service_areas) && !empty($technician->service_areas)) {
                                    $service_areas = explode(',', $technician->service_areas);
                                    } else {
                                    $service_areas = [];
                                    }
                                    @endphp
                                    <div class="form-check">
                                        @foreach($serviceAreas as $area)
                                        <div>
                                            <label class="form-check-label" for="service_areas{{ $area->area_id }}">
                                                <input class="form-check-input" type="checkbox"
                                                    @if(in_array($area->area_id,
                                                $service_areas)) checked @endif
                                                id="service_areas{{ $area->area_id }}" name="service_areas[]"
                                                value="{{ $area->area_id }}">
                                                {{ $area->area_name }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row" style="display:none;">
                <div class="col-sm-12 col-md-12">
                    <div class="mb-3">
                        <label for="role" class="control-label col-form-label">Role</label>
                        <input type="text" class="form-control" id="role" value="{{ old('role', $technician->role) }}"
                            name="role" placeholder="" value="technician" />
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- row -->
    <div class="row" style="width:99%; margin-left:5px;">

        <div class="col-lg-9 d-flex align-items-stretch">
            <div class="card w-100">

                <div class="card-body border-top">

                    <h4 class="card-title">Address</h4>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="mb-3">
                                <label for="address1" class="control-label col-form-label required-field">Address Line 1
                                    (Street)</label>
                                <input type="text" class="form-control" id="address1" name="address1"
                                    value="{{ old('address1', $location->address_line1 ?? null) }}" placeholder=""
                                    required />
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <label for="address_unit" class="control-label col-form-label">Address Line 2</label>
                            <input type="text" class="form-control" id="address_unit" name="address_unit"
                                value="{{ old('address_unit', $location->address_line2 ?? null) }}" placeholder="" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">

                                <label for="state_id" class="control-label col-form-label required-field">State</label>

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
                                <label for="zip_code" class="control-label col-form-label required-field">Zip</label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code"
                                    value="{{ old('zip_code', $location->zipcode ?? null) }}" placeholder="" required />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="col-lg-3 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body border-top">
                    <h4 class="card-title mb-3">&nbsp;</h4>

                    <div class="row" style="margin-top: 10px;">
                        <div class="col-sm-12 col-md-12" style="margin-top: 10px;">
                            <div class="mb-3">
                                <div class="row">

                                    <label for="tag_id" class="control-label col-form-label">User
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
                    </div>
                </div>


            </div>
        </div>


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
                    <button type="submit" class="btn btn-info rounded-pill px-4 waves-effect waves-light">Save</button>
                    <a href="{{ route('technicians.index') }}"> <button type="button"
                            class="btn btn-dark rounded-pill px-4 waves-effect waves-light">Cancel</button> </a>
                </div>
            </div>
        </div>
    </div>


    <!-- End row -->




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
        $("#city").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "{{ route('autocomplete.city') }}"
                    , data: {
                        term: request.term
                    }
                    , dataType: "json"
                    , type: "GET"
                    , success: function(data) {
                        response(data);
                    }
                    , error: function(response) {
                        console.log("Error fetching city data:", response);
                    }
                });
            }
            , minLength: 2
            , select: function(event, ui) {
                $("#city").val(ui.item.city);
                $("#city_id").val(ui.item.city_id);
                return false;
            }
        }).data("ui-autocomplete")._renderItem = function(ul, item) {
            var listItem = $("<li>").text(item.city).appendTo("#autocomplete-results");
            listItem.data("city_id", item.city_id);
            return listItem;
        };

        $("#autocomplete-results").on("click", "li", function() {
            var cityName = $(this).text();
            var cityId = $(this).data("city_id");

            // Check if cityId is retrieved properly
            console.log("Selected City ID:", cityId);

            // Set the city ID before emptying autocomplete results
            $("#city_id").val(cityId);

            $("#city").val(cityName);
            $("#autocomplete-results").hide();


            $("#city").show();


        });
        $("#city").click(function() {

            // Show the autocomplete results box
            $("#autocomplete-results").show();
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

@endsection
@endsection