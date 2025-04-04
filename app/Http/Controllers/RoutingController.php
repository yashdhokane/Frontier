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
use App\Models\Event;
use Illuminate\Support\Facades\Session;
use Storage;
use App\Models\CustomerUserAddress;
use App\Models\Schedule;
use App\Models\JobAssign;
use App\Models\JobRoutingCron;
use Illuminate\Support\Facades\Log;

use App\Models\TechnicianJobsSchedulesOnMap;
use App\Models\RoutingSetting;
use App\Models\RoutingJOb;
use App\Models\RoutingSettingOption;
use Illuminate\Support\Facades\Auth;
use App\Mail\PublishMailTech;
use App\Mail\PublishMailCustomer;
use Illuminate\Support\Facades\Mail;

use Twilio\TwiML\MessagingResponse;

class RoutingController extends Controller
{
    public function index(Request $request)
    {
        $timezone_name = Session::get('timezone_name', 'UTC');
        $inputDate = Carbon::now($timezone_name)->format('Y-m-d');
        $currentDate = Carbon::now($timezone_name);
        $startDate = $currentDate->copy()->startOfDay();

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
        $routingJob = RoutingJob::whereDate('schedule_date_time', $startDate)->get();
        // dd($routingJob);

        return view('jobrouting.index', compact('data', 'responseJson', 'tech', 'routingTriggers', 'location', 'routingJob'));
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

            if ($routingJob) {
                switch ($routing) {
                    case 'bestroute':
                        $routeJobIds = json_decode($routingJob->best_route, true) ?? [];
                        break;
                    case 'shortestroute':
                        $routeJobIds = json_decode($routingJob->short_route, true) ?? [];
                        break;
                    case 'customizedroute':
                        $routeJobIds = json_decode($routingJob->custom_route, true) ?? [];
                        break;
                    default:
                        $routeJobIds = [];
                }
            } else {
                $routeJobIds = [];
            }

            // Default array of job IDs from $jobs
            $defaultJobIds = $jobs->pluck('job_id')->toArray();

            // Merge the routeJobIds with defaultJobIds, preserving order
            $mergedJobIds = array_unique(array_merge($routeJobIds, $defaultJobIds));

            // Convert merged array to the required string format
            $routeType = '[' . implode(',', $mergedJobIds) . ']';

            // dd($mergedJobIds);

            $technicianData = [
                'technician' => [
                    'id' => $technician->id,
                    'dateDay' => $dateDay,
                    'routing' => $routing,
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

                // $filteredJobs = $jobs->whereIn('job_id', $bestRouteJobIds);
                $filteredJobs = $jobs->whereIn('job_id', $bestRouteJobIds)->sortBy(function ($job) use ($bestRouteJobIds) {
                    return array_search($job->job_id, $bestRouteJobIds);
                });


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
        // dd($request);
        $timezone_id = Session::get('timezone_id');
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');
        $inputDate = Carbon::now($timezone_name)->format('Y-m-d');
        $tomorrow = Carbon::parse($inputDate)->addDay()->format('Y-m-d');
        $dayAfterTomorrow = Carbon::parse($inputDate)->addDays(2)->format('Y-m-d');
        $technicians = $request->technicians;
        $jobIds = $request->jobIds;
        $callPerDay = $request->number_of_calls;
        $currentDate1 = \Carbon\Carbon::now($timezone_name);
        $cronDate = \Carbon\Carbon::now($timezone_name)->format('Y-m-d H:i:s');


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

                if ($request->auto_publishing == 'on' && !$jobs->isEmpty()) {

                    foreach ($jobs as $index => $job) {
                        // Retrieve the job
                        $jobpublish = JobModel::find($job->job_id);

                        if ($jobpublish) {
                            $customer = User::find($jobpublish->customer_id); // Get customer
                            $tech = User::find($jobpublish->technician_id);   // Get technician
                            $jobSchedule = Schedule::where('job_id', $job->job_id)->first(); // Get schedule
                            $customerAddress_Sms = CustomerUserAddress::where('user_id', $jobpublish->customer_id)->first();

                            // Prepare mail data
                            $mailData = [
                                'job' => $jobpublish,
                                'customer' => $customer,
                                'technician' => $tech,
                                'schedule' => $jobSchedule,
                            ];

                            // Update the job
                            $jobpublish->is_published = 'yes';
                            $jobpublish->save();

                            if ($tech && $customer) {

                                $scheduleDate = Carbon::parse($jobSchedule->start_date_time)->format('F j, Y'); // Example: March 1, 2025
                                $scheduleTime = Carbon::parse($jobSchedule->start_date_time)->format('h:i A');

                                // Message for Customer
                                $customerMessage = "We would like to confirm that the {$jobpublish->job_title} is scheduled for:\n\n";
                                $customerMessage .= "Date: {$scheduleDate}\n";
                                $customerMessage .= "Time: {$scheduleTime}\n\n";
                                $customerMessage .= "{$tech->name}, our technician, will be there to provide the service.";

                                // $customerPhone = $customer->mobile; // Assuming 'phone' field stores customer's number
                                // $customerPhone = '+14155713129'; 
                                $customerPhone = '+918830711935';
                                // $customerPhone = '+917030467187'; 
                                try {
                                    $responseCustomer = app('SmsService')->sendSms($customerMessage, $customerPhone);
                                } catch (\Exception $e) {
                                    \Log::error("Error sending SMS to Customer: " . $e->getMessage());
                                }


                                // Message for Technician
                                $techMessage = "We would like to confirm that the {$jobpublish->job_title} is scheduled for:\n\n";
                                $techMessage .= "Date: {$scheduleDate}\n";
                                $techMessage .= "Time: {$scheduleTime}\n";
                                $techMessage .= "Customer Name: {$customer->name}\n";
                                $techMessage .= "Address: {$customerAddress_Sms->address_line1},{$customerAddress_Sms->city},{$customerAddress_Sms->state_name},{$customerAddress_Sms->zipcode}";

                                // $techPhone = $tech->mobile; // Assuming 'phone' field stores technician's number
                                // $techPhone = '+14155713129'; 
                                $techPhone = '+918830711935';
                                // $techPhone = '+917030467187'; 
                                sleep(2);
                                try {
                                    $responseTech = app('SmsService')->sendSms($techMessage, $techPhone);
                                } catch (\Exception $e) {
                                    \Log::error("Error sending SMS to Technician: " . $e->getMessage());
                                }



                            }


                            $recipant = 'thesachinraut@gmail.com';

                            // Send mail 'bawanesumit01@gmail.com'
                            Mail::to($recipant)->send(new PublishMailTech($mailData));
                            Mail::to($recipant)->send(new PublishMailCustomer($mailData));
                        } else {
                            Log::error('Job not found', ['job_id' => $job->job_id]);
                        }
                    }
                }

                if ($request->priority_routing == 'on') {


                    $priorityLevels = ['emergency', 'critical', 'high', 'medium', 'low'];

                    $priorityBaseJobs = Schedule::with(['Jobassign', 'Jobassign.userAddress', 'JobModel'])
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
                            $routingJob->cron_route_time = $request->auto_route_time;
                            $routingJob->cron_re_route_time = $request->auto_rerouting_time;
                            $routingJob->cron_publish_time = $request->auto_publishing_time;
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
                                $routingJob->cron_route_time = $request->auto_route_time;
                                $routingJob->cron_re_route_time = $request->auto_rerouting_time;
                                $routingJob->cron_publish_time = $request->auto_publishing_time;
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
                            $routingJob->cron_route_time = $request->auto_route_time;
                            $routingJob->cron_re_route_time = $request->auto_rerouting_time;
                            $routingJob->cron_publish_time = $request->auto_publishing_time;
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

                                if ($travelDuration) {
                                    $newStartTime = $this->roundToNearest30Minutes(
                                        $previousEndCarbon->addSeconds($travelDuration)
                                    );
                                } else {
                                    Log::error('Travel duration is missing or zero.', ['job_id' => $job->job_id]);
                                    $newStartTime = $this->roundToNearest30Minutes($previousEndCarbon);
                                }


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

                                // Save job and assignment
                                $job->save();
                                $assign = JobAssign::where('assign_status', 'active')->where('job_id', $job->job_id)->first();
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
                    $customRouteJobIds = array_keys($bestRouteJobs);

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
                            $routingJob->custom_route = json_encode($customRouteJobIds);
                            $routingJob->created_by = Auth()->user()->id;
                            $routingJob->updated_by = Auth()->user()->id;
                            $routingJob->cron_route_time = $request->auto_route_time;
                            $routingJob->cron_re_route_time = $request->auto_rerouting_time;
                            $routingJob->cron_publish_time = $request->auto_publishing_time;
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
                                $routingJob->custom_route = json_encode($customRouteJobIds);
                                $routingJob->created_by = Auth()->user()->id;
                                $routingJob->updated_by = Auth()->user()->id;
                                $routingJob->cron_route_time = $request->auto_route_time;
                                $routingJob->cron_re_route_time = $request->auto_rerouting_time;
                                $routingJob->cron_publish_time = $request->auto_publishing_time;
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
                            $routingJob->custom_route = json_encode($customRouteJobIds);
                            $routingJob->created_by = Auth()->user()->id;
                            $routingJob->updated_by = Auth()->user()->id;
                            $routingJob->cron_route_time = $request->auto_route_time;
                            $routingJob->cron_re_route_time = $request->auto_rerouting_time;
                            $routingJob->cron_publish_time = $request->auto_publishing_time;
                            $routingJob->save(); // Save the new instance
                        }
                    }


                }

                if ($request->auto_rerouting == 'on' && !$jobs->isEmpty()) {
                    $date = Carbon::parse($inputDate);

                    foreach ($jobs as $job) {
                        $checkEvent = Schedule::whereDate('start_date_time', $date)
                            ->where('technician_id', $technicianId)
                            ->first();

                        // Ensure that the event exists and is of type 'event'
                        if ($checkEvent && $checkEvent->schedule_type == 'event') {
                            $schedule = Schedule::where('job_id', $job->job_id)->first();
                            $assign = JobAssign::where('job_id', $job->job_id)->first();

                            if ($schedule) {
                                // Calculate new start and end dates
                                $startNxtDate = Carbon::parse($schedule->start_date_time)->addDay();
                                $endNxtDate = Carbon::parse($schedule->end_date_time)->addDay();

                                // Update schedule dates
                                $schedule->update([
                                    'start_date_time' => $startNxtDate,
                                    'end_date_time' => $endNxtDate,
                                ]);
                            } else {
                                Log::error('Schedule not found for job_id: ' . $job->job_id);
                            }

                            if ($assign) {
                                // Update assignment dates
                                $assign->update([
                                    'start_date_time' => $startNxtDate,
                                    'end_date_time' => $endNxtDate,
                                ]);
                            } else {
                                Log::error('Job assignment not found for job_id: ' . $job->job_id);
                            }
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


                if ($request->publish_jobs == 'on' && $request->p_open_job == 'on') {

                    $jobs = Schedule::with(['Jobassign', 'Jobassign.userAddress', 'JobModel'])
                        ->where('technician_id', $technicianId)
                        ->whereHas('JobModel', function ($query) {
                            $query->where('status', 'open');
                        })
                        ->orderBy('start_date_time', 'asc')
                        ->get();



                } else if ($request->publish_jobs == 'on') {

                    $jobs = Schedule::with(['Jobassign', 'Jobassign.userAddress', 'JobModel'])
                        ->where('technician_id', $technicianId)
                        ->whereDate('start_date_time', '>=', $inputDate)
                        ->whereHas('JobModel', function ($query) {
                            $query->where('status', 'open');
                        })
                        ->orderBy('start_date_time', 'asc')
                        ->get();

                } else if ($request->p_open_job == 'on') {

                    $jobs = Schedule::with(['Jobassign', 'Jobassign.userAddress', 'JobModel'])
                        ->where('technician_id', $technicianId)
                        ->whereHas('JobModel', function ($query) {
                            $query->where('status', 'open');
                        })
                        ->orderBy('start_date_time', 'asc')
                        ->get();

                } else {

                    $jobs = Schedule::with(['Jobassign', 'Jobassign.userAddress', 'JobModel'])
                        ->where('technician_id', $technicianId)
                        ->whereDate('start_date_time', '>=', $inputDate)
                        ->whereHas('JobModel', function ($query) {
                            $query->where('is_published', 'no');
                        })
                        ->orderBy('start_date_time', 'asc')
                        ->get();

                }



                $jobDistances = [];

                if ($request->auto_publishing == 'on' && !$jobs->isEmpty()) {
                    foreach ($jobs as $index => $job) {
                        // Retrieve the job
                        $jobpublish = JobModel::find($job->job_id);

                        if ($jobpublish) {
                            $customer = User::find($jobpublish->customer_id); // Get customer
                            $tech = User::find($jobpublish->technician_id);   // Get technician
                            $jobSchedule = Schedule::where('job_id', $job->job_id)->first(); // Get schedule
                            $customerAddress_Sms = CustomerUserAddress::where('user_id', $jobpublish->customer_id)->first();


                            $scheduleDate = Carbon::parse($jobSchedule->start_date_time)->format('F j, Y'); // Example: March 1, 2025
                            $scheduleTime = Carbon::parse($jobSchedule->start_date_time)->format('h:i A');

                            // Prepare mail data
                            $mailData = [
                                'job' => $jobpublish,
                                'customer' => $customer,
                                'technician' => $tech,
                                'schedule' => $jobSchedule,
                            ];

                            // Update the job
                            $jobpublish->is_published = 'yes';
                            $jobpublish->save();

                            if ($tech && $customer) {
                                // Message for Technician
                                $techMessage = "We would like to confirm that the {$jobpublish->job_title} is scheduled for:\n\n";
                                $techMessage .= "Date: {$scheduleDate}\n";
                                $techMessage .= "Time: {$scheduleTime}\n";
                                $techMessage .= "Customer Name: {$customer->name}\n";
                                $techMessage .= "Address: {$customerAddress_Sms->address_line1},{$customerAddress_Sms->city},{$customerAddress_Sms->state_name},{$customerAddress_Sms->zipcode}";

                                // $techPhone = $tech->mobile; // Assuming 'phone' field stores technician's number
                                $techPhone = '+14155713129';
                                //$techPhone = '+918830711935'; 
                                // app('SmsService')->sendSms($techMessage, $techPhone);

                                // Message for Customer
                                $customerMessage = "We would like to confirm that the {$jobpublish->job_title} is scheduled for:\n\n";
                                $customerMessage .= "Date: {$scheduleDate}\n";
                                $customerMessage .= "Time: {$scheduleTime}\n\n";
                                $customerMessage .= "{$tech->name}, our technician, will be there to provide the service.";
                                // $customerPhone = $customer->mobile; // Assuming 'phone' field stores customer's number
                                $customerPhone = '+918830711935';
                                // app('SmsService')->sendSms($customerMessage, $customerPhone);
                            }


                            $recipant = 'thesachinraut@gmail.com';

                            // Send mail 'bawanesumit01@gmail.com'
                            // Mail::to($recipant)->send(new PublishMailTech($mailData));
                            // Mail::to($recipant)->send(new PublishMailCustomer($mailData));
                        } else {
                            Log::error('Job not found', ['job_id' => $job->job_id]);
                        }
                    }
                }


                if ($request->priority_routing == 'on') {


                    $priorityLevels = ['emergency', 'critical', 'high', 'medium', 'low'];

                    $priorityBaseJobs = Schedule::with(['Jobassign', 'Jobassign.userAddress', 'JobModel'])
                        ->where('technician_id', $technicianId)
                        ->whereDate('start_date_time', '>=', $inputDate)
                        ->orderBy('start_date_time', 'asc')
                        ->get()
                        ->filter(function ($job) {
                            return $job->JobModel && $job->JobModel->priority !== null; // Exclude null priority
                        })
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
                            $routingJob->cron_route_time = $request->auto_route_time;
                            $routingJob->cron_re_route_time = $request->auto_rerouting_time;
                            $routingJob->cron_publish_time = $request->auto_publishing_time;
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
                                $routingJob->cron_route_time = $request->auto_route_time;
                                $routingJob->cron_re_route_time = $request->auto_rerouting_time;
                                $routingJob->cron_publish_time = $request->auto_publishing_time;
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
                            $routingJob->cron_route_time = $request->auto_route_time;
                            $routingJob->cron_re_route_time = $request->auto_rerouting_time;
                            $routingJob->cron_publish_time = $request->auto_publishing_time;
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
                    $currentDateTime = Carbon::now($timezone_name);
                    $techEvents = Event::where('technician_id', $technicianId)->get();

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


                                // Check if the first job's end time has passed
                                if (Carbon::parse($job->end_date_time)->lessThan($currentDateTime)) {
                                    // Move to next available business day
                                    $nextBusinessDay = Carbon::now($timezone_name);
                                    $currentDayLower = strtolower($nextBusinessDay->format('l'));
                                    $hours = BusinessHours::where('day', $currentDayLower)->first();

                                    if ($hours) {
                                        $job->start_date_time = $currentDateTime->setTimeFromTimeString($hours->start_time);
                                        $job->end_date_time = Carbon::parse($job->start_date_time)->addMinutes($job->Jobassign->duration);
                                        $job->save();

                                        $assign = JobAssign::where('assign_status', 'active')->where('job_id', $job->job_id)->first();
                                        if ($assign) {
                                            $assign->start_date_time = $job->start_date_time;
                                            $assign->end_date_time = $job->end_date_time;
                                            $assign->save();
                                        }
                                    }
                                }

                                $checkDate = Carbon::parse($job->start_date_time); // ✅ Convert to Carbon instance

                                $overlappingEvent = $techEvents->first(function ($event) use ($checkDate) {
                                    $eventStart = Carbon::parse($event->start_date_time); // Parse to Carbon
                                    $eventEnd = Carbon::parse($event->end_date_time);     // Parse to Carbon

                                    // ✅ Check if checkDate is between event start and end times
                                    return $checkDate->between($eventStart, $eventEnd);
                                });

                                if ($overlappingEvent && $overlappingEvent->count() > 0) {
                                    if ($overlappingEvent->event_type === 'full') {
                                        $nextDay = $currentDateTime->copy()->addDay();
                                        $nextDayLower = strtolower($nextDay->format('l'));
                                        $hours = BusinessHours::where('day', $nextDayLower)->first();

                                        if ($hours) {
                                            // ✅ Update the start and end times based on the next available business day
                                            $job->start_date_time = $nextDay->setTimeFromTimeString($hours->start_time);
                                            $job->end_date_time = Carbon::parse($job->start_date_time)->addMinutes($job->Jobassign->duration);
                                            $job->save();

                                            $assign = JobAssign::where('assign_status', 'active')->where('job_id', $job->job_id)->first();
                                            if ($assign) {
                                                $assign->start_date_time = $job->start_date_time;
                                                $assign->end_date_time = $job->end_date_time;
                                                $assign->save();
                                            }

                                        }
                                    } elseif ($overlappingEvent->event_type === 'partial') {
                                        // ✅ Move to the time after the partial block
                                        $partialEnd = Carbon::parse($overlappingEvent->end_date_time)->addMinutes(30);
                                        if ($currentDateTime->lessThan($partialEnd)) {
                                            $job->start_date_time = $partialEnd;
                                            $job->end_date_time = Carbon::parse($job->start_date_time)->addMinutes($job->Jobassign->duration);
                                            $job->save();

                                            $assign = JobAssign::where('assign_status', 'active')->where('job_id', $job->job_id)->first();
                                            if ($assign) {
                                                $assign->start_date_time = $job->start_date_time;
                                                $assign->end_date_time = $job->end_date_time;
                                                $assign->save();
                                            }
                                        }
                                    }

                                }

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

                                if ($travelDuration) {
                                    $newStartTime = $this->roundToNearest30Minutes(
                                        $previousEndCarbon->addSeconds($travelDuration)
                                    );
                                } else {
                                    Log::error('Travel duration is missing or zero.', ['job_id' => $job->job_id]);
                                    $newStartTime = $this->roundToNearest30Minutes($previousEndCarbon);
                                }

                                // Check for overlapping events after calculating the new start time
                                $overlappingEvent = $techEvents->first(function ($event) use ($newStartTime) {
                                    $eventStart = Carbon::parse($event->start_date_time);
                                    $eventEnd = Carbon::parse($event->end_date_time);
                                    return $newStartTime->between($eventStart, $eventEnd);
                                });

                                if ($overlappingEvent && $overlappingEvent->count() > 0) {
                                    if ($overlappingEvent->event_type === 'full') {
                                        $nextDay = $newStartTime->copy()->addDay();
                                        $nextDayLower = strtolower($nextDay->format('l'));
                                        $hours = BusinessHours::where('day', $nextDayLower)->first();

                                        if ($hours) {
                                            $newStartTime = $nextDay->setTimeFromTimeString($hours->start_time);
                                        }
                                    } elseif ($overlappingEvent->event_type === 'partial') {
                                        $partialEnd = Carbon::parse($overlappingEvent->end_date_time)->addMinutes(30);
                                        if ($newStartTime->lessThan($partialEnd)) {
                                            $newStartTime = $partialEnd;
                                        }
                                    }
                                }


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

                                // Save job and assignment
                                $job->save();
                                $assign = JobAssign::where('assign_status', 'active')->where('job_id', $job->job_id)->first();
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
                    $customRouteJobIds = array_keys($bestRouteJobs);

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
                            $routingJob->custom_route = json_encode($customRouteJobIds);
                            $routingJob->created_by = Auth()->user()->id;
                            $routingJob->updated_by = Auth()->user()->id;
                            $routingJob->cron_route_time = $request->auto_route_time;
                            $routingJob->cron_re_route_time = $request->auto_rerouting_time;
                            $routingJob->cron_publish_time = $request->auto_publishing_time;
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
                                $routingJob->custom_route = json_encode($customRouteJobIds);
                                $routingJob->created_by = Auth()->user()->id;
                                $routingJob->updated_by = Auth()->user()->id;
                                $routingJob->cron_route_time = $request->auto_route_time;
                                $routingJob->cron_re_route_time = $request->auto_rerouting_time;
                                $routingJob->cron_publish_time = $request->auto_publishing_time;
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
                            $routingJob->custom_route = json_encode($customRouteJobIds);
                            $routingJob->created_by = Auth()->user()->id;
                            $routingJob->updated_by = Auth()->user()->id;
                            $routingJob->cron_route_time = $request->auto_route_time;
                            $routingJob->cron_re_route_time = $request->auto_rerouting_time;
                            $routingJob->cron_publish_time = $request->auto_publishing_time;
                            $routingJob->save(); // Save the new instance
                        }
                    }


                }


                if ($request->auto_rerouting == 'on' && !$jobs->isEmpty()) {
                    $date = Carbon::parse($inputDate);

                    foreach ($jobs as $job) {
                        $checkEvent = Schedule::whereDate('start_date_time', $date)
                            ->where('technician_id', $technicianId)
                            ->first();

                        // Ensure that the event exists and is of type 'event'
                        if ($checkEvent && $checkEvent->schedule_type == 'event') {
                            $schedule = Schedule::where('job_id', $job->job_id)->first();
                            $assign = JobAssign::where('job_id', $job->job_id)->first();

                            if ($schedule) {
                                // Calculate new start and end dates
                                $startNxtDate = Carbon::parse($schedule->start_date_time)->addDay();
                                $endNxtDate = Carbon::parse($schedule->end_date_time)->addDay();

                                // Update schedule dates
                                $schedule->update([
                                    'start_date_time' => $startNxtDate,
                                    'end_date_time' => $endNxtDate,
                                ]);
                            } else {
                                Log::error('Schedule not found for job_id: ' . $job->job_id);
                            }

                            if ($assign) {
                                // Update assignment dates
                                $assign->update([
                                    'start_date_time' => $startNxtDate,
                                    'end_date_time' => $endNxtDate,
                                ]);
                            } else {
                                Log::error('Job assignment not found for job_id: ' . $job->job_id);
                            }
                        }
                    }
                }

                $savedSettings[] = [
                    'technician_id' => $technicianId,
                    'job_distances' => $jobDistances,
                ];
            }
        }

        // this code for header open jobs 
        $schedules1 = \App\Models\Schedule::where('start_date_time', '>=', $currentDate1)->get();
        // Extract job_ids from schedules
        $jobIds1 = $schedules1->pluck('job_id');
        // Fetch tickets for those job_ids
        $tickets = \App\Models\JobModel::whereIn('id', $jobIds1)->where('is_published', 'no')->get();
        $html = view('admin.open_job_response', compact('tickets'))->render();

        $cronRout = JobRoutingCron::first();

        $cronRout->cron_route_time = $request->auto_route_time;
        $cronRout->cron_route_next_date = $cronDate;
        $cronRout->cron_route_active = $request->auto_route == 'on' ? 'yes' : 'no';
        $cronRout->cron_time_constraints = $request->time_constraints == 'on' ? 'yes' : 'no';
        $cronRout->cron_priority_routing = $request->priority_routing == 'on' ? 'yes' : 'no';
        $cronRout->cron_re_route_time = $request->auto_rerouting_time;
        $cronRout->cron_re_route_next_date = $cronDate;
        $cronRout->cron_re_route_active = $request->auto_rerouting == 'on' ? 'yes' : 'no';
        $cronRout->cron_publish_time = $request->auto_publishing_time;
        $cronRout->cron_publish_next_date = $cronDate;
        $cronRout->cron_publish_active = $request->auto_publishing == 'on' ? 'yes' : 'no';
        $cronRout->cron_job_publish = $request->publish_jobs == 'on' ? 'yes' : 'no';
        $cronRout->cron_job_previous = $request->p_open_job == 'on' ? 'yes' : 'no';
        $cronRout->number_of_calls = $request->number_of_calls;
        $cronRout->tech_ids = json_encode($technicians);

        $cronRout->save();

        return response()->json([
            'success' => true,
            'message' => 'Routing settings saved successfully for all technicians!',
            'savedSettings' => $savedSettings,
            'response' => $response,
            'html' => $html,
        ]);
    }

    public function savereorderedjobs(Request $request)
    {
        $reorderedJobs = $request->input('jobIds');

        foreach ($reorderedJobs as $jobData) {
            $techid = $jobData['techid'];
            $d = $jobData['date'];
            $date = Carbon::parse($d)->format('Y-m-d');
            $jobs = $jobData['jobs'];
            $jobIds = array_column($jobs, 'job_id');

            $routingJob = RoutingJob::where('user_id', $techid)
                ->whereDate('schedule_date_time', $date)
                ->first();



            $routingJob->custom_route = '[' . implode(',', $jobIds) . ']';

            $routingJob->update();
        }

        return response()->json(['success' => true]);
    }

    public function getPopupView($jobId)
    {
        // Fetch job details
        $job = DB::table('jobs')
            ->join('users', 'jobs.customer_id', '=', 'users.id')
            ->join('schedule', 'jobs.id', '=', 'job_id')
            ->where('jobs.id', $jobId)
            ->select('jobs.*', 'users.name as customer_name', 'schedule.start_date_time', 'schedule.end_date_time')
            ->first();

        if (!$job) {
            return response()->json(['popupHtml' => '<div class="pp_jobmodel">Job not found.</div>']);
        }

        // Fetch customer address details
        $address = CustomerUserAddress::where('user_id', $job->customer_id)
            ->select('address_line1', 'city', 'zipcode', 'state_name')
            ->first();

        $fullAddress = $address
            ? "{$address->address_line1}, {$address->city}, {$address->state_name}, {$address->zipcode}"
            : 'Address not available';

        $popupHtml = '
            <div class="pp_jobmodel" style="width: 250px;">
                <h5 class="uppercase text-truncate pb-0 mb-0">#' . $job->id . ' - ' . $job->job_title . '</h5>
                <p class="text-truncate pb-0 mb-0 ft13">' . $job->description . '</p>
                <div class="pp_job_date text-primary">
                ' . Carbon::parse($job->start_date_time)->format('M j Y g:i A') . ' - ' . Carbon::parse($job->end_date_time)->format('g:i A') . '
                </div>
                <p class="ft13 uppercase mb-0 text-truncate">
                    <strong><i class="ri-user-line"></i> ' . $job->customer_name . '</strong>
                </p>
                <div class="ft12"><i class="ri-map-pin-fill"></i> ' . $fullAddress . '</div>
            </div>
        ';

        return response()->json(['popupHtml' => $popupHtml]);
    }

    public function handleIncomingSms(Request $request)
    {
        $from = $request->input('From');  // User's phone number
        $body = $request->input('Body');  // Message content

        \Log::info("Received SMS from $from: $body");

        // Auto-reply to the user
        $response = new MessagingResponse();
        $response->message("Hello! You said: $body");

        return response($response, 200)->header('Content-Type', 'text/xml');
    }


}
