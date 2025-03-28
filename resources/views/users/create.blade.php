    @if(Route::currentRouteName() != 'dash')


@extends('home')

@section('content')
@endif
<link rel="stylesheet" href="{{ asset('public/admin/dist/libs/select2/dist/css/select2.min.css') }}">




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





<form id="myForm" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">

    @csrf

    <!-- End Left Sidebar - style you can find in sidebar.scss  -->

    <!-- -------------------------------------------------------------- -->

    <!-- -------------------------------------------------------------- -->

    <!-- Page wrapper  -->

    <!-- -------------------------------------------------------------- -->

    <div class="page-wrapper" style="display:inline;">

        <!-- -------------------------------------------------------------- -->

        <!-- Bread crumb and right sidebar toggle -->

        <!-- style="padding-top: 0px;" -------------------------------------------------------------- -->

 <!--       <div class="page-breadcrumb" >
             <div class="row">
				<div class="col-9 align-self-center">
                     <h4 class="page-title">Add New Customer</h4>
				</div>
				<div class="col-3 text-end px-4">
					<a href="https://dispatchannel.com/portal/customers"
						class="justify-content-center d-flex align-items-center"><i class="ri-contacts-line" style="margin-right: 8px;"></i> Back to Customers List </a>
				</div>
             </div>
		</div> -->
        <div class="page-breadcrumb">
    <div class="row withoutthreedottest">
        <div class="col-9 align-self-center">
            <h4 class="page-title">Add New Customer</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#.">Customer Management</a></li>
                        <li class="breadcrumb-item"><a href="#">Add New Customer</a></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-3 text-end px-4">
            <a href="https://dispatchannel.com/portal/customers"
                class="btn btn-secondary text-white  align-items-center justify-content-center">
                <i class="ri-contacts-line" style="margin-right: 8px;"></i> Back to Customers List
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



        <div class="row mt-4">
 
            <div class="col-lg-9 d-flex align-items-stretch">

                <div class="card w-100">

                    <div class="card-body border-top">

                        <h4 class="card-title">Customer Details</h4>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="first_name"
                                        class="control-label bold mb5 col-form-label required-field">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="" required />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="last_name"
                                        class="control-label bold mb5 col-form-label required-field">Last Name</label>
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
                                    <label for="email" class="control-label bold mb5 col-form-label ">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="" />
                                </div>
                            </div>
							<div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="control-label bold mb5 col-form-label ">Additional Email</label>
                                    <input type="email" class="form-control" id="additional_email" name="additional_email" placeholder="" />
                                </div>
                            </div>
						</div>
						<div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="mobile_phone"
                                        class="control-label bold mb5 col-form-label required-field">Mobile
                                        Phone</label>
                                    <input type="number" class="form-control" id="mobile_phone" name="mobile_phone"
                                        placeholder="" maxlength="10" required />
                                    <small id="name" class="form-text text-muted">Don’t add +1. Only add number
                                        without space.</small>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="home_phone" class="control-label bold mb5 col-form-label">Home
                                        Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="home_phone"
                                        name="home_phone" placeholder="" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="work_phone" class="control-label bold mb5 col-form-label">Work
                                        Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="work_phone"
                                        name="work_phone" placeholder="" />
                                </div>
                            </div>
                        </div>

                        <h4 class="card-title mt-4">Address Details</h4>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="address1"
                                        class="control-label bold mb5 col-form-label required-field">Address Line 1
                                        (Street)</label>
                                    <input type="text" class="form-control" id="address1" name="address1" placeholder=""
                                        required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-8">
                                <div class="mb-3">
                                    <label for="address_unit" class="control-label bold mb5 col-form-label ">Address
                                        Line 2</label>
                                    <input type="text" class="form-control" id="address_unit" name="address_unit"
                                        placeholder="" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="display_name"
                                        class="control-label bold mb5 col-form-label required-field">Type</label>
                                    <select class="form-select me-sm-2" id="address_type" name="address_type">
                                        <option value="">Select address..</option>
										<option value="general" selected>General</option>
                                        <option value="home">Home</option>
                                        <option value="work">Work</option>
                                        <option value="other">Other</option>
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
                                    <input type="text" class="form-control" id="city" name="city" oninput="searchCity()"
                                        required />
                                    {{-- <input type="text" class="form-control" id="city_id" name="city_id"
                                        oninput="searchCity1()" required /> --}}
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
                                        <option value="{{ $locationState->state_id }}">{{ $locationState->state_name }}
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

                        <h4 class="card-title mt-4">Other Details</h4>
                        
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="company" class="control-label bold mb5 col-form-label">Company</label>
                                    <input type="text" class="form-control" id="company" name="company"
                                        placeholder="" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="role" class="control-label bold mb5 col-form-label">Role</label>
                                    <input type="text" class="form-control" id="role" name="role" placeholder="" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="inputcontact" class="control-label bold mb5 col-form-label">Customer Type</label>
									<div class="row">
										<div class="col-sm-12 col-md-6">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="user_type"
													id="exampleRadios1" value="Homeowner">
												<label class="form-check-label" for="exampleRadios1">Homeowner</label>
											</div>
										</div>
										<div class="col-sm-12 col-md-6">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="user_type"
													id="exampleRadios2" value="Business">
												<label class="form-check-label" for="exampleRadios2">Business</label>
											</div>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="row mt-2">
							<div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="control-label bold mb5 col-form-label">Image
                                        Upload</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*" />
                                </div>
                            </div>
							<div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="source_id" class="control-label bold mb5 col-form-label">Lead
                                        Source</label>
                                    <select class="form-select me-sm-2" id="source_id" name="source_id">
                                        <option value="">Select Lead Source</option>
                                        @foreach($leadSources as $leadSource)
                                        <option value="{{ $leadSource->source_id }}">{{ $leadSource->source_name }}
                                        </option>
                                        @endforeach
                                    </select>
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
                                        <option value="{{ $tag->tag_id }}">{{ $tag->tag_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                         <div class="row mt-2">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label class="control-label bold mb5 col-form-label">Customer Notes</label>
                                    <textarea type="text" class="form-control" id="customer_notes" name="customer_notes"
                                        rows="3" placeholder=""> </textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class=" col-lg-3 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body border-top">
                         <div class="customersSuggetions2" style="display: none;height: 200px;
                                                            overflow-y: scroll;">
                            <div class="card">
                                <div class="card-body px-0">
                                    <div class="">
                                        <h5 class="font-weight-medium mb-2">
                                        </h5>
                                        <div class="customers2">
                                        </div>
                                    </div>
                                </div>
                            </div>
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

    <!-- -------------------------------------------------------------- -->

    <!-- End Container fluid  -->



    </div>

    </div>

</form>







@section('script')

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

// function searchCity() {
// $("#city").autocomplete({
// source: function (request, response) {
// $.ajax({
// url: "{{ route('autocomplete.city') }}",
// data: {
// term: request.term
// },
// dataType: "json",
// type: "GET",
// success: function (data) {
// response(data);
// },
// error: function (response) {
// console.log("Error fetching city data:", response);
// }
// });
// },
// minLength: 3,
// select: function(event, ui) {
// $("#city").val(ui.item.value);
// $("#autocomplete-results").empty();
// return false;
// }
// }).data("ui-autocomplete")._renderItem = function(ul, item) {
// return $("<li>").append("<div>" + item.label + "</div>").appendTo("#autocomplete-results");
//     };

//     // Listen for click events on autocomplete suggestions and select the corresponding option
//     $("#autocomplete-results").on("click", "li", function() {
//     var cityName = $(this).text();
//     $("#city").val(cityName);
//     $("#autocomplete-results").empty(); // Clear the autocomplete results after selection
//     });

//     // Listen for change event on the input field
//     $("#city").on("input", function() {
//     var inputText = $(this).val();
//     if (inputText.trim() === "") { // Check if input field is blank
//     $("#autocomplete-results").empty(); // Clear the autocomplete results
//     }
//     });
//     }
</script>



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

        $('#anothercity').change(function() {

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
    $(document).ready(function() {
        $('#mobile_phone').keyup(function() {
            var phone = $(this).val();
            if (phone.length >= 10) {
                $('.customersSuggetions2').show();
            } else {
                $('.customersSuggetions2').hide();
            }


            $.ajax({
                url: '{{ route('get_number_customer_one') }}',
                 method: 'get',
                 data: {
                    phone: phone
                }, // send the phone number to the server
                success: function(data) {
                    // Handle the response from the server here
                    console.log(data);
                    $('.rescheduleJobs').empty();

                    $('.customers2').empty();

                    if (data.customers) {
                        $('.customers2').append(data.customers);
                    } else {
                        // $('.customers2').html(
                        //     '<div class="customer_sr_box"><div class="row"><div class="col-md-12" style="text-align: center;"><h6 class="font-weight-medium mb-0">No Data Found</h6></div></div></div>'
                        // );
                    }
                },
                //   alert(1);
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error(xhr.responseText);
                }
            });
        });

    });

</script>






@endsection
@if(Route::currentRouteName() != 'dash')

@endsection
@endif