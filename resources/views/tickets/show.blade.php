@extends('home')
@section('content')
    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <!-- -------------------------------------------------------------- -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- -------------------------------------------------------------- -->

    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-md-12">
                <h4 class="page-title">#{{ $technicians->id ?? null }} - {{ $technicians->job_title ?? null }}
                    @foreach ($jobFields as $jobField)
                        <span class="mb-1 badge bg-warning">{{ $jobField->field_name }}</span>
                    @endforeach
                </h4>
            </div>
            <div class="col-md-8">
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

        @if (Session::has('success'))
            <div class="alert_wrap">
                <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show">
                    {{ Session::get('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if (Session::has('error'))
            <div class="alert_wrap">
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            </div>
        @endif

        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <!-- basic table -->

        <div class="row">

            <div class="col-md-4">



                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="">
                                <h5 class="card-title uppercase mt-1 mb-2"><a class="text-dark"
                                        href="{{ url('users/show/' . $technicians->user->id) }}">{{ $technicians->user->name ?? null }}</a>
                                </h5>
                                <div>Address</div>
                                <h5 class="todo-desc mb-2 fs-3 font-weight-medium">
                                    @if (isset($technicians->address) && $technicians->address !== '')
                                        {{ $technicians->address }},
                                    @endif

                                    @if (isset($technicians->city) && $technicians->city !== '')
                                        {{ $technicians->city }},
                                    @endif

                                    @if (isset($technicians->state) && $technicians->state !== '')
                                        {{ $technicians->state }},
                                    @endif

                                    @if (isset($technicians->zipcode) && $technicians->zipcode !== '')
                                        {{ $technicians->zipcode }}
                                    @endif
                                </h5>

                                <iframe id="map238" width="100%" height="150" frameborder="0" style="border: 0"
                                    allowfullscreen=""></iframe>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow text-center">
                            <div class="profile-pic mb-3 mt-3">
                                <h5 class="card-title uppercase mt-3 mb-0">Contact Details</h5>
                                @if (!empty($technicians->user->email))
                                    <a
                                        href="mailto:{{ $technicians->user->email ?? '' }}">{{ $technicians->user->email ?? null }}</a><br>
                                @endif
                                @if (!empty($technicians->user->mobile))
                                    {{ $technicians->user->mobile ?? null }}<br />
                                @endif

                            </div>
                            <div>Address</div>
                            <div class="">
                                <iframe id="map" width="100%" height="150" frameborder="0" style="border: 0"
                                    allowfullscreen=""></iframe>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="row open_items">
                                <div class="col-md-1 ">
                                    <i class="fas fas fa-tag "></i>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title uppercase">Customer Tags</h5>
                                </div>
                                <div class="col-md-2 addCustomerTags" style="cursor: pointer;">
                                    <i class="fas fa-plus "></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        @foreach ($Sitetagnames as $item)
                                            {{ $item->tag_name ?? null }} ,
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
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="row open_items">
                                <div class="col-md-1">
                                    <i class="fas fas fa-tag "></i>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title uppercase">Job Tags</h5>
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
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="row open_items">
                                <div class="col-md-1">
                                    <i class="fas fa-paperclip"></i>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title uppercase">Attachments</h5>
                                </div>
                                <div class="col-md-1 addAttachment" style="cursor: pointer;">
                                    <i class="fas fa-plus "></i>
                                </div>
                            </div>
                            <div class="row">
                                <form action="{{ url('add/attachment/' . $technicians->id) }}" method="POST"
                                    enctype="multipart/form-data" class="showAttachment" style="display: none;">
                                    @csrf
                                    <input type="file" name="attachment" id="" class="form-control">
                                    <div class="mb-3 text-end">
                                        <button type="submit" class="btn btn-primary rounded mt-2">Add</button>
                                    </div>

                                </form>
                                <div>
                                    @foreach ($files as $item)
                                        <a href="{{ url('public/images/users/' . $item->user_id . '/' . $item->filename) }}"
                                            target="_blank"><img
                                                src="{{ url('public/images/users/' . $item->user_id . '/' . $item->filename) }}"
                                                alt="file" width="100px"
                                                onerror="this.onerror=null; this.src='{{ $defaultImage }}';"></a>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="row open_items">
                                <div class="col-md-1">
                                    <i class="fas fa-bullseye "></i>
                                </div>
                                <div class="col-md-9">
                                    <h5 class="card-title uppercase">Lead Source</h5>
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
                    <div class="card">
                        <div class="card-body card-border shadow text-center">
                            <h5 class="card-title uppercase">Technician Assigned</h5>
                            <div class="profile-pic mb-3 mt-3">
                                @isset($technicians->usertechnician->user_image)
                                    <img src="{{ asset('public/images/Uploads/users/' . $technicians->usertechnician->id . '/' . $technicians->usertechnician->user_image) ?? null }}"
                                        width="150" class="rounded-circle" alt="user"
                                        onerror="this.onerror=null; this.src='{{ $defaultImage }}';" />
                                @else
                                    <img src="{{ $defaultImage }}" width="150" class="rounded-circle" alt="user" />
                                @endisset
                                <h5 class="card-title uppercase mt-3 mb-0">
                                    {{ $technicians->usertechnician->name ?? null }}</h5>
                                <a
                                    href="mailto:{{ $technicians->usertechnician->email ?? '' }}">{{ $technicians->usertechnician->email ?? null }}</a><br><small
                                    class="text-muted">{{ $technicians->usertechnician->mobile ?? null }}<br />{{ $technicians->usertechnician->Locationareaname->area_name ?? null }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow text-center">
                            <h5 class="card-title uppercase">Ticket Creator</h5>
                            <div class="profile-pic mb-3 mt-3">
                                @isset($technicians->addedby->user_image)
                                    <img src="{{ asset('public/images/Uploads/users/' . $technicians->addedby->id . '/' . $technicians->addedby->user_image) ?? null }}"
                                        width="150" class="rounded-circle" alt="user"
                                        onerror="this.onerror=null; this.src='{{ $defaultImage }}';" />
                                @else
                                    <img src="{{ $defaultImage }}" width="150" class="rounded-circle" alt="user" />
                                @endisset
                                <h5 class="card-title mt-3 mb-0">{{ $technicians->addedby->name ?? null }}</h5>
                                <a
                                    href="mailto:{{ $technicians->addedby->email ?? '' }}">{{ $technicians->addedby->email ?? null }}</a><br><small
                                    class="text-muted">{{ $technicians->addedby->mobile ?? null }}<br>Frontier Support
                                    Staff</small>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="col-md-8">

                <div class="mb-4 flwrap">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <div class="brwrap">
                                <div class="flborder">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="flowchart">

                                        <div class="icwrap">
                                            <div class="ictop bg-info text-white">
                                                <i class="ri-calendar-event-line"></i>
                                            </div>
                                            <span class="cht">Schedule</span>
                                        </div>
                                        <div class="dtwrap">
                                            <div class="date">
                                                {{ $jobTimings['time_schedule'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="flowchart">

                                        <div class="icwrap">
                                            <div
                                                class="ictop @if ($jobTimings['time_omw'] !== null) bg-info text-white @else icblank @endif">
                                                <i class="ri-truck-line"></i>
                                            </div>
                                            <span class="cht">OMW</span>
                                        </div>
                                        <div class="dtwrap">
                                            <div class="date">
                                                {{ $jobTimings['time_omw'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="flowchart">

                                        <div class="icwrap">
                                            <div
                                                class="ictop @if ($jobTimings['time_start'] !== null) bg-info text-white @else icblank @endif">
                                                <i class="ri-play-line"></i>
                                            </div>
                                            <span class="cht">Start</span>
                                        </div>
                                        <div class="dtwrap">
                                            <div class="date">
                                                {{ $jobTimings['time_start'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="flowchart">

                                        <div class="icwrap">
                                            <div
                                                class="ictop @if ($jobTimings['time_finish'] !== null) bg-info text-white @else icblank @endif">
                                                <i class="ri-stop-circle-line"></i>
                                            </div>
                                            <span class="cht">Finish</span>
                                        </div>
                                        <div class="dtwrap">
                                            <div class="date">
                                                {{ $jobTimings['time_finish'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="flowchart">
                                        <!--<button class="bl"></button>-->
                                        <div class="icwrap">
                                            <div
                                                class="ictop @if ($jobTimings['time_invoice'] !== null) bg-info text-white @else icblank @endif">
                                                <i class="ri-bill-line"></i>
                                            </div>
                                            <span class="cht">Invoice</span>
                                        </div>
                                        <div class="dtwrap">
                                            <div class="date">
                                                {{ $jobTimings['time_invoice'] }}

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="flowchart">
                                        <!--<button class="bl"></button>-->
                                        <div class="icwrap">
                                            <div
                                                class="ictop @if ($jobTimings['time_payment'] !== null) bg-info text-white @else icblank @endif">
                                                <i class="ri-currency-line"></i>
                                            </div>
                                            <span class="cht">Pay</span>
                                        </div>
                                        <div class="dtwrap">
                                            <div class="date">
                                                {{ $jobTimings['time_payment'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">

                            <div class="row">
                                <div class="col-md-7">
                                    <div class="mb-2">
                                        <h5 class="card-title uppercase">#{{ $technicians->id ?? null }} -
                                            {{ $technicians->job_title ?? null }} <span
                                                class="mb-1 badge bg-warning">{{ $technicians->status ?? null }} </span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-2">
                                        <h5 class="card-title uppercase"><i class="fa fa-calendar"
                                                aria-hidden="true"></i>
                                            {{ \Carbon\Carbon::parse($technicians->jobassignname->start_date_time ?? null)->format('jS F Y, h:i A') }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <p>{{ $technicians->description ?? null }}</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <strong>Duration:</strong>
                                        @if ($technicians->jobassignname->duration ?? null)
                                            <?php
                                            $durationInMinutes = $technicians->jobassignname->duration ?? null;
                                            $durationInHours = $durationInMinutes / 60; // Convert minutes to hours
                                            ?>
                                            {{ $durationInHours ?? null }} Hours
                                        @endif
                                    </div>
                                    <div class="mb-2"><strong>Priority:</strong> {{ $technicians->priority ?? null }}
                                    </div>
                                    <div class="mb-2"><strong>Date:
                                        </strong>{{ \Carbon\Carbon::parse($technicians->jobassignname->start_date_time ?? null)->format('jS F Y') }}
                                    </div>
                                    <div class="mb-2"><strong>From:
                                        </strong>{{ $convertTimeToTimezone($technicians->JobAssign->start_date_time ?? null, 'H:i:a') }}
                                    </div>
                                    <div class="mb-2"><strong>To:
                                        </strong>{{ $convertTimeToTimezone($technicians->JobAssign->end_date_time ?? null, 'H:i:a') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2"><strong>Appliances: </strong>
                                        {{ $technicians->JobAppliances->Appliances->appliance->appliance_name ?? null }}
                                    </div>
                                    <div class="mb-2"><strong>Manufacturer:</strong>
                                        {{ $technicians->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}
                                    </div>
                                    <div class="mb-2"><strong>Model Number:
                                        </strong>{{ $technicians->JobAppliances->Appliances->model_number ?? null }}</div>
                                    <div class="mb-2"><strong>Serial Number: </strong>
                                        {{ $technicians->JobAppliances->Appliances->serial_number ?? null }} </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <h5 class="card-title uppercase">Field Tech Status</h5>
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
                                                    @isset($technicians->usertechnician->user_image)
                                                        <img src="{{ asset('public/images/Uploads/users/' . $technicians->usertechnician->id . '/' . $technicians->usertechnician->user_image) }}"
                                                            class="rounded-circle" width="40"
                                                            onerror="this.onerror=null; this.src='{{ $defaultImage }}';">
                                                    @else
                                                        <img src="{{ $defaultImage }}" class="rounded-circle"
                                                            width="40">
                                                    @endisset
                                                    <span
                                                        class="ms-3 fw-normal">{{ $technicians->usertechnician->name ?? null }}</span>
                                                </div>
                                            </td>
                                            <td><span
                                                    class="badge bg-light-success text-success fw-normal">{{ $technicians->usertechnician->status ?? null }}</span>
                                            </td>
                                            <td>&nbsp;{{ $technicians->JobAssign->driving_hours ?? null }} minutes</td>
                                            <td>&nbsp;{{ number_format((($technicians->JobAssign->driving_hours ?? 0) + ($technicians->JobAssign->duration ?? 0)) / 60, 2) }}
                                                hours</td>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <h5 class="card-title uppercase">Services & Parts (Line Items)</h5>
                            <div class="table-responsive">
                                <table class="table customize-table mb-0 v-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-bottom border-top">Line Item</th>
                                            <th class="border-bottom border-top">Unit Price</th>
                                            <th class="border-bottom border-top">Discount</th>
                                            <th class="border-bottom border-top">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($jobproduct as $product)
                                            <tr>
                                                <td>{{ $product->product->product_name ?? null }}</td>
                                                <td>${{ $product->base_price ?? null }}</td>
                                                <td>${{ $product->discount ?? null }}</td>
                                                <td>${{ $product->sub_total ?? null }}</td>
                                            </tr>
                                        @endforeach

                                        @foreach ($jobservice as $service)
                                            <tr>
                                                <td>{{ $service->service->service_name ?? null }} </td>
                                                <td>${{ $service->base_price ?? null }}</td>
                                                <td>${{ $service->discount ?? null }}</td>
                                                <td>${{ $service->sub_total ?? null }}</td>
                                            </tr>
                                        @endforeach



                                    </tbody>
                                </table>
                                <div class="row mb-2 justify-content-end" style="border-top: 1px solid #343434;">
                                    <div class="col-md-5 mt-2 text-right" style="text-align: right;padding-right: 36px;">

                                        <div class="price_h5">Subtotal: <span>${{ $technicians->subtotal ?? null }}</span>
                                        </div>
                                        <div class="price_h5">Discount: <span>${{ $technicians->discount ?? null }}</span>
                                        </div>
                                        <div class="price_h5">Tax ({{ $technicians->tax_details ?? null }}):
                                            <span>${{ $technicians->tax ?? null }}</span>
                                        </div>
                                        <div class="price_h5">Total: <span>${{ $technicians->gross_total ?? null }}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="card">
                            <div class="card-body card-border shadow">
                                <div class="row open_items">
                                    <div class="col-md-10">
                                        <h5 class="card-title uppercase"><i class="fas fa-sticky-note px-1"></i> Job Note
                                        </h5>
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
                                                        src="{{ asset('public/images/Uploads/users/' . $item->added_by . '/' . $item->user_image) ?? null }}"
                                                        width="60" alt="image"
                                                        onerror="this.onerror=null; this.src='{{ $defaultImage }}';" />
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
                                    <h5 class="card-title uppercase mb-3">Add a Note</h5>


                                    <form class="row g-2" method="post" action="{{ route('techniciannote') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @if (session('success'))
                                            <div id="successMessage" class="alert alert-success" role="alert">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        <input type="hidden" name="id" value={{ $technicians->id }}>
                                        <input type="hidden" name="technician_id"
                                            value={{ $technicians->technician_id }}>
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
                        <div class="card">
                            <div class="card-body card-border shadow">
                                <div class="row mb-3 open_items">
                                    <div class="col-md-7">
                                        <h5 class="card-title uppercase"><i class="fas fas fa-dollar-sign px-1"></i>
                                            Payment & Invoice</h5>
                                    </div>
                                    @if ($technicians->invoice_status == 'created')
                                        <div class="col-md-5 text-center">
                                            @php
                                                $payment = \App\Models\Payment::where(
                                                    'job_id',
                                                    $technicians->id,
                                                )->first();
                                            @endphp
                                            @if ($payment)
                                                <a href="{{ route('invoicedetail', ['id' => $payment->id]) }}"
                                                    class="btn waves-effect waves-light btn-primary">View & Send
                                                    Invoice</a>
                                            @endif
                                        </div>
                                    @else
                                        <div class="col-md-5 text-center">
                                            <form action="{{ route('create.payment.invoice') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="job_id" value="{{ $technicians->id }}">
                                                <button type="submit"
                                                    class="btn waves-effect waves-light btn-primary">View & Send
                                                    Invoice</button>
                                            </form>
                                        </div>
                                    @endif

                                </div>
                                <div class="row mb-3">
                                    @if ($technicians->invoice_status == 'created' || $technicians->invoice_status == 'complete')
                                        <div class="col-md-12">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Invoice Number</th>
                                                        <th>Total Payment</th>
                                                        <th>Status</th>
                                                        <th>Due Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $Payment->invoice_number ?? null }}</td>
                                                        <td>${{ $Payment->total ?? null }}</td>
                                                        <td>{{ $Payment->status ?? null }}</td>
                                                        <td>{{ $convertDateToTimezone($Payment->due_date ?? null) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif



                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="card">
                        <div class="card-body card-border shadow">
                            <h5 class="card-title uppercase">ACTIVITY FEED</h5>

                            <div class="table-responsive">
                                <table class="table customize-table mb-0 v-middle">
                                    <tbody>
                                        @foreach ($activity as $item)
                                            <tr>
                                                <td style="width:20%">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ url('public/images/Uploads/users/' . $item->user_id . '/' . $item->user->user_image) }}"
                                                            class="rounded-circle" width="40"
                                                            onerror="this.onerror=null; this.src='{{ $defaultImage }}';"
                                                            alt="Image">
                                                        <span
                                                            class="ms-2 fw-normal">{{ $item->user->name ?? null }}</span>
                                                    </div>
                                                </td>
                                                <td style="width:60%">{{ $item->activity ?? null }}</td>
                                                <td style="width:20%">
                                                    {{ $item->created_at ? $item->created_at->format('D n/j/y g:ia') : null }}


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
    <!-- -------------------------------------------------------------- -->
    <!-- End Container fluid  -->

    @include('tickets.scriptShow')
@endsection
