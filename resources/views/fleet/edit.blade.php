<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')
<div class="container-fluid">

    <div class="page-breadcrumb" style="padding: 0px 0px 10px 0px;">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Edit Vehicle</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('vehicles') }}">Vehicles </a></li>
                            <li class="breadcrumb-item"><a href="#">Edit Vehicle</a></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7 text-end px-4">
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <a href="{{ route('product.index') }}"
                        class="btn {{ Route::currentRouteName() === 'product.index' ? 'btn-info' : 'btn-light-info text-info' }}">Parts</a>
                    <a href="{{ route('tool.index') }}"
                        class="btn {{ Route::currentRouteName() === 'tool.index' ? 'btn-info' : 'btn-light-info text-info' }}">Tools</a>
                    <a href="{{ route('vehicles') }}"
                        class="btn {{ Route::currentRouteName() === 'vehicles' ? 'btn-info' : 'btn-light-info text-info' }}">Vehicles</a>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button"
                            class="btn {{ Route::currentRouteName() === 'assign_product' || Route::currentRouteName() === 'assign_tool' ? 'btn-info' : 'btn-light-info text-info' }} dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Assign
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item {{ Route::currentRouteName() === 'assign_product' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('assign_product') }}">Parts</a>
                            <a class="dropdown-item {{ Route::currentRouteName() === 'assign_tool' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('assign_tool') }}">Tools</a>

                        </div>
                    </div>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop2" type="button"
                            class="btn {{ Route::currentRouteName() === 'addvehicle' || Route::currentRouteName() === 'product.createproduct' || Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : 'btn-light-info text-info' }} dropdown-toggle"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Add New
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop2">
                            <a class="dropdown-item {{ Route::currentRouteName() === 'product.createproduct' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('product.createproduct') }}">Parts</a>
                            <a class="dropdown-item {{ Route::currentRouteName() === 'tool.createtool' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('tool.createtool') }}">Tools</a>
                            <a class="dropdown-item {{ Route::currentRouteName() === 'addvehicle' ? 'btn-info' : 'text-info' }}"
                                href="{{ route('addvehicle') }}">Vehicles</a>
                        </div>
                    </div>
                    <a href="{{ route('partCategory') }}"
                        class="btn {{ Route::currentRouteName() === 'partCategory' ? 'btn-info' : 'btn-light-info text-info' }}">Categories</a>
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

        <div class="row">

            <div class="col-md-6">

                <div class="card">
                    <form class="form" method="post"
                        action="{{ route('fleetupdate', ['id' => $fleetModel->vehicle_id]) }}">
                        @csrf
                        <div class="card-body card-border shadow">


                            <div class="row">
                                <div class="col-md-12">

                                    <div class="mb-2">
                                        <label for="vehicle_name"
                                            class="control-label bold col-form-label required-field">Vehicle
                                            Name</label>
                                        <input name="vehicle_name" id="vehicle_name"
                                            value="{{ $fleetModel->vehicle_name }}" class="form-control"
                                            required></input>
                                    </div>
                                    <div class="mb-2">
                                        <label for="vehicle_no"
                                            class="control-label bold col-form-label required-field">Vehicle
                                            No.</label>
                                        <input name="vehicle_no" id="vehicle_no" value="{{ $fleetModel->vehicle_no }}"
                                            class="form-control" required></input>
                                    </div>
                                    <div class="mb-2">
                                        <label for="vehicle_description"
                                            class="control-label bold mb5 col-form-label required-field">Vehicle
                                            Details</label>
                                        <textarea rows="3" name="vehicle_description" id="vehicle_description"
                                            class="form-control" placeholder="Add Vehicle Details"
                                            required>{{ $fleetModel->vehicle_description }}</textarea>
                                    </div>
                                    {{-- <div class="mb-2">
                                        <label for="vehicle_summary"
                                            class="control-label bold mb5 col-form-label required-field">Vehicle
                                            Summary</label>
                                        <textarea rows="3" name="vehicle_summary" id="vehicle_summary"
                                            class="form-control" placeholder="Add complete summary about vehicle"
                                            required>{{ $fleetModel->vehicle_summary }}</textarea>
                                    </div> --}}
                                    <div class="mb-2">
                                        <label for="technician_id"
                                            class="control-label bold mb5 col-form-label required-field">Select
                                            Technician</label>
                                        <select name="technician_id" id="technician_id" class="form-control select"
                                            required>
                                            <option value="">----- Select Technician -----</option>
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @if($user->id == $fleetModel->technician_id)
                                                selected @endif>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="control-label  bold mb5">Upload Image</label>
                                        <input id="file" value="{{ $fleetModel->vehicle_image }}" type="file"
                                            onchange="showImagePreview()" name="vehicle_image"
                                            class="upload form-control" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="el-element-overlay">
                                        <div class="el-card-item">
                                            <div class="el-card-avatar el-overlay-1">
                                                @if ($fleetModel->vehicle_image)
                                                <img id="imagePreview"
                                                    src="{{ asset('public/vehicle_image/' . $fleetModel->vehicle_image) }}"
                                                    alt="vehicle_image" />

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 mt-4">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>


                        </div>
                    </form>

                </div>

            </div>
            <div class="col-lg-6 col-xlg-6">
                <div class="card">
                    <h5 style="margin-top:3%;margin-left:2%;" class="card-title uppercase">Vehicle / Fleet Management
                    </h5>
                    <div class="card-body">
                        <form id="fleetForm" class="form" method="post" action="{{ route('fleetupdated') }}"> @csrf
                            <input class="form-control" type="hidden" value="{{ $fleetModel->technician_id ?? '' }}"
                                name="id">
                            <input class="form-control" type="hidden" value="{{ $fleetModel->vehicle_id ?? '' }}"
                                name="vehicle_id">
                            <div class="mb-3 row">
                                <label for="oil_change" class="col-md-3 col-form-label">OIL
                                    CHANGE</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ old('oil_change', $oil_change) }}"
                                        name="oil_change">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="tune_up" class="col-md-3 col-form-label">TUNE UP</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $tune_up ?? '' }}" name="tune_up">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="tire_rotation" class="col-md-3 col-form-label">TIRE
                                    ROTATION</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $tire_rotation ?? '' }}"
                                        name="tire_rotation">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="breaks" class="col-md-3 col-form-label">BREAKS</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $breaks ?? '' }}" name="breaks">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="inspection_codes" class="col-md-3 col-form-label">INSPECTION
                                    / CODES</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $inspection_codes ?? '' }}"
                                        name="inspection_codes">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="mileage" class="col-md-3 col-form-label">MILEAGE AS OF
                                    00/00/2024</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="date" value="{{ $mileage ?? '' }}" name="mileage">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="registration_expiration_date" class="col-md-3 col-form-label">REGISTRATION
                                    EXPIRATION
                                    DATE</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="date"
                                        value="{{ $registration_expiration_date ?? '' }}"
                                        name="registration_expiration_date">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="vehicle_coverage" class="col-md-3 col-form-label">VEHICLE
                                    COVERAGE</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $vehicle_coverage ?? '' }}"
                                        name="vehicle_coverage">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="license_plate" class="col-md-3 col-form-label">LICENSE
                                    PLATE</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $license_plate ?? '' }}"
                                        name="license_plate">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="vin_number" class="col-md-3 col-form-label">VIN
                                    NUMBER</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $vin_number ?? '' }}"
                                        name="vin_number">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="make" class="col-md-3 col-form-label">MAKE</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $make ?? '' }}" name="make">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="model" class="col-md-3 col-form-label">MODEL</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $model ?? '' }}" name="model">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="year" class="col-md-3 col-form-label">YEAR</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $year ?? '' }}" name="year">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="color" class="col-md-3 col-form-label">COLOR</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $color ?? '' }}" name="color">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="vehicle_weight" class="col-md-3 col-form-label">VEHICLE
                                    WEIGHT</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $vehicle_weight ?? '' }}"
                                        name="vehicle_weight">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="vehicle_cost" class="col-md-3 col-form-label">VEHICLE
                                    COST</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $vehicle_cost ?? '' }}"
                                        name="vehicle_cost">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="use_of_vehicle" class="col-md-3 col-form-label">USE OF
                                    VEHICLE</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $use_of_vehicle ?? '' }}"
                                        name="use_of_vehicle">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="repair_services" class="col-md-3 col-form-label">REPAIR
                                    SERVICES</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $repair_services ?? '' }}"
                                        name="repair_services">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="ezpass" class="col-md-3 col-form-label">E-ZPass</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $ezpass ?? '' }}" name="ezpass">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="service" class="col-md-3 col-form-label">SERVICE</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $service ?? '' }}" name="service">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="additional_service_notes" class="col-md-3 col-form-label">ADDITIONAL SERVICE
                                    NOTES</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text"
                                        value="{{ $additional_service_notes ?? '' }}" name="additional_service_notes">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="last_updated" class="col-md-3 col-form-label">LAST
                                    UPDATED</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="date" value="{{ $last_updated ?? '' }}"
                                        name="last_updated">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="epa_certification" class="col-md-3 col-form-label">EPA
                                    CERTIFICATION</label>
                                <div class="col-md-9">
                                    <input class="form-control" type="text" value="{{ $epa_certification ?? '' }}"
                                        name="epa_certification">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-md-9 offset-md-3">
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
    document.getElementById('submitBtn').addEventListener('click', function(event) {
    // Custom form submission logic
    event.preventDefault();
    var form = document.getElementById('fleetForm');

    // Additional validation or processing can go here

    // Submit the form
    form.submit();
});
</script>
@endsection
