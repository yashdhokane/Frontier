<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

Use App\Providers\RouteServiceProvider;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;

class LoginController extends Controller
{


use AuthenticatesUsers;

protected $redirectTO = RouteServiceProvider::Home;
  
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

        $input = $request->all();

        $this->validate($request, [
            
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(auth()->Auth::attempt(array('email' => $input['email'], 'password' => $input['password']))) 
        {
            if (Auth::user()->usertype == 'admin'){
                return redirect()->route('admin.main');
            }else if  (Auth::user()->usertype == 'technician'){
                return redirect()->route('admin.main');
            }else{
                return redirect()->route('home');
            }
        }else{
            return redirect()->route('login')->with('error','email-address and password are wrong');
        }
    }

}