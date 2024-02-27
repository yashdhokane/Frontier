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
        // Define the function in the View Composer
        View::composer('*', function ($view) {
            // Get the authenticated user's ID
            $id = auth()->id();

            // Retrieve user's timezone
            $user = $id ? \App\Models\User::with('TimeZone')->find($id) : null;
            $defaultTimezone = $user ? $user->TimeZone->timezone_name : session('selected_timezone');

            $view->with('convertDateToTimezone', function ($date, $customTimezone = null, $format = 'Y-m-d H:i:s') use ($defaultTimezone) {
                if ($date) {
                    $selectedTimezone = $customTimezone ?: session('selected_timezone');
                    $carbonDate = \Carbon\Carbon::parse($date);

                    if ($carbonDate->isValid()) {
                        // Convert to the selected timezone and format it
                        return $carbonDate->timezone($selectedTimezone)->format($format);
                    }
                }

                return null;
            });
        });
    }
}
