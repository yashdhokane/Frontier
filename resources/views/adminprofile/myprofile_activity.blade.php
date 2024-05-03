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
        <div class="row">
            <div class="col-md-6 ">

                <div class="card">
                    <div class="card-body card-border shadow">
                        <h5 class="card-title">ACTIVITY FEED</h5>
                        <div class="table-responsive">
                            <table class="table customize-table mb-0 v-middle">
                                <thead>
                                    <tr>
                                        <!-- <th style="width:20%">User</th> -->
                                        <th>Activity</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activity as $record)
                                    <tr>
                                        <td>{{ $record->activity}}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($record->created_at)->format('D n/j/y g:ia') ??
                                            'null' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>


            </div>

            <div class="col-md-6 ">

                <div class="card">
                    <div class="card-body card-border shadow">
                        <h5 class="card-title">NOTIFICATIONS</h5>
                        <div class="table-responsive">
                            <table class="table customize-table mb-0 v-middle">
                                <thead>
                                    <tr>
                                        <th>Notifications</th>
                                        <th>Date</th>
                                     </tr>
                                </thead>
                                <tbody>
                                    @foreach($userNotifications as $record)
                                    <tr>
                                        <td @if($record->is_read == 0) class="text-muted" @endif>
                                            <span class="fw-normal">{{ $record->notice->notice_title ?? ''
                                                }}</span>
                                        </td>
                                        <td @if($record->is_read == 0) class="text-muted" @endif>
                                            {{ \Carbon\Carbon::parse($record->notice->notice_date)->format('D n/j/y
                                            g:ia') ?? 'null' }}
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


</div>


</div>


</div>
@section('script')


@endsection
@endsection
