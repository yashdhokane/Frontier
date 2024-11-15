<?php namespace App\Http\Controllers;

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
use App\Models\RoutingSetting;
use App\Models\RoutingSettingOption;
use Illuminate\Support\Facades\Auth;

class RoutingController extends Controller
{
    public function index(Request $request)
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
            $technicianLocation = CustomerUserAddress::where('user_id', $technician->id)->first(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

            // Handle case where technician location is not found
            if (!$technicianLocation) {
                $response[] = [
                    'technician' => [
                        'id' => $technician->id,
                        'name' => $technician->name,
                        'error' => 'Technician location not found.',
                    ],
                ];
                continue; // Skip to the next technician
            }

            // Attach technician's full address
            $technicianLocation->full_address = $technicianLocation->address_line1 . ', ' . $technicianLocation->city . ', ' . $technicianLocation->state_name . ' ' . $technicianLocation->zipcode;

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
                'jobs' => [],
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
                $customerLocations = CustomerUserAddress::whereIn('user_id', $customerIds)->get(['user_id', 'latitude', 'longitude', 'address_line1', 'city', 'zipcode', 'state_name']);

                if ($customerLocations->isEmpty()) {
                    $technicianData['jobs'][] = [
                        'job_id' => $job->job_id,
                        'error' => 'No customer locations found for this job.',
                    ];
                    continue;
                }

                foreach ($customerLocations as $customerLocation) {
                    // Fetch customer name
                    $customerName = User::where('id', $customerLocation->user_id)->value('name');

                    // Construct the full address for the customer
                    $customerLocation->full_address = $customerLocation->address_line1 . ', ' . $customerLocation->city . ', ' . $customerLocation->state_name . ' ' . $customerLocation->zipcode;

                    // Calculate distance from technician to customer
                    $distance = $this->calculateDistance($technicianLocation->latitude, $technicianLocation->longitude, $customerLocation->latitude, $customerLocation->longitude);

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
                        'distance' => $distance, // Add the calculated distance
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

        $query = DB::table('job_assigned')->select('job_assigned.id as assign_id', 'job_assigned.job_id as job_id', 'job_assigned.start_date_time', 'job_assigned.end_date_time', 'job_assigned.start_slot', 'job_assigned.end_slot', 'job_assigned.pending_number', 'jobs.job_code', 'jobs.job_title as subject', 'jobs.status', 'jobs.address', 'jobs.city', 'jobs.state', 'jobs.zipcode', 'jobs.latitude', 'jobs.longitude', 'users.name', 'users.email', 'technician.name as technicianname', 'technician.email as technicianemail')->join('jobs', 'jobs.id', 'job_assigned.job_id')->join('users', 'users.id', 'jobs.customer_id')->join('users as technician', 'technician.id', 'job_assigned.technician_id')->whereNotNull('jobs.latitude')->whereNotNull('jobs.longitude')->orderBy('job_assigned.pending_number', 'asc');

        if (isset($inputDate) && !empty($inputDate)) {
            $query->whereDate('start_date_time', $inputDate);
        } else {
            $query->whereNotNull('start_date_time');
        }

        $data = $query->get();

        return view('jobrouting.index', compact('data', 'responseJson', 'tech', 'routingTriggers', 'location'));
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
        // Validate the incoming request data if necessary
        $validatedData = $request->validate([
            'technicians' => 'required|array',
            'technicians.*' => 'integer', // Each technician ID should be an integer
        ]);

        // Initialize an array to store the created routing settings with their options
        $savedSettings = [];

        // Loop through each technician and create a separate RoutingSetting record
        foreach ($request->technicians as $technicianId) {
            // Create a new RoutingSetting for each technician
            $routingSetting = RoutingSetting::create([
                'user_id' => $technicianId, // Store technician ID as user_id
                'created_by' => Auth::id(), // Use Auth facade to get the authenticated user's ID
                'updated_by' => Auth::id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'routing_details' => json_encode($request->all()), // Save all data as JSON or as needed
                'routing_cron' => 'no',
                'routing_cron_date' => Carbon::now(),
                'is_active' => 'yes', // Static value
            ]);

            // Define the options that need to be saved in RoutingSettingOption
            $options = [
                'auto_route' => $request->has('auto_route') ? 'yes' : 'no',
                'time_constraints' => $request->has('time_constraints') ? 'yes' : 'no',
                'priority_routing' => $request->has('priority_routing') ? 'yes' : 'no',
                'auto_rerouting_value' => $request->has('auto_rerouting') ? 'yes' : 'no',
                'auto_publishing' => $request->has('auto_publishing') ? 'yes' : 'no',
                'number_of_calls' => $request->has('number_of_calls') ? 'yes' : 'no',
                'call_limit' => $request->has('call_limit') ? $request->call_limit : '0',
            ];

            // Initialize an array to store the options for this routing setting
            $optionsData = [];

            // Loop through each option and store it in RoutingSettingOption
            foreach ($options as $option => $value) {
                $routingSettingOption = RoutingSettingOption::create([
                    'routing_id' => $routingSetting->routing_id, // Link the option to the created routing_setting
                    'routing_option' => $option,
                    'routing_value' => $value,
                ]);

                // Add the option to the options array
                $optionsData[] = $routingSettingOption;
            }

            // Add the routing setting and its options to the saved settings array
            $savedSettings[] = [
                'routingSetting' => $routingSetting,
                'options' => $optionsData,
            ];
        }

        // Return a success response with the saved routing settings and options
        return response()->json([
            'success' => true,
            'message' => 'Routing settings saved successfully for all technicians!',
            'savedSettings' => $savedSettings,
        ]);
    }
}