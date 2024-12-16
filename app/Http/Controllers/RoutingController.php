<?php
namespace App\Http\Controllers;

use App\Models\BusinessHours;
use App\Models\User;
use App\Models\RoutingTrigger;
use App\Models\LocationServiceArea;
use App\Models\RoutingTriggerTechnician;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\JobModel;

use Illuminate\Support\Facades\Session;
use Storage;
use App\Models\CustomerUserAddress;
use App\Models\Schedule;
use App\Models\JobAssign;
use Illuminate\Support\Facades\Log;

use App\Models\TechnicianJobsSchedulesOnMap;
use App\Models\RoutingSetting;
use App\Models\RoutingJOb;
use App\Models\RoutingSettingOption;
use Illuminate\Support\Facades\Auth;

class RoutingController extends Controller
{
    public function index(Request $request)
    {
        $timezone_name = Session::get('timezone_name', 'UTC');
        $inputDate = Carbon::now($timezone_name)->format('Y-m-d');

        // Fetch active technicians
        $technicians = User::where('role', 'technician')->where('status', 'active')->get();

        if ($technicians->isEmpty()) {
            return view('jobrouting.technicians_jobs_map')->with('technicians', []);
        }

        $response = [];

        foreach ($technicians as $technician) {
            $technicianLocation = CustomerUserAddress::where('user_id', $technician->id)
                ->first(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

            // If no location, add an error response and skip this technician
            if (!$technicianLocation) {
                $response[] = [
                    'technician' => [
                        'id' => $technician->id,
                        'name' => $technician->name,
                        'error' => 'Technician location not found.',
                    ],
                ];
                continue;
            }

            $technicianLocation->full_address = "{$technicianLocation->address_line1}, {$technicianLocation->city}, {$technicianLocation->state_name} {$technicianLocation->zipcode}";

            // Fetch jobs for the technician for the input date
            $jobs = Schedule::where('technician_id', $technician->id)
                ->whereDate('start_date_time', $inputDate)
                ->get(['job_id', 'position', 'is_routes_map', 'job_onmap_reaching_timing']);

            // Fetch best route information
            $routingJob = RoutingJob::where('user_id', $technician->id)->first();

            $technicianData = [
                'technician' => [
                    'id' => $technician->id,
                    'name' => $technician->name,
                    'full_address' => $technicianLocation->full_address,
                    'latitude' => $technicianLocation->latitude,
                    'longitude' => $technicianLocation->longitude,
                ],
                'jobs' => [],
            ];

            // If no jobs found
            if ($jobs->isEmpty()) {
                $technicianData['error'] = 'No jobs found for this technician on the selected date.';
                $response[] = $technicianData;
                continue;
            }

            if ($routingJob && !empty($routingJob->best_route)) {
                $bestRouteJobIds = explode(',', $routingJob->best_route);

                // Filter jobs based on best route job IDs
                $filteredJobs = $jobs->whereIn('job_id', $bestRouteJobIds);

                foreach ($filteredJobs as $job) {
                    $customerIds = JobAssign::where('job_id', $job->job_id)->pluck('customer_id');
                    $customerLocations = CustomerUserAddress::whereIn('user_id', $customerIds)
                        ->get(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

                    foreach ($customerLocations as $customerLocation) {
                        $customerName = User::where('id', $customerLocation->user_id)->value('name');
                        $customerLocation->full_address = "{$customerLocation->address_line1}, {$customerLocation->city}, {$customerLocation->state_name} {$customerLocation->zipcode}";

                        $technicianData['jobs'][] = [
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
                        ];
                    }
                }
            }

            $response[] = $technicianData;
        }

        $responseJson = json_encode($response);

        $tech = User::where('role', 'technician')->where('status', 'active')->get();
        $routingTriggers = RoutingTrigger::all();
        $location = LocationServiceArea::all();

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
            ->join('jobs', 'jobs.id', '=', 'job_assigned.job_id')
            ->join('users', 'users.id', '=', 'jobs.customer_id')
            ->join('users as technician', 'technician.id', '=', 'job_assigned.technician_id')
            ->whereNotNull('jobs.latitude')
            ->whereNotNull('jobs.longitude')
            ->orderBy('job_assigned.pending_number', 'asc');

        if (!empty($inputDate)) {
            $query->whereDate('start_date_time', $inputDate);
        }

        $data = $query->get();

        return view('jobrouting.index', compact('data', 'responseJson', 'tech', 'routingTriggers', 'location'));
    }

    public function jobrouting_filter(Request $request)
    {
        $dateDay = $request->input('dateDay');
        $chooseFrom = $request->input('chooseFrom');
        $chooseTo = $request->input('chooseTo');
        $routing = $request->input('routing');
        $tech_ids = $request->input('technicians', []);

        // Get timezone and calculate date ranges
        $timezone_name = Session::get('timezone_name', 'UTC');
        $currentDate = Carbon::now($timezone_name);
        $startDate = $currentDate->copy()->startOfDay();
        $endDate = $currentDate->copy()->endOfDay();

        // Adjust the date range based on `dateDay` value
        switch ($dateDay) {
            case 'today':
                $endDate = $startDate->copy()->endOfDay();
                break;
            case 'tomorrow':
                $startDate = $currentDate->copy()->addDay()->startOfDay();
                $endDate = $currentDate->copy()->addDay()->endOfDay();
                break;
            case 'nextdays':
                $endDate = $currentDate->copy()->addDays(2)->endOfDay();
                break;
            case 'week':
                $startDate = $currentDate->copy()->startOfDay();
                $endDate = $currentDate->copy()->addDays(6)->endOfDay();
                break;
            case 'chooseDate':
                if ($chooseFrom && $chooseTo) {
                    $startDate = Carbon::parse($chooseFrom, $timezone_name)->startOfDay();
                    $endDate = Carbon::parse($chooseTo, $timezone_name)->endOfDay();
                } else {
                    return response()->json(['success' => false, 'message' => 'Invalid date range.'], 400);
                }
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Invalid dateDay value.'], 400);
        }

        // Fetch active technicians
        if (empty($tech_ids)) {
            $activeTechnicians = User::where('role', 'technician')
                ->where('status', 'active')
                ->get();
        } else {
            // Show only the technicians whose IDs are in the provided list
            $activeTechnicians = User::where('role', 'technician')
                ->where('status', 'active')
                ->whereIn('id', $tech_ids)
                ->get();
        }
        if ($activeTechnicians->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No active technicians found.']);
        }

        $response = [];

        foreach ($activeTechnicians as $technician) {
            $technicianLocation = CustomerUserAddress::where('user_id', $technician->id)
                ->first(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

            if (!$technicianLocation) {
                $response[] = [
                    'technician' => [
                        'id' => $technician->id,
                        'name' => $technician->name,
                        'error' => 'Technician location not found.',
                    ],
                ];
                continue;
            }

            $technicianLocation->full_address = "{$technicianLocation->address_line1}, {$technicianLocation->city}, {$technicianLocation->state_name} {$technicianLocation->zipcode}";

            $jobs = Schedule::where('technician_id', $technician->id)
                ->whereBetween('start_date_time', [$startDate, $endDate])
                ->get();

            $routingJobQuery = RoutingJob::where('user_id', $technician->id)
                ->whereBetween('schedule_date_time', [$startDate, $endDate]);

            if (!empty($tech_ids)) {
                $routingJobQuery->whereIn('user_id', $tech_ids);
            }

            $routingJob = $routingJobQuery->first();

            $routeType = '';

            // Determine the route type based on `routing`
            if ($routingJob) {
                switch ($routing) {
                    case 'bestroute':
                        $routeType = $routingJob->best_route;
                        break;
                    case 'shortestroute':
                        $routeType = $routingJob->short_route;
                        break;
                    case 'customizedroute':
                        $routeType = $routingJob->custom_route; // Fixed typo
                        break;
                    default:
                        $routeType = ''; // No route specified
                }
            }

            $technicianData = [
                'technician' => [
                    'id' => $technician->id,
                    'dateDay' => $dateDay,
                    'color_code' => $technician->color_code,
                    'name' => $technician->name,
                    'full_address' => $technicianLocation->full_address,
                    'latitude' => $technicianLocation->latitude,
                    'longitude' => $technicianLocation->longitude,
                ],
                'jobs' => [],
            ];

            if ($jobs->isEmpty()) {
                $technicianData['error'] = 'No jobs found for this technician in the selected date range.';
                $response[] = $technicianData;
                continue;
            }

            if (!empty($routeType)) {
                $bestRouteJobIds = explode(',', trim($routeType, '[]'));

                $filteredJobs = $jobs->whereIn('job_id', $bestRouteJobIds);

                foreach ($filteredJobs as $job) {
                    $customerIds = JobAssign::where('job_id', $job->job_id)->pluck('customer_id');
                    $customerLocations = CustomerUserAddress::whereIn('user_id', $customerIds)
                        ->get(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

                    foreach ($customerLocations as $customerLocation) {
                        $customerName = User::where('id', $customerLocation->user_id)->value('name');
                        $customerLocation->full_address = "{$customerLocation->address_line1}, {$customerLocation->city}, {$customerLocation->state_name} {$customerLocation->zipcode}";


                        $technicianData['jobs'][] = [
                            'job_id' => $job->job_id,
                            'start_date_time' => $job->start_date_time,
                            'position' => $job->position,
                            'job_title' => DB::table('jobs')
                                ->where('id', $job->job_id)
                                ->value('job_title'),
                            'description' => DB::table('jobs')
                                ->where('id', $job->job_id)
                                ->value('description'),
                            'is_routes_map' => $job->is_routes_map,
                            'job_onmap_reaching_timing' => $job->job_onmap_reaching_timing,
                            'customer' => [
                                'id' => $customerLocation->user_id,
                                'name' => $customerName,
                                'full_address' => $customerLocation->full_address,
                                'latitude' => $customerLocation->latitude,
                                'longitude' => $customerLocation->longitude,
                            ],
                        ];
                    }
                }
            }

            $response[] = $technicianData;
        }

        return response()->json(['success' => true, 'data' => $response]);
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
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }
    private function roundToNearest30Minutes(Carbon $time)
    {
        $minutes = $time->minute;

        // If the time is greater than 10 minutes, round up to the nearest 30 minutes
        if ($minutes > 10) {
            // Add the remaining minutes to reach the next 30-minute interval
            $roundedMinutes = 30 * ceil($minutes / 30);
        } else {
            // If it's 10 minutes or less, round down to the previous 30-minute interval
            $roundedMinutes = $minutes - ($minutes % 30);
        }

        return $time->copy()->setTime($time->hour, $roundedMinutes, 0);
    }


    public function Routesettingstore(Request $request)
    {
        //    dd( $request);
        $timezone_id = Session::get('timezone_id');
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');
        $inputDate = Carbon::now($timezone_name)->format('Y-m-d');
        $tomorrow = Carbon::parse($inputDate)->addDay()->format('Y-m-d');
        $dayAfterTomorrow = Carbon::parse($inputDate)->addDays(2)->format('Y-m-d');
        $technicians = $request->technicians;
        $jobIds = $request->jobIds;
        $callPerDay = $request->number_of_calls;


        $response = [];
        $savedSettings = [];

        if (!empty($jobIds)) {
            $jobs = Schedule::with(['Jobassign', 'Jobassign.userAddress'])
                ->whereIn('job_id', $jobIds)
                ->whereDate('start_date_time', '>=', $inputDate)
                ->orderBy('start_date_time', 'asc')
                ->get();
            $technicians = $jobs->pluck('technician_id')->toArray();

            foreach ($technicians as $technicianId) {
                $technicianLocation = CustomerUserAddress::where('user_id', $technicianId)->first();

                if (!$technicianLocation) {
                    $response[] = [
                        'technician' => [
                            'id' => $technicianId,
                            'error' => 'Technician location not found.',
                        ],
                    ];
                    continue;
                }

                $jobDistances = [];

                if ($request->auto_rerouting == 'on' && !$jobs->isEmpty()) {
                    foreach ($jobs as $job) {
                        $schedule = Schedule::where('job_id', $job->job_id)->first();
                        $assign = JobAssign::where('job_id', $job->job_id)->first();

                        if ($schedule) {
                            $startNxtDate = Carbon::parse($schedule->start_date_time)->addDay();
                            $endNxtDate = Carbon::parse($schedule->end_date_time)->addDay();

                            $schedule->update([
                                'start_date_time' => $startNxtDate,
                                'end_date_time' => $endNxtDate,
                            ]);
                        } else {
                            Log::error('Schedule not found', ['job_id' => $job->job_id]);
                        }

                        if ($assign) {
                            $assign->update([
                                'start_date_time' => $startNxtDate,
                                'end_date_time' => $endNxtDate,
                            ]);
                        } else {
                            Log::error('Job assignment not found', ['job_id' => $job->job_id]);
                        }
                    }
                }


                if ($request->auto_publishing == 'on' && !$jobs->isEmpty()) {
                    foreach ($jobs as $index => $job) {
                        // Retrieve the job
                        $jobpublish = JobModel::find($job->job_id);

                        if ($jobpublish) {

                            // Update the job
                            $jobpublish->is_published = 'yes';
                            $jobpublish->save();

                        } else {
                            Log::error('Job not found', ['job_id' => $job->job_id]);
                        }
                    }
                }

                if ($request->priority_routing == 'on') {

                    
                    $priorityLevels = ['emergency', 'critical', 'high', 'medium', 'low'];

                    $priorityBaseJobs = Schedule::with(['Jobassign', 'Jobassign.userAddress','JobModel'])
                        ->where('technician_id', $technicianId)
                        ->whereDate('start_date_time', '>=', $inputDate)
                        ->orderBy('start_date_time', 'asc')
                        ->get()
                        ->sortBy(function ($job) use ($priorityLevels) {
                            return array_search($job->JobModel->priority, $priorityLevels);
                        });


                    // Extract sorted job IDs
                    $sortedJobIds = $priorityBaseJobs->pluck('job_id')->toArray();
                        // dd($sortedJobIds);

                    $bestRouteJobIds = $sortedJobIds;
                    $shortRouteJobIds = $sortedJobIds;
                    $routingJobs = RoutingJob::where('user_id', $technicianId)->get();

                    $scheduleTimes = [$inputDate, $tomorrow, $dayAfterTomorrow];
                    $existingCount = $routingJobs->count();

                        if ($existingCount > 0) {
                            // Update existing entries
                            foreach ($routingJobs as $index => $routingJob) {
                                $routingJob->schedule_date_time = $scheduleTimes[$index];
                                $routingJob->best_route = json_encode($bestRouteJobIds);
                                $routingJob->short_route = json_encode($shortRouteJobIds);
                                $routingJob->created_by = Auth()->user()->id;
                                $routingJob->updated_by = Auth()->user()->id;
                                $routingJob->save();
                            }

                            // Add new entries if needed
                            if ($existingCount < 3) {
                                $newScheduleTimes = array_slice($scheduleTimes, $existingCount);
                                foreach ($newScheduleTimes as $scheduleTime) {
                                    $routingJob = new RoutingJob(); // Create a new instance
                                    $routingJob->user_id = $technicianId;
                                    $routingJob->schedule_date_time = $scheduleTime;
                                    $routingJob->best_route = json_encode($bestRouteJobIds);
                                    $routingJob->short_route = json_encode($shortRouteJobIds);
                                    $routingJob->created_by = Auth()->user()->id;
                                    $routingJob->updated_by = Auth()->user()->id;
                                    $routingJob->save(); // Save the new instance
                                }
                            }
                        } else {
                            // Create all 3 new entries
                            foreach ($scheduleTimes as $scheduleTime) {
                                $routingJob = new RoutingJob(); // Create a new instance
                                $routingJob->user_id = $technicianId;
                                $routingJob->schedule_date_time = $scheduleTime;
                                $routingJob->best_route = json_encode($bestRouteJobIds);
                                $routingJob->short_route = json_encode($shortRouteJobIds);
                                $routingJob->created_by = Auth()->user()->id;
                                $routingJob->updated_by = Auth()->user()->id;
                                $routingJob->save(); // Save the new instance
                            }
                        }
                    

                }

                if ($request->time_constraints == 'on' && !$jobs->isEmpty()) {

                    $origin = "{$technicianLocation->latitude},{$technicianLocation->longitude}";
                    $previousEndDateTime = null;
                    $bestRouteJobs = [];
                    $shortRouteJobs = [];
                    $dailyJobCount = 0;

                    foreach ($jobs as $index => $job) {
                        if (!$job->Jobassign || !$job->Jobassign->userAddress) {
                            Log::info('Missing Jobassign or userAddress for job.', ['job_id' => $job->job_id]);
                            continue;
                        }

                        $destination = "{$job->Jobassign->userAddress->latitude},{$job->Jobassign->userAddress->longitude}";

                        try {
                            $apiResponse = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                                'origins' => $origin,
                                'destinations' => $destination,
                                'key' => 'AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo',
                            ]);

                            if ($apiResponse->failed()) {
                                Log::error('Distance Matrix API call failed.', ['response' => $apiResponse->body(), 'job_id' => $job->job_id]);
                                continue;
                            }

                            $data = $apiResponse->json();

                            $travelDuration = $data['rows'][0]['elements'][0]['duration']['value'];

                            if ($index === 0) {
                                // Set the first job's end time as the previous end date time
                                $previousEndDateTime = Carbon::parse($job->end_date_time);
                                $dailyJobCount++;
                                $origin = $destination; // Reset origin for subsequent jobs
                                continue;
                            } else {
                                if (!$previousEndDateTime) {
                                    Log::error('Missing previous job end time for technician.', [
                                        'job_id' => $job->job_id,
                                    ]);
                                    continue;
                                }

                                // Calculate the new start time based on previous job's end time and travel duration
                                $previousEndCarbon = Carbon::parse($previousEndDateTime);
                                Log::info('Previous End DateTime:', ['previousEndDateTime' => $previousEndCarbon]);

                                if ($travelDuration) {
                                    $newStartTime = $this->roundToNearest30Minutes(
                                        $previousEndCarbon->addSeconds($travelDuration)
                                    );
                                } else {
                                    Log::error('Travel duration is missing or zero.', ['job_id' => $job->job_id]);
                                    $newStartTime = $this->roundToNearest30Minutes($previousEndCarbon);
                                }

                                Log::info('New Start Time After Travel Duration:', ['newStartTime' => $newStartTime]);

                                // Update the job's start_date_time with the calculated newStartTime
                                $job->start_date_time = $newStartTime;
                                $CurrentDate = Carbon::now($timezone_name)->addDay();
                                $currentDayLower = strtolower($CurrentDate->format('l'));
                                $hours = BusinessHours::where('day', $currentDayLower)->first();

                                // Parse the leave time based on business hours
                                $leaveTime = Carbon::parse($newStartTime->format('Y-m-d') . ' ' . $hours->end_time);

                                // Check if the new start time exceeds working hours or the daily job count limit
                                $maxJobsPerDay = $callPerDay; // Maximum jobs allowed per day
                                $totalJobsScheduled = 0; // Counter for total jobs scheduled

                                while ($dailyJobCount >= $maxJobsPerDay && $totalJobsScheduled < 30) {
                                    // Move to the next day
                                    $nextDay = $newStartTime->copy()->addDay();

                                    // Skip Sundays
                                    while ($nextDay->isSunday()) {
                                        $nextDay->addDay();
                                    }

                                    // Reset daily job count for the new day
                                    $dailyJobCount = 0;

                                    // Update the job's start time to the next day's start time
                                    $job->start_date_time = $nextDay->setTimeFromTimeString($hours->start_time);
                                    $newStartTime = $job->start_date_time;

                                    // Increment the total jobs scheduled
                                    $totalJobsScheduled += $dailyJobCount;
                                }
                                // Recalculate end time
                                $job->end_date_time = $this->roundToNearest30Minutes(
                                    Carbon::parse($job->start_date_time)->addMinutes($job->Jobassign->duration)
                                );

                                // Log the updated times
                                Log::info('Updated Start Date Time:', ['start_date_time' => $job->start_date_time]);
                                Log::info('Updated End Date Time:', ['end_date_time' => $job->end_date_time]);

                                // Save job and assignment
                                $job->save();
                                $assign = JobAssign::where('job_id', $job->job_id)->first();
                                if ($assign) {
                                    $assign->start_date_time = $job->start_date_time;
                                    $assign->end_date_time = $job->end_date_time;
                                    $assign->save();
                                }



                                $dailyJobCount++;
                            }



                            $bestRouteJobs[$job->job_id] = $travelDuration; // Using travel duration for best route sorting
                            $shortRouteJobs[$job->job_id] = $travelDuration; // Shortest distance based on travel duration

                            $origin = $destination; // Reset origin
                            $previousEndDateTime = $job->end_date_time; // Reset previous end time

                        } catch (\Exception $e) {
                            Log::error('Error calling Distance Matrix API for job.', [
                                'job_id' => $job->job_id,
                                'error' => $e->getMessage(),
                            ]);
                            continue;
                        }
                    }

                    $bestRouteJobIds = array_keys($bestRouteJobs);
                    $shortRouteJobIds = array_keys($shortRouteJobs);

                    $routingJobs = RoutingJob::where('user_id', $technicianId)->get();
                    $scheduleTimes = [$inputDate, $tomorrow, $dayAfterTomorrow];
                    $existingCount = $routingJobs->count();

                    if (empty($inputDate) || empty($tomorrow) || empty($dayAfterTomorrow)) {
                        Log::error('One or more schedule dates are empty.');
                        console . log('One or more schedule dates are empty.');
                        console . log($inputDate, $tomorrow, $dayAfterTomorrow);
                        return; // Stop further execution if any date is invalid
                    }

                    if ($existingCount > 0) {
                        // Update existing entries
                        foreach ($routingJobs as $index => $routingJob) {
                            $routingJob->schedule_date_time = $scheduleTimes[$index];
                            $routingJob->best_route = json_encode($bestRouteJobIds);
                            $routingJob->short_route = json_encode($shortRouteJobIds);
                            $routingJob->created_by = Auth()->user()->id;
                            $routingJob->updated_by = Auth()->user()->id;
                            $routingJob->save();
                        }

                        // Add new entries if needed
                        if ($existingCount < 3) {
                            $newScheduleTimes = array_slice($scheduleTimes, $existingCount);
                            foreach ($newScheduleTimes as $scheduleTime) {
                                $routingJob = new RoutingJob(); // Create a new instance
                                $routingJob->user_id = $technicianId;
                                $routingJob->schedule_date_time = $scheduleTime;
                                $routingJob->best_route = json_encode($bestRouteJobIds);
                                $routingJob->short_route = json_encode($shortRouteJobIds);
                                $routingJob->created_by = Auth()->user()->id;
                                $routingJob->updated_by = Auth()->user()->id;
                                $routingJob->save(); // Save the new instance
                            }
                        }
                    } else {
                        // Create all 3 new entries
                        foreach ($scheduleTimes as $scheduleTime) {
                            $routingJob = new RoutingJob(); // Create a new instance
                            $routingJob->user_id = $technicianId;
                            $routingJob->schedule_date_time = $scheduleTime;
                            $routingJob->best_route = json_encode($bestRouteJobIds);
                            $routingJob->short_route = json_encode($shortRouteJobIds);
                            $routingJob->created_by = Auth()->user()->id;
                            $routingJob->updated_by = Auth()->user()->id;
                            $routingJob->save(); // Save the new instance
                        }
                    }


                }

                $savedSettings[] = [
                    'technician_id' => $technicianId,
                    'job_distances' => $jobDistances,
                ];
            }

        } else {
            if (empty($technicians)) {
                return view('jobrouting.technicians_jobs_map')->with('technicians', []);
            }

            foreach ($technicians as $technicianId) {
                $technicianLocation = CustomerUserAddress::where('user_id', $technicianId)->first();

                if (!$technicianLocation) {
                    $response[] = [
                        'technician' => [
                            'id' => $technicianId,
                            'error' => 'Technician location not found.',
                        ],
                    ];
                    continue;
                }



                $jobs = Schedule::with(['Jobassign', 'Jobassign.userAddress'])
                    ->where('technician_id', $technicianId)
                    ->whereDate('start_date_time', '>=', $inputDate)
                    ->orderBy('start_date_time', 'asc')
                    ->get();

                $jobDistances = [];

                if ($request->auto_rerouting == 'on' && !$jobs->isEmpty()) {
                    foreach ($jobs as $job) {
                        $schedule = Schedule::where('job_id', $job->job_id)->first();
                        $assign = JobAssign::where('job_id', $job->job_id)->first();

                        if ($schedule) {
                            $startNxtDate = Carbon::parse($schedule->start_date_time)->addDay();
                            $endNxtDate = Carbon::parse($schedule->end_date_time)->addDay();

                            $schedule->update([
                                'start_date_time' => $startNxtDate,
                                'end_date_time' => $endNxtDate,
                            ]);
                        } else {
                            Log::error('Schedule not found', ['job_id' => $job->job_id]);
                        }

                        if ($assign) {
                            $assign->update([
                                'start_date_time' => $startNxtDate,
                                'end_date_time' => $endNxtDate,
                            ]);
                        } else {
                            Log::error('Job assignment not found', ['job_id' => $job->job_id]);
                        }
                    }
                }

                if ($request->auto_publishing == 'on' && !$jobs->isEmpty()) {
                    foreach ($jobs as $index => $job) {
                        // Retrieve the job
                        $jobpublish = JobModel::find($job->job_id);

                        if ($jobpublish) {

                            // Update the job
                            $jobpublish->is_published = 'yes';
                            $jobpublish->save();

                        } else {
                            Log::error('Job not found', ['job_id' => $job->job_id]);
                        }
                    }
                }

                if ($request->priority_routing == 'on') {

                    
                    $priorityLevels = ['emergency', 'critical', 'high', 'medium', 'low'];

                    $priorityBaseJobs = Schedule::with(['Jobassign', 'Jobassign.userAddress','JobModel'])
                        ->where('technician_id', $technicianId)
                        ->whereDate('start_date_time', '>=', $inputDate)
                        ->orderBy('start_date_time', 'asc')
                        ->get()
                        ->sortBy(function ($job) use ($priorityLevels) {
                            return array_search($job->JobModel->priority, $priorityLevels);
                        });


                    // Extract sorted job IDs
                    $sortedJobIds = $priorityBaseJobs->pluck('job_id')->toArray();
                        // dd($sortedJobIds);

                    $bestRouteJobIds = $sortedJobIds;
                    $shortRouteJobIds = $sortedJobIds;
                    $routingJobs = RoutingJob::where('user_id', $technicianId)->get();

                    $scheduleTimes = [$inputDate, $tomorrow, $dayAfterTomorrow];
                    $existingCount = $routingJobs->count();

                        if ($existingCount > 0) {
                            // Update existing entries
                            foreach ($routingJobs as $index => $routingJob) {
                                $routingJob->schedule_date_time = $scheduleTimes[$index];
                                $routingJob->best_route = json_encode($bestRouteJobIds);
                                $routingJob->short_route = json_encode($shortRouteJobIds);
                                $routingJob->created_by = Auth()->user()->id;
                                $routingJob->updated_by = Auth()->user()->id;
                                $routingJob->save();
                            }

                            // Add new entries if needed
                            if ($existingCount < 3) {
                                $newScheduleTimes = array_slice($scheduleTimes, $existingCount);
                                foreach ($newScheduleTimes as $scheduleTime) {
                                    $routingJob = new RoutingJob(); // Create a new instance
                                    $routingJob->user_id = $technicianId;
                                    $routingJob->schedule_date_time = $scheduleTime;
                                    $routingJob->best_route = json_encode($bestRouteJobIds);
                                    $routingJob->short_route = json_encode($shortRouteJobIds);
                                    $routingJob->created_by = Auth()->user()->id;
                                    $routingJob->updated_by = Auth()->user()->id;
                                    $routingJob->save(); // Save the new instance
                                }
                            }
                        } else {
                            // Create all 3 new entries
                            foreach ($scheduleTimes as $scheduleTime) {
                                $routingJob = new RoutingJob(); // Create a new instance
                                $routingJob->user_id = $technicianId;
                                $routingJob->schedule_date_time = $scheduleTime;
                                $routingJob->best_route = json_encode($bestRouteJobIds);
                                $routingJob->short_route = json_encode($shortRouteJobIds);
                                $routingJob->created_by = Auth()->user()->id;
                                $routingJob->updated_by = Auth()->user()->id;
                                $routingJob->save(); // Save the new instance
                            }
                        }
                    

                }

                if ($request->time_constraints == 'on' && !$jobs->isEmpty()) {
                    $origin = "{$technicianLocation->latitude},{$technicianLocation->longitude}";
                    $previousEndDateTime = null;
                    $bestRouteJobs = [];
                    $shortRouteJobs = [];
                    $dailyJobCount = 0;

                    foreach ($jobs as $index => $job) {
                        if (!$job->Jobassign || !$job->Jobassign->userAddress) {
                            Log::info('Missing Jobassign or userAddress for job.', ['job_id' => $job->job_id]);
                            continue;
                        }

                        $destination = "{$job->Jobassign->userAddress->latitude},{$job->Jobassign->userAddress->longitude}";

                        try {
                            $apiResponse = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                                'origins' => $origin,
                                'destinations' => $destination,
                                'key' => 'AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo',
                            ]);

                            if ($apiResponse->failed()) {
                                Log::error('Distance Matrix API call failed.', ['response' => $apiResponse->body(), 'job_id' => $job->job_id]);
                                continue;
                            }

                            $data = $apiResponse->json();

                            $travelDuration = $data['rows'][0]['elements'][0]['duration']['value'];

                            if ($index === 0) {
                                // Set the first job's end time as the previous end date time
                                $previousEndDateTime = Carbon::parse($job->end_date_time);
                                $dailyJobCount++;
                                $origin = $destination; // Reset origin for subsequent jobs
                                continue;
                            } else {
                                if (!$previousEndDateTime) {
                                    Log::error('Missing previous job end time for technician.', [
                                        'job_id' => $job->job_id,
                                    ]);
                                    continue;
                                }

                                // Calculate the new start time based on previous job's end time and travel duration
                                $previousEndCarbon = Carbon::parse($previousEndDateTime);
                                Log::info('Previous End DateTime:', ['previousEndDateTime' => $previousEndCarbon]);

                                if ($travelDuration) {
                                    $newStartTime = $this->roundToNearest30Minutes(
                                        $previousEndCarbon->addSeconds($travelDuration)
                                    );
                                } else {
                                    Log::error('Travel duration is missing or zero.', ['job_id' => $job->job_id]);
                                    $newStartTime = $this->roundToNearest30Minutes($previousEndCarbon);
                                }

                                Log::info('New Start Time After Travel Duration:', ['newStartTime' => $newStartTime]);

                                // Update the job's start_date_time with the calculated newStartTime
                                $job->start_date_time = $newStartTime;
                                $CurrentDate = Carbon::now($timezone_name)->addDay();
                                $currentDayLower = strtolower($CurrentDate->format('l'));
                                $hours = BusinessHours::where('day', $currentDayLower)->first();

                                // Parse the leave time based on business hours
                                $leaveTime = Carbon::parse($newStartTime->format('Y-m-d') . ' ' . $hours->end_time);

                                // Check if the new start time exceeds working hours or the daily job count limit
                                $maxJobsPerDay = $callPerDay; // Maximum jobs allowed per day
                                $totalJobsScheduled = 0; // Counter for total jobs scheduled

                                while ($dailyJobCount >= $maxJobsPerDay && $totalJobsScheduled < 30) {
                                    // Move to the next day
                                    $nextDay = $newStartTime->copy()->addDay();

                                    // Skip Sundays
                                    while ($nextDay->isSunday()) {
                                        $nextDay->addDay();
                                    }

                                    // Reset daily job count for the new day
                                    $dailyJobCount = 0;

                                    // Update the job's start time to the next day's start time
                                    $job->start_date_time = $nextDay->setTimeFromTimeString($hours->start_time);
                                    $newStartTime = $job->start_date_time;

                                    // Increment the total jobs scheduled
                                    $totalJobsScheduled += $dailyJobCount;
                                }
                                // Recalculate end time
                                $job->end_date_time = $this->roundToNearest30Minutes(
                                    Carbon::parse($job->start_date_time)->addMinutes($job->Jobassign->duration)
                                );

                                // Log the updated times
                                Log::info('Updated Start Date Time:', ['start_date_time' => $job->start_date_time]);
                                Log::info('Updated End Date Time:', ['end_date_time' => $job->end_date_time]);

                                // Save job and assignment
                                $job->save();
                                $assign = JobAssign::where('job_id', $job->job_id)->first();
                                if ($assign) {
                                    $assign->start_date_time = $job->start_date_time;
                                    $assign->end_date_time = $job->end_date_time;
                                    $assign->save();
                                }

                                $dailyJobCount++;
                            }



                            $bestRouteJobs[$job->job_id] = $travelDuration; // Using travel duration for best route sorting
                            $shortRouteJobs[$job->job_id] = $travelDuration; // Shortest distance based on travel duration

                            $origin = $destination; // Reset origin
                            $previousEndDateTime = $job->end_date_time; // Reset previous end time

                        } catch (\Exception $e) {
                            Log::error('Error calling Distance Matrix API for job.', [
                                'job_id' => $job->job_id,
                                'error' => $e->getMessage(),
                            ]);
                            continue;
                        }
                    }

                    $bestRouteJobIds = array_keys($bestRouteJobs);
                    $shortRouteJobIds = array_keys($shortRouteJobs);

                    $routingJobs = RoutingJob::where('user_id', $technicianId)->get();
                    $scheduleTimes = [$inputDate, $tomorrow, $dayAfterTomorrow];
                    $existingCount = $routingJobs->count();

                    if (empty($inputDate) || empty($tomorrow) || empty($dayAfterTomorrow)) {
                        Log::error('One or more schedule dates are empty.');
                        console . log('One or more schedule dates are empty.');
                        console . log($inputDate, $tomorrow, $dayAfterTomorrow);
                        return; // Stop further execution if any date is invalid
                    }

                    if ($existingCount > 0) {
                        // Update existing entries
                        foreach ($routingJobs as $index => $routingJob) {
                            $routingJob->schedule_date_time = $scheduleTimes[$index];
                            $routingJob->best_route = json_encode($bestRouteJobIds);
                            $routingJob->short_route = json_encode($shortRouteJobIds);
                            $routingJob->created_by = Auth()->user()->id;
                            $routingJob->updated_by = Auth()->user()->id;
                            $routingJob->save();
                        }

                        // Add new entries if needed
                        if ($existingCount < 3) {
                            $newScheduleTimes = array_slice($scheduleTimes, $existingCount);
                            foreach ($newScheduleTimes as $scheduleTime) {
                                $routingJob = new RoutingJob(); // Create a new instance
                                $routingJob->user_id = $technicianId;
                                $routingJob->schedule_date_time = $scheduleTime;
                                $routingJob->best_route = json_encode($bestRouteJobIds);
                                $routingJob->short_route = json_encode($shortRouteJobIds);
                                $routingJob->created_by = Auth()->user()->id;
                                $routingJob->updated_by = Auth()->user()->id;
                                $routingJob->save(); // Save the new instance
                            }
                        }
                    } else {
                        // Create all 3 new entries
                        foreach ($scheduleTimes as $scheduleTime) {
                            $routingJob = new RoutingJob(); // Create a new instance
                            $routingJob->user_id = $technicianId;
                            $routingJob->schedule_date_time = $scheduleTime;
                            $routingJob->best_route = json_encode($bestRouteJobIds);
                            $routingJob->short_route = json_encode($shortRouteJobIds);
                            $routingJob->created_by = Auth()->user()->id;
                            $routingJob->updated_by = Auth()->user()->id;
                            $routingJob->save(); // Save the new instance
                        }
                    }


                }

                $savedSettings[] = [
                    'technician_id' => $technicianId,
                    'job_distances' => $jobDistances,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Routing settings saved successfully for all technicians!',
            'savedSettings' => $savedSettings,
            'response' => $response,
        ]);
    }






}
