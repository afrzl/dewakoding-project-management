<?php

use App\Http\Middleware\EnsureUserHasTenant;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            EnsureUserHasTenant::class,
        ]);
        
        // Trust all proxies for signed URLs to work correctly
        $middleware->trustProxies(
            at: '*',
            headers: Illuminate\Http\Request::HEADER_X_FORWARDED_FOR |
                     Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO |
                     Illuminate\Http\Request::HEADER_X_FORWARDED_PORT
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
