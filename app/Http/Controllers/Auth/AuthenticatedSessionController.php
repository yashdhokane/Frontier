<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\TimeZone;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $user = Auth::user();
        $timezoneId = $user->timezone_id;
        $timezone = TimeZone::where('timezone_id', $timezoneId)->first();
        Session::put('timezone_id', $timezoneId);
        Session::put('timezone_name', $timezone->timezone_name);
        Session::put('time_interval', 1);

        if ($user->status == 'disable' || $user->login == 'disable') {
            Auth::logout(); 
            return redirect()->route('login')->with('error', 'Your account is disabled. Please contact with the administrator.');
        }
       
        $user->update(['last_login' => now()]);
       
         $ipAddress = $request->ip();

   
       

   
   DB::table('user_login_history')->updateOrInsert(
    ['user_id' => auth()->id()],
    ['ip_address' => $ipAddress, 'login' => now()]
);


        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
