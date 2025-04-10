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
            <div class="col-4 align-self-center">
                <h4 class="page-title">Vendors</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Asset Management</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('vendor.index') }}">Vendor</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $vendor->vendor_name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
             <div class="col-8 text-end px-4">
                @include('header-top-nav.asset-nav')
           </div>
        </div>
    </div>
    <div class="container-fluid pt-2">

        @if ($errors->any())

            @foreach ($errors->all() as $error)
                <div class="alert_wrap">
                    <div class="alert alert-success alert-dismissible bg-danger text-white border-0 fade show">
                        {{ $error }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endforeach
        @endif


        <!-- Row -->


        <!-- Card -->
        <form action="{{ route('vendor.update', ['id' => $vendor->vendor_id]) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- column -->
                <div class="col-lg-6 col-md-12">
                    <div class="card card-body card-border shadow">

                        <div class="mb-3">
                            <label class="control-label bold mb5 required-field">Name</label>
                            <input type="text" name="vendor_name" id="vendor_name" class="form-control" placeholder=""
                                value="{{ $vendor->vendor_name }}">
                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>

                        <div class="mb-3 justify-content-between">
                            <label class="control-label bold mb5 required-field">Description</label>
                            <textarea id="vendor_description" name="vendor_description" class="form-control" style="height: 180px;">{{ $vendor->vendor_description }}</textarea>
                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>

                        <div class="mb-3 justify-content-between">
                            <label class="control-label bold mb5"
                                style="display: block; font-size: 1rem; margin-bottom: 0.5rem;">Image</label>
                            <input type="file" class="form-control" name="vendor_image" id="">
                            <small id="textHelp" class="form-text text-muted"></small>
                            @if ($vendor->vendor_image)
                                <div class="mt-2">
                                    <img src="{{ asset('public/images/' . $vendor->vendor_image) }}" alt="Vendor Image"
                                        style="max-width: 400px;">
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Card -->




                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card card-body card-border shadow">

                        <div class="mb-3">
                            <label class="control-label bold mb5 required-field">Select Location</label>
                            <select id="city-select" name="city_id" class="form-control" style="width: 100%;"
                                placeholder="Search for a city">
                                <option value="">Select a city</option>
                            </select>


                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>

                        <div class="mb-3 justify-content-between">
                            <label class="control-label bold mb5 required-field">Address Line 1</label>
                            <textarea id="address_line_1" name="address_line_1" class="form-control" style="height: 113px;">{{ $vendor->address_line_1 }}</textarea>
                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>
                        <div class="mb-3 justify-content-between">
                            <label class="control-label bold mb5">Address Line 2</label>
                            <textarea id="address_line_2" name="address_line_2" class="form-control" style="height: 113px;">{{ $vendor->address_line_2 }}</textarea>
                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>
                        <div class="mb-3">
                            <label class="control-label bold mb5">Status</label>
                            <select id="status-select" name="status" class="form-control" style="width: 100%;"
                                placeholder="Select status">
                                <option value="yes" {{ $vendor->is_active == 'yes' ? 'selected' : '' }}>Active</option>
                                <option value="no" {{ $vendor->is_active == 'no' ? 'selected' : '' }}>Deactive</option>
                            </select>



                            <small id="textHelp" class="form-text text-muted"></small>
                        </div>
                    </div>
                    <!-- Card -->



                    <div class="mb-3 pb-3" style="margin-top: 1%">
                        <button type="submit" class="justify-content-center btn btn-primary align-items-center"
                            style="margin-left:90%">
                            Update
                        </button>
                    </div>
                </div>
                <!-- Card -->

            </div>

        </form>

        <!-- column -->



    </div>


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
                // Pre-select city if there's an existing value
                let selectedCityId = "{{ $vendor->city_id }}";
                let selectedCityName = "{{ $vendor->city ?? '' }}";
                if (selectedCityId && selectedCityName) {
                    let option = new Option(selectedCityName, selectedCityId, true, true);
                    $('#city-select').append(option).trigger('change');
                }
            });
        </script>
    @endsection

    @if (Route::currentRouteName() != 'dash')
    @endsection
@endif
