@extends('home')

@section('content')
<style>
    .alert-dismissible {
        position: relative;
        padding-right: 4em;
        /* Adjust as needed */
    }

    .close-alert {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0.75rem 1.25rem;
        color: inherit;
        cursor: pointer;
    }
</style>

<div class="page-wrapper" style="display:inline;">

    <div class="page-breadcrumb" style="padding-top: 0px;">
        <div class="row">
            <div class="col-8 align-self-center">
                <h4 class="page-title">My Profile</h4>
             </div>
            <div class="col-4 align-self-end">
  				<div class="btn-toolbar profile-menu-btn" role="toolbar" aria-label="Toolbar with button groups">
					<div class="btn-group me-1" role="group" aria-label="First group">
						<a href="{{route('myprofile.index')}}" class="btn btn-info mx-2">Profile</a>
						<a href="{{route('myprofile.account')}}" class="btn btn-light-info text-info mx-2">Account Settings</a>
						<a href="{{route('myprofile.activity')}}" class="btn btn-light-info text-info mx-2">Activity</a>
					</div>
				</div>
              </div>
        </div>
    </div>
	
	<div class="container-fluid">
	
		@if(session('success'))
		<div class="alert alert-success alert-dismissible" role="alert" id="success-alert">
			{{ session('success') }}
			<button type="button" class="btn-close close-alert" data-bs-dismiss="alert"
				aria-label="Close"></button>
		</div>
		@endif

		<!-- Display error message if it exists in the session -->
		@if(session('error'))
		<div class="alert alert-danger alert-dismissible" role="alert" id="error-alert">
			{{ session('error') }}
			<button type="button" class="btn-close close-alert" data-bs-dismiss="alert"
				aria-label="Close"></button>
		</div>
		@endif

		<script>
			// Automatically close the alerts after 5 seconds
		setTimeout(function () {
			$('#success-alert, #error-alert').alert('close');
		}, 5000);
		</script>
		
		<div class="row">
		
			<div class="col-md-8">
			
				<div class="card card-border">
 					<div class="card-body p-4">
  						<form action="{{ route('user.infoadmin') }}" method="post"
							enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="id" value="{{$user->id ?? null}}">
							 
							<div class="row">
								<div class="col-lg-4">
									<div class="mb-4">
										<label for="exampleInputtext"
											class="form-label fw-semibold">First Name</label>
										<input type="text" name="first_name" id="first_name" value="{{ old('first_name', $first_name) ?? null}}"
											class="form-control" id="exampleInputtext"
											placeholder="">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="mb-4">
										<label for="exampleInputtext"
											class="form-label fw-semibold">Last Name</label>
										<input type="text" id="last_name" name="last_name" value="{{ old('last_name', $last_name) ?? null}}"
											class="form-control" id="exampleInputtext"
											placeholder="">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="mb-4">
										<label for="exampleInputtext"
											class="form-label fw-semibold">Display Name</label>
										<input type="text" id="display_name" name="name" value="{{ $user->name ?? null}}"
											class="form-control" id="exampleInputtext"
											placeholder="">
									</div>
								</div>
							</div>
  
							<div class="row">
  								<div class="col-lg-4">
									<div class="mb-4">
										<label for="exampleInputtext3"
											class="form-label fw-semibold">Phone</label>
										<input type="text" value="{{ $user->mobile ?? null}}"
											class="form-control" name="mobile"
											id="exampleInputtext3" placeholder="">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="mb-4">
										<label for="exampleInputtext3"
											class="form-label fw-semibold">Home Phone</label>
										<input type="text" value="{{ old('home_phone', $home_phone) ?? null}}"
											class="form-control" name="home_phone"
											id="exampleInputtext3" placeholder="">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="mb-4">
										<label for="exampleInputtext3"
											class="form-label fw-semibold">Work Phone</label>
										<input type="text" value="{{ old('work_phone', $work_phone) ?? null}}"
											class="form-control" name="work_phone"
											id="exampleInputtext3" placeholder="">
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-lg-4">
									<div class="mb-4">
										<label for="exampleInputtext1"
											class="form-label fw-semibold">Email</label>
										<input type="email" name=email value="{{ $user->email ?? null}}"
											class="form-control" id="exampleInputtext1"
											placeholder="" readonly>
									</div>
								</div>
 								<div class="col-lg-4">
									<div class="mb-4">
										<label for="exampleInputtext3"
											class="form-label fw-semibold">SSN (Social Security Number)</label>
											<input type="text" name="ssn"
											value="{{ old('ssn', $ssn) ?? null}}"
											class="form-control" id="exampleInputtext4"
											placeholder="">
 									</div>
								</div>
								<div class="col-lg-4">
									<div class="mb-4">
										<label for="exampleInputtext3"
											class="form-label fw-semibold">DOB (Date of Birth)</label>
											<input type="date" name="dob"
											value="{{ old('dob', $dob) ?? null}}"
											class="form-control" id="exampleInputtext4"
											placeholder="">
 									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-lg-12">
									<div class="mb-4">
										<label for="exampleInputtext4"
											class="form-label fw-semibold">Address line 1</label>
										<input type="text" name="address_line1"
											value="{{ $userAddress->address_line1 ?? null}}"
											class="form-control" id="exampleInputtext4"
											placeholder="">
									</div>
								</div>
								<div class="col-lg-12">
									<div class="mb-4">
										<label for="exampleInputtext4"
											class="form-label fw-semibold">Address line 2</label>
										<input type="text" name="address_line2"
											value="{{ $userAddress->address_line2 ?? null}}"
											class="form-control" id="exampleInputtext4"
											placeholder="">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="mb-4">
										<label for="exampleInputtext4"
											class="form-label fw-semibold">City</label>
										<input type="text" name="city"
											value="{{ $userAddress->city ?? null}}" class="form-control"
											id="exampleInputtext4" placeholder="">
										</select>
									</div>
								</div>
							<div class="col-lg-4">
    <div class="mb-4">
        <label class="form-label fw-semibold">State</label>
        <select class="form-select" aria-label="Default select example" name="state_id">
            @foreach($locationStates as $state)
            <option value="{{ $state->state_id }}" {{ $userAddress && $state->state_id == $userAddress->state_id ? 'selected' : '' }}>
                {{ $state->state_name }}
            </option>
            @endforeach
        </select>
    </div>
</div>
								<div class="col-lg-4">
									<div class="mb-4">
										<label for="exampleInputtext4"
											class="form-label fw-semibold">zipcode</label>
										<input type="text" name="zipcode"
											value="{{ $userAddress->zipcode ?? null}}"
											class="form-control" id="exampleInputtext4"
											placeholder="">
									</div>
								</div>
							</div>
							<div class="row">
 								<div class="col-12">
									<div
										class="d-flex align-items-center justify-content-end mt-4 gap-3">
										<button type="submit" class="btn btn-primary">Save</button>
										
									</div>
								</div>
							</div>
						</form>
					</div>
 				</div>
				
				 
				
			</div>
			
			<div class="col-md-4">
			
				<div class="card card-border">
					<div class="card-body p-4">
					
 					<form action="{{ route('user.adminprofileimg', $user->id) }}" method="post"
						enctype="multipart/form-data">

						@csrf
								 
								  <div class="text-center">
						@if(file_exists(public_path('public/images/Uploads/users/'. $user->id . '/' .$user->user_image)))
						<img src="{{ asset('public/images/Uploads/users/'. $user->id . '/' .$user->user_image) }}"
						alt="User Image" id="imagePreview"
						class="img-fluid rounded-circle" width="120" height="120">
						@else
						<img src="{{ asset('public/admin/assets/images/users/1.jpg') }}"
						alt="Default Preview" id="imagePreview"
						class="img-fluid rounded-circle" width="120" height="120">
						@endif


								<div
									class="d-flex align-items-center justify-content-center my-4 gap-3">
									<input type="file" class="form-control" id="file"
										name="user_image" value="{{ $user->user_image }}"
										accept="image/*" onchange="showImagePreview()" required>
									<input type="hidden" name="id" value="{{$user->id}}">
									<button type="submit" class="btn btn-primary">Upload</button>
									{{-- <button class="btn btn-outline-danger">Reset</button> --}}
								</div>
								<p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
							</div>
						</form>

					</div>
				</div>
				
				<div class="card card-border">
					<div class="card-body p-4">
						<h5 class="card-title fw-semibold">Change Password</h5>
						<form action="{{ route('user.passstoreadmin') }}" method="post"
							enctype="multipart/form-data">
							@csrf
							<input type="hidden" name="id" value="{{$user->id}}">
							<div class="mb-4">
								<label for="exampleInputPassword1"
									class="form-label fw-semibold">Current Password</label>
								<input type="password" name="currentpassword" class="form-control"
									id="exampleInputPassword1" value="" required>
							</div>
							<div class="mb-4">
								<label for="exampleInputPassword2"
									class="form-label fw-semibold">New Password</label>
								<input type="password" name="password" class="form-control"
									id="exampleInputPassword2" value="" required>
							</div>
							<div class="">
								<label for="exampleInputPassword3"
									class="form-label fw-semibold">Confirm Password</label>
								<input type="password" name="conformpassword" class="form-control"
									id="exampleInputPassword3" value="" required>
							</div>
							<div
								class="d-flex align-items-center justify-content-center my-4 gap-3">
								<button type="submit" class="btn btn-primary">Change</button>
								{{-- <button class="btn btn-outline-danger">Reset</button> --}}
							</div>
						</form>
					</div>
				</div>
			
			</div>
			
		</div>
		
		<div class="row mt-5">
		
			<div class="col-md-9">
			
				<div class="card" style="border: 1px solid #D8D8D8;">
					<div class="card-body">
						<h4 class="card-title">ACTIVITY FEED</h4>
						<div class="table-responsive">
							<table class="table customize-table mb-0 v-middle">
								<tbody>
									<tr>
										<td style="width:20%">
											<div class="d-flex align-items-center">
												<img src="https://gaffis.in/frontier/website/public/default/default.png" class="rounded-circle" width="40">
												<span class="ms-2 fw-normal">John Smith</span>
											</div>
										</td>
										<td style="width:60%">Line items updated total = $0.00</td>
										<td style="width:20%">Wed 3/13/24 3:09pm</td>
									</tr>
									<tr>
										<td style="width:20%">
											<div class="d-flex align-items-center">
												<img src="https://gaffis.in/frontier/website/public/default/default.png" class="rounded-circle" width="40">
												<span class="ms-2 fw-normal">John Smith</span>
											</div>
										</td>
										<td style="width:60%">Job scheduled for Thu, Mar 14 at 10:00am</td>
										<td style="width:20%">Wed 3/13/24 2:30pm</td>
									</tr>
									<tr>
										<td style="width:20%">
											<div class="d-flex align-items-center">
												<img src="https://gaffis.in/frontier/website/public/default/default.png" class="rounded-circle" width="40">
												<span class="ms-2 fw-normal">John Smith</span>
											</div>
										</td>
										<td style="width:60%">Job scheduled for Thu, Mar 14 at 11:00am</td>
										<td style="width:20%">Wed 3/13/24 1:11pm</td>
									</tr>
									<tr>
										<td style="width:20%">
											<div class="d-flex align-items-center">
												<img src="https://gaffis.in/frontier/website/public/default/default.png" class="rounded-circle" width="40">
												<span class="ms-2 fw-normal">John Smith</span>
											</div>
										</td>
										<td style="width:60%">Job scheduled for Thu, Mar 14 at 11:00am</td>
										<td style="width:20%">Wed 3/13/24 1:10pm</td>
									</tr>
									<tr>
										<td style="width:20%">
											<div class="d-flex align-items-center">
												<img src="https://gaffis.in/frontier/website/public/default/default.png" class="rounded-circle" width="40">
												<span class="ms-2 fw-normal">John Smith</span>
											</div>
										</td>
										<td style="width:60%">Dispatched to MARK SIERRA</td>
										<td style="width:20%">Wed 3/13/24 1:10pm</td>
									</tr>
									<tr>
										<td style="width:20%">
											<div class="d-flex align-items-center">
												<img src="https://gaffis.in/frontier/website/public/default/default.png" class="rounded-circle" width="40">
												<span class="ms-2 fw-normal">John Smith</span>
											</div>
										</td>
										<td style="width:60%">Job created as Invoice #26295 total = $0.00</td>
										<td style="width:20%">Wed 3/13/24 1:10pm</td>
									</tr>
								</tbody>
							</table>
						</div>
					 </div>
				</div>
 				
			</div>
			
			<div class="col-md-3">
 				<div class="card" style="border: 1px solid #D8D8D8;">
					<div class="card-body">
 						<div class="row">
							<div class="col-12">
								<small class="text-muted pt-1 db">Profile Created</small>
								<h6>03-15-2024 @3.00PM</h6>
							</div>
						</div>
 						<div class="row mt-2">
							<div class="col-12">
								<small class="text-muted pt-1 db">Last Login</small>
								<h6>03-15-2024 @3.00PM</h6>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-12">
								<small class="text-muted pt-1 db">Status</small>
								<h6>Active</h6>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-12">
								<small class="text-muted pt-1 db">Login</small>
								<h6>Enabled</h6>
							</div>
						</div>
						<div class="row mt-2">
							<div class="col-12">
								<small class="text-muted pt-1 db">IP Address</small>
								<h6>192.168.1.1</h6>
							</div>
						</div>
 					</div>
				</div>
 			</div>
		
		</div>
		
		
		<div class="row mt-5">
 			<div class="col-md-9">
 				<div class="card" style="border: 1px solid #D8D8D8;">
					<div class="card-body">
						<h4 class="card-title">SETTINGS</h4>
						<div class="row mt-2">
							<div class="col-12">
								
 								<div class="d-flex align-items-center justify-content-between py-3 border-top">
									<div>
									<h5 class="fs-4 fw-semibold mb-0">E-mail Verification</h5>
									<p class="mb-0">E-mail to send verification link</p>
									</div>
									<button class="btn bg-primary-subtle text-primary">Send Email</button>
								</div>
								
								<div class="d-flex align-items-center justify-content-between py-3 border-top">
									<div class="d-flex align-items-center">
									<div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
									<i class="ti ti-mail text-dark d-block fs-7" width="22" height="22"></i>
									</div>
									<div>
									<h5 class="fs-4 fw-semibold">Email Notification</h5>
									<p class="mb-0">Turn on email notificaiton to get updates through email</p>
									</div>
									</div>
									<div class="form-check form-switch mb-0">
									<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked4" checked="">
									</div>
								</div>
								
								<div class="d-flex align-items-center justify-content-between py-3 border-top">
									<div class="d-flex align-items-center">
									<div class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
									<i class="ti ti-mail text-dark d-block fs-7" width="22" height="22"></i>
									</div>
									<div>
									<h5 class="fs-4 fw-semibold">SMS Notification</h5>
									<p class="mb-0">Turn on SMS notificaiton to get updates through SMS</p>
									</div>
									</div>
									<div class="form-check form-switch mb-0">
									<input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked4" checked="">
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
@section('script')
<script>
    const firstNameInput = document.getElementById('first_name');
    const lastNameInput = document.getElementById('last_name');
    const displayNameInput = document.getElementById('display_name');

    // Function to update the display name field
    function updateDisplayName() {
        const firstName = firstNameInput.value.trim();
        const lastName = lastNameInput.value.trim();

        // Concatenate first and last name
        const displayName = firstName + ' ' + lastName;

        // Set the display name input value
        displayNameInput.value = displayName;
    }

    // Listen for input changes on first and last name fields
    firstNameInput.addEventListener('input', updateDisplayName);
    lastNameInput.addEventListener('input', updateDisplayName);

</script>

@endsection
@endsection