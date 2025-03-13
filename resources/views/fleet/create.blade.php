<!-- resources/views/clients/index.blade.php -->
@if (Route::currentRouteName() != 'dash')
    @extends('home')

    @section('content')
    @endif


    <div class="page-breadcrumb">
        <div class="row">

            <div class="col-4 align-self-center">
                <h4 class="page-title">Vehicles </h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Asset Management</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('vehicles') }}">Vehicles </a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add </li>
                        </ol>
                    </nav>
                </div>
            </div>
             <div class="col-8 text-end px-4">
                @include('header-top-nav.asset-nav')
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


    <div class="container-fluid pt-2">

        <div class="row">
            <div class="col-md-6 card card-border shadow">
                <form action="{{ route('fleet.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <h5 class="card-title uppercase">Vehicle Details</h5>

                        <div class="row">
                            <div class="col-md-12">

                                <div class="mb-2">
                                    <label for="vehicle_name"
                                        class="control-label bold col-form-label required-field">Name</label>
                                    <input name="vehicle_name" id="vehicle_name" class="form-control" required></input>
                                </div>
                            </div>
                            <div class="col-md-6">


                                <div class="mb-2">
                                    <label for="vehicle_no" class="control-label bold col-form-label required-field">Vehicle
                                        No.</label>
                                    <input name="vehicle_no" id="vehicle_no" class="form-control" required></input>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="mb-2">
                                    <label for="vin_number" class="control-label bold col-form-label required-field">VIN
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
                                        <input type="date" class="form-control" id="valid_upto" name="valid_upto"
                                            required>
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
                                        <input type="file" class="form-control" id="document" name="document">
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

    @include('fleet.script')
    @if (Route::currentRouteName() != 'dash')
    @endsection
@endif
