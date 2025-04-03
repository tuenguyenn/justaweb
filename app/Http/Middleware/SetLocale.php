<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request and set the application locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Nếu session chưa có, đặt mặc định là 'vn'
        if (!session()->has('app_locale')) {
            session(['app_locale' => 'vn']);
        }

        // Lấy locale từ session và đặt cho ứng dụng
        \App::setLocale(session('app_locale'));

        return $next($request);
    }
}
