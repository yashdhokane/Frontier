<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoutingTrigger;
use App\Models\LocationServiceArea;
use App\Models\RoutingTriggerTechnician;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $routing = $request->input('routing');

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
                $endDate = $currentDate->copy()->addDay(); // Today and tomorrow
                break;
            case 'nextdays':
                $endDate = $currentDate->copy()->addDays(2); // Today to the next three days
                break;
            default:
                return response()->json(['success' => false, 'message' => 'Invalid dateDay value.'], 400);
        }

        // Fetch active technicians
        $activeTechnicians = User::where('role', 'technician')->where('status', 'active')->get();

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
                ->get(['job_id', 'position', 'is_routes_map', 'job_onmap_reaching_timing']);

            $routingJob = RoutingJob::where('user_id', $technician->id)->first();
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

    public function Routesettingstore(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'technicians' => 'required|array',
            'technicians.*' => 'integer',
        ]);

        // Get timezone and current date
        $timezone_name = Session::get('timezone_name');
        $inputDate = Carbon::now($timezone_name)->format('Y-m-d');

        $technicians = $request->technicians;

        if (empty($technicians)) {
            return view('jobrouting.technicians_jobs_map')->with('technicians', []);
        }

        $response = [];
        $savedSettings = [];

        foreach ($technicians as $technicianId) {
            // Fetch technician location
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

            $technicianLocation->full_address = implode(', ', array_filter([
                $technicianLocation->address_line1,
                $technicianLocation->city,
                $technicianLocation->state_name . ' ' . $technicianLocation->zipcode,
            ]));

            // Fetch jobs for the technician
            $jobs = Schedule::with(['Jobassign', 'Jobassign.userAddress'])
                ->where('technician_id', $technicianId)
                ->whereDate('start_date_time', '=', $inputDate)
                ->get();

            $timeConstraintsEnabled = $request->input('time_constraints') === 'on';
            $jobDistances = [];

            if ($timeConstraintsEnabled && !$jobs->isEmpty()) {
                // Ensure technicianLocation exists
                if (!$technicianLocation) {
                    Log::error('Technician location not found.');
                    return; // Stop execution if there's no technician location
                }

                // Set the initial origin to technician's location
                $origin = $technicianLocation->latitude . ',' . $technicianLocation->longitude;

                $previousEndDateTime = null;
                //  dd($jobs);

                foreach ($jobs as $index => $job) {
                    if (!$job->Jobassign || !$job->Jobassign->userAddress) {
                        Log::info('Missing Jobassign or userAddress for job.', ['job_id' => $job->job_id]);
                        dd('1 skip');
                        continue;
                    }

                    $userAddress = $job->Jobassign->userAddress->first();
                    $destination = $userAddress->latitude . ',' . $userAddress->longitude;

                    // Make API call to Distance Matrix
                    try {
                        $apiResponse = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json', [
                            'origins' => $origin,
                            'destinations' => $destination,
                            'key' => 'AIzaSyCa7BOoeXVgXX8HK_rN_VohVA7l9nX0SHo', // Ensure your API key is correct
                        ]);

                        // Check if the API request failed
                        if ($apiResponse->failed()) {
                            Log::error('Distance Matrix API call failed.', ['response' => $apiResponse->body(), 'job_id' => $job->job_id]);
                            dd('2 skip');
                            continue; // Skip this job on failure
                        }

                        $data = $apiResponse->json();

                        // Store job distance and duration
                        $jobDistances[] = [
                            'job_id' => $job->job_id,
                            'distance_value' => $data['rows'][0]['elements'][0]['distance']['value'],  // distance in meters
                            'distance_text' => $data['rows'][0]['elements'][0]['distance']['text'],    // human-readable distance
                            'duration_value' => $data['rows'][0]['elements'][0]['duration']['value'],  // duration in seconds
                            'duration_text' => $data['rows'][0]['elements'][0]['duration']['text'],    // human-readable duration
                        ];

                        if ($index === 0) {
                            // For the first job, store the previous end time
                            $previousEndDateTime = Carbon::parse($job->end_date_time);
                        } else {
                          
                            // For subsequent jobs
                            if (!$previousEndDateTime) {
                                Log::error('Missing previous job end time for technician.', [
                                    'job_id' => $job->job_id,
                                ]);
                                dd('4 skip');
                                continue; // Skip if no previous end time
                            }

                            // Calculate the new start time based on previous job's end time and travel duration
                            $newStartTime = Carbon::parse($previousEndDateTime)
                                ->addSeconds($data['rows'][0]['elements'][0]['duration']['value']);

                            // Check if the current job's start time aligns with the calculated new start time
                            $currentJobStart = Carbon::parse($job->start_date_time);
                            $timeDifference = $newStartTime->diffInMinutes($currentJobStart);

                            if ($timeDifference > 15) {
                                // Adjust the current job's start time
                                $job->start_date_time = $newStartTime->addMinutes(10); // Add a buffer of 10 minutes
                                $job->end_date_time = $job->start_date_time->addMinutes($job->duration); // Update end time
                                $job->save();

                                Log::info('Updated job timing for alignment.', [
                                    'job_id' => $job->job_id,
                                    'new_start_time' => $job->start_date_time,
                                    'new_end_time' => $job->end_date_time,
                                ]);
                            } else {
                                Log::info('Job timing already aligned.', [
                                    'job_id' => $job->job_id,
                                    'start_time' => $currentJobStart,
                                ]);
                            }

                            // Update the origin for the next job
                            $origin = $destination;

                            // Set the previous end date-time for the next iteration
                            $previousEndDateTime = $job->end_date_time;
                        }


                        // Update the origin for the next job
                        $origin = $destination;

                        // Update the previous job's end date time
                        $previousEndDateTime = $job->end_date_time;

                    } catch (\Exception $e) {
                        Log::error('Error calling Distance Matrix API for job.', [
                            'job_id' => $job->job_id,
                            'error' => $e->getMessage(),
                        ]);
                        continue; // Skip this job if the API call throws an error
                    }
                }
            }

            // Add technician's saved settings to the response
            $savedSettings[] = [
                'technician_id' => $technicianId,
                'job_distances' => $jobDistances,
            ];
        }

        // Return the final response
        return response()->json([
            'success' => true,
            'message' => 'Routing settings saved successfully for all technicians!',
            'savedSettings' => $savedSettings,
            'response' => $response,
        ]);
    }





}
