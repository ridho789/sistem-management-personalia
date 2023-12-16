<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $roles = func_get_args();
        array_shift($roles);
    
        if (Auth::check() && in_array(Auth::user()->level, $roles)) {
            return $next($request);
        }

        // Check if the user is authenticated
        if (Auth::check()) {
            // Redirect based on the user's level
            switch (Auth::user()->level) {
                case 1:
                    return redirect('/dashboard');
                    break;
                case 2:
                    return redirect('/leaves-summary');
                    break;
                case 3:
                    return redirect('/list-daily-report');
                    break;
                default:
                    return redirect('/dashboard');
            }
        }
    
        return redirect('/dashboard');
    }
}
