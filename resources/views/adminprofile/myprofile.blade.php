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

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Account Setting</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">My Profile</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-7 align-self-center">
                <div class="d-flex no-block justify-content-end align-items-center">
                    <div class="me-2">
                        <div class="lastmonth"></div>
                    </div>
                    <div class="">
                        <small>LAST MONTH</small>
                        <h4 class="text-info mb-0 font-medium">$58,256</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="body-wrapper1111">

        <div class="container-fluid">

            <div class="card">

               
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
                <div class="card-body">

                    <div class="tab-content" id="pills-tabContent">

                        <div class="tab-pane fade active show" id="pills-account" role="tabpanel"
                            aria-labelledby="pills-account-tab" tabindex="0">
                            <div class="row">
                                <div class="col-lg-6 d-flex align-items-stretch">
                                    <form action="{{ route('user.adminprofileimg', $user->id) }}" method="post"
                                        enctype="multipart/form-data">

                                        @csrf
                                        <div class="card w-100 position-relative overflow-hidden">
                                            <div class="card-body p-4">
                                                <h5 class="card-title fw-semibold">Change Profile</h5>
                                                <p class="card-subtitle mb-4">Change your profile picture from here</p>
                                                <div class="text-center">
    @if(file_exists(public_path('images/superadmin/' . $user->user_image)))
    <img src="{{ asset('public/images/superadmin/' . $user->user_image) }}"
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
                                                        <input type="hidden" name="id" value="{{  $user->id}}">
                                                        <button type="submit" class="btn btn-primary">Upload</button>
                                                        {{-- <button class="btn btn-outline-danger">Reset</button> --}}
                                                    </div>
                                                    <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-lg-6 d-flex align-items-stretch">
                                    <div class="card w-100 position-relative overflow-hidden">
                                        <div class="card-body p-4">
                                            <h5 class="card-title fw-semibold">Change Password</h5>
                                            <p class="card-subtitle mb-4">To change your password please confirm here
                                            </p>
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
                                <div class="col-12">
                                    <div class="card w-100 position-relative overflow-hidden mb-0">
                                        <div class="card-body p-4">
                                            <h5 class="card-title fw-semibold">Personal Details</h5>
                                            <p class="card-subtitle mb-4">To change your personal detail , edit and save
                                                from here</p>
                                            <form action="{{ route('user.infoadmin') }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$user->id}}">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-4">
                                                            <label for="exampleInputtext"
                                                                class="form-label fw-semibold">Your Name</label>
                                                            <input type="text" name="name" value="{{ $user->name }}"
                                                                class="form-control" id="exampleInputtext"
                                                                placeholder="">
                                                        </div>
                                                        <div class="mb-4">
                                                            <label for="exampleInputtext1"
                                                                class="form-label fw-semibold">Email</label>
                                                            <input type="email" name=email value="{{ $user->email }}"
                                                                class="form-control" id="exampleInputtext1"
                                                                placeholder="">
                                                        </div>
                                                        <div class="mb-4">
                                                            <label for="exampleInputtext3"
                                                                class="form-label fw-semibold">Phone</label>
                                                            <input type="text" value="{{ $user->mobile }}"
                                                                class="form-control" name="mobile"
                                                                id="exampleInputtext3" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="">
                                                            <label for="exampleInputtext4"
                                                                class="form-label fw-semibold">Address</label>
                                                            <input type="text" name="address_line1"
                                                                value="{{ $userAddress->address_line1 }}"
                                                                class="form-control" id="exampleInputtext4"
                                                                placeholder="">
                                                        </div>
                                                        <div class="mb-4">
                                                            <label for="exampleInputtext4"
                                                                class="form-label fw-semibold">City</label>
                                                            <input type="text" name="city"
                                                                value="{{ $userAddress->city }}" class="form-control"
                                                                id="exampleInputtext4" placeholder="">
                                                            </select>
                                                        </div>
                                                        <div class="mb-4">
                                                            <label class="form-label fw-semibold">State</label>
                                                            <select class="form-select"
                                                                aria-label="Default select example" name="state_id">
                                                                @foreach($locationStates as $state)
                                                                <option value="{{ $state->state_id }}" {{ $state->
                                                                    state_id == $userAddress->state_id ? 'selected' : ''
                                                                    }}>
                                                                    {{ $state->state_name }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div
                                                            class="d-flex align-items-center justify-content-end mt-4 gap-3">
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                            <button
                                                                class="btn bg-danger-subtle text-danger">Cancel</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-security" role="tabpanel"
                            aria-labelledby="pills-security-tab" tabindex="0">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-body p-4">
                                            <div
                                                class="d-flex align-items-center justify-content-between py-3 border-top">
                                                <div>
                                                    <h5 class="fs-4 fw-semibold mb-0">E-mail Recovery</h5>
                                                    <p class="mb-0">E-mail to send verification link</p>
                                                </div>
                                                <button class="btn bg-primary-subtle text-primary">Setup</button>
                                            </div>
                                            <div
                                                class="d-flex align-items-center justify-content-between py-3 border-top">
                                                <div>
                                                    <h5 class="fs-4 fw-semibold mb-0">SMS Recovery</h5>
                                                    <p class="mb-0">Your phone number or something</p>
                                                </div>
                                                <button class="btn bg-primary-subtle text-primary">Setup</button>
                                            </div>
                                            <div
                                                class="d-flex align-items-center justify-content-between py-3 border-top">
                                                <div>
                                                    <h5 class="fs-4 fw-semibold mb-0">Current Timezone</h5>
                                                    <p class="mb-0">Time zones and calendar display settings.</p>
                                                    <p class="mb-0"><span>Time zone:</span> America/Los_Angeles </p>
                                                </div>
                                                <button class="btn bg-primary-subtle text-primary">Change</button>
                                            </div>
                                            <div
                                                class="d-flex align-items-center justify-content-between py-3 border-top">
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                                        <i class="ti ti-mail text-dark d-block fs-7" width="22"
                                                            height="22"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="fs-4 fw-semibold">Email Notification</h5>
                                                        <p class="mb-0">Turn on email notificaiton to get updates
                                                            through email</p>
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="flexSwitchCheckChecked4" checked="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body p-4">
                                            <div
                                                class="text-bg-light rounded-1 p-6 d-inline-flex align-items-center justify-content-center mb-3">
                                                <i class="ti ti-device-laptop text-primary d-block fs-7" width="22"
                                                    height="22"></i>
                                            </div>
                                            <button class="btn btn-primary mb-4">Sign out from all devices</button>
                                            <button class="btn bg-primary-subtle text-primary w-100 py-1">Need Help
                                                ?</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-end gap-3">
                                        <button class="btn btn-primary">Save</button>
                                        <button class="btn bg-danger-subtle text-danger">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-notifications" role="tabpanel"
                            aria-labelledby="pills-notifications-tab" tabindex="0">
                            <div class="row justify-content-left">
                                <div class="col-lg-9">
                                    <div class="card">
                                        <div class="card-body p-4">
                                            <h4 class="fw-semibold mb-3">Notification</h4>
                                            <p>All notifications will go here</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-bills" role="tabpanel" aria-labelledby="pills-bills-tab"
                            tabindex="0">
                            <div class="row justify-content-left">
                                <div class="col-lg-9">
                                    <div class="card">
                                        <div class="card-body p-4">
                                            <h4 class="fw-semibold mb-3">My Messages</h4>
                                            <p>All messages will go here</p>
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
</div>

</div>
@section('script')

@endsection
@endsection