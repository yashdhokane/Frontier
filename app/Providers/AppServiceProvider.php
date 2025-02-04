<?php

namespace App\Providers;

use Carbon\Carbon;

use App\Models\JobActivity;
use App\Models\JobAssign;
use App\Models\JobTechEvent;
use App\Models\Payment;
use App\Models\PermissionModel;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Models\NotificationModel;
use App\Models\TimeZone;
use App\Models\UserNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Twilio\Rest\Client;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\MyCustomEmail;
use App\Models\CustomerUserAddress;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->singleton('UserPermissionChecker', function () {
            return new class {
                public function checkUserPermission($user_id, $permissions_type, $module_id)
                {
                    if ($permissions_type == 'all') {
                        return true; // Allow access
                    } elseif ($permissions_type == 'block') {
                        return Redirect::to('unauthorized'); // Redirect to unauthorized page
                    } elseif ($permissions_type == 'selected') {
                        $permission = DB::table('user_permissions')
                            ->where('user_id', $user_id)
                            ->where('module_id', $module_id)
                            ->value('permission');
                        if ($permission === 1) {
                            return true; // Allow access
                        } else {
                            return Redirect::to('unauthorized'); // Redirect to unauthorized page
                        }
                    }
                    return Redirect::to('unauthorized'); // Default unauthorized response
                }
            };
        });


        // Bind a singleton with a common function
        $this->app->singleton('JobActivityManager', function () {
            return new class {
                public function addJobActivity($jobId, $activityDescription)
                {


                    $jobActivity = new JobActivity();
                    $jobActivity->job_id = $jobId;
                    $jobActivity->user_id = auth()->user()->id;
                    $jobActivity->activity = $activityDescription;
                    $jobActivity->save();

                    return $jobActivity;
                }
            };
        });

        $this->app->singleton('JobTimingManager', function () {
            return new class {
                public function getJobTimings($jobId)
                {
                    $job = JobTechEvent::where('job_id', $jobId)->first();


                    return [
                        'time_schedule' => isset($job->job_schedule) ? Carbon::parse($job->job_schedule ?? null)->format('Y-m-d h:i a') : null,
                        'time_omw' => isset($job->job_enroute) ? Carbon::parse($job->job_enroute ?? null)->format('Y-m-d h:i a') : null,
                        'time_start' => isset($job->job_start) ? Carbon::parse($job->job_start ?? null)->format('Y-m-d h:i a') : null,
                        'time_finish' => isset($job->job_end) ? Carbon::parse($job->job_end ?? null)->format('Y-m-d h:i a') : null,
                        'time_invoice' => isset($job->job_invoice) ? Carbon::parse($job->job_invoice)->format('Y-m-d') : null,
                        'time_payment' => isset($job->job_payment) ? Carbon::parse($job->job_payment)->format('Y-m-d') : null,

                    ];
                }
            };
        });

        $this->app->singleton('modifyDateTime', function ($app) {
            return function ($dateTime, $hours, $operation = 'add', $format = 'Y-m-d H:i:s') {
                $date = Carbon::parse($dateTime);

                if ($operation === 'add') {
                    return $date->addHours($hours)->format($format);
                } elseif ($operation === 'subtract') {
                    return $date->subHours($hours)->format($format);
                } else {
                    throw new \InvalidArgumentException("Invalid operation. Use 'add' or 'subtract'.");
                }
            };
        });

        // Register the singleton for sending SMS
        $this->app->singleton('SmsService', function ($app) {
            return new class {
                public function sendSms($message, $to)
                {
                    $sid = env('TWILIO_SID');
                    $token = env('TWILIO_TOKEN');
                    $fromNumber = env('TWILIO_FROM');

                    try {
                        $client = new Client($sid, $token);
                        $client->messages->create($to, [
                            'from' => $fromNumber,
                            'body' => $message
                        ]);

                        return 'SMS has been sent successfully';
                    } catch (Exception $e) {
                        return 'Error: ' . $e->getMessage();
                    }
                }
            };
        });

        // Define a common function that accepts subject, msg, from, to, and type
        app()->singleton('commonFunction', function () {
            return function ($subject, $msg, $from, $to, $type, $data) {

                $maildata = [
                    'subject' => $subject,
                    'msg' => $msg,
                    'from' => $from,
                    'type' => $type,
                    'data' => $data,
                ];

                Mail::to($to)->send(new MyCustomEmail($maildata));

                return "Email sent to {$to} with subject '{$subject}'";
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
      



        View::composer('*', function ($view) {
            $view->with('modifyDateTime', app('modifyDateTime'));
        });

        $this->app->singleton('JobActivityManagerapp', function () {
            return new class {
                public function addJobActivity($jobId, $activityDescription, $userId)
                {
                    $jobActivity = new JobActivity();
                    $jobActivity->job_id = $jobId;
                    $jobActivity->user_id = $userId;  // Use the passed user ID
                    $jobActivity->activity = $activityDescription;
                    $jobActivity->save();

                    return $jobActivity;
                }
            };
        });
        //particular  api side
        app()->singleton('sendNoticesapp', function ($app) {
            return function ($notice_heading, $notice_title, $notice_link, $notice_section, $admin_id, $job_id) {
                // Step 1: Insert into notifications table
                $notification = new NotificationModel();
                $notification->notice_title = $notice_title;
                $notification->notice_heading = $notice_heading;
                $notification->notice_date = now();
                $notification->notice_link = $notice_link;
                $notification->notice_section = $notice_section;
                // Adding job_id to the notification
                $notification->save();

                $notice_id = $notification->id;

                // Step 2: Retrieve the specified admin
                $admin = User::find($admin_id);

                // Step 3: Insert into user_notifications for the specified admin
                if ($admin) {
                    $userNotification = new UserNotification();
                    $userNotification->user_id = $admin->id;
                    $userNotification->notice_id = $notice_id;
                    $userNotification->is_read = 0;
                    //  $userNotification->read_at = now();
                    $userNotification->job_id = $job_id;
                    $userNotification->save();
                }
            };
        });




        app()->singleton('sendNotices', function ($app) {
            return function ($notice_heading, $notice_title, $notice_link, $notice_section) {
                // Step 1: Insert into notifications table
                $notification = new NotificationModel();
                $notification->notice_title = $notice_title;
                $notification->notice_heading = $notice_heading;
                $notification->notice_date = now();
                $notification->notice_link = url()->current();
                $notification->notice_section = $notice_section;
                $notification->save();

                $notice_id = $notification->id;

                // Step 2: Retrieve list of employees (admins, dispatchers, superadmins)
                $employees = User::whereIn('role', ['admin', 'dispatcher', 'superadmin'])->get();

                // Step 3: Insert into user_notifications for each employee
                foreach ($employees as $employee) {
                    $userNotification = new UserNotification();
                    $userNotification->user_id = $employee->id;
                    $userNotification->notice_id = $notice_id;
                    $userNotification->is_read = 0;
                    $userNotification->read_at = now();
                    $userNotification->save();
                }
            };
        });




        // Define the functions in the View Composer
        View::composer('*', function ($view) {
            // Function to convert date to the user's preferred timezone
            $view->with('convertDateToTimezone', function ($date, $format = 'm-d-Y') {
                // Retrieve user's timezone preference directly from the authenticated user
                $userTimezone = auth()->user()->TimeZone->timezone_name ?? 'Asia/Kolkata';
                // {{$siteSettings->name}}Convert the date to the user's preferred timezone
                return $date ? \Carbon\Carbon::parse($date)->timezone($userTimezone)->format($format) : null;
            });


            $siteSettings = DB::table('site_settings')->find(1);


            view()->share('siteSettings', $siteSettings);


            // Define the isEnd function and share it with all views
            view()->share('isEnd', function ($date) {
                // Convert the provided date string to a Carbon instance
                $date = Carbon::parse($date);

                // {{ $isEnd($date) }}Calculate the difference between the provided date and the current time
                return $date->diffForHumans();
            });
            view()->share('getUserAddress', function ($user_id, $attr) {
                $whereclause = " WHERE user_address.user_id = " . $user_id;

                if (isset($attr['address_primary']) && $attr['address_primary'] != "") {
                    $whereclause .= " AND address_primary = '" . $attr['address_primary'] . "'";
                }
                if (isset($attr['address_type']) && $attr['address_type'] != "") {
                    $whereclause .= " AND address_type = '" . $attr['address_type'] . "'";
                }

                $sql_address = "SELECT user_address.*,  location_states.state_name, location_states.state_code, location_cities.city
                    FROM `user_address`
                    INNER JOIN location_states ON user_address.state_id = location_states.state_id
                    INNER JOIN location_cities ON user_address.city = location_cities.city_id
                    " . $whereclause;

                $rs_address = DB::select($sql_address);
                // $address = (array) $rs_address[0];
                if (!empty($rs_address)) {
                    $address = (array) $rs_address[0];
                } else {

                    $address = null;
                }


                if ($address !== null) {
                    if (isset($attr['address_format']) && $attr['address_format'] != "") {
                        $exp1 = explode(',', $attr['address_format']);
                        $return_addr_arr = [];

                        foreach ($exp1 as $item) {
                            $return_addr_arr[] = $address[trim($item)];
                        }

                        $return_addr = implode(", ", $return_addr_arr);
                    } else {
                        //DEFAULT ADDRESS
                        $return_addr = $address['address_line1'] . ', ' . $address['address_line2'] . ', ' . $address['city'] . ', ' . $address['zipcode'] . ', ' . $address['state_code'];
                    }
                } else {
                    // Handle the case when $address is null
                    // For example, set $return_addr to a default value or return null
                    $return_addr = null;
                }

                return $return_addr;
            });


            // Function to convert time to the user's preferred timezone
            $view->with('convertTimeToTimezone', function ($time, $format = 'H:i:s') {
                // Retrieve user's timezone preference directly from the authenticated user
                $userTimezone = auth()->user()->TimeZone->timezone_name ?? 'Asia/Kolkata';
                // Convert the time to the user's preferred timezone
                return $time ? \Carbon\Carbon::parse($time)->timezone($userTimezone)->format($format) : null;
            });

            View::composer('*', function ($view) {
                $view->with('getTimeZoneDate', function ($date, $timezone = null, $format = 'm-d-Y H:i:s', $param = []) {
                    if ($date) {
                        $timezone = $timezone ?: session('timezone_name', 'UTC');
                        return Carbon::parse($date)->setTimezone($timezone)->format($format);
                    } else {
                        return null;
                    }
                });
            });



            // Function to format date
            $view->with('formatDate', function ($date, $format = 'm-d-Y') {
                // Format the date
                return $date ? \Carbon\Carbon::parse($date)->format($format) : null;
            });

            // Function to default image
            $defaultImage = url('/public/images/login_img_bydefault.png');
            $view->with('defaultImage', $defaultImage);

            //useraddress function

            $user = auth()->user();
            if ($user) {
                $timezone = User::where('id', $user->id)->first();
                if ($timezone) {
                    $timezoneName = TimeZone::where('timezone_id', $timezone->timezone_id)->value('timezone_name');
                    if ($timezoneName) {
                        $view->with('timezoneName', $timezoneName);
                    }
                }
            }
        });

        view()->share('getPermissionsByParentId', function ($parentId) {
            return PermissionModel::with('module')->where('parent_id', $parentId)->get();
        });
    }
}
