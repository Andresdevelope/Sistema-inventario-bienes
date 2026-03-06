<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::query()
            ->select(['id', 'nombre', 'estado', 'created_at'])
            ->orderBy('nombre')
            ->paginate(12);

        $usoCategorias = Bien::query()
            ->selectRaw('categoria, COUNT(*) as total')
            ->whereNotNull('categoria')
            ->whereRaw("TRIM(categoria) <> ''")
            ->groupBy('categoria')
            ->pluck('total', 'categoria');

        return view('bienes.categorias', compact('categorias', 'usoCategorias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^(?=.{3,30}$)(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ .\-]+$/u',
                Rule::unique('categorias', 'nombre'),
            ],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.min' => 'La categoría debe tener al menos 3 caracteres.',
            'nombre.max' => 'La categoría no puede superar los 30 caracteres.',
            'nombre.regex' => 'La categoría debe contener solo texto válido.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        Categoria::create([
            'nombre' => $this->normalizeCategoryName($validated['nombre']),
            'estado' => 'activo',
        ]);

        return redirect()
            ->route('bienes.categorias.index')
            ->with('status', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^(?=.{3,30}$)(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ .\-]+$/u',
                Rule::unique('categorias', 'nombre')->ignore($categoria->id),
            ],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.min' => 'La categoría debe tener al menos 3 caracteres.',
            'nombre.max' => 'La categoría no puede superar los 30 caracteres.',
            'nombre.regex' => 'La categoría debe contener solo texto válido.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        $nuevoNombre = $this->normalizeCategoryName($validated['nombre']);
        $nombreAnterior = $categoria->nombre;

        $categoria->update(['nombre' => $nuevoNombre]);

        if ($nombreAnterior !== $nuevoNombre) {
            Bien::query()
                ->where('categoria', $nombreAnterior)
                ->update(['categoria' => $nuevoNombre]);
        }

        return redirect()
            ->route('bienes.categorias.index')
            ->with('status', 'Categoría actualizada correctamente.');
    }

    public function toggle(Categoria $categoria): RedirectResponse
    {
        $nuevoEstado = $categoria->estado === 'activo' ? 'inactivo' : 'activo';
        $categoria->update(['estado' => $nuevoEstado]);

        $mensaje = $nuevoEstado === 'activo'
            ? 'Categoría activada correctamente.'
            : 'Categoría inactivada correctamente.';

        return redirect()->route('bienes.categorias.index')->with('status', $mensaje);
    }

    private function normalizeCategoryName(string $value): string
    {
        $clean = trim(strip_tags($value));
        $clean = preg_replace('/\s+/u', ' ', $clean) ?? $clean;

        return mb_convert_case($clean, MB_CASE_TITLE, 'UTF-8');
    }
}
