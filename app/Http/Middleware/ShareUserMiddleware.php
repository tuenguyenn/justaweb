<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ShareUserMiddleware
{
    public function handle($request, Closure $next)
    {
        view()->share('currentUser', Auth::user());

        return $next($request);
    }
}
