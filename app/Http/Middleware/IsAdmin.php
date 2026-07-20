<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Admin OR Doctor allowed
        if (in_array(Auth::user()->role, ['admin', 'doctor'])) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}

