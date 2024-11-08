@if (Route::currentRouteName() != 'dash')
    @extends('home')
    @section('content')
    @endif
    <style>
        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
    <!-- -------------------------------------------------------------- -->

    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title"> Add Vendor </h4>
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




        <!-- Row -->


        <!-- Card -->
        <form action="{{ route('vendor.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- column -->
                <div class="col-lg-6 col-md-12">
                    <div class="card card-body card-border shadow">

                        <div class="mb-3">
                            <label class="control-label bold mb5 required-field">Name</label>
                            <input type="text" name="vendor_name" id="vendor_name" class="form-control" placeholder="">
                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>

                        <div class="mb-3 justify-content-between">
                            <label class="control-label bold mb5 required-field">Description</label>
                            <textarea id="vendor_description" name="vendor_description" class="form-control" style="height: 180px;">
							</textarea>
                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>

                        <div class="mb-3 justify-content-between">
                            <label class="control-label bold mb5 required-field"
                                style="display: block; font-size: 1rem; margin-bottom: 0.5rem;">Image</label>
                            <input type="file" class="form-control" name="vendor_image" id="">
                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>
                    </div>
                    <!-- Card -->




                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card card-body card-border shadow">

                        <div class="mb-3">
                            <label class="control-label bold mb5 required-field">Name</label>
                            <select id="city-select" name="city_id" class="form-control" style="width: 100%;"
                                placeholder="Search for a city">
                                <option value="">Select a city</option>
                            </select>


                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>

                        <div class="mb-3 justify-content-between">
                            <label class="control-label bold mb5 required-field">Address Line 1</label>
                            <textarea id="address_line_1" name="address_line_1" class="form-control" style="height: 113px;">
							</textarea>
                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>
                        <div class="mb-3 justify-content-between">
                            <label class="control-label bold mb5 required-field">Address Line 2</label>
                            <textarea id="address_line_2" name="address_line_2" class="form-control" style="height: 113px;">
							</textarea>
                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>
                    </div>
                    <!-- Card -->



                    <div class="mb-3 pb-3" style="margin-top: 1%">
                        <button type="submit" class="justify-content-center btn btn-primary align-items-center"
                            style="margin-left:90%">
                            Submit
                        </button>
                    </div>
                </div>
                <!-- Card -->

            </div>

        </form>

        <!-- column -->



    </div>
    <!-- Row -->




    <!-- -------------------------------------------------------------- -->
    <!-- Recent comment and chats -->
    <!-- -------------------------------------------------------------- -->
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
    <!-- -------------------------------------------------------------- -->
    <!-- Recent comment and chats -->
    <!-- -------------------------------------------------------------- -->
    @section('script')
        <script>
            $(document).ready(function() {
                $('#city-select').select2({
                    placeholder: 'Search for a city',
                    allowClear: true,
                    ajax: {
                        url: '{{ route('cities.search') }}', // Route to your search endpoint
                        dataType: 'json',
                        delay: 250, // Delay in milliseconds to prevent too many requests
                        data: function(params) {
                            return {
                                q: params.term // Search term
                            };
                        },
                        processResults: function(data) {
                            // Parse the results into the format Select2 expects
                            return {
                                results: data.map(function(city) {
                                    return {
                                        id: city.city_id,
                                        text: city.city
                                    };
                                })
                            };
                        },
                        cache: true
                    }
                });
            });
        </script>
    @endsection
    @if (Route::currentRouteName() != 'dash')
    @endsection
@endif
