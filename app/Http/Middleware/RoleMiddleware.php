<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Si no está logueado
        if (! $user) {
            // Si no está autenticado, lo mandamos al login
            return redirect()->route('login');
        }

        // Si su rol NO está en la lista permitida
        if (! in_array($user->role, $roles)) {
            // En lugar de mostrar la página 403, redirigimos al dashboard
            // con un mensaje claro sobre el rol del usuario
            return redirect()
                ->route('dashboard')
                ->with('error', 'Tu rol de operador no te permite ingresar a este módulo (gestión de usuarios o bitácora).');
        }

        return $next($request);
    }
}