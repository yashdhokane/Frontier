<style>
  .color_box {
		width: 30px;
		height: 24px;
		border-radius: 4px;
  }
  </style>

<style>
    .required-field::after {
        content: " *";
        color: red;
    }

    #autocomplete-results {
        position: absolute;
        background-color: #fff;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        width: calc(30% - 2px);
        /* Subtract border width from input width */
    }

    #autocomplete-results ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    #autocomplete-results li {
        padding: 8px 12px;
        cursor: pointer;
    }

    #autocomplete-results li:hover {
        background-color: #f0f0f0;
    }

    #autocomplete-results li:hover::before {
        content: "";
        display: block;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
        z-index: -1;
    }

    /* Ensure hover effect covers the entire autocomplete result */
    #autocomplete-results li:hover::after {
        content: "";
        display: block;
        position: absolute;
    }
</style>

{{-- model change password --}}
    <div id="changePasswordModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Password</h4>
                    <button type="button" class="close btn btn-danger" data-dismiss="modal" style="opacity: 1;"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="passwordMatchMessage" class="alert" style="display:none; margin-bottom:5px;"></div>
                    <div id="passwordStrengthMessage" class="alert" style="display:none; margin-bottom:5px;"></div>
                    <form id="changePasswordForm" method="get" action="{{ route('update-customer-password') }}">
                        @csrf
                        <input type="hidden" class="form-control" name="id" value="{{ $commonUser->id }}" placeholder=""
                            required />
                        <div class="form-group">
                            <label for="newPassword">New Password</label>
                                                    <div class="input-group">


                            <input type="password" class="form-control" id="newPassword" name="password" required>
                             <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        </div>

                        <div class="form-group" style="margin-top:15px;">
                            <label for="confirmPassword">Confirm Password</label>
                                                    <div class="input-group">

                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password"
                                required>
                                 <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        </div>

                        <button style="margin-top:15px;" type="submit" class="btn btn-primary btn-block">Change
                            Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
{{-- -- end model --}}
<form action="{{ route('technicians.update', $commonUser->id) }}" id="edittechnicianform" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->


    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->

    <div class="container-inline">

        <!-- row user information -->
        <div class="row">

            <div class="col-sm-9 col-md-9">
                       
						<h5 class="card-title uppercase">Edit Details</h5>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="first_name" class="control-label bold mb5 col-form-label required-field">First
                                        Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="{{ $UsersDetails->first_name ?? null }}" placeholder="" required />


                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="last_name" class="control-label bold mb5 col-form-label required-field">Last
                                        Name</label>
                                    <input type="text" class="form-control" id="last_name"
                                        value="{{ $UsersDetails->last_name ?? null }}" name="last_name" placeholder="" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="display_name"
                                        class="control-label bold mb5 col-form-label required-field">Display Name (shown
                                        on invoice)</label>
                                    <input type="text" class="form-control" id="display_name" name="display_name"
                                        value="{{ old('display_name', $commonUser->name) }}" placeholder="" required />
                                </div>
                            </div>
                        </div>
						
						<div class="row">
							<div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="mobile_phone" class="control-label bold mb5 col-form-label required-field">Mobile
                                        Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="mobile_phone"
                                        name="mobile_phone" value="{{ old('mobile_phone', $commonUser->mobile) }}"
                                        placeholder="" required />
                                    <small id="name" class="form-text text-muted">Don’t add +1. Only add mobile number
                                        without space.</small>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="home_phone" class="control-label bold mb5 col-form-label">Home Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="home_phone"
                                        name="home_phone" value="{{ $UsersDetails->home_phone ?? null  }}"
                                        placeholder="" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="work_phone" class="control-label bold mb5 col-form-label">Work Phone</label>
                                    <input type="number" maxlength="10" class="form-control" id="work_phone"
                                        name="work_phone" value="{{ $UsersDetails->work_phone ?? null  }}"
                                        placeholder="" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="email"
                                        class="control-label bold mb5 col-form-label required-field">Email</label>
                                    <input type="text" class="form-control" id="email" name="email"
                                        value="{{ old('email', $commonUser->email) }}" placeholder="" required />
                                </div>
                            </div>
                        </div>
						
					 
						
						<h5 class="card-title uppercase mt-4">Address Details</h5>
 						<div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="mb-3">
                                    <label for="address1" class="control-label bold mb5 col-form-label required-field">Address
                                        Line 1
                                        (Street)</label>
                                    <input type="text" class="form-control" id="address1" name="address1"
                                        value="{{ old('address1', $location->address_line1 ?? null) }}" placeholder=""
                                        required />
                                </div>
                            </div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-12">
								<div class="mb-3">
									<label for="address_unit" class="control-label bold mb5 col-form-label">Address Line 2</label>
									<input type="text" class="form-control" id="address_unit" name="address_unit"
										value="{{ old('address_unit', $location->address_line2 ?? null) }}"
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
                                        value="{{ $commonUser->Location->city ?? null }}" oninput="searchCity()"
                                        required />
                                    {{-- <input type="hidden" class="form-control" id="city_id" name="city_id"
                                        oninput="searchCity1()" required /> --}}
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

                                        <option value="{{ $locationState->state_id }}" {{ ($location->state_id ?? null)
                                            == $locationState->state_id ? 'selected' : '' }}>

                                            {{ $locationState->state_name }}

                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>
 
							<div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="zip_code"
                                        class="control-label bold mb5 col-form-label required-field">Zip</label>
                                    <input type="text" class="form-control" id="zip_code" name="zip_code"
                                        value="{{ old('zip_code', $location->zipcode ?? null) }}" placeholder=""
                                        required />
                                </div>
                            </div>
                        </div>
						
						<h5 class="card-title uppercase mt-4">Other Details</h5>
						
                        <div class="row">
							<!-- Date of Birth -->
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="dob" class="control-label bold mb5 col-form-label required-field">DOB</label>
                                    <!-- Date input field with preselected value -->
                                    <input type="date" class="form-control" id="dob" name="dob" placeholder=""
                                        value="{{ $UsersDetails->dob ?? null  }}" required />
                                </div>
                            </div>
 							
							<!-- License Number -->
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="license_number"
                                        class="control-label bold mb5 col-form-label required-field">License Number</label>
                                    <!-- Text input field for license number with preselected value -->
                                    <input type="text" class="form-control" id="license_number" name="license_number"
                                        placeholder="" value="{{ $UsersDetails->license_number ?? null  }}" required />
                                </div>
                            </div>
 
							<!-- SSN (Social Security Number) -->
							<div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="ssn" class="control-label bold mb5 col-form-label">SSN(Social Security
                                        Number)</label>
                                    <!-- Text input field for SSN with preselected value -->
                                    <input type="text" class="form-control" id="ssn" name="ssn" placeholder=""
                                        value="{{ $UsersDetails->ssn ?? null  }}" />

                                    <input type="hidden" class="form-control" id="role"
                                        value="{{ old('role', $commonUser->role) }}" name="role" placeholder=""
                                        value="technician" />
                                </div>
                            </div>
                        </div>
						
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="control-label bold mb5 col-form-label">Display Picture</label>
                                    <input type="file" class="form-control" id="image"
                                        value="{{ old('image', $commonUser->user_image) }}" name="image"
                                        accept="image/*" />
                                </div>
                            </div>
							<div class="col-sm-12 col-md-4">
								<div class="mb-3">
									<label for="tag_id" class="control-label bold mb5 col-form-label">User Tags</label>
									<select class="form-control select2-hidden-accessible" id="select2-with-tags"
										name="tag_id[]" multiple="multiple" data-bgcolor="light"
										data-select2-id="select2-data-select2-with-tags" tabindex="-1"
										aria-hidden="true" style="width: 100%">

										@foreach($tags as $tag)

										<option value="{{ $tag->tag_id }}" {{ (in_array($tag->tag_id, old('tag_id',
											$userTags->pluck('tag_id')->toArray()))) ? 'selected' : '' }}>

											{{ $tag->tag_name }}

										</option>

										@endforeach

									</select>
								</div>
 							</div>
                         </div>
						
                        <div class="row"><a href="#" id="openChangePasswordModal">Click here to change password</a></div>
                   
			</div>    

				
			<div class="col-sm-3 col-md-3">
				<h5 class="card-title uppercase mt-4">Color Codes</h5>                            
				<div class="row">
					@foreach ($colorcode as $colorCode)
						<div class="col-sm-12 col-md-4">
							<div class="form-check">
								<input class="form-check-input success check-outline outline-success" type="radio" name="color_code" id="{{ $colorCode->color_code }}" value="{{ $colorCode->color_code }}" @if ($colorCode->color_code == $commonUser->color_code) checked @endif>
								<label class="form-check-label" for="{{ $colorCode->color_code }}">
									<div class="color_box" style="background-color: {{ $colorCode->color_code }};"></div>
								</label>
							</div>
						</div>
					@endforeach
				</div>
			</div>


		</div>
 
        <!-- row -->
        <div class="row">
            <div class="p-3 border-top">
                <div class="action-form">
                    <div class="mb-3 mb-0 text-center">
                        <button type="submit"  onclick="document.getElementById('edittechnicianform').submit();"
                            class="btn btn-info rounded-pill px-4 waves-effect waves-light">Save</button>
                        <a href="{{ route('technicians.index') }}"> <button type="button"
                                class="btn btn-dark rounded-pill px-4 waves-effect waves-light">Cancel</button> </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End row -->
		
 
	</div>


</form>