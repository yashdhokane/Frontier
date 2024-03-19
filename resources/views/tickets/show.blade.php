@extends('home')
@section('content')

    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Jobs / Ticket Details</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Jobs List</a></li>
                            <li class="breadcrumb-item"><a href="#">Jobs / Ticket</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Information</li>
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
        <!-- basic table -->

        <div class="row">

            <div class="col-md-3">

                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body text-center">
                            <h4 class="card-title">Customer</h4>
                            <div class="profile-pic mb-3 mt-3">
                                @isset($technicians->user->user_image)
                                    <img src="{{ asset('public/images/customer/' . $technicians->user->user_image) ?? null }}"
                                        width="150" class="rounded-circle" alt="user1" />
                                @else
                                    <img src="{{ $defaultImage }}" width="150" class="rounded-circle" alt="user" />
                                @endisset
                                <h4 class="mt-3 mb-0">{{ $technicians->user->name ?? null }}</h4>
                                <a
                                    href="mailto:{{ $technicians->user->email ?? '' }}">{{ $technicians->user->email ?? null }}</a><br>
                                <small
                                    class="text-muted">{{ $technicians->user->mobile ?? null }}<br />{{ $technicians->address ?? null }},{{ $technicians->city ?? null }},{{ $technicians->state ?? null }},{{ $technicians->zipcode ?? null }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body">
                            <div class="">
                                <h5 class="todo-desc mb-0 fs-3 font-weight-medium">
                                    {{ $technicians->address ?? null }},{{ $technicians->city ?? null }},{{ $technicians->state ?? null }},{{ $technicians->zipcode ?? null }}
                                </h5>
                                <iframe id="map238" width="100%" height="150" frameborder="0" style="border: 0"
                                    allowfullscreen=""></iframe>
                                <h5 class="todo-desc mb-0 fs-3 font-weight-medium">Service Area:
                                    {{ $technicians->serviceareaname->area_name ?? null }} </h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body">
                            <div class="row open_items">
                                <div class="col-md-2 ">
                                    <i class="fas fas fa-tag "></i>
                                </div>
                                <div class="col-md-8">
                                    <h4>Customer Tags</h4>
                                </div>
                                <div class="col-md-2 addCustomerTags" style="cursor: pointer;">
                                    <i class="fas fa-plus "></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @foreach ($Sitetagnames as $item)
                                            {{ $item->tag_name }} ,
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-12 showCustomerTags" style="display:none; ">
                                    <form action="{{ url('add/customer_tags/' . $technicians->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <select class="select2-with-menu-bg form-control  me-sm-2"
                                                name="customer_tags[]" id="menu-bg-multiple1" multiple="multiple"
                                                data-bgcolor="light" data-bgcolor-variation="accent-3"
                                                data-text-color="blue" style="width: 100%" required>
                                                @foreach ($customer_tag as $item)
                                                    <option value="{{ $item->tag_id }}">
                                                        {{ $item->tag_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 text-end">
                                            <button type="submit" class="btn btn-primary rounded">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body">
                            <div class="row open_items">
                                <div class="col-md-1">
                                    <i class="fas fas fa-tag "></i>
                                </div>
                                <div class="col-md-9">
                                    <h4>Job Tags</h4>
                                </div>
                                <div class="col-md-1 addJobTags" style="cursor: pointer;">
                                    <i class="fas fa-plus "></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @foreach ($jobtagnames as $item)
                                            {{ $item->field_name }} ,
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-12 showJobTags" style="display:none; ">
                                    <form action="{{ url('add/job_tags/' . $technicians->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <select class="select2-with-menu-bg form-control  me-sm-2" name="job_tags[]"
                                                id="menu-bg-multiple" multiple="multiple" data-bgcolor="light"
                                                data-bgcolor-variation="accent-3" data-text-color="blue"
                                                style="width: 100%" required>
                                                @foreach ($job_tag as $item)
                                                    <option value="{{ $item->field_id }}">
                                                        {{ $item->field_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 text-end">
                                            <button type="submit" class="btn btn-primary rounded">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body">
                            <div class="row open_items">
                                <div class="col-md-1">
                                    <i class="fas fa-paperclip"></i>
                                </div>
                                <div class="col-md-9">
                                    <h4>Attachments</h4>
                                </div>
                                <div class="col-md-1 addAttachment" style="cursor: pointer;">
                                    <i class="fas fa-plus "></i>
                                </div>
                            </div>
                            <div class="row showAttachment" style="display: none;">
                                <form action="{{ url('add/attachment/' . $technicians->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="attachment" id="" class="form-control">
                                    <div class="mb-3 text-end">
                                        <button type="submit" class="btn btn-primary rounded mt-2">Add</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body">
                            <div class="row open_items">
                                <div class="col-md-1">
                                    <i class="fas fa-bullseye "></i>
                                </div>
                                <div class="col-md-9">
                                    <h4>Lead Source</h4>
                                </div>
                                <div class="col-md-1 addSource" style="cursor: pointer;">
                                    <i class="fas fa-plus "></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @foreach ($source as $item)
                                            {{ $item->source_name }} ,
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-12 showSource" style="display:none; ">
                                    <form action="{{ url('add/leadsource/' . $technicians->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <select class="select2-with-menu-bg form-control  me-sm-2"
                                                name="lead_source[]" id="menu-bg-multiple2" multiple="multiple"
                                                data-bgcolor="light" data-bgcolor-variation="accent-3"
                                                data-text-color="blue" style="width: 100%" required>
                                                @foreach ($leadsource as $item)
                                                    <option value="{{ $item->source_id }}">
                                                        {{ $item->source_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3 text-end">
                                            <button type="submit" class="btn btn-primary rounded">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body text-center">
                            <h4 class="card-title">Technician Assigned</h4>
                            <div class="profile-pic mb-3 mt-3">
                                @isset($technicians->usertechnician->user_image)
                                    <img src="{{ asset('public/images/technician/' . $technicians->usertechnician->user_image) ?? null }}"
                                        width="150" class="rounded-circle" alt="user" />
                                @else
                                    <img src="{{ $defaultImage }}" width="150" class="rounded-circle" alt="user" />
                                @endisset
                                <h4 class="mt-3 mb-0">{{ $technicians->usertechnician->name ?? null }}</h4>
                                <a
                                    href="mailto:{{ $technicians->usertechnician->email ?? '' }}">{{ $technicians->usertechnician->email ?? null }}</a><br><small
                                    class="text-muted">{{ $technicians->usertechnician->mobile ?? null }}<br />{{ $technicians->usertechnician->Locationareaname->area_name ?? null }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body text-center">
                            <h4 class="card-title">Ticket Creator</h4>
                            <div class="profile-pic mb-3 mt-3">
                                @isset($technicians->addedby->user_image)
                                    <img src="{{ asset('public/images/technician/' . $technicians->addedby->user_image) ?? null }}"
                                        width="150" class="rounded-circle" alt="user" />
                                @else
                                    <img src="{{ $defaultImage }}" width="150" class="rounded-circle" alt="user" />
                                @endisset
                                <h4 class="mt-3 mb-0">{{ $technicians->addedby->name ?? null }}</h4>
                                <a
                                    href="mailto:{{ $technicians->addedby->email ?? '' }}">{{ $technicians->addedby->email ?? null }}</a><br><small
                                    class="text-muted">{{ $technicians->addedby->mobile ?? null }}<br>Frontier Support
                                    Staff</small>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="col-md-9">

                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-2">
                                        <h4 class="card-title">{{ $technicians->job_title ?? null }}
                                            ({{ $technicians->job_code ?? null }}) <span class="mb-1 badge bg-warning"
                                                style="font-size: 15px;">{{ $technicians->status ?? null }}
                                            </span> </h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <h5 class="card-title"><i class="fa fa-calendar" aria-hidden="true"></i>
                                            {{ $convertDateToTimezone($technicians->created_at ?? null, 'jS F Y') }},
                                            {{ $convertTimeToTimezone($technicians->created_at ?? null, 'h:i A') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <p>{{ $technicians->description ?? null }}</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>Duration:</strong>
                                        @if ($technicians->jobassignname->duration)
                                            <?php
                                            $durationInMinutes = $technicians->jobassignname->duration ?? null;
                                            $durationInHours = $durationInMinutes / 60; // Convert minutes to hours
                                            ?>
                                            {{ $durationInHours ?? null }} Hours
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>Priority:</strong> {{ $technicians->priority ?? null }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>Appliances:</strong>
                                        {{ $technicians->jobdetailsinfo->apliencename->appliance_name ?? null }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>Manufacturer:</strong>
                                        {{ $technicians->jobdetailsinfo->manufacturername->manufacturer_name ?? null }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>Model Number :</strong>
                                        {{ $technicians->jobdetailsinfo->model_number ?? null }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>Serial Number :</strong>
                                        {{ $technicians->jobdetailsinfo->serial_number ?? null }} </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body">
                            <h4 class="card-title">FIELD TECH STATUS</h4>
                            <div class="table-responsive">
                                <table class="table customize-table mb-0 v-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-bottom border-top">Technician</th>
                                            <th class="border-bottom border-top">Status</th>
                                            <th class="border-bottom border-top">Total travel time</th>
                                            <th class="border-bottom border-top">Total time on job</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($technicians->usertechnician->user_image)
                                                        <img src="{{ url('/images/technician/' . $technicians->usertechnician->user_image) }}"
                                                            class="rounded-circle" width="40">
                                                    @else
                                                        <img src="{{ $defaultImage }}" class="rounded-circle"
                                                            width="40">
                                                    @endif
                                                    <span
                                                        class="ms-3 fw-normal">{{ $technicians->usertechnician->name ?? null }}</span>
                                                </div>
                                            </td>
                                            <td><span
                                                    class="badge bg-light-success text-success fw-normal">{{ $technicians->usertechnician->status ?? null }}</span>
                                            </td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body">
                            <h4 class="card-title">SERVICES & PARTS (LINE ITEMS)</h4>
                            <div class="table-responsive">
                                <table class="table customize-table mb-0 v-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-bottom border-top">Line Item</th>
                                            <th class="border-bottom border-top">Unit Price</th>
                                            <th class="border-bottom border-top">Discount</th>
                                            <th class="border-bottom border-top">Tax</th>
                                            <th class="border-bottom border-top">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $technicians->job_code ?? null }} -
                                                {{ $technicians->jobserviceinfo->service_name ?? null }} <small
                                                    class="text-muted">{{ $technicians->warranty_type ?? null }}</small>
                                            </td>
                                            <td>${{ $technicians->jobserviceinfo->base_price ?? null }}</td>
                                            <td>${{ $technicians->jobserviceinfo->discount ?? null }}</td>
                                            <td>${{ $technicians->jobserviceinfo->tax ?? null }}</td>
                                            <td>${{ $technicians->jobserviceinfo->sub_total ?? null }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $technicians->job_code ?? null }} -
                                                {{ $technicians->jobproductinfo->product_name ?? null }}
                                                {{-- <small class="text-muted">LG Washing Machine Stand</small> --}}
                                            </td>
                                            <td>${{ $technicians->jobproductinfo->base_price ?? null }}</td>
                                            <td>${{ $technicians->jobproductinfo->discount ?? null }}</td>
                                            <td>${{ $technicians->jobproductinfo->tax ?? null }}</td>
                                            <td>${{ $technicians->jobproductinfo->sub_total ?? null }}</td>
                                        </tr>
                                        <tr>
                                            <th class="border-bottom border-top">&nbsp;</th>
                                            <th class="border-bottom border-top">&nbsp;</th>
                                            <th class="border-bottom border-top">${{ $technicians->discount ?? null }}
                                            </th>
                                            <th class="border-bottom border-top">${{ $technicians->tax ?? null }}</th>
                                            <th class="border-bottom border-top">${{ $technicians->gross_total ?? null }}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body ">
                            <div class="row open_items">
                                <div class="col-md-10">
                                    <h4><i class="fas fa-sticky-note px-1"></i> Job Note</h4>
                                </div>
                                <div class="col-md-2 text-center addnotes" style="cursor: pointer;">
                                    <i class="fas fa-plus "></i>
                                </div>
                            </div>
                            <div class="row mt-2">
                                @foreach ($techniciansnotes as $item)
                                    <ul class="list-unstyled mt-3">
                                        <li class="d-flex align-items-start">
                                            @isset($item->user_image)
                                                <img class="me-3 rounded"
                                                    src="{{ asset('public/images/admin/' . $item->user_image) ?? null }}"
                                                    width="60" alt="image" />
                                            @else
                                                <img class="me-3 rounded" src="{{ $defaultImage }}" width="60"
                                                    alt="image" />
                                            @endisset
                                            <div class="media-body">
                                                <h5 class="mt-0 mb-1">{{ $item->name ?? 'Unknown' }}</h5>
                                                {!! $item->note ?? null !!}
                                            </div>
                                        </li>
                                    </ul>
                                @endforeach
                            </div>

                            <div class="shownotes" style="display: none;">
                                <h4 class="mb-3">Add a Note</h4>


                                <form class="row g-2" method="post" action="{{ route('techniciannote') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @if (session('success'))
                                        <div id="successMessage" class="alert alert-success" role="alert">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <input type="hidden" name="id" value={{ $technicians->id }}>
                                    <input type="hidden" name="technician_id" value={{ $technicians->technician_id }}>
                                    <textarea id="mymce" name="note"></textarea>

                                    <div class="col-md-2">
                                        <button type="submit" id="submitBtn"
                                            class="mt-3 btn waves-effect waves-light btn-success">
                                            Send
                                        </button>
                                    </div>


                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body ">
                            <div class="row mb-3 open_items">
                                <div class="col-md-7">
                                    <h4><i class="fas fas fa-dollar-sign px-1"></i> Invoice</h4>
                                </div>
                                <div class="col-md-5 text-center">
                                    <button type="button" class="btn waves-effect waves-light btn-primary">View &
                                        Send Invoice</button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <i class="fas fa-pencil-alt px-1"></i> By accessing, viewing and/or using this
                                    site, you, the user...
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card" style="border: 1px solid #D8D8D8;">
                        <div class="card-body">
                            <h4 class="card-title">ACTIVITY FEED</h4>

                            <div class="table-responsive">
                                <table class="table customize-table mb-0 v-middle">
                                    <tbody>
                                        <tr>
                                            <td style="width:20%">
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/images/users/1.jpg" class="rounded-circle"
                                                        width="40">
                                                    <span class="ms-2 fw-normal">John Smith</span>
                                                </div>
                                            </td>
                                            <td style="width:60%">Line items updated total = $0.00</td>
                                            <td style="width:20%">Wed 3/13/24 3:09pm</td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%">
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/images/users/1.jpg" class="rounded-circle"
                                                        width="40">
                                                    <span class="ms-2 fw-normal">John Smith</span>
                                                </div>
                                            </td>
                                            <td style="width:60%">Job scheduled for Thu, Mar 14 at 10:00am</td>
                                            <td style="width:20%">Wed 3/13/24 2:30pm</td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%">
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/images/users/1.jpg" class="rounded-circle"
                                                        width="40">
                                                    <span class="ms-2 fw-normal">John Smith</span>
                                                </div>
                                            </td>
                                            <td style="width:60%">Job scheduled for Thu, Mar 14 at 11:00am</td>
                                            <td style="width:20%">Wed 3/13/24 1:11pm</td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%">
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/images/users/1.jpg" class="rounded-circle"
                                                        width="40">
                                                    <span class="ms-2 fw-normal">John Smith</span>
                                                </div>
                                            </td>
                                            <td style="width:60%">Job scheduled for Thu, Mar 14 at 11:00am</td>
                                            <td style="width:20%">Wed 3/13/24 1:10pm</td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%">
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/images/users/1.jpg" class="rounded-circle"
                                                        width="40">
                                                    <span class="ms-2 fw-normal">John Smith</span>
                                                </div>
                                            </td>
                                            <td style="width:60%">Dispatched to MARK SIERRA</td>
                                            <td style="width:20%">Wed 3/13/24 1:10pm</td>
                                        </tr>
                                        <tr>
                                            <td style="width:20%">
                                                <div class="d-flex align-items-center">
                                                    <img src="../../assets/images/users/1.jpg" class="rounded-circle"
                                                        width="40">
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

            </div>

        </div>

    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- End Container fluid  -->


@section('script')
    <!-- This page JavaScript -->
    <!-- --------------------------------------------------------------- -->
    <script src="https://gaffis.in/frontier/website/public/admin/dist/libs/tinymce/tinymce.min.js"></script>
    <!--c3 charts -->
    <script src="https://gaffis.in/frontier/website/public/admin/dist/libs/c3/htdocs/js/d3-3.5.6.js"></script>


    <script src="https://gaffis.in/frontier/website/public/admin/dist/libs/c3/htdocs/js/c3-0.4.9.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#manufacturer_ids').select2();


            $('.addnotes').click(function() {
                $('.shownotes').toggle('fade', function() {
                    if ($(this).is(':visible')) { // Check if the element is visible after toggle
                        $('html, body').animate({
                            scrollTop: $(this).offset()
                                .top // Scroll to the top position of the element
                        }, 'fast');
                    }
                });
            });
            $('.addCustomerTags').click(function() {
                $('.showCustomerTags').toggle('fade');

            });
            $('.addJobTags').click(function() {
                $('.showJobTags').toggle('fade');

            });
            $('.addAttachment').click(function() {
                $('.showAttachment').toggle('fade');

            });
            $('.addSource').click(function() {
                $('.showSource').toggle('fade');

            });
        });
    </script>

    <script>
        $(function() {
            tinymce.init({
                selector: 'textarea#mymce'
            });
            $('#submitBtn').click(function() {
                // Check if the TinyMCE textarea is empty
                if (tinymce.activeEditor.getContent().trim() === '') {
                    // If textarea is empty, prevent form submission
                    alert('Please enter a Job note.');
                    return false;
                }
            });
            // ==============================================================
            // Our Visitor
            // ==============================================================

            var chart = c3.generate({
                bindto: '#visitor',
                data: {
                    columns: [
                        ['Open', 4],
                        ['Closed', 2],
                        ['In progress', 2],
                        ['Other', 0],
                    ],

                    type: 'donut',
                    tooltip: {
                        show: true,
                    },
                },
                donut: {
                    label: {
                        show: false,
                    },
                    title: 'Tickets',
                    width: 35,
                },

                legend: {
                    hide: true,
                    //or hide: 'data1'
                    //or hide: ['data1', 'data2']
                },
                color: {
                    pattern: ['#40c4ff', '#2961ff', '#ff821c', '#7e74fb'],
                },
            });
        });
    </script>
    <script>
        // Get latitude and longitude values from your data or variables
        var latitude = {{ $technicians->latitude ?? null }}; // Example latitude
        var longitude = {{ $technicians->longitude ?? null }}; // Example longitude

        // Construct the URL with the latitude and longitude values
        var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' +
            latitude + ',' + longitude + '&zoom=13';

        document.getElementById('map').src = mapUrl;
    </script>
    <script>
        setTimeout(function() {
            $('#successMessage').fadeOut('fast');
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
    <script>
        // Get latitude and longitude values from your data or variables
        var latitude = {{ $technicians->latitude ?? null }}; // Example latitude
        var longitude = {{ $technicians->longitude ?? null }}; // Example longitude

        // Construct the URL with the latitude and longitude values
        var mapUrl = 'https://www.google.com/maps/embed/v1/view?key=AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo&center=' +
            latitude + ',' + longitude + '&zoom=18';

        document.getElementById('map238').src = mapUrl;
    </script>
@endsection
@endsection
