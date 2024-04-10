<!-- resources/views/clients/index.blade.php -->

@extends('home')

@section('content')
    <div class="container-fluid">

        <div class="page-breadcrumb pb-2">
            <div class="row">
                <div class="col-5 align-self-center">

                    </h4>
                    <div class="d-flex align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('fleet') }}" class="fs-5">Fleet </a></li>
                                <li class="breadcrumb-item"><a href="#" class="fs-5"> Edit Fleet
                                        Vehicle</a></li>

                            </ol>
                        </nav>
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

            <div class="card">
                <div class="card-body">

                    <div class="row">

                        <div class="col-lg-8 col-xlg-9">

                            <h4 class="card-title">Fleet Maintenance </h4>

                            <form class="form" method="post" action="{{route('updatefleetdetails')}}">
                                @csrf
                                <input class="form-control" type="hidden" value="{{$fleet->vehicle_id}}"
                                    name="vehicle_id">

                                <div class="mb-3 row">
                                    <label for="oil_change" class="col-md-3 col-form-label">OIL
                                        CHANGE</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text"
                                            value="{{ old('oil_change',  $oil_change) }}" name="oil_change">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="tune_up" class="col-md-3 col-form-label">TUNE UP</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" value="{{$tune_up ?? ''}}"
                                            name="tune_up">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="tire_rotation" class="col-md-3 col-form-label">TIRE
                                        ROTATION</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text"
                                            value="{{$tire_rotation ?? ''}}" name="tire_rotation">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="breaks" class="col-md-3 col-form-label">BREAKS</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" value="{{$breaks ?? ''}}"
                                            name="breaks">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="inspection_codes" class="col-md-3 col-form-label">INSPECTION
                                        / CODES</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text"
                                            value="{{$inspection_codes ?? ''}}" name="inspection_codes">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="mileage" class="col-md-3 col-form-label">MILEAGE AS OF
                                        00/00/2024</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="date" value="{{$mileage ?? ''}}"
                                            name="mileage">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="registration_expiration_date"
                                        class="col-md-3 col-form-label">REGISTRATION EXPIRATION DATE</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="date"
                                            value="{{$registration_expiration_date ?? ''}}"
                                            name="registration_expiration_date">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="vehicle_coverage" class="col-md-3 col-form-label">VEHICLE
                                        COVERAGE</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text"
                                            value="{{$vehicle_coverage ?? ''}}" name="vehicle_coverage">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="license_plate" class="col-md-3 col-form-label">LICENSE
                                        PLATE</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text"
                                            value="{{$license_plate ?? ''}}" name="license_plate">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="vin_number" class="col-md-3 col-form-label">VIN
                                        NUMBER</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text"
                                            value="{{$vin_number ?? ''}}" name="vin_number">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="make" class="col-md-3 col-form-label">MAKE</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" value="{{$make ?? ''}}"
                                            name="make">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="model" class="col-md-3 col-form-label">MODEL</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" value="{{$model ?? ''}}"
                                            name="model">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="year" class="col-md-3 col-form-label">YEAR</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" value="{{$year ?? ''}}"
                                            name="year">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="color" class="col-md-3 col-form-label">COLOR</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" value="{{$color ?? ''}}"
                                            name="color">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="vehicle_weight" class="col-md-3 col-form-label">VEHICLE
                                        WEIGHT</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text"
                                            value="{{$vehicle_weight ?? ''}}" name="vehicle_weight">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="vehicle_cost" class="col-md-3 col-form-label">VEHICLE
                                        COST</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text"
                                            value="{{$vehicle_cost ?? ''}}" name="vehicle_cost">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="use_of_vehicle" class="col-md-3 col-form-label">USE OF
                                        VEHICLE</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text"
                                            value="{{$use_of_vehicle ?? ''}}" name="use_of_vehicle">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="repair_services" class="col-md-3 col-form-label">REPAIR
                                        SERVICES</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text"
                                            value="{{$repair_services ?? ''}}" name="repair_services">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="ezpass" class="col-md-3 col-form-label">E-ZPass</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" value="{{$ezpass ?? ''}}"
                                            name="ezpass">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="service" class="col-md-3 col-form-label">SERVICE</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text" value="{{$service ?? ''}}"
                                            name="service">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="additional_service_notes"
                                        class="col-md-3 col-form-label">ADDITIONAL SERVICE NOTES</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text"
                                            value="{{$additional_service_notes ?? ''}}"
                                            name="additional_service_notes">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="last_updated" class="col-md-3 col-form-label">LAST
                                        UPDATED</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="date"
                                            value="{{$last_updated ?? ''}}" name="last_updated">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="epa_certification" class="col-md-3 col-form-label">EPA
                                        CERTIFICATION</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="text"
                                            value="{{$epa_certification ?? ''}}" name="epa_certification">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <div class="col-md-9 offset-md-3">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>


                            </form>

                        </div>
                    </div>

                </div>
            </div>
    </div>


@endsection
