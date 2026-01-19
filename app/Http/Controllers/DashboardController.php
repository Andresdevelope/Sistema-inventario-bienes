<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Muestra el panel principal con datos cacheados para reducir tráfico.
     */
    public function index(): View
    {
        // Cachear conteos (TTL 2 minutos)
        $counts = Cache::remember('dashboard:counts', 120, function () {
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

        // Cachear últimos eventos de bitácora con eager loading del usuario (TTL 2 minutos)
        $ultimosEventos = Cache::remember('dashboard:ultimos_eventos', 120, function () {
            return Bitacora::query()
                ->select(['id', 'user_id', 'modulo', 'accion', 'resultado', 'created_at'])
                ->with(['user:id,name'])
                ->latest('created_at')
                ->take(5)
                ->get();
        });

        return view('dashboard', compact('counts', 'ultimosBienes', 'ultimosEventos'));
    }
}
