@extends('home')
@section('content')
<!-- Page wrapper  -->
<!-- -------------------------------------------------------------- -->
<div class="page-wrapper" style="display:inline;">
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">{{ $dispatcher->name }}</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('dispatcher.index') }}">Dispatcher</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
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
    <!-- -------------------------------------------------------------- -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Container fluid  -->
    <!-- -------------------------------------------------------------- -->
    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- Row -->
        <div class="row">
            <!-- Column -->
            <div class="col-lg-4 col-xlg-3 col-md-5">

                <div class="card">
                    <div class="card-body">
                        <div class="row text-left justify-content-md-left">
                            <h4 class="card-title mt-2">Summary</h4>
                            <div class="col-6">
                                <small class="text-muted pt-1 db">Last service</small>
                                <h6>Active</h6>
                            </div>
                            <div class="col-6">
                                <small class="text-muted pt-1 db">Profile Created</small>
                                <h6>{{ $dispatcher->created_at ?
                                    \Carbon\Carbon::parse($dispatcher->created_at)->format('m-d-Y') : null }}</h6>
                            </div>
                            <div class="col-6">
                                <small class="text-muted pt-1 db">Lifetime value</small>
                                <h6>$0.00</h6>
                            </div>
                            <div class="col-6">
                                <small class="text-muted pt-1 db">Outstanding balance</small>
                                <h6>$0.00</h6>
                            </div>
                            <div class="col-12">
                                <h4 class="card-title mt-4">Contact info</h4>

                                <h6 style="font-weight: normal;">
                                    <i class="fas fa-home"></i>{{ old('home_phone', $home_phone) }}
                                </h6>
                                <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i>
                                    {{$dispatcher->mobile}}</h6>
                                {{-- <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i> +1 123 456 7890
                                </h6> --}}
                                <h6 style="font-weight: normal;"><i class="fas fa-envelope"></i> {{$dispatcher->email}}
                                </h6>
                            </div>
                            <div class="col-12">
                                <h4 class="card-title mt-4">Notifications</h4>
                                <h6 style="font-weight: normal;margin-bottom: 0px;"><i class="fas fa-check"></i> Yes
                                </h6>
                            </div>
                            <div class="col-12">
                                <h4 class="card-title mt-4">Tags</h4>
                                <div class="mt-0">
                                    @if($dispatcher->tags->isNotEmpty())
                                    @foreach($dispatcher->tags as $tag)
                                    <span class="badge bg-dark">{{ $tag->tag_name }}</span>
                                    @endforeach
                                    @else
                                    <span class="badge bg-dark">No tags available</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12" ">
					{{-- 			<h4 class=" card-title mt-4">Lead Source</h4>
                                <div class="mt-0">
                                    <span class="mb-1 badge bg-primary">{{$dispatcher->leadsourcename->source_name ??
                                        null }}</span>
                                </div> --}}
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card">
                </div>

            </div>

            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-8 col-xlg-9 col-md-7">
                <!-- ---------------------
                            start Timeline
                        ---------------- -->
                <div class="card">
                    <!-- Tabs -->
                    <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">

                        <li class="nav-item">
                            <a class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" href="#profile_tab"
                                role="tab" aria-controls="pills-profile" aria-selected="true">Profile</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="pills-setting-tab" data-bs-toggle="pill" href="#calls_tab"
                                role="tab" aria-controls="pills-setting" aria-selected="false">Calls Scheduled</a>
                        </li>



                        <li class="nav-item">
                            <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#others_tab"
                                role="tab" aria-controls="pills-timeline" aria-selected="false">Note Tab</a>
                        </li>
                        <li class="nav-item" style="">
                            <a class="nav-link" id="pills-payment-tab" data-bs-toggle="pill" href="#payment_tab"
                                role="tab" aria-controls="pills-payments" aria-selected="false">Activity</a>
                        </li>

                    </ul>
                    <!-- Tabs -->
                    <div class="tab-content" id="pills-tabContent">

                        <div class="tab-pane fade show active" id="profile_tab" role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 col-xs-6 b-r">
                                        <div class="card">
                                            <div class="card-body">
                                                <center class="mt-1">
                                                    @if($dispatcher->user_image)
                                                    <img src="{{ asset('public/images/dispatcher/'. $dispatcher->id . '/' . $dispatcher->user_image) }}"
                                                        class="rounded-circle" width="150" />
                                                    @else
                                                    <img src="{{asset('public/images/login_img_bydefault.png')}}"
                                                        alt="avatar" class="rounded-circle" width="150" />
                                                    @endif
                                                    <h4 class="card-title mt-1">{{ $dispatcher->name }}</h4>
                                                    {{-- <h6 class="card-subtitle">{{ $dispatcher->company ?? 'null' }}
                                                    </h6> --}}
                                                </center>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-6 b-r">&nbsp;
                                    </div>
                                    <div class="col-md-8 col-xs-6 b-r">
                                        <div>
                                            @foreach($location as $location)
                                            <div>
                                                <small class="text-muted pt-4 db">Address</small>
                                                <div style="display:flex;">
                                                    @if($location)
                                                    <h6>{{ $userAddresscity}}</h6>&nbsp;
                                                    <h6>{{ $location->locationStateName->state_name ?? null }}</h6>
                                                    <span>,</span>
                                                    <h6>{{ $location->zipcode }}</h6>
                                                    @else
                                                    <h6>null</h6>
                                                    @endif
                                                    <br />
                                                </div>
                                                <iframe id="map" width="100%" height="150" frameborder="0"
                                                    style="border: 0" allowfullscreen></iframe>
                                            </div>
                                            <hr />
                                            @endforeach
                                        </div>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="card-title mt-4">Files & Attachments</h4>
                                        <div class="row">
                                            <p class="col-md-4 col-xs-6"><i class="fas fa-file-alt"></i> File Name 1</p>
                                            <p class="col-md-4 col-xs-6"><i class="fas fa-file-alt"></i> File Name 2</p>
                                            <p class="col-md-4 col-xs-6"><i class="fas fa-file-alt"></i> File Name 3</p>
                                            <p class="col-md-4 col-xs-6"><i class="fas fa-file-alt"></i> File Name 4</p>
                                        </div>
                                    </div>
                                </div>

                                <hr />
                            </div>
                        </div>

                        <div class="tab-pane fade" id="calls_tab" role="tabpanel" aria-labelledby="pills-setting-tab">
                            <div class="card-body">
                                <div class="table-responsive mt-4">
                                    <table id="zero_config" class="table table-bordered text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Job Name </th>
                                                <th>Date</th>
                                                <th>Technician</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jobasign as $customercall)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><a href="{{ route('tickets.show', $customercall->id) }}"
                                                        class="font-medium link">{{$customercall->job_title
                                                        ?? null}}</a><br />
                                                </td>
                                                {{-- <td><a href="#" class="fw-bold link">{{$customercall->job_code ??
                                                        null}}</a>
                                                </td> --}}
                                                @php
                                                $jobassign = DB::table('job_assigned')->where('job_id',
                                                $customercall->id)->first();
                                                $created_at = $jobassign ? $jobassign->created_at : null;
                                                @endphp

                                                <td>
                                                    @if ($created_at)
                                                    <div class="font-medium link">{{
                                                        \Carbon\Carbon::parse($created_at)->format('m-d-y') }}
                                                    </div>
                                                    <div style="font-size:12px;">
                                                        {{
                                                        \Carbon\Carbon::parse($jobassign->start_date_time)->format('g:ia')
                                                        }}
                                                        to {{
                                                        \Carbon\Carbon::parse($jobassign->end_date_time)->format('g:ia')
                                                        }}
                                                    </div>
                                                    @else
                                                    <div>N/A</div>
                                                    @endif
                                                </td>

                                                <td>
                                                    @php
                                                    $technician = DB::table('users')->where('id',
                                                    $customercall->technician_id)->first();
                                                    @endphp

                                                    {{ $technician ? $technician->name : 'N/A' }}
                                                </td>
                                                <td><span
                                                        class="badge bg-light-warning text-warning font-medium">{{$customercall->status
                                                        ?? null}}</span></td>


                                            </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="payment_tab" role="tabpanel" aria-labelledby="pills-payment-tab">

                            <div class="card-body">
                                <div class="table-responsive mt-4">
                                    <table id="zero_config" class="table table-bordered text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Activity</th>
                                                <th>Date</th>
                                                <th>Added by</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($activity as $customercall)
                                            <tr>

                                                <td>{{ $loop->iteration }}</td>
                                                <td> {{$customercall->activity ??
                                                    null}}
                                                </td>
                                                {{-- <td><a href="#" class="fw-bold link">{{$customercall->job_code ??
                                                        null}}</a>
                                                </td> --}}


                                                <td>
                                                    @if ($customercall->created_at)
                                                    <div class="font-medium link">{{
                                                        \Carbon\Carbon::parse($customercall->created_at)->format('m-d-y')
                                                        }}
                                                    </div>

                                                    @else
                                                    <div>N/A</div>
                                                    @endif
                                                </td>

                                                <td>
                                                    @php
                                                    $technician = DB::table('users')->where('id',
                                                    $customercall->user_id)->first();
                                                    @endphp

                                                    {{ $technician ? $technician->name : 'N/A' }}
                                                </td>



                                            </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade show " id="others_tab" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body">
                                <div class="profiletimeline mt-0">
                                @foreach ($notename as $notename )
                                    <div class="sl-item">
                                        <div class="sl-left">
                                            @php
                                        $username = DB::table('users')->where('id',
                                        $notename->added_by)->first();
                                        @endphp
@if($username && $username->user_image)                                            <img src="{{ asset('public/images/admin/'. $username->id . '/' . $username->user_image) }}"
                                                class="rounded-circle" alt="user" />
                                            @else
                                            <img src="{{ asset('public/images/login_img_bydefault.png') }}" alt="user"
                                                class=" rounded-circle" />
                                            @endif

                                        </div>
                                       
                                        <div class="sl-right">
                                            <div>
                                                <a href="javascript:void(0)" class="link"> {{$username->name ??
                                                    null}}</a>
                                                <span class="sl-date">
                                                    {{ \Carbon\Carbon::parse($notename->created_at)->diffForHumans() }}
                                                </span>
                                                <p><strong> </strong><a href="javascript:void(0)">
                                                    </a></p>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12">
                                                        {{ $notename->note }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr />
                                    @endforeach
                                    {{-- <div class="sl-item">
                                        <div class="sl-left">
                                            <img src="{{ asset('public/admin/assets/images/users/1.jpg') }}" alt="user"
                                                class="rounded-circle" />
                                        </div>
                                        <div class="sl-right">
                                            <div>
                                                <span class="sl-date">2 days ago</span>
                                                <a href="javascript:void(0)" class="link">John Smith</a>
                                                <p><strong>LG AC REPAIR </strong><a href="javascript:void(0)">
                                                        View
                                                        Ticket</a></p>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12">Lorem ipsum dolor sit amet,
                                                        consectetur adipiscing elit, sed do eiusmod tempor
                                                        incididunt ut
                                                        labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                        quis
                                                        nostrud exercitation ullamco laboris nisi ut aliquip ex
                                                        ea
                                                        commodo consequat</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="sl-item">
                                        <div class="sl-left">
                                            <img src="{{ asset('public/admin/assets/images/users/1.jpg') }}" alt="user"
                                                class="rounded-circle" />
                                        </div>
                                        <div class="sl-right">
                                            <div>
                                                <a href="javascript:void(0)" class="link">James Nelson</a>
                                                <span class="sl-date">4 days ago</span>
                                                <p><strong>LG AC REPAIR </strong><a href="javascript:void(0)">
                                                        View
                                                        Ticket</a></p>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12">Lorem ipsum dolor sit amet,
                                                        consectetur adipiscing elit, sed do eiusmod tempor
                                                        incididunt ut
                                                        labore et dolore magna aliqua. Ut enim ad minim veniam,
                                                        quis
                                                        nostrud exercitation ullamco laboris nisi ut aliquip ex
                                                        ea
                                                        commodo consequat</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>













                    </div>
                </div>
                <!-- ---------------------
                            end Timeline
                        ---------------- -->
            </div>
            <!-- Column -->
        </div>
        <!-- Row -->
        <!-- -------------------------------------------------------------- -->
        <!-- End PAge Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------- -->
        <!-- Right sidebar -->
        <!-- -------------------------------------------------------------- -->
        <!-- .right-sidebar -->
        <!-- -------------------------------------------------------------- -->
        <!-- End Right sidebar -->
        <!-- -------------------------------------------------------------- -->
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Container fluid  -->
</div>
</div>
@section('script')
<script>
    // Get latitude and longitude values from your data or variables
        var latitude = {{$latitude}}; // Example latitude
        var longitude = {{$longitude}}; // Example longitude

        // Construct the URL with the latitude and longitude values
        var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' + latitude + ',' + longitude + '&zoom=13';

        document.getElementById('map').src = mapUrl;
</script>

@endsection

@endsection