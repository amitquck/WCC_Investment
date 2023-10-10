<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;


class IsEmployee 
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //dd(Auth::guard('customerlogin')->user()->id);
        if (!Auth::user() || !Auth::user()->id) {
            return redirect('/');
        }

        return $next($request);
    }
}
