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
            <div class="col-6 align-self-center">
                <h4 class="page-title">My Profile</h4>
            </div>
            <div class="col-6 align-self-end">
                <div class="profile-menu-btn" role="toolbar" aria-label="Toolbar with button groups" style="text-align: right;">
                    <div class="btn-group me-1" role="group" aria-label="First group">
                        <a href="{{ route('myprofile.index') }}"
                            class="btn mx-2
                                    @if(request()->routeIs('myprofile.index')) btn-info active @else btn-light-info text-info @endif">Profile</a>
                        <a href="{{ route('myprofile.account') }}"
                            class="btn mx-2
                                    @if(request()->routeIs('myprofile.account')) btn-info active @else btn-light-info text-info @endif">Account Settings</a>
                        <a href="{{ route('myprofile.activity') }}"
                            class="btn mx-2
                                    @if(request()->routeIs('myprofile.activity')) btn-info active @else btn-light-info text-info @endif">Activity and Notifications</a>
                        {{-- <a href="{{ route('myprofile.notification') }}"
                            class="btn mx-2
                                    @if(request()->routeIs('myprofile.notification')) btn-info active @else btn-light-info text-info @endif">Notification</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close close-alert" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Display error message if it exists in the session -->
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert" id="error-alert">
            {{ session('error') }}
            <button type="button" class="btn-close close-alert" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <script>
            // Automatically close the alerts after 5 seconds
            setTimeout(function() {
                $('#success-alert, #error-alert').alert('close');
            }, 5000);

        </script>

        <div class="row mt-12 ">


            <div class="col-md-9">
                <div class="card">
                    <div class="card-body card-border shadow">
                        <h5 class="card-title">SETTINGS</h5>
                        <div class="row mt-2">
                            <div class="col-12">

                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div>
                                        <h5 class="fs-4 fw-semibold mb-0">E-mail Verification</h5>
                                        <p class="mb-0">E-mail to send verification link</p>

                                    </div>
                                    <form id="emailForm" action="{{ route('myprofile.email_verified') }}" method="post">
                                        @csrf
                                        <button type="submit" id="sendEmailBtn"
                                            class="btn @if($user->email_verified == 1) bg-success text-white @else bg-warning text-dark @endif">
                                            @if($user->email_verified == 1)
                                            Send Email
                                            @else
                                            Un-Send Email
                                            @endif
                                        </button>
                                    </form>
                                </div>

                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-mail text-dark d-block fs-7" width="22" height="22"></i>
                                        </div>
                                        <div>
                                            <h5 class="fs-4 fw-semibold">Email Notification</h5>
                                            <p class="mb-0">Turn on email notification to get updates through email</p>
                                        </div>
                                    </div>
                                    <form id="emailNotificationForm" action="{{ route('myprofile.email') }}"
                                        method="POST">
                                        @csrf
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="flexSwitchCheckChecked4" @if($user->email_notifications == 1)
                                            checked @endif onchange="toggleEmailNotification()">
                                        </div>
                                    </form>
                                </div>




                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="text-bg-light rounded-1 p-6 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-mail text-dark d-block fs-7" width="22" height="22"></i>
                                        </div>
                                        <div>
                                            <h5 class="fs-4 fw-semibold">SMS Notification</h5>
                                            <p class="mb-0">Turn on SMS notification to get updates through SMS</p>
                                        </div>
                                    </div>
                                    <form id="smsNotificationForm" action="{{ route('myprofile.sms') }}" method="POST">
                                        @csrf
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="flexSwitchCheckChecked4" @if($user->sms_notification == 1) checked
                                            @endif onchange="toggleSmsNotification()">
                                        </div>
                                    </form>
                                </div>




                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body card-border shadow">
                        <h4 class="card-title"></h4>
                        <div class="row mt-2">
                            <div class="col-12">


                                <div class="card-body">


                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <small class="text-muted pt-1 db">Status</small>
                                            <h6>{{ $user->status ?? null}}</h6>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <small class="text-muted pt-1 db">Login</small>
                                            <h6>{{ $user->login ?? null}}</h6>
                                        </div>
                                    </div>
                                    @if (!empty($user))
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <small class="text-muted pt-1 db">Last Login</small>
                                            <h6>{{ date('m-d-Y @h:iA',
                                                strtotime($user->last_login)) }}</h6>
                                        </div>
                                    </div>
                                    @else
                                    @endif


                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <small class="text-muted pt-1 db">IP Address</small>
                                            <h6>{{ $user->LoginHistory->ip_address ?? null}}</h6>
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





</div>


</div>
@section('script')
<script>
    // Add event listener to the form submission
    document.getElementById('emailForm').addEventListener('submit', function(event) {
        // Prevent default form submission
        event.preventDefault();
        // Change button color to green
        document.getElementById('sendEmailBtn').classList.remove('bg-primary-subtle');
        document.getElementById('sendEmailBtn').classList.add('bg-success');
        // Submit the form
        this.submit();
    });

</script>
<script>
    function toggleEmailNotification() {
        // Submit the form when the checkbox state changes
        document.getElementById('emailNotificationForm').submit();
    }

</script>
<script>
    function toggleSmsNotification() {
        // Submit the form when the checkbox state changes
        document.getElementById('smsNotificationForm').submit();
    }

</script>
@endsection
@endsection
