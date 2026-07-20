<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsDoctor
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'doctor') {
            return redirect('/')->with('error', 'Unauthorized.');
        }

        return $next($request);
    }
}
