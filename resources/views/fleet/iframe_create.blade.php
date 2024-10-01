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
                            <h4 class="breadcrumb-item active" aria-current="page">Create</h4>

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
                                            <li>
                                                <a href="{{ route('vehicle_iframe_index') }}"
                                                    class="dropdown-item ">Back</a>
                                            </li>
                                            <!-- Parts, Tools, vehicle_iframe_index -->
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
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <form action="{{ route('iframestore') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body card-border shadow">
                                <h5 class="card-title uppercase">Vehicle Details</h5>

                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="mb-2">
                                            <label for="vehicle_name"
                                                class="control-label bold col-form-label required-field">Name</label>
                                            <input name="vehicle_name" id="vehicle_name" class="form-control"
                                                required></input>
                                        </div>
                                    </div>
                                    <div class="col-md-6">


                                        <div class="mb-2">
                                            <label for="vehicle_no"
                                                class="control-label bold col-form-label required-field">Vehicle
                                                No.</label>
                                            <input name="vehicle_no" id="vehicle_no" class="form-control" required></input>
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="mb-2">
                                            <label for="vin_number"
                                                class="control-label bold col-form-label required-field">VIN
                                                Number</label>
                                            <input class="form-control" type="text" name="vin_number" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="mb-2">
                                            <label for="make"
                                                class="control-label bold col-form-label required-field">Make</label>
                                            <input class="form-control" type="text" name="make" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="mb-2">
                                            <label for="model"
                                                class="control-label bold col-form-label required-field">Model</label>
                                            <input class="form-control" type="text" name="model" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="mb-2">
                                            <label for="color"
                                                class="control-label bold col-form-label required-field">Color</label>
                                            <input class="form-control" type="text" name="color" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="mb-2">
                                            <label for="vehicle_weight"
                                                class="control-label bold col-form-label required-field">Weight</label>
                                            <input class="form-control" type="text" name="vehicle_weight" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="mb-2">
                                            <label for="year"
                                                class="control-label bold col-form-label required-field">Year</label>
                                            <input class="form-control" type="text" name="year" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">

                                        <div class="mb-2">
                                            <label for="vehicle_cost"
                                                class="control-label bold col-form-label required-field">Cost</label>
                                            <input class="form-control" type="text" name="vehicle_cost" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">

                                        <div class="mb-2">
                                            <label for="vehicle_description"
                                                class="control-label bold col-form-label required-field">Vehicle
                                                Details</label>
                                            <textarea rows="3" name="vehicle_description" id="vehicle_description" class="form-control"
                                                placeholder="Add Vehicle Details" required></textarea>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-8">
                                            <label class="control-label col-form-label required-field bold">Vehicle
                                                Photo</label>
                                            <div class="btn waves-effect waves-light">
                                                <input id="file" type="file" onchange="showImagePreview()"
                                                    name="vehicle_image" class="upload form-control" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="el-element-overlay">
                                                <div class="el-card-item">
                                                    <div class="el-card-avatar el-overlay-1">
                                                        <img src="" class="w-50" id="imagePreview" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <hr />
                                    <h5 class="card-title uppercase mt-4">Technician Assigned</h5>

                                    <div class="mb-2">
                                        <label for="technician_id"
                                            class="control-label bold mb5 col-form-label required-field">Select
                                            Technician</label>
                                        <select name="technician_id" id="technician_id" class="form-control" required>
                                            <option value="">----- Select Technician -----</option>
                                            @foreach ($user as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>



                                    <hr />
                                    <h5 class="card-title uppercase mt-4">Insurance Details</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <label for="name"
                                                    class="control-label bold col-form-label required-field">Policy
                                                    Holder Name </label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                            <!-- Valid Upto -->
                                            <div class="mb-2">
                                                <label for="valid_upto"
                                                    class="control-label bold col-form-label required-field">Valid
                                                    Upto</label>
                                                <input type="date" class="form-control" id="valid_upto"
                                                    name="valid_upto" required>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Insurance Company -->
                                            <div class="mb-2">
                                                <label for="company"
                                                    class="control-label bold col-form-label required-field">Insurance
                                                    Company</label>
                                                <input type="text" class="form-control" id="company" name="company"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        </div>
                                    </div>




                                    <!-- Document -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <label for="premium"
                                                    class="control-label bold col-form-label required-field">Premium</label>
                                                <input type="number" class="form-control" id="premium" name="premium"
                                                    required>
                                            </div>

                                        </div>
                                        <div class="col-md-6">

                                            <!-- Cover Amount -->
                                            <div class="mb-2">
                                                <label for="cover"
                                                    class="control-label bold col-form-label required-field">Cover
                                                </label>
                                                <input type="number" class="form-control" id="cover" name="cover"
                                                    required>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-2">
                                                <label for="document"
                                                    class="control-label bold col-form-label required-field">Insurance
                                                    Copy</label>
                                                <input type="file" class="form-control" id="document"
                                                    name="document">
                                            </div>
                                        </div>





                                        <div class="mb-3 mt-4">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </form>
                    </div>


                </div>
            </div>

        </div>
    </div>
    </div>

    @include('fleet.script')
    @if (Route::currentRouteName() != 'dash')
    @endsection
@endif
