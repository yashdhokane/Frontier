@extends('home')

@section('content')

    <div class="container-fluid">
        <!-- -------------------------------------------------------------- -->
        <!-- Start Page Content -->
        <!-- -------------------------------------------------------------- -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between pb-2">
                    <h4 class="mb-3 page-title text-info fw-bold">
                        <a href="#" class="create-layout" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="fa fa-plus-circle me-1 text-success"></i>
                        </a>
                        {{ $layout->layout_name ?? null }}
                        @if ($layout->added_by == auth()->user()->id)
                            <a href="#" class="edit-layout" data-bs-toggle="modal" data-bs-target="#editModal">
                                <i class="fa fa-edit align-top fs-1 text-danger"></i>
                            </a>
                        @endif
                    </h4>

                    <!-- Create Layout Name Modal -->
                    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editForm" action="{{ route('createLayout') }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Add New Layout</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="editLayoutId" name="id" value="">
                                        <div class="mb-3">
                                            <label for="editLayoutName" class="form-label">Layout Name</label>
                                            <input type="text" class="form-control" id="editLayoutName"
                                                name="layout_name" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Edit Layout Name Modal -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editForm" action="{{ route('updateLayoutName', ['id' => $layout->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Layout Name</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="editLayoutId" name="id" value="">
                                        <div class="mb-3">
                                            <label for="editLayoutName" class="form-label">Layout Name</label>
                                            <input type="text" class="form-control" id="editLayoutName"
                                                name="layout_name" value="{{ $layout->layout_name ?? null }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex">
                        @if ($layout->added_by == auth()->user()->id)
                            <form action="{{ route('update.status') }}" method="POST" class="d-flex">
                                @csrf
                                @php
                                    // Array of titles with element_id and layout_id as keys
                                    $titles = [
                                        ['element_id' => 1, 'layout_id' => 1, 'title' => 'Upcoming Job'],
                                        ['element_id' => 2, 'layout_id' => 1, 'title' => 'Open invoices'],
                                        ['element_id' => 3, 'layout_id' => 1, 'title' => 'Paid invoices'],
                                        ['element_id' => 4, 'layout_id' => 1, 'title' => 'Stats'],
                                        ['element_id' => 5, 'layout_id' => 1, 'title' => 'Jobs by manufacturer'],
                                        ['element_id' => 6, 'layout_id' => 1, 'title' => 'Jobs by Service Types'],
                                        ['element_id' => 7, 'layout_id' => 1, 'title' => 'Quick Links'],
                                        ['element_id' => 8, 'layout_id' => 1, 'title' => 'MY ACTIVITY'],
                                        ['element_id' => 9, 'layout_id' => 1, 'title' => 'MY NOTIFICATIONS'],
                                        ['element_id' => 10, 'layout_id' => 1, 'title' => 'Active Technicians'],
                                        ['element_id' => 11, 'layout_id' => 1, 'title' => 'Top Customers'],
                                        ['element_id' => 12, 'layout_id' => 2, 'title' => 'Title1'],
                                        ['element_id' => 13, 'layout_id' => 2, 'title' => 'Title2'],
                                        ['element_id' => 14, 'layout_id' => 2, 'title' => 'Title3'],
                                    ];
                                @endphp
                                <select name="status" class="form-select" required>
                                    @if ($variable->isEmpty())
                                        <option value="">All section already exists</option>
                                    @else
                                        <option value="">Select to add section</option>
                                        @foreach ($variable as $value)
                                            @php
                                                // Find the matching title based on element_id and layout_id
                                                $title = null;
                                                foreach ($titles as $titleItem) {
                                                    if (
                                                        $titleItem['element_id'] == $value->element_id &&
                                                        $titleItem['layout_id'] == $value->layout_id
                                                    ) {
                                                        $title = $titleItem['title'];
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            <option value="{{ $value->id }}">
                                                {{ $title ?? 'All section already exists' }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <button type="submit" class="btn btn-info mx-2">Add</button>
                            </form>

                        @endif
                        <form id="urlForm" class="d-flex" action="{{ route('dash') }}" method="GET">
                            <select id="urlSelect" name="id" class="form-select">
                                <option value="">--Select a Layout--</option>
                                @foreach ($layoutList as $value)
                                    <option value="{{ $value->id }}">{{ $value->layout_name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" id="showButton" class="btn btn-info ms-2">Show</button>
                        </form>


                    </div>
                </div>
                <form id="positionForm" method="POST" action="{{ route('savePositions') }}">
                    @csrf
                    <input type="hidden" name="positions" id="positions">
                    <div class="row draggable-cards" id="draggable-area">

                        @foreach ($cardPositions as $cardPosition)
                            {{-- first layout --}}
                            @if ($cardPosition->layout_id == 1 && $cardPosition->element_id == 1)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-info d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Upcoming Job </h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive mt-1" style="overflow-x: scroll !important;">
                                                <table id="" class="table table-bordered text-nowrap">
                                                    <thead class="uppercase">
                                                        <tr>
                                                            <th>Jobs Details</th>
                                                            <th>Customer</th>
                                                            <th>Technician</th>
                                                            <th>Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($job as $item)
                                                            <tr>
                                                                <td>
                                                                    <a href="{{ url('tickets/' . $item->JobModel->id) }}"
                                                                        class="font-medium link">{{ $item->JobModel->job_title ?? null }}</a><br />
                                                                    {{ $item->JobModel->description ?? null }}
                                                                </td>
                                                                <td>{{ $item->JobModel->user->name ?? null }}</td>
                                                                <td>{{ $item->technician->name ?? null }}</td>
                                                                <td>
                                                                    @if ($item && $item->start_date_time)
                                                                        <div class="font-medium link ft12">
                                                                            {{ $convertDateToTimezone($item->start_date_time ?? null) }}
                                                                        </div>
                                                                    @else
                                                                        <div></div>
                                                                    @endif
                                                                    <div class="ft12">
                                                                        {{ $convertTimeToTimezone($item->start_date_time ?? null, 'H:i:a') }}
                                                                        to
                                                                        {{ $convertTimeToTimezone($item->end_date_time ?? null, 'H:i:a') }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 2)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-danger d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Open invoices</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive mt-1" style="overflow-x: scroll !important;">
                                                <table id="" class="table table-bordered text-nowrap">
                                                    <thead>
                                                        <!-- start row -->
                                                        <tr>
                                                            <th>ID</th>

                                                            <th>Customer</th>
                                                            <th>Technician</th>
                                                            <th>Inv. Date</th>
                                                            <th>Due Date</th>
                                                            <th>Amount</th>

                                                        </tr>
                                                        <!-- end row -->
                                                    </thead>
                                                    <tbody>
                                                        <!-- start row -->
                                                        @foreach ($paymentclose as $index => $item)
                                                            <tr>
                                                                <td><a
                                                                        href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number ?? null }}</a>
                                                                </td>

                                                                <td>{{ $item->user->name ?? null }}</td>
                                                                <td>{{ $item->JobModel->technician->name ?? null }}</td>
                                                                <td>{{ $convertDateToTimezone($item->issue_date ?? null) }}
                                                                </td>
                                                                <td>{{ $item->due_date ?? null }}</td>
                                                                <td>${{ $item->total ?? null }}</td>


                                                            </tr>
                                                            <!-- Modal for adding comment -->
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 3)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-success d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Paid invoices</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive mt-1" style="overflow-x: scroll !important;">
                                                <table id="" class="table table-bordered text-nowrap">
                                                    <thead>
                                                        <!-- start row -->
                                                        <tr>
                                                            <th>ID</th>

                                                            <th>Customer</th>
                                                            <th>Technician</th>
                                                            <th>Inv. Date</th>
                                                            <th>Due Date</th>
                                                            <th>Amount</th>

                                                        </tr>
                                                        <!-- end row -->
                                                    </thead>
                                                    <tbody>
                                                        <!-- start row -->
                                                        @foreach ($paymentopen as $index => $item)
                                                            <tr>
                                                                <td><a
                                                                        href="{{ url('invoice-detail/' . $item->id) }}">{{ $item->invoice_number ?? null }}</a>
                                                                </td>

                                                                <td>{{ $item->user->name ?? null }}</td>
                                                                <td>{{ $item->JobModel->technician->name ?? null }}</td>
                                                                <td>{{ $convertDateToTimezone($item->issue_date ?? null) }}
                                                                </td>
                                                                <td>{{ $item->due_date ?? null }}</td>
                                                                <td>${{ $item->total ?? null }}</td>


                                                            </tr>
                                                            <!-- Modal for adding comment -->
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 4)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Stats</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <div class="text-white bg-primary rounded">
                                                        <div class="card-body">
                                                            <span><i class="ri-group-line"
                                                                    style="font-size: 36px;"></i></span>
                                                            <h3 class="card-title mt-1 mb-0 text-white">
                                                                {{ $customerCount ?? null }}</h3>
                                                            <p class="card-text text-white-50 fs-3 fw-normal">Customers</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <div class="text-white bg-warning rounded">
                                                        <div class="card-body">
                                                            <span><i class="ri-contacts-line"
                                                                    style="font-size: 36px;"></i></span>
                                                            <h3 class="card-title mt-1 mb-0 text-white">
                                                                {{ $technicianCount ?? null }}</h3>
                                                            <p class="card-text text-white-50 fs-3 fw-normal">Technicians
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="text-white bg-success rounded">
                                                        <div class="card-body">
                                                            <span><i class="ri-admin-line"
                                                                    style="font-size: 36px;"></i></span>
                                                            <h3 class="card-title mt-1 mb-0 text-white">
                                                                {{ $dispatcherCount ?? null }}</h3>
                                                            <p class="card-text text-white-50 fs-3 fw-normal">Dispatchers
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-white bg-danger rounded">
                                                        <div class="card-body">
                                                            <span><i class="ri-admin-fill"
                                                                    style="font-size: 36px;"></i></span>
                                                            <h3 class="card-title mt-1 mb-0 text-white">
                                                                {{ $adminCount ?? null }}</h3>
                                                            <p class="card-text text-white-50 fs-3 fw-normal">Admin</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 5)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-primary d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Jobs by manufacturer</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div id="chart-pie-simple"></div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 6)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-info d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Jobs by Service Types</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div id="chart-pie-donut"></div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 7)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Quick Links</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><i
                                                        class="ri-file-list-line feather-sm me-2"></i> <a
                                                        href="{{ route('download') }}">Download App </a></li>
                                                <li class="list-group-item"><i
                                                        class="ri-file-list-line feather-sm me-2"></i> <a
                                                        href="#.">View
                                                        Website </a></li>
                                                <li class="list-group-item"><i
                                                        class="ri-file-list-line feather-sm me-2"></i> <a
                                                        href="{{ route('contact') }}">Contact Support </a></li>
                                                <li class="list-group-item"><i
                                                        class="ri-file-list-line feather-sm me-2"></i> <a
                                                        href="{{ route('about') }}">About Dispat Channel</a></li>
                                                <li class="list-group-item"><i
                                                        class="ri-file-list-line feather-sm me-2"></i> <a
                                                        href="{{ route('reviews') }}">Reviews</a></li>
                                                <li class="list-group-item"><i
                                                        class="ri-file-list-line feather-sm me-2"></i> <a
                                                        href="{{ route('privacy') }}">Privacy Policy </a></li>
                                                <li class="list-group-item"><i
                                                        class="ri-file-list-line feather-sm me-2"></i> <a
                                                        href="{{ route('documentation') }}">Documentation </a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 8)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">MY ACTIVITY</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table customize-table mb-0 v-middle">
                                                    <tbody>
                                                        @foreach ($activity as $record)
                                                            <tr>
                                                                <td>
                                                                    <div>{{ $record->activity }}</div>
                                                                    <div class="text-muted">
                                                                        {{ \Carbon\Carbon::parse($record->created_at)->format('D
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    n/j/y
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    g:ia') ??
                                                                            'null' }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <a class="nav-link text-dark" href="{{ route('myprofile.activity') }}">
                                                <strong>View All</strong>
                                                <i data-feather="chevron-right" class="feather-sm"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 9)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">MY NOTIFICATIONS</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table customize-table mb-0 v-middle">

                                                    <tbody>
                                                        @foreach ($userNotifications as $record)
                                                            <tr>
                                                                <td
                                                                    @if ($record->is_read == 0) class="text-muted" @endif>
                                                                    <div class="fw-normal">
                                                                        {{ $record->notice->notice_title ?? '' }}
                                                                    </div>
                                                                    <div class="text-muted">
                                                                        {{ \Carbon\Carbon::parse($record->notice->notice_date)->format('D
                                                                                                                                                                                                                                                                                                                                                                                                                            n/j/y g:ia') ??
                                                                            'null' }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <a class="nav-link text-dark" href="{{ route('myprofile.activity') }}">
                                                <strong>View All </strong>
                                                <i data-feather="chevron-right" class="feather-sm"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 10)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Active Technicians</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach ($technicianuser as $item)
                                                    <div class="col-lg-4">
                                                        <div class="card card-border shadow">
                                                            <div class="card-body">
                                                                <h5 class="card-title ft13 uppercase text-primary">
                                                                    {{ $item->name ?? null }}</h5>
                                                                <h6 class="ft11 mb-2 d-flex align-items-center">
                                                                    <i class="fas fa-map-marker-alt"
                                                                        style="margin-right: 5px;"></i>
                                                                    @if (isset($item->area_name) && !empty($item->area_name))
                                                                        {{ $item->area_name ?? null }}
                                                                    @endif
                                                                </h6>
                                                                <p class="card-text pt-2 ft12">
                                                                    {{ $item->completed_jobs_count }}/{{ $item->total_jobs_count }}
                                                                    Job Completed<br />
                                                                    Completion Rate:
                                                                    {{ number_format($item->completion_rate, 2) }}%
                                                                </p>
                                                                <a href="{{ route('technicians.show', ['id' => $item->id]) }}"
                                                                    class="card-link">View
                                                                    Profile</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 1 && $cardPosition->element_id == 11)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Top Customers</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach ($customeruser as $user)
                                                    <div class="col-lg-4">
                                                        <div class="card card-border shadow">
                                                            <div class="card-body">
                                                                <h5 class="card-title ft13 uppercase text-primary">
                                                                    {{ $user->name ?? null }}</h5>
                                                                @foreach ($user->user_addresses as $address)
                                                                    <h6 class="ft11 mb-2 d-flex align-items-center">
                                                                        <i class="fas fa-map-marker-alt"
                                                                            style="margin-right: 5px;"></i>
                                                                        {{ $address->address_line1 ?? null }},
                                                                        {{ $address->city ?? null }},
                                                                        {{ $address->state_name ?? null }},
                                                                        {{ $address->zipcode ?? null }}
                                                                    </h6>
                                                                @endforeach
                                                                <p class="card-text pt-2 ft12">
                                                                    {{ count($user->jobs) }} Jobs<br />
                                                                    LifetimeValue: ${{ $user->gross_total ?? 0 }}</p>
                                                                <a href="{{ route('users.show', ['id' => $user->id]) }}"
                                                                    class="card-link">View Profile</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- end first layout --}}
                            @elseif($cardPosition->layout_id == 2 && $cardPosition->element_id == 12)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-danger d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 8</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 8</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 8</a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 2 && $cardPosition->element_id == 13)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-warning d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 9</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 9</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 9</a>
                                        </div>
                                    </div>
                                </div>
                            @elseif($cardPosition->layout_id == 2 && $cardPosition->element_id == 14)
                                <div class="col-md-6 col-sm-12" data-id="{{ $cardPosition->element_id }}">
                                    <div class="card card-hover">
                                        <div class="card-header bg-info d-flex justify-content-between">
                                            <h4 class="mb-0 text-white">Card Title 10</h4>
                                            @if ($layout->added_by == auth()->user()->id)
                                                <button class="btn btn-light mx-2 clearSection"
                                                    data-element-id="{{ $cardPosition->element_id }}">X</button>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h3 class="card-title">Special title treatment 10</h3>
                                            <p class="card-text">
                                                With supporting text below as a natural lead-in to additional content.
                                            </p>
                                            <a href="javascript:void(0)" class="btn btn-inverse">Go somewhere 10</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                    @if ($layout->added_by == auth()->user()->id)
                        <button type="submit" class="btn btn-primary mt-3">Save Positions</button>
                    @endif
                </form>
            </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- End PAge Content -->
        <!-- -------------------------------------------------------------- -->
    </div>

    <div class="chat-windows"></div>

@section('script')
    <style>
        .gu-mirror {
            opacity: 0.6;
            position: fixed;
            z-index: 9999;
            pointer-events: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('urlForm');
            const select = document.getElementById('urlSelect');

            form.addEventListener('submit', function(event) {
                event.preventDefault(); // prevent the form from submitting normally

                // Get the selected value from the dropdown
                const selectedValue = select.value;

                if (selectedValue) {
                    // Construct the URL and redirect
                    const url = `{{ route('dash') }}?id=${selectedValue}`;
                    window.location.href = url;
                }
            });
        });
    </script>
    </script>
    <script>
        $(function() {
            dragula([document.getElementById('draggable-area')])
                .on('drag', function(e) {
                    e.className = e.className.replace('card-moved', '');
                })
                .on('over', function(e, t) {
                    t.className += ' card-over';
                })
                .on('out', function(e, t) {
                    t.className = t.className.replace('card-over', '');
                });


            $('#positionForm').on('submit', function(event) {
                var positions = [];
                $('#draggable-area .col-md-6').each(function(index, element) {
                    positions.push({
                        element_id: $(element).data('id'),
                        position: index
                    });
                });
                $('#positions').val(JSON.stringify(positions));
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.clearSection').on('click', function() {
                var elementId = $(this).data('element-id');

                $.ajax({
                    url: '{{ route('changeStatus') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        element_id: elementId
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('An error occurred. Please try again.');
                    }
                });
            });
        });
    </script>
@endsection

@endsection
