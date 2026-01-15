<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class BitacoraController extends Controller
{
    /**
     * Mostrar listado paginado de registros de bitácora.
     */
    public function index(Request $request): View
    {
        $query = Bitacora::with('user')->orderByDesc('id');

        // Filtros básicos
        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $query->where(function ($w) use ($q) {
                $w->where('descripcion', 'like', "%$q%")
                  ->orWhere('modulo', 'like', "%$q%")
                  ->orWhere('accion', 'like', "%$q%")
                  ->orWhere('resultado', 'like', "%$q%");
            });
        }

        if ($request->filled('modulo')) {
            $query->where('modulo', (string) $request->input('modulo'));
        }
        if ($request->filled('accion')) {
            $query->where('accion', (string) $request->input('accion'));
        }
        if ($request->filled('resultado')) {
            $query->where('resultado', (string) $request->input('resultado'));
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->input('user_id'));
        }

        // Rango de fechas (created_at)
        $desde = $request->date('desde');
        $hasta = $request->date('hasta');
        if ($desde) {
            $query->where('created_at', '>=', $desde->startOfDay());
        }
        if ($hasta) {
            $query->where('created_at', '<=', $hasta->endOfDay());
        }

        $registros = $query->paginate(20)->appends($request->query());

        // Opciones para selects (distintos valores actuales)
        $modulos = Bitacora::select('modulo')->distinct()->orderBy('modulo')->pluck('modulo');
        $acciones = Bitacora::select('accion')->distinct()->orderBy('accion')->pluck('accion');
        $resultados = Bitacora::select('resultado')->distinct()->orderBy('resultado')->pluck('resultado');
        $usuarios = User::select('id', 'name')->orderBy('name')->get();

        return view('bitacora.index', compact('registros', 'modulos', 'acciones', 'resultados', 'usuarios'));
    }
}
