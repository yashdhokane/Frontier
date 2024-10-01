  <div class="card-body card-border shadow">
                            <h5 class="card-title uppercase">Vehicle </h5>


                            <div class="row mt-3">

                                <div class="col-lg-6 col-xlg-6">
                                    <h5 class="card-title uppercase">Edit Vehicle Details </h5>
                                    @if (empty($vehiclefleet->technician_id))
                                    <div class="alert alert-info mt-4 col-md-12" role="alert">
                                        Please go to the Vehicle section and assign a vehicle to a
                                        {{ $commonUser->name ?? '' }}. <strong><a href="{{ route('vehicles') }}">Add
                                                New</a></strong>
                                    </div>
                                    @else
                                    <p>Description: {{ $vehiclefleet->vehicle_description ?? '' }}</p>
                                    <form class="form" id="fleetFormvehicle" method="post"
                                        action="{{ route('update_fleet_technician') }}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="vehicle_description"
                                                        class="control-label bold mb5 col-form-label required-field">Change
                                                        Vehicle (title)</label>
                                                    <select name="vehicle_description" class="form-select" required>
                                                        @foreach ($vehicleDescriptions as $description)
                                                        @if (in_array($description, explode(',',
                                                        $vehiclefleet->vehicle_description)))
                                                        <option value="{{ $description }}" selected>
                                                            {{ $description }}</option>
                                                        @else
                                                        <option value="{{ $description }}">
                                                            {{ $description }}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <input type="hidden" class="form-control" name="technician_id"
                                                    value="{{ $commonUser->id }}" />

                                                <div class="mb-3">
                                                    <button type="submit" id="submitBtnvehicle"
                                                        class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    @endif
                                </div>

                                <script>
                                    document.getElementById('submitBtnvehicle').addEventListener('click', function(event) {
                                    // Custom form submission logic
                                    event.preventDefault();
                                    var form = document.getElementById('fleetFormvehicle');

                                    // Additional validation or processing can go here

                                    // Submit the form
                                    form.submit();
                                });
                                </script>
                                @if (!empty($vehiclefleet->technician_id))

                                <div class="col-lg-6 col-xlg-6">
                                    <h5 class="card-title uppercase">Fleet Maintenance </h5>

                                    <form class="form" id="fleetForm" method="post" action="{{ route('updatefleet') }}">
                                        @csrf
                                        <input class="form-control" type="hidden" value="{{ $commonUser->id }}"
                                            name="id">
                                            <input class="form-control" type="hidden" value="{{ $vehiclefleet->vehicle_id ?? '' }}"
                                            name="vehicle_id">

                                        <div class="mb-3 row">
                                            <label for="oil_change" class="col-md-3 col-form-label">OIL
                                                CHANGE</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text"
                                                    value="{{ old('oil_change', $oil_change) }}" name="oil_change">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="tune_up" class="col-md-3 col-form-label">TUNE UP</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text" value="{{ $tune_up ?? '' }}"
                                                    name="tune_up">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="tire_rotation" class="col-md-3 col-form-label">TIRE
                                                ROTATION</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text"
                                                    value="{{ $tire_rotation ?? '' }}" name="tire_rotation">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="breaks" class="col-md-3 col-form-label">BREAKS</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text" value="{{ $breaks ?? '' }}"
                                                    name="breaks">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="inspection_codes" class="col-md-3 col-form-label">INSPECTION
                                                / CODES</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text"
                                                    value="{{ $inspection_codes ?? '' }}" name="inspection_codes">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="mileage" class="col-md-3 col-form-label">MILEAGE AS OF
                                                00/00/2024</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="date" value="{{ $mileage ?? '' }}"
                                                    name="mileage">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="registration_expiration_date"
                                                class="col-md-3 col-form-label">REGISTRATION EXPIRATION DATE</label>
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
                                                <input class="form-control" type="text"
                                                    value="{{ $vehicle_coverage ?? '' }}" name="vehicle_coverage">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="license_plate" class="col-md-3 col-form-label">LICENSE
                                                PLATE</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text"
                                                    value="{{ $license_plate ?? '' }}" name="license_plate">
                                            </div>
                                        </div>
                                        <div class="mb-3 row" style="display:none;">
                                            <label for="vin_number" class="col-md-3 col-form-label">VIN
                                                NUMBER</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text" value="{{ $vin_number ?? '' }}"
                                                    name="vin_number">
                                            </div>
                                        </div>
                                        <div class="mb-3 row" style="display:none;">
                                            <label for="make" class="col-md-3 col-form-label">MAKE</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text" value="{{ $make ?? '' }}"
                                                    name="make">
                                            </div>
                                        </div>
                                        <div class="mb-3 row" style="display:none;">
                                            <label for="model" class="col-md-3 col-form-label">MODEL</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text" value="{{ $model ?? '' }}"
                                                    name="model">
                                            </div>
                                        </div>
                                        <div class="mb-3 row" style="display:none;">
                                            <label for="year" class="col-md-3 col-form-label">YEAR</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text" value="{{ $year ?? '' }}"
                                                    name="year">
                                            </div>
                                        </div>
                                        <div class="mb-3 row" style="display:none;">
                                            <label for="color" class="col-md-3 col-form-label">COLOR</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text" value="{{ $color ?? '' }}"
                                                    name="color">
                                            </div>
                                        </div>
                                        <div class="mb-3 row" style="display:none;">
                                            <label for="vehicle_weight" class="col-md-3 col-form-label">VEHICLE
                                                WEIGHT</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text"
                                                    value="{{ $vehicle_weight ?? '' }}" name="vehicle_weight">
                                            </div>
                                        </div>
                                        <div class="mb-3 row" style="display:none;">
                                            <label for="vehicle_cost" class="col-md-3 col-form-label">VEHICLE
                                                COST</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text"
                                                    value="{{ $vehicle_cost ?? '' }}" name="vehicle_cost">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="use_of_vehicle" class="col-md-3 col-form-label">USE OF
                                                VEHICLE</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text"
                                                    value="{{ $use_of_vehicle ?? '' }}" name="use_of_vehicle">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="repair_services" class="col-md-3 col-form-label">REPAIR
                                                SERVICES</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text"
                                                    value="{{ $repair_services ?? '' }}" name="repair_services">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="ezpass" class="col-md-3 col-form-label">E-ZPass</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text" value="{{ $ezpass ?? '' }}"
                                                    name="ezpass">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="service" class="col-md-3 col-form-label">SERVICE</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text" value="{{ $service ?? '' }}"
                                                    name="service">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="additional_service_notes"
                                                class="col-md-3 col-form-label">ADDITIONAL SERVICE NOTES</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text"
                                                    value="{{ $additional_service_notes ?? '' }}"
                                                    name="additional_service_notes">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="last_updated" class="col-md-3 col-form-label">LAST
                                                UPDATED</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="date"
                                                    value="{{ $last_updated ?? '' }}" name="last_updated">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label for="epa_certification" class="col-md-3 col-form-label">EPA
                                                CERTIFICATION</label>
                                            <div class="col-md-9">
                                                <input class="form-control" type="text"
                                                    value="{{ $epa_certification ?? '' }}" name="epa_certification">
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-md-9 offset-md-3">
                                                <button type="submit" id="submitBtnfleet"
                                                    class="btn btn-primary">Update</button>
                                            </div>
                                        </div>


                                    </form>

                                </div>

                                @endif
                            </div>

                        </div>