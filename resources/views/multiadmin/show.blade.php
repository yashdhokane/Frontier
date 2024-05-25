@extends('home')
@section('content')
<!-- Page wrapper  -->
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
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-9 align-self-center">
            <h4 class="page-title">{{ $commonUser->name }}</h4>
            <!--<div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('multiadmin.index') }}">Admin</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                </div>-->
        </div>
        <div class="col-3 text-end">
            <a href="{{ route('multiadmin.index') }}" class="btn btn-primary font-weight-medium shadow"><i
                    class="ri-contacts-line" style="margin-right: 8px;"></i>Admin List </a>
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

                    <li class="nav-item" style="">
                        <a class="nav-link" id="pills-setting-tab" data-bs-toggle="pill" href="#calls_tab" role="tab"
                            aria-controls="pills-setting" aria-selected="false"><i class="fas fa-calendar-check"></i>
                            Calls</a>
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

                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#edit_profile_tab"
                            role="tab" aria-controls="pills-timeline" aria-selected="false"><i class="ri-edit-fill"></i>
                            Edit Details</a>

                    </li>


                    <li class="nav-item">
                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#others_tab" role="tab"
                            aria-controls="pills-timeline" aria-selected="false"><i class="ri-draft-line"></i> Notes</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#settings_tab"
                            role="tab" aria-controls="pills-timeline" aria-selected="false">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#permission_tab"
                            role="tab" aria-controls="pills-timeline" aria-selected="false">Permission</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#activity_tab"
                            role="tab" aria-controls="pills-timeline" aria-selected="false">Activity</a>
                    </li>
                    <!--<li class="nav-item" style="">
                            <a class="nav-link" id="pills-payment-tab" data-bs-toggle="pill" href="#payment_tab"
                                role="tab" aria-controls="pills-payments" aria-selected="false">Activity</a>
                        </li>-->
                </ul>
                <!-- Tabs -->
                <div class="tab-content" id="pills-tabContent">

                    <div class="tab-pane fade show active" id="profile_tab" role="tabpanel"
                        aria-labelledby="pills-profile-tab">
                        <div class="card-body card-border shadow">
                            <div class="row">
                                <div class="col-lg-3 col-xlg-9">

                                    <center class="mt-1">
                                        @if($commonUser->user_image)
                                        <img src="{{ asset('public/images/Uploads/users/' . $commonUser->id . '/' . $commonUser->user_image) }}"
                                            class="rounded-circle" width="150" />
                                        @else
                                        <img src="{{asset('public/images/login_img_bydefault.png')}}" alt="avatar"
                                            class="rounded-circle" width="150" />
                                        @endif <h4 class="card-title mt-1">{{ $commonUser->name }}</h4>
                                        {{-- <h6 class="card-subtitle">{{ $commonUser->company ?? null }}
                                        </h6> --}}
                                    </center>

                                    <div class="col-12">
                                        <h5 class="card-title uppercase mt-4">Tags</h5>
                                        <div class="mt-0">
                                            @if($commonUser->tags->isNotEmpty())
                                            @foreach($commonUser->tags as $tag)
                                            <span class="badge bg-dark">{{ $tag->tag_name }}</span>
                                            @endforeach
                                            @else
                                            <span class="badge bg-dark">No tags available</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <h5 class="card-title uppercase mt-4">Files & Attachments</h5>
                                        <div class="mt-0">
                                            @foreach($customerimage as $image)
                                            @if($image->filename)
                                            <a href="{{ asset('storage/app/' . $image->filename) }}" download>
                                                <p><i class="fas fa-file-alt"></i></p>
                                                <img src="{{ asset('storage/app/' . $image->filename) }}"
                                                    alt="Customer Image" style="width: 50px; height: 50px;">
                                            </a>
                                            @else
                                            <!-- Default image if no image available -->
                                            <img src="{{ asset('public/admin/assets/images/users/1.jpg') }}"
                                                alt="Default Image" style="width: 50px; height: 50px;">
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>


                                </div>

                                <div class="col-lg-9 col-xlg-9">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-6 b-r">
                                            <div class="row text-left justify-content-md-left">

                                                <div class="col-12">
                                                    <h5 class="card-title uppercase mt-1">Contact info</h5>

                                                    <!--<h6 style="font-weight: normal;">
														<i class="fas fa-home"></i>{{ old('home_phone', $home_phone) }}
													</h6>-->
                                                    <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i>
                                                        {{$commonUser->mobile}}</h6>
                                                    {{-- <h6 style="font-weight: normal;"><i
                                                            class="fas fa-mobile-alt"></i> +1
                                                        123 456 7890
                                                    </h6> --}}
                                                    <h6 style="font-weight: normal;"><i class="fas fa-envelope"></i>
                                                        {{$commonUser->email}}
                                                    </h6>
                                                </div>
                                                <div class="col-12">
                                                    <h5 class="card-title uppercase mt-5">Address</h5>
                                                    <h6 style="font-weight: normal;"><i class="ri-map-pin-line"></i>
                                                        {{ $address ?? '' }}

                                                    </h6>

                                                </div>
                                                <h5 class="card-title uppercase mt-4">Summary</h5>
                                                <div class="col-12">
                                                    <small class="text-muted pt-1 db">Last service </small>
                                                    <h6>Active</h6>
                                                </div>
                                                <div class="col-12">
                                                    <small class="text-muted pt-1 db">Profile Created</small>
                                                    <h6>{{ $commonUser->created_at ?
                                                        \Carbon\Carbon::parse($commonUser->created_at)->format('m-d-Y')
                                                        : null
                                                        }}</h6>
                                                </div>
                                                <div class="col-12">
                                                    <small class="text-muted pt-1 db">Lifetime value</small>
                                                    <h6>$0.00</h6>
                                                </div>
                                                <div class="col-12">
                                                    <small class="text-muted pt-1 db">Outstanding balance</small>
                                                    <h6>$0.00</h6>
                                                </div>

                                                <div class="col-12">
                                                    <h5 class="card-title uppercase mt-4">Notifications</h5>
                                                    <h6 style="font-weight: normal;margin-bottom: 0px;"><i
                                                            class="fas fa-check"></i> Yes
                                                    </h6>
                                                </div>

                                                <div class="col-12">
                                                    {{-- <h4 class=" card-title mt-4">Lead Source</h4>
                                                    <div class="mt-0">
                                                        <span
                                                            class="mb-1 badge bg-primary">{{$commonUser->leadsourcename->source_name
                                                            ??
                                                            null }}</span>
                                                    </div> --}}
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-1 col-xs-6 b-r">&nbsp;</div>

                                        <div class="col-md-8 col-xs-6 b-r">
                                            <div class="mt-2">

                                                <div>

                                                    <iframe id="map{{ $location->address_id }}" width="100%"
                                                        height="300" frameborder="0" style="border: 0"
                                                        allowfullscreen></iframe>

                                                    <small class="text-muted pt-4 db">{{ $location->address_type
                                                        }}</small>
                                                    <div style="display:flex;">

                                                        <h6>{{ $address ?? ''}}
                                                        </h6>
                                                        <br />
                                                    </div>

                                                    {{-- <iframe
                                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6991603.699017098!2d-100.0768425!3d31.168910300000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864070360b823249%3A0x16eb1c8f1808de3c!2sTexas%2C%20USA!5e0!3m2!1sen!2sin!4v1701086703789!5m2!1sen!2sin"
                                                        width="100%" height="300" style="border:0;" allowfullscreen=""
                                                        loading="lazy"
                                                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                    --}}

                                                </div>
                                                <hr />





                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>


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
                    <div class="tab-pane fade" id="estimate_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">

                        <div class="card-body card-border shadow">


                            @include('commonfiles.estimate_for_profiles')


                        </div>



                    </div>
                    <div class="tab-pane fade" id="settings_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            {{-- <h5 class="card-title uppercase">Settings</h5> --}}
                            @include('commonfiles.setting_for_profiles') </div>
                    </div>
                    <div class="tab-pane fade" id="permission_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            <h5 class="card-title uppercase">Permission</h5>
                            @include('dispatcher.permission')
                        </div>
                    </div>
                    <div class="tab-pane fade" id="activity_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            {{-- <h5 class="card-title uppercase">Activity </h5> --}}
                            @include('commonfiles.activity_for_profiles')

                        </div>
                    </div>

                    <div class="tab-pane fade" id="edit_profile_tab" role="tabpanel"
                        aria-labelledby="pills-timeline-tab">

                        <div class="card-body card-border shadow">

                            @include('multiadmin.edit')

                        </div>

                    </div>


                    <div class="tab-pane fade show " id="others_tab" role="tabpanel"
                        aria-labelledby="pills-timeline-tab">
                        <div class="card-body card-border shadow">
                            @include('commonfiles.notes_for_profiles')

                        </div>
                    </div>

                    <div class="tab-pane fade" id="settings_tab" role="tabpanel" aria-labelledby="pills-timeline-tab">

                        <div class="card-body card-border shadow">

                            @include('multiadmin.setting')

                        </div>

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
    document.addEventListener('DOMContentLoaded', function() {
        var allCheckbox = document.querySelectorAll('.permission-checkbox');
        var allRadio = document.querySelectorAll('input[name="radio-solid-info"]');

        allRadio.forEach(function(radio) {
            radio.addEventListener('change', function() {
                if (this.value === 'all') {
                    allCheckbox.forEach(function(checkbox) {
                        checkbox.checked = true;
                        checkbox.value = 1;
                        checkbox.disabled = false; // Set value to 1 for all checkboxes when 'All' is selected
                    });
                } else if (this.value === 'block') {
                    allCheckbox.forEach(function(checkbox) {
                        checkbox.checked = false;
                        checkbox.value = 0;
                        checkbox.disabled = true; // Set value to 0 for all checkboxes when 'Block' is selected
                    });
                }
                else if (this.value === 'selected') {
                allCheckbox.forEach(function(checkbox) {
            //  checkbox.checked = true;
            // checkbox.value = 1;
                checkbox.disabled = false; // Set value to 0 for all checkboxes when 'Block' is selected
                });
                }
            });
        });

        // Update the state of the 'All' radio button based on checkbox states
        allCheckbox.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var allChecked = true;
                allCheckbox.forEach(function(cb) {
                    if (!cb.checked) {
                        allChecked = false;
                    }
                });
                document.getElementById('permissions_type_all').checked = allChecked;
            });
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
    $(document).ready(function() {


        $(document).on('click', '.updatevalue', function() {
            var updatevalue = $(this).val();
            if (this.checked) {
                $(this).val(1);
            } else {
                $(this).val(0);
            }
        });

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
    function updateRadio() {
        // Get all checkboxes with class 'updatevalue'
        var checkboxes = document.querySelectorAll('.updatevalue');

        // Get the radio buttons with name 'radio-solid-info'
        var radioAll = document.getElementById('permissions_type_all');
        var radioSelected = document.getElementById('permissions_type_selected');

        // Check if all checkboxes are checked
        var allChecked = true;
        checkboxes.forEach(function(checkbox) {
            if (!checkbox.checked) {
                allChecked = false;
            }
        });

        // Update the radio button based on the checked status of checkboxes
        if (allChecked) {
            radioAll.checked = true;
            radioSelected.checked = false;
        } else {
            radioAll.checked = false;
            radioSelected.checked = true;
        }
    }

    // Attach the 'updateRadio' function to each checkbox's 'change' event
    var checkboxes = document.querySelectorAll('.updatevalue');
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', updateRadio);
    });

    // Call the 'updateRadio' function initially to set the radio button based on current checkbox status
    updateRadio();
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
    @foreach($userAddresscity as $location)
    var latitude = {{ $location->latitude }}; // Example latitude
    var longitude = {{ $location->longitude }}; // Example longitude

    // Construct the URL with the latitude and longitude values
    var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' + latitude
    + ',' + longitude + '&zoom=13';

    document.getElementById('map{{ $location->address_id }}').src = mapUrl;
    @endforeach
</script>


@endsection

@endsection