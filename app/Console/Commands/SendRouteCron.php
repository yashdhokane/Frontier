<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\SendCronMail;


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
use Illuminate\Support\Facades\Log;

use App\Models\TechnicianJobsSchedulesOnMap;
use App\Models\RoutingSetting;
use App\Models\RoutingJOb;
use App\Models\RoutingSettingOption;
use Illuminate\Support\Facades\Auth;
use App\Mail\PublishMailTech;
use App\Mail\PublishMailCustomer;
use App\Models\JobRoutingCron;
use Illuminate\Support\Facades\Mail;

use Twilio\TwiML\MessagingResponse;

class SendRouteCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-route-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $cronRout = JobRoutingCron::first();

        $timezone_id = Session::get('timezone_id');
        $timezone_name = Session::get('timezone_name');
        $time_interval = Session::get('time_interval');
        $inputDate = Carbon::now($timezone_name)->format('Y-m-d');
        $tomorrow = Carbon::parse($inputDate)->addDay()->format('Y-m-d');
        $dayAfterTomorrow = Carbon::parse($inputDate)->addDays(2)->format('Y-m-d');
        $technicians = $cronRout->tech_ids;
        $callPerDay = $cronRout->number_of_calls;
        $currentDate1 = \Carbon\Carbon::now($timezone_name);



        $response = [];
        $savedSettings = [];



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


            if ($cronRout->cron_job_publish == 'yes' && $cronRout->cron_job_previous == 'yes') {

                $jobs = Schedule::with(['Jobassign', 'Jobassign.userAddress', 'JobModel'])
                    ->where('technician_id', $technicianId)
                    ->whereHas('JobModel', function ($query) {
                        $query->where('status', 'open');
                    })
                    ->orderBy('start_date_time', 'asc')
                    ->get();



            } else if ($cronRout->cron_job_publish == 'yes') {

                $jobs = Schedule::with(['Jobassign', 'Jobassign.userAddress', 'JobModel'])
                    ->where('technician_id', $technicianId)
                    ->whereDate('start_date_time', '>=', $inputDate)
                    ->whereHas('JobModel', function ($query) {
                        $query->where('status', 'open');
                    })
                    ->orderBy('start_date_time', 'asc')
                    ->get();

            } else if ($cronRout->cron_job_previous == 'yes') {

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

            if ($cronRout->cron_publish_active == 'yes' && !$jobs->isEmpty()) {
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

            if ($cronRout->cron_route_active == 'yes') {

                if ($cronRout->cron_priority_routing == 'yes') {


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
                            $routingJob->cron_route_time = $cronRout->cron_route_time;
                            $routingJob->cron_re_route_time = $cronRout->cron_re_route_time;
                            $routingJob->cron_publish_time = $cronRout->cron_publish_time;
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
                                $routingJob->cron_route_time = $cronRout->cron_route_time;
                                $routingJob->cron_re_route_time = $cronRout->cron_re_route_time;
                                $routingJob->cron_publish_time = $cronRout->cron_publish_time;
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
                            $routingJob->cron_route_time = $cronRout->cron_route_time;
                            $routingJob->cron_re_route_time = $cronRout->cron_re_route_time;
                            $routingJob->cron_publish_time = $cronRout->cron_publish_time;
                            $routingJob->save(); // Save the new instance
                        }
                    }


                }

                if ($cronRout->$cronRout->cron_time_constraints == 'yes' && !$jobs->isEmpty()) {
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
                            $routingJob->cron_route_time = $cronRout->cron_route_time;
                            $routingJob->cron_re_route_time = $cronRout->cron_re_route_time;
                            $routingJob->cron_publish_time = $cronRout->cron_publish_time;
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
                                $routingJob->cron_route_time = $cronRout->cron_route_time;
                                $routingJob->cron_re_route_time = $cronRout->cron_re_route_time;
                                $routingJob->cron_publish_time = $cronRout->cron_publish_time;
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
                            $routingJob->cron_route_time = $cronRout->cron_route_time;
                            $routingJob->cron_re_route_time = $cronRout->cron_re_route_time;
                            $routingJob->cron_publish_time = $cronRout->cron_publish_time;
                            $routingJob->save(); // Save the new instance
                        }
                    }


                }

            }

            if ($cronRout->cron_re_route_active == 'yes' && !$jobs->isEmpty()) {
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


        // Logging for debugging
        Log::info('Job route set successfully via cron!');
        // Console ke liye output
        $this->info('Job route set successfully!');
    }
}
