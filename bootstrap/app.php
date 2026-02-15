<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as FrameworkVerifyCsrfToken;
use App\Http\Middleware\VerifyCsrfToken as AppVerifyCsrfToken;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register route middleware
        $middleware->alias([
            'student' => \App\Http\Middleware\StudentMiddleware::class,
        ]);
        // Register SkipCsrfForNgrok as global middleware
        $middleware->append(\App\Http\Middleware\SkipCsrfForNgrok::class);
        $middleware->validateCsrfTokens(except: [
            'webhook/paymongo', // Webhook route (keep for webhook safety)
        ]);
        })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
