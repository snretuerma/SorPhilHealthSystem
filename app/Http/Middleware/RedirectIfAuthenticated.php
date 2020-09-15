<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::user()->hasRole('admin')) {
                return redirect('/admin');
            }
            if(Auth::user()->hasRole('user')) {
                return redirect('/user');
            }
            if(Auth::user()->hasRole('observer')) {
                return redirect('/observer');
            }
        }

        return $next($request);
    }
}
