<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Define the functions in the View Composer
        View::composer('*', function ($view) {
            // Function to convert date to the user's preferred timezone
            $view->with('convertDateToTimezone', function ($date, $format = 'm-d-Y') {
                // Retrieve user's timezone preference directly from the authenticated user
                $userTimezone = auth()->user()->TimeZone->timezone_name ?? 'Asia/Kolkata';
                // Convert the date to the user's preferred timezone
                return $date ? \Carbon\Carbon::parse($date)->timezone($userTimezone)->format($format) : null;
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
            $defaultImage = '../public/default/default.png';
            $view->with('defaultImage', $defaultImage);

        });
    }
}
