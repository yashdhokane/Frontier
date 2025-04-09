<a class="nav-link dropdown-toggle waves-effect waves-dark" title="Open Jobs" href="#" id="showJobList" data-bs-toggle="dropdown"
    aria-haspopup="true" aria-expanded="false"> <i class="ri-draft-line ft20 align-self-baseline"></i>
</a>

@php
$timezone_name = Session::get('timezone_name', 'UTC');
$time_interval = Session::get('time_interval', 0);
$currentDate = \Carbon\Carbon::now($timezone_name);

$schedules = \App\Models\Schedule::whereDate('start_date_time', '>=', $currentDate)->get();

// Extract job_ids from schedules
$jobIds = $schedules->pluck('job_id');
$techIds = $schedules->pluck('technician_id');

// Fetch tickets for those job_ids
$tickets = \App\Models\JobModel::whereIn('id', $jobIds)->where('is_published', 'no')->get();
$customerIds = $tickets->pluck('customer_id');
$technician = \App\Models\User::whereIn('id', $techIds)->get();
$customer = \App\Models\User::whereIn('id', $customerIds)->get();
$title = \App\Models\SiteJobTitle::all();

@endphp
<div class="dropdown-menu dropdown-menu-end mailbox dropdown-menu-animate-up openjobsDropdown"  aria-labelledby="showJobList">
    <span class="with-arrow"><span class="bg-site"></span></span>
    <ul class="list-style-none">
        <li>
            <div class="drop-title text-white bg-site d-flex justify-content-between">
                <h5 class="mb-0 mt-1 uppercase">Open Jobs </h5>
                <span id="stickyRouting" class="text-decoration-none text-light pointer " style="z-index: 9;">
					<i class="ri-settings-2-line"></i> Routing Setting 
				</span>
            </div>
        </li>
        <li>
           
            <div class=" job-list bg-white">

                    <div class="row shadow m-0">
                        <div class="" id="colrouting"></div>
                        <div class="col-sm-12 col-md-12 py-2" id="showStickyRouting">
                            <div class="row">
                                <div class="col-md-6 settings-panel" style="max-height: 700px; overflow-y: auto;">
                                    <div class="card card-body card-border card-shadow   p-3 border2 ">
                                        <div class="row mb-2 ">
                                            <label class="col-8 col-form-label">Auto Route Settings</label>
                                            <div class="col-4 bt-switch">
                                                <input type="checkbox" id="autoRouteTimesticky" name="auto_route"
                                                    data-toggle="switchbutton" data-on-color="success" data-off-color="default"
                                                    onchange="toggleTimeInput(this, 'autoRouteTime1')">
                                                <input type="hidden" name="auto_route_value" value="no">
                                            </div>
                                        </div>
                                        <div class="row mb-2 d-none" id="autoRouteTime1">
                                            <div class="col-8 offset-4 mb-2">
                                                <input type="time" class="form-control" name="auto_route_time" value="08:00">
                                            </div>

                                            <div class="row mb-2">
                                                <label class="col-8 col-form-label">Time Constraints</label>
                                                <div class="col-4  bt-switch">
                                                    <input type="checkbox" name="time_constraintsMain" id="time_constraint_job"
                                                        data-toggle="switchbutton" data-on-color="success" value="no"
                                                        data-off-color="default" onchange="updateConstraintValue(this)">

                                                    <input type="hidden" name="time_constraint_job_value"
                                                        id="time_constraint_job_value" value="off">
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label class="col-8 col-form-label">Priority Routing</label>
                                                <div class="col-4  bt-switch">
                                                    <input type="checkbox" name="priority_routing" value="no"
                                                        data-toggle="switchbutton" data-on-color="success" data-off-color="default"
                                                        onchange="updatePriorityValue(this)" id="priority_routing">
                                                    <input type="hidden" name="priority_job_value" value="no"
                                                        id="priority_job_value">
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
                                                    id="auto_rerouting_switch" data-on-color="success" data-off-color="default"
                                                    onchange="updateReroutingValue(this)">
                                                <input type="hidden" name="auto_rerouting_value" value="no" id="auto_rerouting">
                                            </div>
                                            <div class="row mb-2" id="autoReRouteTime1">
                                                <div class="col-8 offset-4">
                                                    <input type="time" class="form-control" name="auto_rerouting_time" value="08:00">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2 border-btm">
                                            <label class="col-8 col-form-label">Auto Publishing</label>
                                            <div class="col-4 bt-switch">
                                                <input type="checkbox" name="auto_publishingMain" data-toggle="switchbutton"
                                                    data-on-color="success" data-off-color="default" id="auto_publishingMain"
                                                    onchange="updatePublishingValue(this)">
                                                <input type="hidden" name="auto_publishing_job_value" value="off"
                                                    id="auto_publishing_job_value">
                                            </div>
                                             <div class="row mb-2" id="autoPublishingTime1">
                                                <div class="col-8 offset-4">
                                                    <input type="time" class="form-control" name="auto_publishing_time" value="08:00">
                                                </div>
                                            </div>
                                        </div>
                                       

                                        <div class="row mb-2">
                                            <label for="call-limit" class="col-6 col-form-label">Max Calls</label>
                                            <div class="col-6">
                                                <input type="number" id="number_of_calls" name="number_of_calls"
                                                    class="form-control" placeholder="Set Limit" value="5">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col">
                                            <button id="stickySaveButton" type="button" class="btn btn-success ms-2"
                                                onclick="updateCheckboxValue1(this)">Save</button>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="row shadow m-0 py-2">
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
                        <div class="col-sm-12 col-md-12">
                            <div class="table-responsive" style="overflow: scroll; height: 570px;">
                                <table id="sticky_job_list" class="table table-hover table-striped table-bordered text-nowrap"
                                    data-paging="false">
                                    <thead>
                                        <tr>
                                            <th> <input type="checkbox" class="form-check-input primary border border-info" id"allCheckbox"
                                                    onchange="toggleAllCheckboxes(this)"></th>
                                            <th class="job-details-column">Job Details</th>
                                            <th>Customer</th>
                                            <th>Technician</th>
                                            <th>Priority</th>
                                            <th>isConfirmed</th>
                                            <th>Date & Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($tickets as $ticket)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input primary jobIds border border-info" name="jobIds[]"
                                                    value="{{ $ticket->id }}" onchange="checkAllSelected()">
                                            </td>
                                            <td class="job-details-column">
                                                <div class="text-wrap2 d-flex">
                                                    <div class=" text-truncate">
                                                        <a href="{{ route('tickets.show', $ticket->id) }}" class="font-medium link">
                                                            #{{ $ticket->id }}-{{ $ticket->job_title ?? null }}</a>
                                                    </div>
                                                    <span class="badge bg-light-warning text-warning font-medium">{{ $ticket->status
                                                        }}</span>
                                                </div>
                                                <div style="font-size:12px;">
                                                    @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances)
                                                    {{ $ticket->JobAppliances->Appliances->appliance->appliance_name ?? null }}/
                                                    @endif
                                                    @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances)
                                                    {{ $ticket->JobAppliances->Appliances->manufacturer->manufacturer_name ?? null
                                                    }}/
                                                    @endif
                                                    @if ($ticket->JobAppliances && $ticket->JobAppliances->Appliances->model_number)
                                                    {{ $ticket->JobAppliances->Appliances->model_number ?? null }}/
                                                    @endif
                                                    @if ($ticket->JobAppliances &&
                                                    $ticket->JobAppliances->Appliances->serial_number)
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
                                            <td class="priority-column">{{ $ticket->priority ?? 'None' }}</td>
                                            <!-- Hidden column -->
                                            <td class="isConfirmed-column">{{ $ticket->isConfirmed ?? 'None' }}</td>
                                            <!-- Hidden column -->
                                            <td>
                                                @if ($ticket->jobassignname && $ticket->jobassignname->start_date_time)
                                                <div class="font-medium link">
                                                    {{
                                                    \Carbon\Carbon::parse($ticket->jobassignname->start_date_time)->addMinutes($time_interval)->format('m-d-Y')
                                                    }}
                                                </div>
                                                @else
                                                <div></div>
                                                @endif
                                                <div style="font-size:12px;">
                                                    {{
                                                    \Carbon\Carbon::parse($ticket->jobassignname->start_date_time)->addMinutes($time_interval)->format('h:i
                                                    A') }}
                                                    to
                                                    {{
                                                    \Carbon\Carbon::parse($ticket->jobassignname->end_date_time)->addMinutes($time_interval)->format('h:i
                                                    A') }}
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
        </li>
    </ul>
    

</div>