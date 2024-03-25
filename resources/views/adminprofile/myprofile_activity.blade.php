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
		
	<div class="row mt-12 justify-content-center">
    <div class="col-md-9">
			
				<div class="card" style="border: 1px solid #D8D8D8;">
					<div class="card-body">
						<h4 class="card-title">ACTIVITY FEED</h4>
						<div class="table-responsive">
							<table class="table customize-table mb-0 v-middle">
							<tbody>
    @foreach($activity as $record)
    <tr>
        <td style="width:20%">
            <div class="d-flex align-items-center">
             @if($record->user_image)
                                       {{-- <img src="{{ asset('public/images/Uploads/users/' . $record->id . '/' . $record->user_image) }}"
                                            alt="avatar" class="rounded-circle" width="40" />  --}} 
                                             <img src="{{ asset('public/images/superadmin/' . $record->user_image) }}"
                                            alt="avatar" class="rounded-circle" width="40" />
                                            
                                        @else
                                        <img src="{{asset('public/images/login_img_bydefault.png')}}" alt="avatar"
                                            class="rounded-circle" width="40" />


                                        @endif


              
                <span class="ms-2 fw-normal">{{ $record->name }}</span>
            </div>
        </td>
        <td style="width:60%">{{ $record->activity}}</td>
<td style="width:20%">
    {{ \Carbon\Carbon::parse($record->created_at)->format('D n/j/y g:ia') ?? 'null' }}
</td>
    </tr>
    @endforeach
</tbody>

							</table>
						</div>
					 </div>
				</div>
 				
			</div>
					</div>
		
		
		</div>
		
	
			
		{{-- <div class="row mt-5">
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
		</div> --}}
		
	</div>
			
    
</div>
@section('script')


@endsection
@endsection