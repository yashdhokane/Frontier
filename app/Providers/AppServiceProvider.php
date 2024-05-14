<?php

namespace App\Providers;

use Carbon\Carbon;

use App\Models\JobActivity;
use App\Models\JobAssign;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Models\NotificationModel;
use App\Models\TimeZone;
use App\Models\UserNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;





class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind a singleton with a common function
        $this->app->singleton('JobActivityManager', function () {
            return new class
            {
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
            return new class
            {
                public function getJobTimings($jobId)
                {
                    $job = JobAssign::where('job_id', $jobId)->first();
                    $invoice = Payment::where('job_id', $jobId)->first();

                    return [
                        'time_schedule' => Carbon::parse($job->start_date_time ?? null)->format('Y-m-d h:i a'),
                        'time_omw' => Carbon::parse($job->start_date_time ?? null)->format('Y-m-d h:i a'),
                        'time_start' => Carbon::parse($job->start_date_time ?? null)->format('Y-m-d h:i a'),
                        'time_finish' => Carbon::parse($job->end_date_time ?? null)->format('Y-m-d h:i a'),
                        'time_invoice' => isset($invoice->issue_date) ? Carbon::parse($invoice->issue_date)->format('Y-m-d') : null,

                    ];
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {


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
    }
}
