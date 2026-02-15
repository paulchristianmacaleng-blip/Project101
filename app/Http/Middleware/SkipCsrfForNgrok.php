<?php

namespace App\Http\Middleware;

use Closure;

class SkipCsrfForNgrok
{
    public function handle($request, Closure $next)
    {
        if (str_contains($request->getHost(), 'ngrok-free.dev') || str_contains($request->getHost(), 'ngrok-free.app')) {
            app()->instance('middleware.disable.csrf', true);
        }
        return $next($request);
    }
}
