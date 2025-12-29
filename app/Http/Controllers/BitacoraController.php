<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use Illuminate\View\View;

class BitacoraController extends Controller
{
    /**
     * Mostrar listado paginado de registros de bitácora.
     */
    public function index(): View
    {
        $registros = Bitacora::with('user')->orderByDesc('id')->paginate(20);

        return view('bitacora.index', compact('registros'));
    }
}
