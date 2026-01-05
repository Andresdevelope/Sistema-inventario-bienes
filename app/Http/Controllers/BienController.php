<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Bitacora;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador para gestionar el módulo de bienes del inventario.
 *
 * Incluye las operaciones básicas de CRUD:
 * - Listar bienes
 * - Registrar un nuevo bien
 * - Ver el detalle de un bien
 * - Editar un bien existente
 * - Eliminar un bien
 */
class BienController extends Controller
{
    /**
     * Mostrar listado paginado de bienes.
     */
    public function index(Request $request): View
    {
        $query = Bien::query();
        // Filtro por búsqueda general (nombre, código, descripción)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('codigo', 'like', "%$search%")
                  ->orWhere('descripcion', 'like', "%$search%");
            });
        }
        // Filtro por estado
        if ($request->filled('estado') && in_array($request->estado, ['bueno', 'regular', 'malo'])) {
            $query->where('estado', $request->estado);
        }
        // Filtro por ubicación
        if ($request->filled('ubicacion')) {
            $query->where('ubicacion', 'like', "%{$request->ubicacion}%");
        }

        $bienes = $query->paginate(15)->appends($request->except('page'));

        if ($request->ajax()) {
            return view('bienes.partials.tabla', compact('bienes'));
        }
        return view('bienes.index', compact('bienes'));
    }

    /**
     * Mostrar formulario para registrar un nuevo bien.
     */
    public function create(): View
    {
        return view('bienes.create');
    }

    /**
     * Guardar en base de datos un nuevo bien a partir del formulario.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'codigo' => ['required', 'string', 'max:50', 'unique:bienes,codigo'],
            'descripcion' => ['required', 'string', 'max:255'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'ubicacion' => ['nullable', 'string', 'max:150'],
            'estado' => ['required', 'in:bueno,regular,malo'],
            'fecha_adquisicion' => ['nullable', 'date'],
            'valor' => ['nullable', 'numeric', 'min:0'],
        ], [
            'nombre.required' => 'El nombre del bien es obligatorio.',
            'codigo.required' => 'El código del bien es obligatorio.',
            'codigo.unique' => 'Ya existe un bien con este código.',
            'codigo.max' => 'El código del bien no puede superar los 50 caracteres.',

            'descripcion.required' => 'La descripción del bien es obligatoria.',
            'descripcion.max' => 'La descripción no puede superar los 255 caracteres.',

            'categoria.max' => 'La categoría no puede superar los 100 caracteres.',

            'ubicacion.max' => 'La ubicación no puede superar los 150 caracteres.',

            'estado.required' => 'El estado del bien es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',

            'fecha_adquisicion.date' => 'La fecha de adquisición no tiene un formato válido.',

            'valor.numeric' => 'El valor debe ser un número.',
            'valor.min' => 'El valor no puede ser negativo.',
        ]);

        $bien = Bien::create($validated);

        Bitacora::registrar(
            'bienes', // módulo
            'crear',  // acción
            $bien->id,
            sprintf('Registró el bien "%s" (código %s, ID %d).', $bien->nombre, $bien->codigo, $bien->id)
        );

        return redirect()->route('bienes.index')->with('status', 'Bien registrado correctamente.');
    }

    /**
     * Mostrar el detalle de un bien específico.
     */
    public function show(Bien $bien): View
    {
        return view('bienes.show', compact('bien'));
    }

    /**
     * Mostrar formulario de edición para un bien específico.
     */
    public function edit(Bien $bien): View
    {
        return view('bienes.edit', compact('bien'));
    }

    /**
     * Actualizar en base de datos un bien existente.
     */
    public function update(Request $request, Bien $bien): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'codigo' => ['required', 'string', 'max:50', 'unique:bienes,codigo,' . $bien->id],
            'descripcion' => ['required', 'string', 'max:255'],
            'categoria' => ['nullable', 'string', 'max:100'],
            'ubicacion' => ['nullable', 'string', 'max:150'],
            'estado' => ['required', 'in:bueno,regular,malo'],
            'fecha_adquisicion' => ['nullable', 'date'],
            'valor' => ['nullable', 'numeric', 'min:0'],
        ], [
            'nombre.required' => 'El nombre del bien es obligatorio.',
            'codigo.required' => 'El código del bien es obligatorio.',
            'codigo.unique' => 'Ya existe un bien con este código.',
            'codigo.max' => 'El código del bien no puede superar los 50 caracteres.',

            'descripcion.required' => 'La descripción del bien es obligatoria.',
            'descripcion.max' => 'La descripción no puede superar los 255 caracteres.',

            'categoria.max' => 'La categoría no puede superar los 100 caracteres.',

            'ubicacion.max' => 'La ubicación no puede superar los 150 caracteres.',

            'estado.required' => 'El estado del bien es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido.',

            'fecha_adquisicion.date' => 'La fecha de adquisición no tiene un formato válido.',

            'valor.numeric' => 'El valor debe ser un número.',
            'valor.min' => 'El valor no puede ser negativo.',
        ]);

        $bien->update($validated);

        Bitacora::registrar(
            'bienes',
            'actualizar',
            $bien->id,
            sprintf('Actualizó el bien "%s" (código %s, ID %d).', $bien->nombre, $bien->codigo, $bien->id)
        );

        return redirect()->route('bienes.index')->with('status', 'Bien actualizado correctamente.');
    }

    /**
     * Eliminar un bien del inventario.
     */
    public function destroy(Bien $bien): RedirectResponse
    {
        $nombre = $bien->nombre;
        $codigo = $bien->codigo;
        $id = $bien->id;

        $bien->delete();

        Bitacora::registrar(
            'bienes',
            'eliminar',
            $id,
            sprintf('Eliminó el bien "%s" (código %s, ID %d).', $nombre, $codigo, $id)
        );

        return redirect()->route('bienes.index')->with('status', 'Bien eliminado correctamente.');
    }

}
