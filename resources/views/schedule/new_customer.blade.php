
<form id="myForm" method="POST" action="{{ url('new/customer/schedule') }}"
enctype="multipart/form-data">

@csrf

<div class="container-fluid">

       <div class="row">
 
            <div class="col-lg-9 d-flex align-items-stretch">

                <div class="card w-100">

                    <div class="card-body border-top">

                        <h4 class="card-title">Customer Details</h4>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="first_name"
                                        class="control-label bold mb5 col-form-label required-field">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="" required />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="last_name"
                                        class="control-label bold mb5 col-form-label required-field">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        placeholder="" required />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="display_name"
                                        class="control-label bold mb5 col-form-label required-field">Display Name (shown
                                        on invoice)</label>
                                    <input type="text" class="form-control" id="display_name" name="display_name"
                                        placeholder="" required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="control-label bold mb5 col-form-label ">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="" />
                                </div>
                            </div>
							<div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="control-label bold mb5 col-form-label ">Additional Email</label>
                                    <input type="email" class="form-control" id="additional_email" name="additional_email" placeholder="" />
                                </div>
                            </div>
						</div>
						<div class="row">
                             <div class="col-sm-12 col-md-4">

                                <div class="mb-3">

                                    <label for="mobile_phone" class="control-label bold mb5 col-form-label required-field">Mobile Phone</label>

                                    <input type="text" class="form-control"
                                        id="mobile_phone" name="mobile_phone" placeholder=""
                                        required />
                                    <small id="name" class="form-text text-muted">Don’t
                                        add +1. Only add mobile without space.</small>

                                </div>

                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="home_phone" class="control-label bold mb5 col-form-label">Home
                                        Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="home_phone"
                                        name="home_phone" placeholder="" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="work_phone" class="control-label bold mb5 col-form-label">Work
                                        Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="work_phone"
                                        name="work_phone" placeholder="" />
                                </div>
                            </div>
                        </div>

                        <h4 class="card-title mt-4">Address Details</h4>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="address1"
                                        class="control-label bold mb5 col-form-label required-field">Address Line 1
                                        (Street)</label>
                                    <input type="text" class="form-control" id="address1" name="address1" placeholder=""
                                        required />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="address_unit" class="control-label bold mb5 col-form-label ">Address Line 2</label>
                                    <input type="text" class="form-control" id="address_unit" name="address_unit" placeholder="" />
									<input type="hidden" class="form-control" id="address_type" name="address_type" value="general"  />
                                </div>
                            </div>
							
							<?php 
							/*
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="display_name"
                                        class="control-label bold mb5 col-form-label required-field">Type</label>
                                    <select class="form-select me-sm-2" id="address_type" name="address_type">
                                        <option value="">Select address..</option>
										<option value="general" selected>General</option>
                                        <option value="home">Home</option>
                                        <option value="work">Work</option>
                                        <option value="other">Other</option>
                                     </select>
                                </div>
                            </div>
							*/
							?>
							
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
                                        required />
                                    <div id="autocomplete-results"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="state_id"
                                        class="control-label bold mb5 col-form-label required-field">State</label>
                                    <select class="form-select me-sm-2" id="state_id" name="state_id" required>
                                        <option selected disabled value="">Select State...</option>
                                        @foreach($locationStates as $locationState)
                                        <option value="{{ $locationState->state_id }}">{{ $locationState->state_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="zip_code"
                                        class="control-label bold mb5 col-form-label required-field">Zip</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder=""
                                        required />
                                </div>
                            </div>
                        </div>

                        <h4 class="card-title mt-4">Other Details</h4>
                        
                        <div class="row mt-2">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="company" class="control-label bold mb5 col-form-label">Company</label>
                                    <input type="text" class="form-control" id="company" name="company"
                                        placeholder="" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="role" class="control-label bold mb5 col-form-label">Role</label>
                                    <input type="text" class="form-control" id="role" name="role" placeholder="" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="inputcontact" class="control-label bold mb5 col-form-label">Customer Type</label>
									<div class="row">
										<div class="col-sm-12 col-md-6">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="user_type"
													id="exampleRadios1" value="Homeowner">
												<label class="form-check-label" for="exampleRadios1">Homeowner</label>
											</div>
										</div>
										<div class="col-sm-12 col-md-6">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="user_type"
													id="exampleRadios2" value="Business">
												<label class="form-check-label" for="exampleRadios2">Business</label>
											</div>
										</div>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="row mt-2">
							<div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="control-label bold mb5 col-form-label">Profile Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*" />
                                </div>
                            </div>
							<div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="source_id" class="control-label bold mb5 col-form-label">Lead Source</label>
                                    <select class="form-select me-sm-2" id="source_id" name="source_id">
                                        <option value="">Select Lead Source</option>
                                        @foreach($leadSources as $leadSource)
                                        <option value="{{ $leadSource->source_id }}">{{ $leadSource->source_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
							 <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="tag_id"
                                        class="control-label bold mb5 col-form-label">Customer
                                        Tags</label>
                                    <select class="form-control  me-sm-2" id="" name="tag_id[]"
                                        multiple="multiple" style="width: 100%">
                                        @foreach ($site as $item)
                                            <option value="{{ $item->tag_id }}">
                                                {{ $item->tag_name }}</option>
                                        @endforeach
                                    </select>

                                    <small id="name" class="form-text text-muted">You can
                                        select multiple tags.</small>
                                </div>
                            </div>
                        </div>
                         <div class="row mt-2">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label class="control-label bold mb5 col-form-label">Customer Notes</label>
                                    <textarea type="text" class="form-control" id="customer_notes" name="customer_notes"
                                        rows="3" placeholder=""> </textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

           <div class="col-lg-3 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body border-top px-0">
                    <div class="customersSuggetions2"
                        style="display: none;height: 200px;
                            overflow-y: scroll;">
                        <div class="card">
                            <div class="card-body px-0">
                                <div class="">
                                    <div class="customers2">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>



        </div>

    <div class="row">

        <div class="p-3 border-top">

            <div class="action-form">

                <div class="mb-3 mb-0 text-center">

                    <button type="button" id="cancelBtn"
                        class="btn btn-dark rounded-pill px-4 waves-effect waves-light">Cancel</button>

                    <button type="submit" id="submitBtn"
                        class="btn btn-info rounded-pill px-4 waves-effect waves-light">Save</button>



                </div>

            </div>

        </div>

    </div>

</div>



</form>