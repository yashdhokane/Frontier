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
                <h4 class="page-title">{{ $user->name }}</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="https://gaffis.in/frontier/website/home">Home</a></li>
                            <li class="breadcrumb-item"><a href="https://gaffis.in/frontier/website/users">Customers</a>
                            </li>
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

                                <h6>
                                    {{ $jobasigndate && $jobasigndate->start_date_time ?
                                    \Carbon\Carbon::parse($jobasigndate->start_date_time)->format('m-d-Y') :
                                    null }}
                                </h6>

                            </div>
                            <div class="col-6">
                                <small class="text-muted pt-1 db">Profile Created</small>
                                <h6>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('m-d-Y') :
                                    null }}</h6>


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
                                <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i> {{$user->mobile}}
                                </h6>
                                {{-- <h6 style="font-weight: normal;"><i class="fas fa-mobile-alt"></i> +1 123 456 7890
                                </h6> --}}
                                <h6 style="font-weight: normal;"><i class="fas fa-envelope"></i> {{$user->email}}</h6>
                            </div>
                            <div class="col-12">
                                <h4 class="card-title mt-4">Notifications</h4>
                                <h6 style="font-weight: normal;margin-bottom: 0px;"><i class="fas fa-check"></i> Yes
                                </h6>
                            </div>
                            <div class="col-12">
                                <h4 class="card-title mt-4">Tags</h4>
                                <div class="mt-0">
                                    @if($user->tags->isNotEmpty())
                                    @foreach($user->tags as $tag)
                                    <span class="badge bg-dark">{{ $tag->tag_name }}</span>
                                    @endforeach
                                    @else
                                    <span class="badge bg-dark">No tags available</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <h4 class="card-title mt-4">Lead Source</h4>
                                <div class="mt-0">
                                    <span class="mb-1 badge bg-primary">{{$user->leadsourcename->source_name ??
                                        null }}</span>
                                </div>
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
                                role="tab" aria-controls="pills-setting" aria-selected="false">Calls</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="pills-payment-tab" data-bs-toggle="pill" href="#payment_tab"
                                role="tab" aria-controls="pills-payments" aria-selected="false">Payments & Estimates</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#others_tab"
                                role="tab" aria-controls="pills-timeline" aria-selected="false">Other Details</a>
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
                                                    @if($user->user_image)
                                                    <img src="{{ asset('public/images/customer/' . $user->user_image) }}"
                                                        class="rounded-circle" width="150" />
                                                    @else
                                                    <img src="{{ asset('public/images/login_img_bydefault.png') }}"
                                                        alt="avatar" class="rounded-circle" width="150" />
                                                    @endif
                                                    <h4 class="card-title mt-1">{{ $user->name }}</h4>
                                                    <h6 class="card-subtitle">{{ $user->company ?? null }}</h6>
                                                </center>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-1 col-xs-6 b-r">&nbsp;</div>

                                    <div class="col-md-8 col-xs-6 b-r">
                                        <div>
                                            @foreach($userAddresscity as $location)

                                            <div>
                                                <small class="text-muted pt-4 db">Address - {{ $location->address_type
                                                    }}</small>
                                                <div style="display:flex;">

                                                    <h6>{{ $location->city}}</h6>&nbsp;
                                                    <h6>{{ $location->state_name ?? null }}</h6>
                                                    <span>,</span>
                                                    <h6>{{ $location->zipcode }}</h6>


                                                    <br />
                                                </div>
                                                <iframe id="map{{ $location->address_id }}" width="100%" height="150"
                                                    frameborder="0" style="border: 0" allowfullscreen></iframe>
                                                {{-- <iframe
                                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6991603.699017098!2d-100.0768425!3d31.168910300000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864070360b823249%3A0x16eb1c8f1808de3c!2sTexas%2C%20USA!5e0!3m2!1sen!2sin!4v1701086703789!5m2!1sen!2sin"
                                                    width="100%" height="300" style="border:0;" allowfullscreen=""
                                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                                --}}

                                            </div>
                                            <hr />
                                            @endforeach




                                        </div>


                                    </div>
                                    <div class="col-md-12">
                                        <div class="row" style="margin-left:12px;">

                                            <h4 class="card-title mt-4">Files & Attachments</h4>
                                            <div class="row">
                                                @foreach($customerimage as $image)
                                                <div class="col-md-4 col-xs-6">

                                                    @if($image->filename)
                                                    <a href="{{ asset('/storage/app/customers/' . $image->filename) }}"
                                                        download>
                                                        <p><i class="fas fa-file-alt"></i></p>
                                                        <img src="{{ asset('/storage/app/customers/' . $image->filename) }}"
                                                            alt="Customer Image" style="width: 50px; height: 50px;">
                                                    </a>
                                                    @else
                                                    <!-- Default image if no image available -->
                                                    <img src="{{ asset('https://gaffis.in/frontier/website/public/admin/assets/images/users/1.jpg') }}"
                                                        alt="Default Image" style="width: 50px; height: 50px;">
                                                    @endif
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="calls_tab" role="tabpanel" aria-labelledby="pills-setting-tab">
                            <div class="card-body">
                                <div class="table-responsive mt-4">
                                    <table id="zero_config" class="table table-bordered text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Status</th>
                                                <th>Title</th>
                                                <th>ID</th>
                                                <th>Date</th>
                                                <th>Technician</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($jobasign as $customercall)
                                            <tr>
                                                <td><span class="badge bg-light-warning text-warning font-medium">In
                                                        Progress</span></td>
                                                <td><a href="#" class="font-medium link">{{$customercall->assign_title
                                                        ?? null}}</a><br />
                                                </td>
                                                <td><a href="#" class="fw-bold link">{{$customercall->id ?? null}}</a>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($customercall->created_at)->format('d-m-y')
                                                    }}</td>
                                                <td>
                                                    @php
                                                    $technician = DB::table('users')->where('id',
                                                    $customercall->technician_id)->first();
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

                        <div class="tab-pane fade" id="payment_tab" role="tabpanel" aria-labelledby="pills-payment-tab">
                            <div class="card-body">
                                <h4>Payments</h6>
                                    <div class="table-responsive mt-4">
                                        <table id="zero_config" class="table table-bordered text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Ticket</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Technician</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>AONO-123456401</td>
                                                    <td>11-08-2023</td>
                                                    <td>$320 <span class="badge bg-success font-weight-100">Paid</span>
                                                    </td>
                                                    <td>Adam Smith</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button type="button"
                                                                class="btn btn-light-primary text-primary dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="ri-settings-3-fill align-middle fs-5"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="javascript:void(0)"><i
                                                                        data-feather="eye" class="feather-sm me-2"></i>
                                                                    Open</a>
                                                                <a class="dropdown-item" href="javascript:void(0)"><i
                                                                        data-feather="edit-2"
                                                                        class="feather-sm me-2"></i> Edit</a>
                                                                <a class="dropdown-item" href="javascript:void(0)"><i
                                                                        data-feather="trash-2"
                                                                        class="feather-sm me-2"></i> Delete</a>
                                                                <a class="dropdown-item" href="javascript:void(0)"><i
                                                                        data-feather="message-circle"
                                                                        class="feather-sm me-2"></i>
                                                                    Comments</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <br />
                                    <h4>Estimates</h6>
                                        <div class="table-responsive mt-4">
                                            <table id="zero_config" class="table table-bordered text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Ticket</th>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Technician</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>AONO-123456401</td>
                                                        <td>11-08-2023</td>
                                                        <td>$320 <span
                                                                class="badge bg-success font-weight-100">Paid</span>
                                                        </td>
                                                        <td>Adam Smith</td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <button type="button"
                                                                    class="btn btn-light-primary text-primary dropdown-toggle"
                                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    <i class="ri-settings-3-fill align-middle fs-5"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item"
                                                                        href="javascript:void(0)"><i data-feather="eye"
                                                                            class="feather-sm me-2"></i>
                                                                        Open</a>
                                                                    <a class="dropdown-item"
                                                                        href="javascript:void(0)"><i
                                                                            data-feather="edit-2"
                                                                            class="feather-sm me-2"></i>
                                                                        Edit</a>
                                                                    <a class="dropdown-item"
                                                                        href="javascript:void(0)"><i
                                                                            data-feather="trash-2"
                                                                            class="feather-sm me-2"></i>
                                                                        Delete</a>
                                                                    <a class="dropdown-item"
                                                                        href="javascript:void(0)"><i
                                                                            data-feather="message-circle"
                                                                            class="feather-sm me-2"></i>
                                                                        Comments</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                            </div>
                        </div>



                        <div class="tab-pane fade show " id="others_tab" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">
                            <div class="card-body">
                                <div class="profiletimeline mt-0">
                                    <div class="sl-item">
                                        <div class="sl-left">
                                            <img src="{{asset('https://gaffis.in/frontier/webapp/design/assets/images/users/1.jpg')}}"
                                                alt="user" class="rounded-circle" />
                                        </div>
                                        <div class="sl-right">
                                            <div>
                                                <a href="javascript:void(0)" class="link">John Doe</a>
                                                <span class="sl-date">1 days ago</span>
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
                                            <img src="{{asset('https://gaffis.in/frontier/webapp/design/assets/images/users/1.jpg')}}"
                                                alt="user" class="rounded-circle" />
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
                                            <img src="https://gaffis.in/frontier/webapp/design/assets/images/users/1.jpg"
                                                alt="user" class="rounded-circle" />
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
                                    </div>
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
    @foreach($userAddresscity as $location)
    var latitude = {{ $location->latitude }}; // Example latitude
    var longitude = {{ $location->longitude }}; // Example longitude

    // Construct the URL with the latitude and longitude values
    var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' + latitude
    + ',' + longitude + '&zoom=13';

    document.getElementById('map{{ $location->address_id }}').src = mapUrl;
    @endforeach
</script>



@endsection

@endsection
