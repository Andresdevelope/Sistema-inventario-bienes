<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Usar el middleware de CORS nativo de Laravel (compatible con Laravel 7+ y recomendado en Laravel 11/12)
        $middleware->prepend(\Illuminate\Http\Middleware\HandleCors::class);
        // Alias de middlewares para las rutas
        $middleware->alias([
            'auth' => Authenticate::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
