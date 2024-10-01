<!-- resources/views/clients/index.blade.php -->
@if (Route::currentRouteName() != 'dash')
    @extends('home')

    @section('content')
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

        html,
        body {
            overflow: auto !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
    <div class="container-fluid">

        <div class="page-breadcrumb" style="padding: 0px 0px 10px 0px;">
            <div class="row">
                <div class="card threedottest" style="display:block;">
                    <div class="row card-body">
                        <!-- Search Input on the Left -->
                        <div class="col-6 align-self-center">
                            {{-- <form>
                                  <input type="text" class="form-control" id="searchInput" placeholder="Search Parts"
                                      onkeyup="filterTable()" />
                              </form> --}}
                            <h4 class="breadcrumb-item active" aria-current="page">Edit</h4>

                        </div>

                        <!-- Three Dot Dropdown on the Right -->
                        <div class="col-6 align-self-center">
                            <div class="d-flex justify-content-end">
                                <!-- Dropdown Menu for Filters -->
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                    <div class="dropdown">
                                        <a href="#" class="link" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-more-vertical feather-sm">
                                                <circle cx="12" cy="12" r="1"></circle>
                                                <circle cx="12" cy="5" r="1"></circle>
                                                <circle cx="12" cy="19" r="1"></circle>
                                            </svg>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                            <!-- Filters Section -->

                                            <!-- Parts, Tools, vehicle_iframe_index -->
                                            <li>
                                                <a href="{{ route('vehicle_iframe_index') }}"
                                                    class="dropdown-item ">Back</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('product.index_iframe') }}"
                                                    class="dropdown-item {{ Route::currentRouteName() === 'product.index_iframe' ? 'btn-info' : '' }}">Parts</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('tool.index_iframe') }}"
                                                    class="dropdown-item {{ Route::currentRouteName() === 'tool.index_iframe' ? 'btn-info' : '' }}">Tools</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('vehicle_iframe_index') }}"
                                                    class="dropdown-item {{ Route::currentRouteName() === 'vehicle_iframe_index' ? 'btn-info' : '' }}">vehicle</a>
                                            </li>
                                            <li class="dropdown-submenu">
                                                <a class="dropdown-item dropdown-toggle {{ Route::currentRouteName() === 'iframe_part_assign' || Route::currentRouteName() === 'assign_tool.iframe' ? 'btn-info' : '' }}"
                                                    href="#">Assign</a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item {{ Route::currentRouteName() === 'iframe_part_assign' ? 'btn-info' : '' }}"
                                                            href="{{ route('iframe_part_assign') }}">Parts</a></li>
                                                    <li><a class="dropdown-item {{ Route::currentRouteName() === 'assign_tool.iframe' ? 'btn-info' : '' }}"
                                                            href="{{ route('assign_tool.iframe') }}">Tools</a></li>
                                                </ul>
                                            </li>
                                            <li class="dropdown-submenu">
                                                <a class="dropdown-item dropdown-toggle {{ Route::currentRouteName() === 'iframeaddvehicle' || Route::currentRouteName() === 'product.createproduct.iframe' || Route::currentRouteName() === 'tool.createtool.iframe' ? 'btn-info' : '' }}"
                                                    href="#">Add New</a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item {{ Route::currentRouteName() === 'product.createproduct.iframe' ? 'btn-info' : '' }}"
                                                            href="{{ route('product.createproduct.iframe') }}">Parts</a>
                                                    </li>
                                                    <li><a class="dropdown-item {{ Route::currentRouteName() === 'tool.createtool.iframe' ? 'btn-info' : '' }}"
                                                            href="{{ route('tool.createtool.iframe') }}">Tools</a></li>
                                                    <li><a class="dropdown-item {{ Route::currentRouteName() === 'iframeaddvehicle' ? 'btn-info' : '' }}"
                                                            href="{{ route('iframeaddvehicle') }}">vehicle</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                <a href="{{ route('partCategoryiframe') }}"
                                                    class="dropdown-item {{ Route::currentRouteName() === 'partCategoryiframe' ? 'btn-info' : '' }}">Categories</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            {{-- <li>
                                                <a href="#." id="filterButton" class="dropdown-item">
                                                    <i class="ri-filter-line"></i> Filters
                                                </a>
                                            </li> --}}

                                        </ul>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- -------------------------------------------------------------- -->
            <!-- Start Page Content -->
            <!-- -------------------------------------------------------------- -->
            <!-- basic table -->
            @if (Session::has('success'))
                <div class="alert_wrap">
                    <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
                        {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <div class="row mt-3">

                <div class="col-md-6">

                    <div class="card">
                        <form method="post" id="form2"
                            action="{{ route('iframevehicleupdateinsurance', ['id' => $policy->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body card-border shadow">

                                <h5 class="card-title uppercase">Vehicle Details</h5>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="vehicle_name"
                                                class="control-label bold col-form-label required-field">Name</label>
                                            <input name="vehicle_name" id="vehicle_name"
                                                value="{{ $fleetModel->vehicle_name ?? '' }}" class="form-control"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="vehicle_no"
                                                class="control-label bold col-form-label required-field">Vehicle
                                                No.</label>
                                            <input name="vehicle_no" id="vehicle_no"
                                                value="{{ $fleetModel->vehicle_no ?? '' }}" class="form-control"
                                                required></input>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2 ">
                                            <label for="vin_number"
                                                class="control-label bold col-form-label required-field">VIN Number</label>
                                            <input class="form-control" type="text"
                                                value="{{ $fleetModel->vin_number ?? '' }}" name="vin_number" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2 ">
                                            <label for="make"
                                                class="control-label bold col-form-label required-field">Make</label>
                                            <input class="form-control" type="text"
                                                value="{{ $fleetModel->make ?? '' }}" name="make" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2 ">
                                            <label for="model"
                                                class="control-label bold col-form-label required-field">Model</label>
                                            <input class="form-control" type="text"
                                                value="{{ $fleetModel->model ?? '' }}" name="model" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2 ">
                                            <label for="color"
                                                class="control-label bold col-form-label required-field">Color</label>
                                            <input class="form-control" type="text"
                                                value="{{ $fleetModel->color ?? '' }}" name="color" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2 ">
                                            <label for="vehicle_weight"
                                                class="control-label bold col-form-label required-field">Weight</label>
                                            <input class="form-control" type="text"
                                                value="{{ $fleetModel->vehicle_weight ?? '' }}" name="vehicle_weight" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2 ">
                                            <label for="year"
                                                class="control-label bold col-form-label required-field">Year</label>
                                            <input class="form-control" type="text"
                                                value="{{ $fleetModel->year ?? '' }}" name="year" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2 ">
                                            <label for="vehicle_cost"
                                                class="control-label bold col-form-label required-field">Cost</label>
                                            <input class="form-control" type="text"
                                                value="{{ $fleetModel->vehicle_cost ?? '' }}" name="vehicle_cost" />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="vehicle_description"
                                                class="control-label bold mb5 col-form-label required-field">Vehicle
                                                Details</label>
                                            <textarea rows="3" name="vehicle_description" id="vehicle_description" class="form-control"
                                                placeholder="Add Vehicle Details" required>{{ $fleetModel->vehicle_description ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        @if ($fleetModel->vehicle_image)
                                            <img id="imagePreview"
                                                src="{{ asset('public/vehicle_image/' . $fleetModel->vehicle_image) }}"
                                                alt="vehicle_image" />
                                        @endif
                                        <div class="mb-3">
                                            <label class="control-label  bold mb5">Vehicle Photo</label>
                                            <input type="file" id="file" onchange="showImagePreview()"
                                                name="vehicle_image" class="upload form-control" />
                                        </div>
                                    </div>
                                </div>


                                <hr />
                                <h5 class="card-title uppercase mt-4">Technician Assigned</h5>
                                <div class="mb-2">
                                    <label for="technician_id"
                                        class="control-label bold mb5 col-form-label required-field">Select
                                        Technician</label>
                                    <select name="technician_id" id="technician_id" class="form-control select" required>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}"
                                                @if ($user->id == $fleetModel->technician_id) selected @endif>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <hr />
                                <h5 class="card-title uppercase mt-4">Insurance Details</h5>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="name"
                                                class="control-label bold col-form-label required-field">Policy Holder
                                                Name</label>
                                            <input name="name" id="name" value="{{ $policy->name ?? '' }}"
                                                class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="valid_upto"
                                                class="control-label bold col-form-label required-field">Valid Upto</label>
                                            <input type="date" name="valid_upto" id="valid_upto"
                                                value="{{ $policy->valid_upto ?? '' }}" class="form-control" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="valid_upto"
                                                class="control-label bold col-form-label required-field">Insurance
                                                Company</label>
                                            <input name="company" id="company" value="{{ $policy->company ?? '' }}"
                                                class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="valid_upto"
                                                class="control-label bold col-form-label required-field">Premium</label>
                                            <input type="number" step="0.01" name="premium" id="premium"
                                                value="{{ $policy->premium ?? '' }}" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label for="valid_upto"
                                                class="control-label bold col-form-label required-field">Cover
                                                Amount</label>
                                            <input type="number" step="0.01" name="cover" id="cover"
                                                value="{{ $policy->cover ?? '' }}" class="form-control" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-2">
                                            <label for="document" class="control-label bold col-form-label">Insurance
                                                Copy</label>
                                            <input type="file" name="document" id="document" class="form-control" />
                                        </div>
                                    </div>
                                </div>


                                <input type="hidden" name="vehicle_id" value="{{ $policy->vehicle_id ?? '' }}">



                                <div class="mb-3 mt-3 row">
                                    <div class="col-md-3">
                                        <button id="button2" type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>


                        </form>
                    </div>

                </div>

            </div>
            <div class="col-lg-6 col-xlg-6">
                <div class="card card-border shadow">

                    <div class="card-body ">
                        <form id="fleetForm" class="form" method="post" action="{{ route('fleetupdated') }}"> @csrf
                            <input class="form-control" type="hidden" value="{{ $fleetModel->technician_id ?? '' }}"
                                name="id">
                            <input class="form-control" type="hidden" value="{{ $fleetModel->vehicle_id ?? '' }}"
                                name="vehicle_id">

                            <h5 class="card-title uppercase">Vehicle / Fleet Management</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Oil Change</label>
                                        <input class="form-control" type="text"
                                            value="{{ old('oil_change', $oil_change) }}" name="oil_change" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Tune Up</label>
                                        <input class="form-control" type="text" value="{{ $tune_up ?? '' }}"
                                            name="tune_up" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Tire
                                            Rotation</label>
                                        <input class="form-control" type="text" value="{{ $tire_rotation ?? '' }}"
                                            name="tire_rotation" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Breaks</label>
                                        <input class="form-control" type="text" value="{{ $breaks ?? '' }}"
                                            name="breaks" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Inspection /
                                            Codes</label>
                                        <input class="form-control" type="text" value="{{ $inspection_codes ?? '' }}"
                                            name="inspection_codes" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Mileage as of
                                            00/00/2024</label>
                                        <input class="form-control" type="date" value="{{ $mileage ?? '' }}"
                                            name="mileage" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Registration
                                            Expiration Date</label>
                                        <input class="form-control" type="date"
                                            value="{{ $registration_expiration_date ?? '' }}"
                                            name="registration_expiration_date" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Vehicle
                                            Coverage</label>
                                        <input class="form-control" type="text" value="{{ $vehicle_coverage ?? '' }}"
                                            name="vehicle_coverage" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">License
                                            Plate</label>
                                        <input class="form-control" type="text" value="{{ $license_plate ?? '' }}"
                                            name="license_plate">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Use of
                                            vehicle</label>
                                        <input class="form-control" type="text" value="{{ $use_of_vehicle ?? '' }}"
                                            name="use_of_vehicle">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Repair
                                            Services</label>
                                        <input class="form-control" type="text" value="{{ $repair_services ?? '' }}"
                                            name="repair_services" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">E-ZPass</label>
                                        <input class="form-control" type="text" value="{{ $ezpass ?? '' }}"
                                            name="ezpass" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Service</label>
                                        <input class="form-control" type="text" value="{{ $service ?? '' }}"
                                            name="service" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">EPA
                                            Certification</label>
                                        <input class="form-control" type="text"
                                            value="{{ $epa_certification ?? '' }}" name="epa_certification" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Additional Service
                                            Notes</label>
                                        <input class="form-control" type="text"
                                            value="{{ $additional_service_notes ?? '' }}"
                                            name="additional_service_notes" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        <label for="cover" class="control-label bold col-form-label">Last
                                            Updated</label>
                                        <input class="form-control" type="date" value="{{ $last_updated ?? '' }}"
                                            name="last_updated" />
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 mt-3 row">
                                <div class="col-md-3">
                                    <button id="submitBtn" type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>


        </div>


    </div>

    </div>
    </div>
    <script>
        document.getElementById('button2').addEventListener('click', function(event) {
            event.preventDefault();

            // Assuming form1 and form2 are the IDs of your forms
            // var form1 = document.getElementById('form1');
            var form2 = document.getElementById('form2');

            // Submit form1
            //  form1.submit();

            // Submit form2
            form2.submit();
        });
    </script>



    <script>
        document.getElementById('submitBtn').addEventListener('click', function(event) {
            // Custom form submission logic
            event.preventDefault();
            var form = document.getElementById('fleetForm');

            // Additional validation or processing can go here

            // Submit the form
            form.submit();
        });
    </script>
    @if (Route::currentRouteName() != 'dash')
    @endsection
@endif
