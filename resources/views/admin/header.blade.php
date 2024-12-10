@php
    // Set to 'off' to hide the sidebar
    $header = null; // Set to 'off' to hide the header
@endphp

@auth

    @if (auth()->user()->role == 'dispatcher')
    @elseif(auth()->user()->role == 'admin')

    @elseif(auth()->user()->role == 'superadmin')
    @else
    @endif
@endauth

<style>
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        border: 2px solid #6c757d;
        margin-top: 4px;
    }

    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }

    .bt-switch input[type="checkbox"] {
        transform: scale(0.8);
        margin: 0;
    }

    .modalbodyclass {
        padding: 1.5rem !important;
        background-color: #f8f9fa;
    }

    .border-btm {
        border-bottom: 1px solid #D8D8D8;
        /* Add a 1px solid bottom border */
    }
</style>
<style>
    .form-check-input {
        padding: 0px !important
    }

    ;
</style>
<link href="{{ asset('public/admin/dist/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') }}"
    rel="stylesheet">

<!-- Default Sidebar for other roles -->
@if (request('header') == 'off')
    <!-- Do not display the header -->
@elseif ($header == 'off')
    <!-- Do not display the header -->
@else
    <header class="topbar">
        <link rel="stylesheet" href="{{ url('public/admin/dashboard/style.css') }}">

        <nav class="navbar top-navbar navbar-expand-md navbar-dark">

            <div class="navbar-header">
                @include('admin.nav-logo')
            </div>

            <div class="navbar-collapse collapse" id="navbarSupportedContent">

                <ul class="navbar-nav me-auto">
                    @if ($prefix != 'inbox')
                        <li class="nav-item d-none d-md-block">
                            <a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                                data-sidebartype="mini-sidebar"><i data-feather="menu" class="feather-sm"></i></a>
                        </li>
                    @endif
                    <li class="toplinks"><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>

                    <li @if (request()->routeIs('schedule')) class="toplinks selected" @else class="toplinks" @endif
                        class="toplinks"><a href="{{ route('schedule') }}"><i class="fas fa-calendar-check"></i>
                            Schedule</a></li>
                    <!-- <li class="toplinks selected" class="toplinks"><a href="https://dispatchannel.com/portal/schedule_new"><i class="fas fa-calendar-check"></i>
                        // Schedule New</a></li> -->

                    <li class="toplinks"><a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Customer</a>
                    </li>

                    <!-- mega menu -->
                    <li class="nav-item dropdown mega-dropdown">
                        @include('admin.nav-mega-menu')
                    </li>
                    <!-- End mega menu -->

                    <!-- create new -->
                    <li class="nav-item dropdown">
                        @include('admin.nav-create-new')
                    </li>
                    <!-- End create new -->

                    <!-- SEARCH -->
                    <li class="nav-item search-box">
                        @include('admin.nav-search')
                    </li>
                    <!-- END SEARCH -->

                </ul>

                <!-- Right side toggle and nav items -->
                <ul class="navbar-nav">
                    @php
                        $currentFormattedDate = \Carbon\Carbon::now($timezoneName)->format('D d, M\' y');
                        $currentFormattedDateTime = \Carbon\Carbon::now($timezoneName)->format('h:i:s A T');
                    @endphp

                    <li class="nav-item dropdown align-self-center px-2">
                        <div class="nav-clock"><span>{{ $currentFormattedDate }}</span><br />
                            <span id="liveTime"></span>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark" id="showStickyNote"><i
                                class="fas fa-sticky-note ft20"></i> </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark" id="showJobList"><i
                                class="fas fa-calendar-check ft20"></i> </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="{{ route('map') }}"><i
                                class="fas fa-map-marker-alt ft20"></i> </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark"
                            href="{{ route('buisnessprofile.index') }}"><i class="far fa-sun ft20"></i></a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark"
                            href="https://dispatchannel.com/portal/reports/jobs"><i class="fas fa-chart-line ft20"></i>
                        </a>
                    </li>

                    <!-- NOTIFICATION -->
                    <li class="nav-item dropdown">
                        @include('admin.nav-notification')
                    </li>
                    <!-- END NOTIFICATION -->

                    <!-- MESSAGES -->
                    <li class="nav-item dropdown">
                        @include('admin.nav-messages')
                    </li>
                    <!-- END MESSAGES -->


                    <!-- USER PROFILE AND SEARCH -->
                    <li class="nav-item dropdown">
                        @include('admin.nav-user-profile')
                    </li>
                    <!-- END USER PROFILE AND SEARCH -->

                    <link rel="stylesheet"
                        href="{{ asset('public/admin/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">

                </ul>

            </div>

        </nav>


        <div class="stickyMainSection" style="display: none;">
            <a href="#." class="close-task-detail in" id="close-task-detail" style="display: ;">
                X
            </a>
            <div class="sticky-note bg-white w-100 h-100">

                <div class="row m-2 stickyNotesList">
                    <div class="col-sm-12 col-md-12">
                        <h3 class="p-3 mt-2"> Notes </h3>
                    </div>
                    <div class="col-sm-12 col-md-12"><button type="button"
                            class="btn btn-primary ms-3 addStickyNoteBtn">
                            <i class="fa fa-plus"></i> Add Note</button></div>

                    @php
                        $stickyNote = \App\Models\StickyNotes::all();
                    @endphp

                    <div class="col-sm-9 col-md-9">
                        <div class="row sticknoteslist">
                            @foreach ($stickyNote as $item)
                                <div class="col-sm-4 col-md-4 my-3">
                                    <div class="card border rounded p-3 h-100 justify-content-between">
                                        <div class="row d-flex justify-content-between">
                                            <div class="col-9"> {{ $item->note }} </div>
                                            <div class="col-2 btn-group ms-2">
                                                <div class="text-primary fw-bold fs-7 actionBtnNote"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    ...
                                                </div>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item editStckyNoteBtn"
                                                        data-note-id="{{ $item->note_id }}"><i data-feather="edit"
                                                            class="feather-sm me-2"></i> Edit</a>
                                                    <input type="hidden" class="edit_note_id"
                                                        value="{{ $item->note_id }}">
                                                    <a class="dropdown-item deleteStckyNoteBtn"
                                                        data-note-id="{{ $item->note_id }}"><i data-feather="trash"
                                                            class="feather-sm me-2"></i> Delete</a>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <div> {{ \Carbon\Carbon::parse($item->updated_at)->format('Y-m-d h:i A') }}
                                            </div>
                                            <div> <i class="fa fa-circle"
                                                    style="color:{{ $item->color_code }} ;"></i>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-sm-3 col-md-3"> </div>

                </div>

                <div class="row m-2 addStickyNote" style="display: none;">
                    <div class="col-sm-12 col-md-12">
                        <h3 class="p-1 mt-2">Add Notes </h3>
                        <hr>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <h4>Note Details</h4>
                    </div>

                    @php
                        $color = \App\Models\ColorCode::all();
                    @endphp

                    <div class="col-sm-9 col-md-9">
                        <form id="colorNoteForm" method="post" class="form-horizontal form-material"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-md-12 mb-4">
                                        <label for="" class="my-2"> Color Code </label>
                                        <select name="color_code" class="form-control" id="">
                                            @foreach ($color as $value)
                                                <option value="{{ $value->color_code }}"
                                                    style="background: {{ $value->color_code }};">
                                                    {{ $value->color_code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="">
                                            <label for="" class="my-2">Note </label>
                                            <textarea name="note" class="form-control"></textarea>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-info " data-bs-dismiss="modal">
                                    Save
                                </button>
                                <button type="button" class="btn btn-info waves-effect closeStickyAdd"
                                    data-bs-dismiss="modal">
                                    Cancel
                                </button>

                            </div>
                        </form>
                    </div>
                    <div class="col-sm-3 col-md-3"> </div>

                </div>

                <div class="row m-2 editStickyNote" style="display: none;">
                    <div class="col-sm-12 col-md-12">
                        <h3 class="p-1 mt-2">Edit Note </h3>
                        <hr>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <h4>Note Details</h4>
                    </div>


                    <div class="col-sm-9 col-md-9">
                        <form id="editNoteForm" method="post" class="form-horizontal form-material"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="note_id" id="edit_note_id2" value="">
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-md-12 mb-4">
                                        <label for="" class="my-2"> Color Code </label>
                                        <select name="color_code" class="form-control" id="edit_color_code">
                                            @foreach ($color as $value)
                                                <option value="{{ $value->color_code }}"
                                                    style="background: {{ $value->color_code }};">
                                                    {{ $value->color_code }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="">
                                            <label for="" class="my-2">Note </label>
                                            <textarea name="note" class="form-control" id="edit_note"></textarea>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-info ">
                                    Update
                                </button>
                                <button type="button" class="btn btn-info waves-effect closeStickyAdd">
                                    Cancel
                                </button>

                            </div>
                        </form>
                    </div>
                    <div class="col-sm-3 col-md-3"> </div>

                </div>


            </div>

        </div>


        @php
            $timezone_name = Session::get('timezone_name', 'UTC');
            $time_interval = Session::get('time_interval', 0);
            $currentDate = \Carbon\Carbon::now($timezone_name);
            $schedules = \App\Models\Schedule::where('start_date_time', '>=', $currentDate)->get();

            // Extract job_ids from schedules
            $jobIds = $schedules->pluck('job_id');
            $techIds = $schedules->pluck('technician_id');

            // Fetch tickets for those job_ids
            $tickets = \App\Models\JobModel::whereIn('id', $jobIds)->get();
            $customerIds = $tickets->pluck('customer_id');
            $technician = \App\Models\User::whereIn('id', $techIds)->get();
            $customer = \App\Models\User::whereIn('id', $customerIds)->get();
            $title = \App\Models\SiteJobTitle::all();

        @endphp

        <div class="jobMainSection" style="display: none;">
            <a href="#." class="close-task-detail in" id="close-job-detail"">
                X
            </a>
            <div class="job-list sticky-note bg-white w-100 h-100">

                <div class="row me-5 pe-5 shadow pb-3 m-0">
                    <div class="col-sm-6 col-md-6">
                        <h3 class="py-2 ps-2"> Job List </h3>
                    </div>
                    <div class="col-sm-4 col-md-4 text-end">
                        <a href="javascript:void(0);" id="stickyRouting" class="text-decoration-none text-primary"
                            style="color:black;"><i class="ri-settings-2-line"></i> Routing Setting</a>
                    </div>
                    <div class="col-sm-2 col-md-2" id="colrouting"></div>
                    <div class="col-sm-10 col-md-10" id="showStickyRouting">
                        <div class="row">
                            <div class="col-md-6 settings-panel" style="max-height: 700px; overflow-y: auto;">
                                <div class="card card-body card-border card-shadow   p-3 border2 ">
                                    <div class="row mb-2 ">
                                        <label class="col-8 col-form-label">Auto Route Settings</label>
                                        <div class="col-4 bt-switch">
                                            <input type="checkbox" name="auto_route" data-toggle="switchbutton"
                                                data-on-color="success" data-off-color="default"
                                                onchange="toggleTimeInput(this, 'autoRouteTime1')">
                                            <input type="hidden" name="auto_route_value" value="no">
                                        </div>
                                    </div>
                                    <div class="row mb-2 d-none" id="autoRouteTime1">
                                        <div class="col-8 offset-4 mb-2">
                                            <input type="time" class="form-control" name="auto_route_time"
                                                value="08:00">
                                        </div>

                                        <div class="row mb-2">
                                            <label class="col-8 col-form-label">Time Constraints</label>
                                            <div class="col-4  bt-switch">
                                                <input type="checkbox" name="time_constraints"
                                                    data-toggle="switchbutton" data-on-color="success"
                                                    data-off-color="default" onchange="updateCheckboxValue(this)">
                                                <input type="hidden" name="time_constraints_value" value="no">
                                            </div>
                                        </div>
                                        <div class="row mb-2 border-btm">
                                            <label class="col-8 col-form-label">Priority Routing</label>
                                            <div class="col-4  bt-switch">
                                                <input type="checkbox" name="priority_routing"
                                                    data-toggle="switchbutton" data-on-color="success"
                                                    data-off-color="default" onchange="updateCheckboxValue(this)">
                                                <input type="hidden" name="priority_routing_value" value="no">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 settings-panel" style="max-height: 700px; overflow-y: auto;">
                                <div class="card card-body card-border card-shadow   p-3 border2 ">

                                    <div class="row mb-2 border-btm">
                                        <label class="col-8 col-form-label">Automatic Re-Routing</label>
                                        <div class="col-4 bt-switch">
                                            <input type="checkbox" name="auto_rerouting" data-toggle="switchbutton"
                                                data-on-color="success" data-off-color="default"
                                                onchange="toggleTimeInput(this, 'autoReRouteTime1')">
                                            <input type="hidden" name="auto_rerouting_value" value="no">
                                        </div>
                                    </div>
                                    <div class="row mb-2 d-none" id="autoReRouteTime1">
                                        <div class="col-8 offset-4">
                                            <input type="time" class="form-control" name="auto_rerouting_time"
                                                value="08:00">
                                        </div>
                                    </div>
                                    <div class="row mb-2 border-btm">
                                        <label class="col-8 col-form-label">Auto Publishing</label>
                                        <div class="col-4 bt-switch">
                                            <input type="checkbox" name="auto_publishing" data-toggle="switchbutton"
                                                data-on-color="success" data-off-color="default"
                                                onchange="toggleTimeInput(this, 'autoPublishingTime1')">
                                            <input type="hidden" name="auto_publishing_value" value="no">
                                        </div>
                                    </div>
                                    <div class="row mb-2 d-none" id="autoPublishingTime1">
                                        <div class="col-8 offset-4">
                                            <input type="time" class="form-control" name="auto_publishing_time"
                                                value="08:00">
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <label for="call-limit" class="col-6 col-form-label">Max Calls</label>
                                        <div class="col-6">
                                            <input type="number" id="call-limit" name="number_of_calls"
                                                class="form-control" placeholder="Set Limit">
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-sm-2 col-md-2"></div>
                    <div class="col-md-2">
                        <div class="d-flex flex-column align-items-baseline">
                            <!-- Filter by other column (example: Manufacturer) -->
                            <label class="text-nowrap"><b>Technician </b></label>
                            <select id="technician-filter" class="form-control mx-2">
                                <option value="">All</option>
                                @foreach ($technician as $item)
                                    <option value="{{ $item->name }}" class="text-uppercase">{{ $item->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex flex-column align-items-baseline">
                            <!-- Date filtering input -->

                            <label><b>Customer</b></label>
                            <select id="customer-filter" class="form-control mx-2">
                                <option value="">All</option>
                                @foreach ($customer as $item)
                                    <option value="{{ $item->name }}" class="text-uppercase">{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="d-flex flex-column  align-items-baseline">
                            <label class="text-nowrap"><b>Priority</b></label>
                            <select id="priority-filter" class="form-control mx-2">
                                <option value="">All</option>
                                <option value="critical">Critical</option>
                                <option value="emergency">Emergency</option>
                                <option value="high">High</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="d-flex flex-column  align-items-baseline">
                            <!-- Filter by status -->
                            <label class="text-nowrap"><b>Job Title</b></label>
                            <select id="title-filter" class="form-control mx-2">
                                <option value="">All</option>field_name
                                @foreach ($title as $item)
                                    <option value="{{ $item->field_name }}">{{ $item->field_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex flex-column  align-items-baseline">
                            <!-- Filter by status -->
                            <label class="text-nowrap"><b>Job Confirmed</b></label>
                            <select id="isConfirmed-filter" class="form-control mx-2">
                                <option value="">All</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex flex-column  align-items-baseline">
                            <!-- Filter by status -->
                            <label class="text-nowrap"><b>Service Area</b></label>
                            <select id="area-filter" class="form-control mx-2">
                                <option value="">All</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row m-2 jobList">

                    <div class="col-sm-12 col-md-12 w-75">
                        <div class="table-responsive" style="overflow: scroll; height: 570px;">
                            <table id="sticky_job_list"
                                class="table table-hover table-striped table-bordered text-nowrap" data-paging="true"
                                data-paging-size="7">
                                <thead>
                                    <tr>
                                        <th>Job ID</th>
                                        <th class="job-details-column">Job Details</th>
                                        <th>Customer</th>
                                        <th>Technician</th>
                                        <th>Date & Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input bg-dark-subtle"
                                                    id="" name="technicians[]">
                                            </td>
                                            <td class="job-details-column">
                                                <div class="text-wrap2 d-flex">
                                                    <div class=" text-truncate">
                                                        <a href="{{ route('tickets.show', $ticket->id) }}"
                                                            class="font-medium link">
                                                            #{{ $ticket->id }}-{{ $ticket->job_title ?? null }}</a>
                                                    </div>
                                                    <span
                                                        class="badge bg-light-warning text-warning font-medium">{{ $ticket->status }}</span>
                                                </div>
                                                <div style="font-size:12px;">
                                                    @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances)
                                                        {{ $ticket->JobAppliances->Appliances->appliance->appliance_name ?? null }}/
                                                    @endif
                                                    @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances)
                                                        {{ $ticket->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null }}/
                                                    @endif
                                                    @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances->model_number)
                                                        {{ $ticket->JobAppliances->Appliances->model_number ?? null }}/
                                                    @endif
                                                    @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances->serial_number)
                                                        {{ $ticket->JobAppliances->Appliances->serial_number ?? null }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-uppercase">
                                                @if ($ticket->user)
                                                    {{ $ticket->user->name }}
                                                @else
                                                    Unassigned
                                                @endif
                                            </td>
                                            <td class="text-uppercase">
                                                @if ($ticket->technician)
                                                    {{ $ticket->technician->name }}
                                                @else
                                                    Unassigned
                                                @endif
                                            </td>
                                            <td>
                                                @if ($ticket->jobassignname && $ticket->jobassignname->start_date_time)
                                                    <div class="font-medium link">
                                                        {{ $modifyDateTime($ticket->jobassignname->start_date_time ?? null, $time_interval, 'add', 'm-d-Y') }}
                                                    </div>
                                                @else
                                                    <div></div>
                                                @endif
                                                <div style="font-size:12px;">
                                                    {{ $modifyDateTime($ticket->jobassignname->start_date_time ?? null, $time_interval, 'add', 'h:i A') }}
                                                    to
                                                    {{ $modifyDateTime($ticket->jobassignname->end_date_time ?? null, $time_interval, 'add', 'h:i A') }}
                                                </div>
                                            </td>
                                            <td>
                                                <span><a class="btn btn-success"
                                                        href="{{ route('tickets.show', $ticket->id) }}">View</a></span>
                                                <span style="display:none;"><a class="btn btn-primary"
                                                        href="{{ route('tickets.edit', $ticket->id) }}">Edit</a></span>
                                                <span style="display:none;">
                                                    <form method="POST"
                                                        action="{{ route('tickets.destroy', $ticket->id) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </span>
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

    </header>

    <script>
        const storeColorNoteUrl = "{{ route('store.colorNote') }}";
        const updateColorNoteUrl = "{{ route('update.colorNote') }}";
        const storeEditNoteUrl = "{{ route('note.get') }}";
        const deleteNoteUrl = "{{ route('note.delete') }}";
        const csrfToken = "{{ csrf_token() }}";

        function updateTime() {
            const timezoneName = '{{ $timezoneName }}'; // Dynamic timezone from backend
            const options = {
                timeZone: timezoneName,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true,
                timeZoneName: 'short'
            };

            const formatter = new Intl.DateTimeFormat('en-US', options);
            const now = new Date();
            const formattedTime = formatter.format(now);

            document.getElementById('liveTime').innerText = formattedTime;
        }

        setInterval(updateTime, 1000); // Update every second
        updateTime(); // Initial call
    </script>
    <script src="{{ asset('public/admin/dist/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
  

@endif
