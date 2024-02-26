<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role):Response
    {
        // Check if the user has the required role
        if (!$request->user() || !$request->user()->hasRole($role)) {
            
            return $next($request);
        }else{

            abort(403, 'Unauthorized action.');
        }
        
    }
}
