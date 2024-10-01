<div class="row">

    <div class="col-lg-6 d-flex align-items-stretch">
        <div class="card w-100">

            <div class="card-body border-top">
                <div id="passwordMatchMessage" class="alert" style="display:none; margin-bottom:5px;"></div>

                <h4 class="card-title">Account Info</h4>

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <label for="email" class="control-label col-form-label required-field">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder=""
                                required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label for="password" class="control-label col-form-label required-field">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder=""
                                required />


                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label for="confirm_password" class="control-label col-form-label required-field">Confirm
                                Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                placeholder="" required />
                        </div>
                    </div>
                </div>

                <h4 class="card-title">Contact Info</h4>
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label for="first_name" class="control-label col-form-label required-field">First
                                Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder=""
                                required />


                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="mb-3">
                            <label for="last_name" class="control-label col-form-label required-field">Last
                                Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder=""
                                required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <label for="display_name" class="control-label col-form-label required-field">Display Name
                                (shown
                                on invoice)</label>
                            <input type="text" class="form-control" id="display_name" name="display_name"
                                placeholder="" required />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <label for="image" class="control-label col-form-label">Image Upload</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="col-lg-3 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body border-top">
                <h4 class="card-title mb-3">Other Info&nbsp;</h4>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <label for="dob" class="control-label col-form-label required-field">DOB</label>
                            <!-- Change the input type to "date" -->
                            <input type="date" class="form-control" id="dob" name="dob" placeholder=""
                                required />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <label for="license_number" class="control-label col-form-label required-field">License
                                Number</label>
                            <input type="text" class="form-control" id="license_number" name="license_number"
                                placeholder="" required />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <label for="ssn" class="control-label col-form-label ">SSN(Social Security
                                Number)</label>
                            <input type="text" class="form-control" id="ssn" name="ssn"
                                placeholder="" />
                        </div>
                    </div>
                </div>



                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <label for="mobile_phone" class="control-label col-form-label required-field">Mobile
                                Phone</label>
                            <input type="number" maxlength="10" class="form-control" id="mobile_phone"
                                name="mobile_phone" placeholder="" required />
                            <small id="name" class="form-text text-muted">Don’t add +1. Only add mobile number
                                without space.</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <label for="home_phone" class="control-label col-form-label">Home Phone</label>
                            <input type="number" maxlength="10" class="form-control" id="home_phone"
                                name="home_phone" placeholder="" />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <label for="work_phone" class="control-label col-form-label">Work Phone</label>
                            <input type="number" maxlength="10" class="form-control" id="work_phone"
                                name="work_phone" placeholder="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body border-top">
                <h4 class="card-title mb-3">Service Area</h4>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                @foreach ($serviceAreas as $area)
                                    <label class="form-check-label" for="service_areas{{ $area->area_id }}"
                                        style="width: 100%;">
                                        <input class="form-check-input" type="checkbox"
                                            id="service_areas{{ $area->area_id }}" name="service_areas[]"
                                            value="{{ $area->area_id }}">
                                        {{ $area->area_name }}
                                    </label>
                                    <span style="margin-right: 35px;"></span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="row">

        <div class="col-lg-9 d-flex align-items-stretch">
            <div class="card w-100">

                <div class="card-body border-top">
                    <h4 class="card-title">Address</h4>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="mb-3">
                                <label for="address1" class="control-label col-form-label required-field">Address Line
                                    1
                                    (Street)</label>
                                <input type="text" class="form-control" id="address1" name="address1"
                                    placeholder="" required />
                            </div>
                        </div>
                        <div class="row" style="display:none;">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="role" class="control-label col-form-label">Role</label>
                                    <input type="text" class="form-control" id="role" name="role"
                                        placeholder="" value="technician" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <div class="mb-3">
                                <label for="address_unit" class="control-label col-form-label ">Address Line
                                    2</label>
                                <input type="text" class="form-control" id="address_unit" name="address_unit"
                                    placeholder="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label for="city"
                                    class="control-label bold mb5 col-form-label required-field">City</label>
                                {{-- <select class="form-select" id="city" name="city" required>
                                    <option selected disabled value="">Select City...</option>
                                </select> --}}
                                <input type="text" class="form-control" id="city" name="city"
                                    oninput="searchCity()" required />
                                {{-- <input type="text" class="form-control" id="city_id" name="city_id"
                                    oninput="searchCity1()" required />
                                --}}
                                <div id="autocomplete-results"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label for="state_id"
                                    class="control-label bold mb5 col-form-label required-field">State</label>
                                <select class="form-select me-sm-2" id="state_id" name="state_id" required>
                                    <option selected disabled value="">Select State...</option>
                                    @foreach ($locationStates as $locationState)
                                        <option value="{{ $locationState->state_id }}">{{ $locationState->state_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-4">
                            <div class="mb-3">
                                <label for="zip_code" class="control-label col-form-label required-field">Zip</label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code"
                                    placeholder="" required />
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="col-lg-3 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body border-top">
                    <h4 class="card-title mb-3">Tags</h4>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="row">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <div class="mb-3">
                                            <select class="form-control select2-hidden-accessible"
                                                id="select2-with-tags" name="tag_id[]" multiple="multiple"
                                                data-bgcolor="light" data-select2-id="select2-data-select2-with-tags"
                                                tabindex="-1" aria-hidden="true" style="width: 100%">
                                                @foreach ($tags as $tag)
                                                    <option value="{{ $tag->tag_id }}">{{ $tag->tag_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- End row -->

    <!-- row -->

    <!-- End row -->

    <!-- row -->
    <div class="row">
        <div class="p-3 border-top">
            <div class="action-form">
                <div class="mb-3 mb-0 text-center">
                    <button type="submit"
                        class="btn btn-info rounded-pill px-4 waves-effect waves-light">Save</button>
                    <button type="submit"
                        class="btn btn-dark rounded-pill px-4 waves-effect waves-light">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-- End row -->



</div>
