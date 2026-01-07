<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Limitador para intentos de login: 5 por minuto por IP+usuario
        RateLimiter::for('login', function (Request $request) {
            $key = sprintf('login|%s|%s', $request->ip(), (string) $request->input('name'));
            return [
                Limit::perMinute(5)->by($key)->response(function() {
                    return response('Demasiados intentos de inicio de sesión. Intenta más tarde.', 429);
                })
            ];
        });

        // Limitador para recuperación de contraseña: 5 por minuto por IP+email
        RateLimiter::for('recover', function (Request $request) {
            $key = sprintf('recover|%s|%s', $request->ip(), (string) $request->input('email'));
            return [
                Limit::perMinute(5)->by($key)->response(function() {
                    return response('Demasiadas solicitudes de recuperación. Intenta más tarde.', 429);
                })
            ];
        });
    }
}
