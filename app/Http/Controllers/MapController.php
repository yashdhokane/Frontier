<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoutingTrigger;
use App\Models\LocationServiceArea;
use App\Models\RoutingTriggerTechnician;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Storage;
use App\Models\CustomerUserAddress;
use App\Models\Schedule;
use App\Models\JobAssign;
use Illuminate\Support\Facades\Log;

use App\Models\TechnicianJobsSchedulesOnMap;



class MapController extends Controller
{

    public function index(Request $request)
    {

        $user_auth = auth()->user();
        $user_id = $user_auth->id;
        $permissions_type = $user_auth->permissions_type;
        $module_id = 31;

        $permissionCheck = app('UserPermissionChecker')->checkUserPermission($user_id, $permissions_type, $module_id);
        if ($permissionCheck === true) {
            // Proceed with the action
        } else {
            return $permissionCheck; // This will handle the redirection
        }

        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');
        $currentDate = Carbon::now($timezone_name);
        // Get the first and last date of the previous month
        $lastMonthStart = $currentDate->subMonth()->startOfMonth()->toDateTimeString();
        $lastMonthEnd = $currentDate->endOfMonth()->toDateTimeString();


        $locationServiceSouthWest = DB::table('location_service_area')->where('area_name', 'South West')->first();

        $query = DB::table('job_assigned')
            ->select(
                'job_assigned.id as assign_id',
                'job_assigned.job_id as job_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_assigned.start_slot',
                'job_assigned.end_slot',
                'job_assigned.pending_number',
                'jobs.job_code',
                'jobs.job_title as subject',
                'jobs.status',
                'jobs.address',
                'jobs.city',
                'jobs.state',
                'jobs.zipcode',
                'jobs.latitude',
                'jobs.longitude',
                'users.name',
                'users.email',
                'technician.name as technicianname',
                'technician.email as technicianemail'
            )
            ->join('jobs', 'jobs.id', 'job_assigned.job_id')
            ->join('users', 'users.id', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', 'job_assigned.technician_id')
            ->where('assign_status', 'active')
            ->whereNotNull('jobs.latitude')
            ->whereNotNull('jobs.longitude')
            ->whereDate('job_assigned.start_date_time', [$lastMonthStart, $lastMonthEnd])
            ->orderBy('job_assigned.pending_number', 'asc');

        if (isset($request->area_id) && !empty($request->area_id)) {
            $query->where('jobs.service_area_id', $request->area_id);
        } elseif (isset($locationServiceSouthWest->area_id) && !empty($locationServiceSouthWest->area_id)) {
            $query->where('jobs.service_area_id', $locationServiceSouthWest->area_id);
        }

        $data = $query->get();



        $locationServiceArea = DB::table('location_service_area')->where('area_type', 'region')->get();

        return view('maps.index', compact('data', 'locationServiceArea', 'locationServiceSouthWest'));
    }

    public function getMarkerDetails(Request $request)
    {
        $data = $request->all();

        $result = DB::table('job_assigned')
            ->select(
                'job_assigned.id as assign_id',
                'job_assigned.job_id as job_id',
                'job_assigned.start_date_time',
                'job_assigned.end_date_time',
                'job_assigned.start_slot',
                'job_assigned.end_slot',
                'job_assigned.pending_number',
                'jobs.job_code',
                'jobs.description',
                'jobs.job_title as subject',
                'jobs.status',
                'jobs.address',
                'jobs.city',
                'jobs.state',
                'jobs.zipcode',
                'jobs.latitude',
                'jobs.longitude',
                'users.name',
                'users.email',
                'technician.name as technicianname',
                'technician.email as technicianemail',
                'jobs.job_field_ids'
            )
            ->join('jobs', 'jobs.id', 'job_assigned.job_id')
            ->join('users', 'users.id', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', 'job_assigned.technician_id')
            ->where('job_assigned.job_id', $data['id'])->first();

        $jobFieldsIds = explode(',', $result->job_field_ids);

        $jobFields = DB::table('site_job_fields')
            ->whereIn('field_id', $jobFieldsIds)
            ->get();

        $show_status = '';

        foreach ($jobFields as $item) {
            $show_status .= '<span class="mb-1 badge bg-success">' . $item->field_name . '</span> ';
        }
        if (empty($jobFields)) {
            $show_status = '<span class="mb-1 badge bg-secondary">No Fields</span>';
        }
        $fullAddress = $result
            ? "{$result->address}, {$result->city}, {$result->state}, {$result->zipcode}"
            : 'Address not available';

        $content = '
            <div class="pp_jobmodel" style="width: 250px;">
                <h5 class="uppercase text-truncate pb-0 mb-0">#' . $result->job_id . ' - ' . $result->subject . '</h5>
                <p class="text-truncate pb-0 mb-0 ft13">' . $result->description . '</p>
                <div class="pp_job_date text-primary">
                ' . Carbon::parse($result->start_date_time)->format('M j Y g:i A') . ' - ' . Carbon::parse($result->end_date_time)->format('g:i A') . '
                </div>
                <p class="ft13 uppercase mb-0 text-truncate">
                    <strong><i class="ri-user-line"></i> ' . $result->name . '</strong>
                </p>
                <div class="ft12"><i class="ri-map-pin-fill"></i> ' . $fullAddress . '</div>
 			<div class="mt-2"><a href="tickets/' . $result->job_id . '" target="_blank" class="btn btn-success waves-effect waves-light btn-sm btn-info">View</a> <a href="#" class="btn btn-warning waves-effect waves-light btn-sm btn-info reschedule" data-job_id="' . $result->job_id . '">Reschedule</a></div>
            </div>
        ';

        return response()->json(['content' => $content]);
    }

    public function getTechnicianAreaWise(Request $request)
    {

        $data = $request->all();

        $options = "<option selected value=''>-- Select Technician --</option>";

        if (isset($data['id']) && !empty($data['id'])) {

            $technician = User::select('id', 'name')->where('role', 'technician')->where('service_areas', 'LIKE', '%' . $data['id'] . '%')->get();

            if (isset($technician) && !empty($technician->count())) {

                foreach ($technician as $key => $value) {
                    $options .= "<option value='" . $value->id . "'>" . $value->name . "</option>";
                }
            }
        }

        return $options;
    }

    public function getJobDetails(Request $request)
    {
        $locationServiceSouthWest = DB::table('location_service_area')->where('area_name', 'South West')->first();

        $data = $request->all();

        if (isset($data) && !empty($data)) {

            $job_id = $data['job_id'];
            $count = $data['count'];

            $getData = DB::table('job_assigned')
                ->select(
                    'job_assigned.start_date_time',
                    'job_assigned.job_id',
                    'job_assigned.customer_id',
                    'job_assigned.technician_note_id',
                    'jobs.job_title',
                    'jobs.description',
                    'jobs.address',
                    'jobs.city',
                    'jobs.state',
                    'jobs.zipcode',
                    'users.name',
                    'job_assigned.duration',
                    'job_assigned.driving_hours',
                )
                ->join('users', 'users.id', 'job_assigned.customer_id')
                ->join('jobs', 'jobs.id', 'job_assigned.job_id')
                ->where('job_assigned.job_id', $job_id)->first();

            $start_date_time = Carbon::parse($getData->start_date_time);

            $getData->start_date_time = $start_date_time->format('Y-m-d\TH:i');

            $technician = null;

            if (isset($request->area_id) && !empty($request->area_id)) {
                $technician = User::select('id', 'name')->where('role', 'technician')->where('status', 'active')->where('service_areas', 'LIKE', '%' . $request->area_id . '%')->get();
            } elseif (isset($locationServiceSouthWest->area_id) && !empty($locationServiceSouthWest->area_id)) {
                $technician = User::select('id', 'name')->where('role', 'technician')->where('status', 'active')->where('service_areas', 'LIKE', '%' . $locationServiceSouthWest->area_id . '%')->get();
            }

            return view('maps.reschedule_list', compact('getData', 'count', 'technician'));
        }
    }

    public function rescheduleJob(Request $request)
    {

        $data = $request->all();
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');

        try {

            if (isset($data['technician_id']) && !empty($data['technician_id'])) {

                $technician_id = $data['technician_id'];

                if (isset($data['rescheduleData']) && !empty($data['rescheduleData'])) {

                    foreach ($data['rescheduleData'] as $key => $value) {

                        $newFormattedDateTime = Carbon::parse($value['start_date_time'])->subHours($time_interval)->format('Y-m-d H:i:s');


                        $start_date_time = Carbon::parse($newFormattedDateTime);

                        $end_date_time = $start_date_time->copy()->addMinutes($value['duration']);

                        $jobData = DB::table('jobs')->where('id', $value['job_id'])->update([
                            'technician_id' => $technician_id,
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);

                        $JobAssignedData = [
                            'assign_status' => 'rescheduled',
                        ];

                        $jobAssignedID = DB::table('job_assigned')->where('job_id', $value['job_id'])->update($JobAssignedData);

                        $newJobAssignedData = [
                            'technician_id' => $technician_id,
                            'customer_id' => $value['customer_id'],
                            'job_id' => $value['job_id'],
                            'duration' => $value['duration'],
                            'driving_hours' => $value['driving_hours'],
                            'assign_title' => $value['assign_title'],
                            'technician_note_id' => $value['technician_note_id'],
                            'assign_description' => $value['assign_description'],
                            'start_date_time' => $start_date_time->format('Y-m-d h:i:s'),
                            'end_date_time' => $end_date_time->format('Y-m-d h:i:s'),
                            'added_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'start_slot' => $start_date_time->format('H'),
                            'end_slot' => $end_date_time->format('H'),
                        ];

                        $newjobAssignedID = DB::table('job_assigned')->insertGetId($newJobAssignedData);

                        $scheduleData = [
                            'technician_id' => $technician_id,
                            'start_date_time' => $start_date_time->format('Y-m-d h:i:s'),
                            'end_date_time' => $end_date_time->format('Y-m-d h:i:s'),
                            'updated_by' => auth()->id(),
                            'updated_at' => date('Y-m-d H:i:s'),
                            'start_slot' => $start_date_time->format('H'),
                            'end_slot' => $end_date_time->format('H'),
                        ];

                        $schedule = DB::table('schedule')->where('job_id', $value['job_id'])->update($scheduleData);

                        $technician_name = User::where('id', $technician_id)->first();
                        $now = Carbon::now($timezone_name);
                        $formattedDate = $start_date_time->format('D, M j');
                        $formattedTime = $now->format('g:ia');
                        $formattedDateTime = "{$formattedDate} at {$formattedTime}";
                        $activity = 'Job Re-Scheduled for ' . $formattedDateTime;

                        app('JobActivityManager')->addJobActivity($value['job_id'], $activity);
                        app('sendNotices')(
                            "Reschedule Job",
                            "Reschedule Job (#{$value['job_id']} - {$value['customer_name']}) added by {$technician_name->name}",
                            url()->current(),
                            'job'
                        );

                        app('sendNoticesapp')(
                            "Job started",
                            "Job started (#{$value['job_id']} - {$value['customer_name']}) started by {$technician_name->name}",
                            url()->current(),
                            'job',
                            $technician_name->id,
                            $value['job_id']
                        );
                    }
                }
            }

            return 'true';
        } catch (\Exception $e) {

            Storage::append('Reschedule.log', ' error_msg -- ' . json_encode($e->getMessage()) . ' line number: ' . json_encode($e->getLine()) . ' File: ' . json_encode($e->getFile()) . ' - ' . date('Y-m-d H:i:s') . PHP_EOL);

            return 'false';
        }
    }

    public function getAllTechniciansRoutes(Request $request)
    {
        // Get today's date
        $timezone_name = Session::get('timezone_name');

        $inputDate = Carbon::now($timezone_name)->format('Y-m-d');


        // Fetch technician IDs where role is 'technician'
        $technicians = User::where('role', 'technician')->where('status', 'active')->get();

        // Handle case when no technicians are found
        if ($technicians->isEmpty()) {
            return view('jobrouting.technicians_jobs_map')->with('technicians', []);
        }

        // Prepare the response array for the view
        $response = [];

        foreach ($technicians as $technician) {
            // Fetch technician's location
            $technicianLocation = CustomerUserAddress::where('user_id', $technician->id)
                ->first(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

            // Handle case where technician location is not found
            if (!$technicianLocation) {
                $response[] = [
                    'technician' => [
                        'id' => $technician->id,
                        'name' => $technician->name,
                        'error' => 'Technician location not found.'
                    ]
                ];
                continue; // Skip to the next technician
            }

            // Attach technician's full address
            $technicianLocation->full_address = $technicianLocation->address_line1 . ', ' .
                $technicianLocation->city . ', ' .
                $technicianLocation->state_name . ' ' .
                $technicianLocation->zipcode;

            // Fetch jobs for the technician on the input date
            $jobs = Schedule::where('technician_id', $technician->id)
                ->whereDate('start_date_time', '=', $inputDate)
                ->get(['job_id', 'position', 'is_routes_map', 'job_onmap_reaching_timing']);

            // Initialize technician data structure
            $technicianData = [
                'technician' => [
                    'id' => $technician->id,
                    'name' => $technician->name,
                    'full_address' => $technicianLocation->full_address,
                    'latitude' => $technicianLocation->latitude,
                    'longitude' => $technicianLocation->longitude,
                ],
                'jobs' => []
            ];

            if ($jobs->isEmpty()) {
                $technicianData['error'] = 'No jobs found for this technician on the selected date.';
                $response[] = $technicianData;
                continue; // Skip to the next technician
            }

            // Create an array to store job distances
            $jobDistances = [];

            foreach ($jobs as $job) {
                // Fetch customer IDs assigned to the job
                $customerIds = JobAssign::where('job_id', $job->job_id)->pluck('customer_id');

                // Fetch customer locations
                $customerLocations = CustomerUserAddress::whereIn('user_id', $customerIds)
                    ->get(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

                if ($customerLocations->isEmpty()) {
                    $technicianData['jobs'][] = [
                        'job_id' => $job->job_id,
                        'error' => 'No customer locations found for this job.'
                    ];
                    continue;
                }

                foreach ($customerLocations as $customerLocation) {
                    // Fetch customer name
                    $customerName = User::where('id', $customerLocation->user_id)->value('name');

                    // Construct the full address for the customer
                    $customerLocation->full_address = $customerLocation->address_line1 . ', ' .
                        $customerLocation->city . ', ' .
                        $customerLocation->state_name . ' ' .
                        $customerLocation->zipcode;

                    // Calculate distance from technician to customer
                    $distance = $this->calculateDistance(
                        $technicianLocation->latitude,
                        $technicianLocation->longitude,
                        $customerLocation->latitude,
                        $customerLocation->longitude
                    );

                    // Add customer info to the job and store the distance
                    $jobDistances[] = [
                        'job_id' => $job->job_id,
                        'position' => $job->position,
                        'is_routes_map' => $job->is_routes_map,
                        'job_onmap_reaching_timing' => $job->job_onmap_reaching_timing,
                        'customer' => [
                            'id' => $customerLocation->user_id,
                            'name' => $customerName,
                            'full_address' => $customerLocation->full_address,
                            'latitude' => $customerLocation->latitude,
                            'longitude' => $customerLocation->longitude,
                        ],
                        'distance' => $distance // Add the calculated distance
                    ];
                }
            }

            // Sort jobs by distance
            usort($jobDistances, function ($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });

            // Assign sorted jobs to the technician data
            $technicianData['jobs'] = $jobDistances;
            $response[] = $technicianData;
        }
        // dd($response );

        $responseJson = json_encode($response);
        $tech = user::where('role', 'technician')->where('status', 'active')->get();
        $routingTriggers = RoutingTrigger::all();
        $location = LocationServiceArea::all();
        // dd($responseJson );
        // Return the view with technicians and their jobs
        return view('jobrouting.technicians_jobs_map', compact('responseJson', 'tech', 'routingTriggers', 'location'));
    }

    public function getAllTechniciansRoutesdate(Request $request)
    {
        // Get today's date
        // $inputDate = Carbon::today()->format('Y-m-d');
        // $inputDate = $request->has('date') ? Carbon::parse($request->input('date'))->format('Y-m-d') : Carbon::today()->format('Y-m-d');


        // This line checks if a date parameter exists in the request.
        $inputDate = $request->has('date') ?
            Carbon::parse(explode(':', $request->input('date'))[0])->format('Y-m-d') :
            Carbon::today()->format('Y-m-d');

        // Fetch technician IDs where role is 'technician'
        $technicians = User::where('role', 'technician')->where('status', 'active')->get();

        // Handle case when no technicians are found
        if ($technicians->isEmpty()) {
            return view('jobrouting.technicians_jobs_map')->with('technicians', []);
        }

        // Prepare the response array for the view
        $response = [];

        foreach ($technicians as $technician) {
            // Fetch technician's location
            $technicianLocation = CustomerUserAddress::where('user_id', $technician->id)
                ->first(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

            // Handle case where technician location is not found
            if (!$technicianLocation) {
                $response[] = [
                    'technician' => [
                        'id' => $technician->id,
                        'name' => $technician->name,
                        'error' => 'Technician location not found.'
                    ]
                ];
                continue; // Skip to the next technician
            }

            // Attach technician's full address
            $technicianLocation->full_address = $technicianLocation->address_line1 . ', ' .
                $technicianLocation->city . ', ' .
                $technicianLocation->state_name . ' ' .
                $technicianLocation->zipcode;

            // Fetch jobs for the technician on the input date
            $jobs = Schedule::where('technician_id', $technician->id)
                ->whereDate('start_date_time', '=', $inputDate)
                ->get(['job_id', 'position', 'is_routes_map', 'job_onmap_reaching_timing']);

            // Initialize technician data structure
            $technicianData = [
                'technician' => [
                    'id' => $technician->id,
                    'name' => $technician->name,
                    'full_address' => $technicianLocation->full_address,
                    'latitude' => $technicianLocation->latitude,
                    'longitude' => $technicianLocation->longitude,
                ],
                'jobs' => []
            ];

            if ($jobs->isEmpty()) {
                $technicianData['error'] = 'No jobs found for this technician on the selected date.';
                $response[] = $technicianData;
                continue; // Skip to the next technician
            }

            // Create an array to store job distances
            $jobDistances = [];

            foreach ($jobs as $job) {
                // Fetch customer IDs assigned to the job
                $customerIds = JobAssign::where('job_id', $job->job_id)->pluck('customer_id');

                // Fetch customer locations
                $customerLocations = CustomerUserAddress::whereIn('user_id', $customerIds)
                    ->get(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

                if ($customerLocations->isEmpty()) {
                    $technicianData['jobs'][] = [
                        'job_id' => $job->job_id,
                        'error' => 'No customer locations found for this job.'
                    ];
                    continue;
                }

                foreach ($customerLocations as $customerLocation) {
                    // Fetch customer name
                    $customerName = User::where('id', $customerLocation->user_id)->value('name');

                    // Construct the full address for the customer
                    $customerLocation->full_address = $customerLocation->address_line1 . ', ' .
                        $customerLocation->city . ', ' .
                        $customerLocation->state_name . ' ' .
                        $customerLocation->zipcode;

                    // Calculate distance from technician to customer
                    $distance = $this->calculateDistance(
                        $technicianLocation->latitude,
                        $technicianLocation->longitude,
                        $customerLocation->latitude,
                        $customerLocation->longitude
                    );

                    // Add customer info to the job and store the distance
                    $jobDistances[] = [
                        'job_id' => $job->job_id,
                        'position' => $job->position,
                        'is_routes_map' => $job->is_routes_map,
                        'job_onmap_reaching_timing' => $job->job_onmap_reaching_timing,
                        'customer' => [
                            'id' => $customerLocation->user_id,
                            'name' => $customerName,
                            'full_address' => $customerLocation->full_address,
                            'latitude' => $customerLocation->latitude,
                            'longitude' => $customerLocation->longitude,
                        ],
                        'distance' => $distance // Add the calculated distance
                    ];
                }
            }

            // Sort jobs by distance
            usort($jobDistances, function ($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });

            // Assign sorted jobs to the technician data
            $technicianData['jobs'] = $jobDistances;
            $response[] = $technicianData;
        }
        // dd($response );

        // $responseJson = json_encode($response);
        $tech = user::where('role', 'technician')->where('status', 'active')->get();
        // $routingTriggers = RoutingTrigger::all();
        // dd($responseJson );
        // Return the view with technicians and their jobs
        // return view('jobrouting.technicians_jobs_map', compact('responseJson','tech'));
        return response()->json($response);

    }

    // Distance calculation function
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Ensure all inputs are floats
        $lat1 = (float) $lat1;
        $lon1 = (float) $lon1;
        $lat2 = (float) $lat2;
        $lon2 = (float) $lon2;

        $earthRadius = 6371; // Earth radius in kilometers

        // Calculate differences
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        // Calculate distance using Haversine formula
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }


    public function storerouting(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'technician' => 'required|array',
            'days' => 'required|array',
            'date' => 'required|date',
        ]);

        // Extract and initialize data
        $technicianIds = $validatedData['technician'];
        $requestedDate = Carbon::parse($request->date);
        $updatedSchedules = [];
        $updatedJobAssigns = [];

        Log::info("Request data", [
            'technician_ids' => $technicianIds,
            'days' => $validatedData['days'],
            'requested_date' => $requestedDate
        ]);

        foreach ($technicianIds as $technicianId) {
            foreach ($validatedData['days'] as $day) {
                $searchDate = $requestedDate->toDateString();
                $cumulativeMinutes = 0; // Reset cumulative time for each technician-day combination

                // Handle Schedule Model for current technician and day
                $existingSchedules = Schedule::where('technician_id', $technicianId)
                    ->whereDate('start_date_time', $searchDate)
                    ->get();

                //   dd($existingSchedules, $searchDate);

                foreach ($existingSchedules as $existingSchedule) {
                    $startDateTime = Carbon::parse($existingSchedule->start_date_time);
                    $endDateTime = Carbon::parse($existingSchedule->end_date_time);
                    $durationInMinutes = $startDateTime->diffInMinutes($endDateTime);
                    //dd($durationInMinutes);
                    if ($cumulativeMinutes + $durationInMinutes > 360) {
                        // Adjust the date while keeping the time the same
                        $nextOccurrenceDate = $this->getNextOccurrence($requestedDate, $this->getDayOfWeek($day))->toDateString();
                        $nextDayStartDateTime = Carbon::parse($nextOccurrenceDate)
                            ->setTimeFrom($startDateTime)
                            ->addDay();
                        $nextDayEndDateTime = Carbon::parse($nextOccurrenceDate)
                            ->setTimeFrom($endDateTime)
                            ->addDay();

                        // Update schedule with the new date and original time
                        $existingSchedule->start_date_time = $nextDayStartDateTime;
                        $existingSchedule->end_date_time = $nextDayEndDateTime;
                    } else {
                        $cumulativeMinutes += $durationInMinutes;
                    }

                    $existingSchedule->save();

                    // Collect the updated schedule data
                    $updatedSchedules[] = [
                        'technician_id' => $technicianId,
                        'day' => $day,
                        'original_date' => $startDateTime->toDateString(),
                        'updated_date' => Carbon::parse($existingSchedule->start_date_time)->toDateString(),
                        'updated_time' => Carbon::parse($existingSchedule->start_date_time)->toTimeString(),
                    ];
                }

                // Handle JobAssign Model for current technician and day
                $existingJobAssigns = JobAssign::where('technician_id', $technicianId)
                    ->whereDate('start_date_time', $searchDate)
                    ->get();

                foreach ($existingJobAssigns as $existingJobAssign) {
                    $startDateTime = Carbon::parse($existingJobAssign->start_date_time);
                    $endDateTime = Carbon::parse($existingJobAssign->end_date_time);
                    $durationInMinutes = $startDateTime->diffInMinutes($endDateTime);

                    if ($cumulativeMinutes + $durationInMinutes > 360) {
                        // Adjust the date while keeping the time the same
                        $nextOccurrenceDate = $requestedDate->copy()->next($this->getDayOfWeek($day))->toDateString();
                        $nextDayStartDateTime = Carbon::parse($nextOccurrenceDate)
                            ->setTimeFrom($startDateTime)
                            ->addDay();
                        $nextDayEndDateTime = Carbon::parse($nextOccurrenceDate)
                            ->setTimeFrom($endDateTime)
                            ->addDay();

                        // Update job assign with the new date and original time
                        $existingJobAssign->start_date_time = $nextDayStartDateTime;
                        $existingJobAssign->end_date_time = $nextDayEndDateTime;
                    } else {
                        $cumulativeMinutes += $durationInMinutes;
                    }

                    $existingJobAssign->save();

                    // Collect the updated job assignment data
                    $updatedJobAssigns[] = [
                        'technician_id' => $technicianId,
                        'day' => $day,
                        'original_date' => $startDateTime->toDateString(),
                        'updated_date' => Carbon::parse($existingJobAssign->start_date_time)->toDateString(),
                        'updated_time' => Carbon::parse($existingJobAssign->start_date_time)->toTimeString(),
                    ];
                }
            }
        }

        // Check if any records were updated, else create a new entry
        if (empty($updatedSchedules) && empty($updatedJobAssigns)) {
            TechnicianJobsSchedulesOnMap::create([
                'technician_ids' => implode(',', $technicianIds),
                'days_ids' => implode(',', $validatedData['days']),
                'previous_start_date_time' => $requestedDate,
            ]);

            Log::info("Created new entry in TechnicianJobsSchedulesOnMap", [
                'technician_ids' => implode(',', $technicianIds),
                'days_ids' => implode(',', $validatedData['days'])
            ]);
        }

        return response()->json([
            'success' => 'Data saved successfully',
            'updated_schedules' => $updatedSchedules,
            'updated_job_assigns' => $updatedJobAssigns,
        ]);
    }


    private function getDayOfWeek($dayIndex)
    {
        $dayMap = [
            0 => Carbon::MONDAY,
            1 => Carbon::TUESDAY,
            2 => Carbon::WEDNESDAY,
            3 => Carbon::THURSDAY,
            4 => Carbon::FRIDAY,
            5 => Carbon::SATURDAY,
            6 => Carbon::SUNDAY,
        ];

        return $dayMap[$dayIndex] ?? Carbon::MONDAY;
    }


    private function getNextOccurrence($startDate, $targetDayOfWeek)
    {
        if (!$startDate instanceof Carbon) {
            $startDate = Carbon::parse($startDate);
        }

        $daysUntilNext = ($targetDayOfWeek - $startDate->dayOfWeek + 7) % 7;

        if ($daysUntilNext == 0) {
            $daysUntilNext = 7;
        }

        return $startDate->copy()->addDays($daysUntilNext);
    }



    public function routingrurl(Request $request)
    {
        // Try to find an existing RoutingTrigger by routing_title, or create a new one if it doesn't exist
        $routingTrigger = RoutingTrigger::updateOrCreate(
            ['routing_title' => $request->input('routing_title')], // Check for existing routing title
            [
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
                'routing_cron' => 'no',
                'routing_cron_date' => now(),
                'is_active' => 1,
            ]
        );

        // Get technicians and days from the request
        $technicians = $request->input('technicians', []);
        $days = $request->input('days', []); // Array of days

        // Loop through each technician to create or update entries in RoutingTriggerTechnician
        foreach ($technicians as $technicianId) {
            // For each technician, create a new entry with days selected as an array (stored as JSON)
            RoutingTriggerTechnician::updateOrCreate(
                [
                    'routing_id' => $routingTrigger->routing_id,
                    'technician_id' => $technicianId,
                ],
                [
                    'days_selected' => json_encode($days), // Store days as a JSON array
                    'job_confirmed' => $request->has('job_confirmed') ? 1 : 0,
                    'parts_available' => $request->has('parts_available') ? 1 : 0,
                    'last_updated' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return response()->json(['message' => 'Routing trigger and technicians saved or updated successfully.']);
    }


}
