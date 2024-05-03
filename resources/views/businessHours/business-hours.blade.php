@extends('home')
<!--<meta name="csrf-token" content="{{ csrf_token() }}">-->

@section('content')
 

        <div class="page-breadcrumb" >
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Business hours</h4>
					<div class="d-flex align-items-center">
						<nav aria-label="breadcrumb">
						  <ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="{{ route('buisnessprofile.index') }}">Settings</a></li>
							<li class="breadcrumb-item active" aria-current="page">Business Hours</li>
						  </ol>
						</nav>
					  </div>
                 </div>
                <div class="col-7 align-self-center">
                 </div>
            </div>
        </div>

        <div class="container-fluid">


            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
            <script>
                $(document).ready(function() {
                    $(".closetag").hide();
                    $("#edit_hours").click(function() {
                        $(".form_fields").show();
                        $(".form_values").hide();
                        $(".closetag").show();
                    });
                    $("#close_edit").click(function() {
                        $(".form_fields").hide();
                        $(".form_values").show();
                        $(".closetag").hide();
                    });
                });
            </script>

            <script>
                $(document).ready(function() {
                    $(".closetag").hide();
                    $("#edit_online").click(function() {
                        $(".form_fields_online").show();
                        $(".form_values").hide();
                        $(".closetag").show();

                    });
                    $("#close_online").click(function() {
                        $(".form_fields_online").hide();
                        $(".form_values").show();
                        $(".closetag").hide();

                    });
                });
            </script>
            <style>
                .form_fields {
                    display: none;
                }

                .form_fields_online {
                    display: none;
                }
            </style>
            @php
                $displayOperatingHours = true;
                $displayBookingWindows = true;
                $displayEditButton = true;
                $displayEditButtononline = true;

            @endphp

         <div class="card card-border shadow">
         <div class="card-body">
            <div class="row pb-4 mt-2">
                <div class="col-md-6 col-xl-2">
                    <div class="row ">
                        <div class="col-md-9 col-xl-2">
                            @if ($displayOperatingHours)
                                <h5 class="card-title uppercase text-info">Operating hours</h5>
                                @php $displayOperatingHours = false; @endphp
                            @endif
                        </div>
                        <div class="col-md-3 col-xl-2 justify-content-md-end">
                            @if ($displayEditButton)
                                <button type="button" class="btn btn-xs waves-effect waves-light btn-sm btn-outline-info"
                                    id="edit_hours">EDIT</button>
                                @php $displayEditButton = false; @endphp
                            @endif
                        </div>
                    </div>


                    <div class="row pt-3">
                        <div class="col-md-12 col-xl-2">
                            @foreach ($businessHours as $hours)
                                <small class="text-muted pt-4 db">{{ ucfirst($hours->day) }}</small>
                                @if ($hours->open_close != 'close')
                                    <h6>{{ $hours->start_time }} - {{ $hours->end_time }}</h6>
                                @else
                                    <h6> closed </h6>
                                @endif
                                <form id="businessHoursForm">
                                    @csrf

                                    <div class="form_fields">
                                        <div class="row mb-2">
                                            <div class="col-4 py-2 px-3">
                                                <label for="stime"
                                                    class="col-sm-4 text-start control-label col-form-label">Start</label>
                                                <input type="time" name="day[{{ $hours->day }}][start]"
                                                    class="form-control" id="stime" value="{{ $hours->start_time }}" />
                                            </div>
                                            <div class="col-4 py-2 px-3">
                                                <label for="stime"
                                                    class="col-sm-4 text-start control-label col-form-label">End</label>
                                                <input type="time" name="day[{{ $hours->day }}][end]"
                                                    class="form-control" id="stime" value="{{ $hours->end_time }}" />
                                            </div>
                                            <div class="col-4 py-2 px-3">
                                                <label for="stime"
                                                    class="col-sm-4 text-start control-label col-form-label">Status</label>
                                                <div class="col-6 form-check">
                                                    <input type="radio" id="customRadio3"
                                                        name="day[{{ $hours->day }}][status]" class="form-check-input"
                                                        value="on"
                                                        {{ $hours->open_close === 'open' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="customRadio3">Open</label>
                                                </div>
                                                <div class="col-6 form-check">
                                                    <input type="radio" id="customRadio3"
                                                        name="day[{{ $hours->day }}][status]" class="form-check-input"
                                                        value="off"
                                                        {{ $hours->open_close === 'close' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="customRadio3">Closed</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endforeach


                        </div>

                        <div class="form_fields text-center pt-3 pb-3 col-md-12 col-xl-2">
                            <button type="button" onclick="submitForm()"
                                class="btn btn-info rounded-pill px-4 waves-effect waves-light">
                                Save
                            </button>
                            <button type="button" id="close_edit"
                                class="btn btn-dark rounded-pill px-4 waves-effect waves-light">
                                Cancel
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 col-xl-2">
                    <div class="row ">
                        <div class="col-md-9 col-xl-2">
                            @if ($displayBookingWindows)
                                <h5 class="card-title uppercase text-info">Online booking windows</h5>
                                @php $displayBookingWindows = false; @endphp
                            @endif
                        </div>
                        <div class="col-md-3 col-xl-2 justify-content-md-end">
                            @if ($displayEditButtononline)
                                <button type="button" class="btn btn-xs waves-effect waves-light btn-sm btn-outline-info"
                                    id="edit_online">EDIT</button>
                                @php $displayEditButtononline = false; @endphp
                            @endif
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-12 col-xl-2">
                            @foreach ($businessHours as $hours)
                                <small class="text-muted pt-4 db">{{ ucfirst($hours->day) }}</small>
                                @if ($hours->booking_open_close != 'close')
                                    <h6>{{ $hours->booking_start_time }} - {{ $hours->booking_end_time }}</h6>
                                @else
                                    <h6> closed </h6>
                                @endif
                                <form id="onlineHoursForm">
                                    @csrf
                                    <div class="form_fields_online">
                                        <div class="row mb-2">
                                            <div class="col-4 py-2 px-3">
                                                <label for="stime"
                                                    class="col-sm-4 text-start control-label col-form-label">Start</label>
                                                <input type="time" name="day[{{ $hours->day }}][start]"
                                                    class="form-control" id="stime"
                                                    value="{{ $hours->booking_start_time }}" />
                                            </div>
                                            <div class="col-4 py-2 px-3">
                                                <label for="stime"
                                                    class="col-sm-4 text-start control-label col-form-label">End</label>
                                                <input type="time" name="day[{{ $hours->day }}][end]"
                                                    class="form-control" id="stime"
                                                    value="{{ $hours->booking_end_time }}" />
                                            </div>
                                            <div class="col-4 py-2 px-3">
                                                <label for="stime"
                                                    class="col-sm-4 text-start control-label col-form-label">Status</label>
                                                <div class="col-6 form-check">
                                                    <input type="radio" id="customRadio3"
                                                        name="day[{{ $hours->day }}][status]" class="form-check-input"
                                                        value="on"
                                                        {{ $hours->booking_open_close === 'open' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="customRadio3">Open</label>
                                                </div>
                                                <div class="col-6 form-check">
                                                    <input type="radio" id="customRadio3"
                                                        name="day[{{ $hours->day }}][status]" class="form-check-input"
                                                        value="off"
                                                        {{ $hours->booking_open_close === 'close' ? 'checked' : '' }} />
                                                    <label class="form-check-label" for="customRadio3">Closed</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                        <div class="form_fields_online text-center pt-3 pb-3 col-md-12 col-xl-2">
                            <button type="button" onclick="submitonlineForm()"
                                class="btn btn-info rounded-pill px-4 waves-effect waves-light">
                                Save
                            </button>
                            <button type="button" id="close_online"
                                class="btn btn-dark rounded-pill px-4 waves-effect waves-light">
                                Cancel
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- column -->
                <div class="col-lg-6">
                    <br />
                </div>
                <!-- column -->
                <div class="col-lg-6">
                    <br />
                </div>
            </div>

        </div>
        </div>
        </div>



  


    <script>
        function submitForm() {
            var formData = $('#businessHoursForm').serialize();
            // alert(formData);
            $.ajax({
                type: 'POST',
                url: "{{ route('updateBusinessHours') }}",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    location.reload();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
    </script>
    <script>
        function submitonlineForm() {
            var formData = $('#onlineHoursForm').serialize();
            // alert(formData);
            $.ajax({
                type: 'POST',
                url: "{{ route('updateOnlineHours') }}",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    location.reload();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
    </script>
@endsection
