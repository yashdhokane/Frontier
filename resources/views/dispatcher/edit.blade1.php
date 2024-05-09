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



                <button type="button" class="close btn btn-danger" data-dismiss="modal" style="opacity: 1; "
                    aria-label="Close">



                    <span aria-hidden="true">&times;</span>



                </button>



            </div>



            <div class="modal-body">



                <div id="passwordMatchMessage" class="alert" style="display:none; margin-bottom:5px;"></div>



                <form id="changePasswordForm" method="get" action="{{route('update-customer-password')}}">



                    @csrf



                    <input type="hidden" class="form-control" name="id" value="{{$dispatcher->id}}" placeholder=""
                        required />



                    <div class="form-group">



                        <label for="newPassword">New Password</label>



                        <input type="password" class="form-control" id="newPassword" name="password" required>



                    </div>



                    <div class="form-group " style="margin-top:15px;">



                        <label for="confirmPassword">Confirm Password</label>



                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password"
                            required>



                    </div>



                    <button style="margin-top:15px;" type="submit" class="btn btn-primary btn-block">Change

                        Password</button>



                </form>



            </div>



        </div>



    </div>



</div>



{{-- -- end model --}}







<form action="{{ route('dispatcher.update', $dispatcher->id) }}" method="POST" enctype="multipart/form-data">



    @csrf



    @method('PUT')

 
    <div class="container-inline">
  
            <div class="row">

				<div class="col-sm-9 col-md-9">
					<h5 class="card-title uppercase">Contact Info</h5>
					<div class="row">
						<div class="col-sm-4 col-md-4">
							<div class="mb-3">
								<label for="first_name"
								class="control-label col-form-label required-field">First Name</label>
								<input type="text" class="form-control" id="first_name"
								name="first_name" value="{{ $UsersDetails->first_name }}"
								placeholder="" required />
							</div>
						</div>
						<div class="col-sm-4 col-md-4">
							<div class="mb-3">
								<label for="last_name" class="control-label col-form-label required-field">Last Name</label>
								<input type="text" class="form-control" id="last_name" value="{{ $UsersDetails->last_name }}" name="last_name" placeholder="" />
								</div>
						</div>
						<div class="col-sm-4 col-md-4">
							<div class="mb-3">
								<label for="display_name" class="control-label col-form-label required-field">Display Name (shown on invoice)</label>
								<input type="text" class="form-control" id="display_name" name="display_name" value="{{ $dispatcher->name }}" placeholder="" required />
								</div>
						</div>
					</div>
				</div>
				
				<div class="col-sm-3 col-md-3">
				</div>

                <div class="container-fluid">
  
                    <div class="row">
						
					
                        <div class="col-lg-6 d-flex align-items-stretch">
                           
						   <div class="card">
							
                                <div class="card-body card-border">

                                    <h4 class="card-title">Contact Info</h4>



                                    <div class="row">



                                        <div class="col-sm-12 col-md-6">



                                           



                                        </div>



                                        <div class="col-sm-12 col-md-6">



                                           



                                        </div>



                                    </div>



                                    <div class="row">



                                        <div class="col-sm-12 col-md-12">



                                            



                                        </div>



                                    </div>



                                    {{-- <div class="row">



                                        <div class="col-sm-12 col-md-12">



                                            <div class="mb-3">



                                                <label for="email"
                                                    class="control-label col-form-label required-field">Email</label>



                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ old('email', $dispatcher->email) }}" placeholder=""
                                                    required />



                                            </div>



                                        </div>



                                    </div> --}}



                                    <div class="row">



                                        <div class="col-sm-12 col-md-12">



                                            <div class="mb-3">



                                                <label for="image" class="control-label col-form-label">Image
                                                    Upload</label>



                                                <input type="file" class="form-control" id="image"
                                                    value="{{ old('image', $dispatcher->user_image) }}" name="image"
                                                    accept="image/*" />



                                            </div>



                                        </div>



                                    </div>



                                    <a href="#" id="openChangePasswordModal">Click here to change password</a>







                                </div>







                            </div>



                        </div>







                        <div class="col-lg-3 d-flex align-items-stretch">



                            <div class="card w-100">



                                <div class="card-body border-top">



                                    <h4 class="card-title mb-3">&nbsp;</h4>



                                    <div class="row">



                                        <div class="col-sm-12 col-md-12">



                                            <div class="mb-3">



                                                <label for="mobile_phone"
                                                    class="control-label col-form-label required-field">Mobile

                                                    Phone</label>



                                                <input type="number" maxlength="10" class="form-control"
                                                    id="mobile_phone" name="mobile_phone"
                                                    value="{{ $dispatcher->mobile }}" placeholder="" required />
                                                <small id="name" class="form-text text-muted">Don’t add +1. Only add
                                                    mobile number
                                                    without space.</small>



                                            </div>



                                        </div>



                                    </div>



                                    <div class="row">



                                        <div class="col-sm-12 col-md-12">



                                            <div class="mb-3">



                                                <label for="home_phone" class="control-label col-form-label">Home
                                                    Phone</label>



                                                <input type="number" maxlength="10" class="form-control" id="home_phone"
                                                    name="home_phone" value="{{ $UsersDetails->home_phone }}"
                                                    placeholder="" />



                                            </div>



                                        </div>



                                    </div>



                                    <div class="row">



                                        <div class="col-sm-12 col-md-12">



                                            <div class="mb-3">



                                                <label for="work_phone" class="control-label col-form-label">Work
                                                    Phone</label>



                                                <input type="number" maxlength="10" class="form-control" id="work_phone"
                                                    name="work_phone" value="{{ $UsersDetails->work_phone }}"
                                                    placeholder="" />



                                            </div>



                                        </div>



                                    </div>



                                </div>



                            </div>



                        </div>







                        <div class="col-lg-3 d-flex align-items-stretch">



                            <div class="card w-100">



                                <div class="card-body border-top">



                                    <h4 class="card-title mb-3">&nbsp;</h4>



                                    <div class="row">



                                        <div class="row">



                                            <div class="row">



                                                <div class="col-sm-12 col-md-12">



                                                    <div class="mb-3">



                                                        <label for="tag_id" class="control-label col-form-label">User



                                                            Tags</label>


                                                        <select class="form-control select2-hidden-accessible"
                                                            id="select2-with-tags" name="tag_id[]" multiple="multiple"
                                                            data-bgcolor="light"
                                                            data-select2-id="select2-data-select2-with-tags"
                                                            tabindex="-1" aria-hidden="true" style="width: 100%">

                                                            @foreach($tags as $tag)

                                                            <option value="{{ $tag->tag_id }}" {{ (in_array($tag->
                                                                tag_id,
                                                                old('tag_id',
                                                                $userTags->pluck('tag_id')->toArray()))) ? 'selected' :
                                                                '' }}>

                                                                {{ $tag->tag_name }}

                                                            </option>

                                                            @endforeach

                                                        </select>



                                                    </div>



                                                </div>



                                            </div>



                                        </div>



                                    </div>



                                </div>

                                <div class="row">



                                    <div class="col-sm-12 col-md-12">

                                        <div class="mb-3" style="display:none;">

                                            <h5 class="card-title mb-3"> Permission to user view</h5>



                                            @foreach($permissions as $permission)

                                            <input type="checkbox" id="{{ $permission }}_permission"
                                                name="permissions[]" value="{{ $permission }}"
                                                @if(is_array($dispatcher->permissions) &&
                                            in_array($permission, json_decode($dispatcher->permissions, true)))
                                            checked="checked"
                                            @endif>

                                            <label for="{{ $permission }}_permission">{{ $permission }}</label><br>

                                            @endforeach

















                                        </div>

                                    </div>

                                </div>





                                <div class="row" style="display:none;">



                                    <div class="col-sm-12 col-md-12">



                                        <div class="mb-3">



                                            <label for="role" class="control-label col-form-label">Role</label>



                                            <input type="text" class="form-control" id="role"
                                                value="{{ old('role', $dispatcher->role) }}" name="role" placeholder=""
                                                value="dispatcher" />



                                        </div>



                                    </div>



                                </div>







                            </div>



                        </div>



                    </div>







                </div>



                <!-- End row -->











                <!-- row -->



                <div class="row" style="margin-left:1px;">







                    <div class="col-lg-9 d-flex align-items-stretch">



                        <div class="card w-100">







                            <div class="card-body border-top">



                                <h4 class="card-title">Address</h4>



                                <div class="row">



                                    <div class="col-sm-12 col-md-12">



                                        <div class="mb-3">



                                            <label for="address1"
                                                class="control-label col-form-label required-field">Address Line 1



                                                (Street)</label>



                                            <input type="text" class="form-control" id="address1" name="address1"
                                                value="{{ old('address1', $location->address_line1) }}" placeholder=""
                                                required />



                                        </div>



                                    </div>







                                </div>



                                <div class="col-sm-12 col-md-12">



                                    <div class="mb-3">



                                        <label for="address_unit" class="control-label col-form-label ">Address Line

                                            2</label>



                                        <input type="text" class="form-control" id="address_unit"
                                            value="{{ old('address_unit', $location->address_line2) }}"
                                            name="address_unit" placeholder="" />



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
                                                value="{{ $dispatcher->Location->city ?? null }}" oninput="searchCity()"
                                                required />
                                            {{-- <input type="hidden" class="form-control" id="city_id" name="city_id"
                                                oninput="searchCity1()" required /> --}}
                                            <div id="autocomplete-results"></div>
                                        </div>

                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <div class="mb-3">

                                            <label for="state_id"
                                                class="control-label col-form-label required-field">State</label>

                                            <select class="form-select me-sm-2" id="state_id" name="state_id" required>

                                                <option selected disabled value="">Select State...</option>

                                                @foreach($locationStates as $locationState)

                                                <option value="{{ $locationState->state_id }}" {{ ($location->state_id
                                                    ?? null)
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
                                                class="control-label col-form-label required-field">Zip</label>



                                            <input type="text" class="form-control" id="zip_code" name="zip_code"
                                                value="{{ old('zip_code', $location->zipcode) }}" placeholder=""
                                                required />



                                        </div>



                                    </div>



                                </div>



                                <div class="row">



                                    <div class="col-sm-12 col-md-12">



                                    </div>



                                </div>



                            </div>







                        </div>



                    </div>







                    {{-- <div class="col-lg-3 d-flex align-items-stretch">



                        <div class="card w-100">



                            <div class="card-body border-top">



                                <h4 class="card-title mb-3">&nbsp;</h4>



                                <div class="row">



                                    <div class="col-sm-12 col-md-12">



                                        <iframe
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6991603.699017098!2d-100.0768425!3d31.168910300000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864070360b823249%3A0x16eb1c8f1808de3c!2sTexas%2C%20USA!5e0!3m2!1sen!2sin!4v1701086703789!5m2!1sen!2sin"
                                            width="100%" height="auto" style="border:0;" allowfullscreen=""
                                            loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>



                                    </div>



                                </div>



                            </div>



                        </div>



                    </div>



                    --}}







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



                                <a href="{{route('dispatcher.index')}}"> <button type="button"
                                        class="btn btn-dark rounded-pill px-4 waves-effect waves-light">Cancel</button>
                                </a>



                            </div>



                        </div>



                    </div>



                </div>











                <!-- End row -->



















                <!-- -------------------------------------------------------------- -->



                <!-- End PAge Content -->



                <!-- -------------------------------------------------------------- -->



            </div>



            <!-- -------------------------------------------------------------- -->



            <!-- End Container fluid  -->






 


    </div>



</form>
