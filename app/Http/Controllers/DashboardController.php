<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Muestra el panel principal con datos cacheados para reducir tráfico.
     */
    public function index(): View
    {
        /** @var \App\Models\User|null $authUser */
        $authUser = Auth::user();
        $isAdmin = $authUser?->isAdmin() ?? false;

        // Cachear conteos (TTL 2 minutos)
        $counts = Cache::remember('dashboard:counts:' . ($isAdmin ? 'admin' : 'user'), 120, function () use ($isAdmin) {
            if (!$isAdmin) {
                return [
                    'bienes' => Bien::count(),
                ];
            }

            return [
                'bienes' => Bien::count(),
                'usuarios' => User::count(),
                'bitacora' => Bitacora::count(),
            ];
        });

        // Cachear últimos bienes (TTL 2 minutos)
        $ultimosBienes = Cache::remember('dashboard:ultimos_bienes', 120, function () {
            return Bien::query()
                ->select(['id', 'nombre', 'codigo', 'categoria', 'estado', 'created_at'])
                ->latest('created_at')
                ->take(5)
                ->get();
        });

        $ultimosEventos = collect();

        // Cachear últimos eventos de bitácora solo para administradores (TTL 2 minutos)
        if ($isAdmin) {
            $ultimosEventos = Cache::remember('dashboard:ultimos_eventos:admin', 120, function () {
                return Bitacora::query()
                    ->select(['id', 'user_id', 'modulo', 'accion', 'resultado', 'created_at'])
                    ->with(['user:id,name'])
                    ->latest('created_at')
                    ->take(5)
                    ->get();
            });
        }

        return view('dashboard', compact('counts', 'ultimosBienes', 'ultimosEventos', 'isAdmin'));
    }
}
