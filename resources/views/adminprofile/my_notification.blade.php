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
            <div class="col-7 align-self-center">
                <h4 class="page-title">My Profile</h4>
            </div>
            <div class="col-5 align-self-end">
                <div class="btn-toolbar profile-menu-btn" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group me-1" role="group" aria-label="First group">
                        <a href="{{route('myprofile.index')}}" class="btn btn-info mx-2">Profile</a>
                        <a href="{{route('myprofile.account')}}" class="btn btn-light-info text-info mx-2">Account
                            Settings</a>
                        <a href="{{route('myprofile.activity')}}" class="btn btn-light-info text-info mx-2">Activity</a>
                    <a href="{{route('myprofile.notification')}}" class="btn btn-light-info text-info mx-2">Notification</a>

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
<div class="row col-md-12 " style="display:flex; justify-content: space-between;">
        <div class="row col-md-8 ">
        
                <div class="card" style="border: 1px solid #D8D8D8;">
                    <div class="card-body">
                        <h4 class="card-title">NOTIFICATIONS</h4>
                       <div class="table-responsive">
    <table class="table customize-table mb-0 v-middle">
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
               <!-- <th>Read</th> -->
            </tr>
        </thead>
        <tbody>
            @foreach($userNotifications as $record)
            <tr>
               <td @if($record->is_read == 0) class="text-muted" @endif>
    <span class="ms-2 fw-normal">{{ $record->notice->notice_title ?? '' }}</span>
</td>
<td @if($record->is_read == 0) class="text-muted" @endif>
    {{ \Carbon\Carbon::parse($record->notice->notice_date)->format('D n/j/y g:ia') ?? 'null' }}
</td>

               <!-- <td >
                    @if($record->is_read == 0)
                        No
                    @elseif($record->is_read == 1)
                        Yes
                    @endif
                </td>    -->                                   
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