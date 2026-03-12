<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Bitacora;
use App\Models\Categoria;
use App\Models\Ubicacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $this->resolveTab($request);

        $categorias = Categoria::query()
            ->select(['id', 'nombre', 'estado', 'created_at'])
            ->orderBy('nombre')
            ->paginate(12, ['*'], 'categorias_page')
            ->withQueryString();

        $ubicaciones = Ubicacion::query()
            ->select(['id', 'nombre', 'estado', 'created_at'])
            ->orderBy('nombre')
            ->paginate(12, ['*'], 'ubicaciones_page')
            ->withQueryString();

        $usoCategorias = Bien::query()
            ->leftJoin('categorias', 'bienes.categoria_id', '=', 'categorias.id')
            ->selectRaw("COALESCE(categorias.nombre, 'Sin categoría') as categoria_nombre, COUNT(*) as total")
            ->groupBy('categoria_nombre')
            ->pluck('total', 'categoria_nombre');

        $usoUbicaciones = Bien::query()
            ->selectRaw('ubicacion_id, COUNT(*) as total')
            ->whereNotNull('ubicacion_id')
            ->groupBy('ubicacion_id')
            ->pluck('total', 'ubicacion_id');

        return view('bienes.categorias', compact('tab', 'categorias', 'ubicaciones', 'usoCategorias', 'usoUbicaciones'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->sanitizeCatalogInput($request);

        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^(?=.{3,30}$)(?!(?:.*\d){4,})(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .\-]+$/u',
                'not_regex:/<[^>]*>/',
                Rule::unique('categorias', 'nombre'),
            ],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.min' => 'La categoría debe tener al menos 3 caracteres.',
            'nombre.max' => 'La categoría no puede superar los 30 caracteres.',
            'nombre.regex' => 'La categoría debe contener texto válido y como máximo 3 números.',
            'nombre.not_regex' => 'La categoría no puede contener etiquetas HTML o código.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        $nombre = $this->normalizeCategoryName($validated['nombre']);
        $this->ensureCategoryTextQuality($nombre);

        $categoriaCreada = Categoria::create([
            'nombre' => $nombre,
            'estado' => 'activo',
        ]);

        Bitacora::registrar(
            'categorias',
            'crear',
            $categoriaCreada?->id,
            sprintf('Creó la categoría "%s".', $nombre)
        );

        return redirect()
            ->route('bienes.categorias.index')
            ->with('status', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $this->sanitizeCatalogInput($request);

        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^(?=.{3,30}$)(?!(?:.*\d){4,})(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .\-]+$/u',
                'not_regex:/<[^>]*>/',
                Rule::unique('categorias', 'nombre')->ignore($categoria->id),
            ],
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.min' => 'La categoría debe tener al menos 3 caracteres.',
            'nombre.max' => 'La categoría no puede superar los 30 caracteres.',
            'nombre.regex' => 'La categoría debe contener texto válido y como máximo 3 números.',
            'nombre.not_regex' => 'La categoría no puede contener etiquetas HTML o código.',
            'nombre.unique' => 'Ya existe una categoría con ese nombre.',
        ]);

        $nuevoNombre = $this->normalizeCategoryName($validated['nombre']);
        $nombreAnterior = $categoria->nombre;
        $this->ensureCategoryTextQuality($nuevoNombre);

        $categoria->update(['nombre' => $nuevoNombre]);

        Bitacora::registrar(
            'categorias',
            'actualizar',
            $categoria->id,
            sprintf('Actualizó la categoría de "%s" a "%s".', $nombreAnterior, $nuevoNombre)
        );

        return redirect()
            ->route('bienes.categorias.index')
            ->with('status', 'Categoría actualizada correctamente.');
    }

    public function toggle(Categoria $categoria): RedirectResponse
    {
        $nuevoEstado = $categoria->estado === 'activo' ? 'inactivo' : 'activo';
        $categoria->update(['estado' => $nuevoEstado]);

        Bitacora::registrar(
            'categorias',
            $nuevoEstado === 'activo' ? 'activar' : 'inactivar',
            $categoria->id,
            sprintf('%s la categoría "%s".', $nuevoEstado === 'activo' ? 'Activó' : 'Inactivó', $categoria->nombre)
        );

        $mensaje = $nuevoEstado === 'activo'
            ? 'Categoría activada correctamente.'
            : 'Categoría inactivada correctamente.';

        return redirect()->route('bienes.categorias.index')->with('status', $mensaje);
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        $bienesAsociados = Bien::query()
            ->where('categoria_id', $categoria->id)
            ->count();

        if ($bienesAsociados > 0) {
            return redirect()
                ->route('bienes.categorias.index')
                ->with('status', 'No se puede eliminar la categoría porque tiene bienes asociados. Puedes inactivarla en su lugar.');
        }

        $categoria->delete();

        Bitacora::registrar(
            'categorias',
            'eliminar',
            $categoria->id,
            sprintf('Eliminó la categoría "%s".', $categoria->nombre)
        );

        return redirect()
            ->route('bienes.categorias.index')
            ->with('status', 'Categoría eliminada correctamente.');
    }

    public function storeUbicacion(Request $request): RedirectResponse
    {
        $this->sanitizeCatalogInput($request);

        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^(?=.{3,50}$)(?!(?:.*\d){4,})(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,\-#°]+$/u',
                'not_regex:/<[^>]*>/',
                Rule::unique('ubicaciones', 'nombre'),
            ],
        ], [
            'nombre.required' => 'El nombre de la ubicación es obligatorio.',
            'nombre.min' => 'La ubicación debe tener al menos 3 caracteres.',
            'nombre.max' => 'La ubicación no puede superar los 50 caracteres.',
            'nombre.regex' => 'La ubicación debe contener texto válido y como máximo 3 números.',
            'nombre.not_regex' => 'La ubicación no puede contener etiquetas HTML o código.',
            'nombre.unique' => 'Ya existe una ubicación con ese nombre.',
        ]);

        $nombre = $this->normalizeLocationName($validated['nombre']);
        $this->ensureLocationTextQuality($nombre);

        $ubicacionCreada = Ubicacion::create([
            'nombre' => $nombre,
            'estado' => 'activo',
        ]);

        Bitacora::registrar(
            'ubicaciones',
            'crear',
            $ubicacionCreada?->id,
            sprintf('Creó la ubicación "%s".', $nombre)
        );

        return redirect()
            ->route('bienes.categorias.index', ['tab' => 'ubicaciones'])
            ->with('status', 'Ubicación creada correctamente.');
    }

    public function updateUbicacion(Request $request, Ubicacion $ubicacion): RedirectResponse
    {
        $this->sanitizeCatalogInput($request);

        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^(?=.{3,50}$)(?!(?:.*\d){4,})(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,\-#°]+$/u',
                'not_regex:/<[^>]*>/',
                Rule::unique('ubicaciones', 'nombre')->ignore($ubicacion->id),
            ],
        ], [
            'nombre.required' => 'El nombre de la ubicación es obligatorio.',
            'nombre.min' => 'La ubicación debe tener al menos 3 caracteres.',
            'nombre.max' => 'La ubicación no puede superar los 50 caracteres.',
            'nombre.regex' => 'La ubicación debe contener texto válido y como máximo 3 números.',
            'nombre.not_regex' => 'La ubicación no puede contener etiquetas HTML o código.',
            'nombre.unique' => 'Ya existe una ubicación con ese nombre.',
        ]);

        $nuevoNombre = $this->normalizeLocationName($validated['nombre']);
        $nombreAnterior = $ubicacion->nombre;
        $this->ensureLocationTextQuality($nuevoNombre);

        $ubicacion->update(['nombre' => $nuevoNombre]);

        Bitacora::registrar(
            'ubicaciones',
            'actualizar',
            $ubicacion->id,
            sprintf('Actualizó la ubicación de "%s" a "%s".', $nombreAnterior, $nuevoNombre)
        );

        return redirect()
            ->route('bienes.categorias.index', ['tab' => 'ubicaciones'])
            ->with('status', 'Ubicación actualizada correctamente.');
    }

    public function toggleUbicacion(Ubicacion $ubicacion): RedirectResponse
    {
        $nuevoEstado = $ubicacion->estado === 'activo' ? 'inactivo' : 'activo';
        $ubicacion->update(['estado' => $nuevoEstado]);

        Bitacora::registrar(
            'ubicaciones',
            $nuevoEstado === 'activo' ? 'activar' : 'inactivar',
            $ubicacion->id,
            sprintf('%s la ubicación "%s".', $nuevoEstado === 'activo' ? 'Activó' : 'Inactivó', $ubicacion->nombre)
        );

        $mensaje = $nuevoEstado === 'activo'
            ? 'Ubicación activada correctamente.'
            : 'Ubicación inactivada correctamente.';

        return redirect()
            ->route('bienes.categorias.index', ['tab' => 'ubicaciones'])
            ->with('status', $mensaje);
    }

    public function destroyUbicacion(Ubicacion $ubicacion): RedirectResponse
    {
        $bienesAsociados = Bien::query()
            ->where('ubicacion_id', $ubicacion->id)
            ->count();

        if ($bienesAsociados > 0) {
            return redirect()
                ->route('bienes.categorias.index', ['tab' => 'ubicaciones'])
                ->with('status', 'No se puede eliminar la ubicación porque tiene bienes asociados. Puedes inactivarla en su lugar.');
        }

        $ubicacion->delete();

        Bitacora::registrar(
            'ubicaciones',
            'eliminar',
            $ubicacion->id,
            sprintf('Eliminó la ubicación "%s".', $ubicacion->nombre)
        );

        return redirect()
            ->route('bienes.categorias.index', ['tab' => 'ubicaciones'])
            ->with('status', 'Ubicación eliminada correctamente.');
    }

    private function resolveTab(Request $request): string
    {
        $tab = trim((string) $request->query('tab', 'categorias'));

        return in_array($tab, ['categorias', 'ubicaciones'], true)
            ? $tab
            : 'categorias';
    }

    private function normalizeCategoryName(string $value): string
    {
        $clean = trim(strip_tags($value));
        $clean = preg_replace('/\s+/u', ' ', $clean) ?? $clean;

        return mb_convert_case($clean, MB_CASE_TITLE, 'UTF-8');
    }

    private function normalizeLocationName(string $value): string
    {
        $clean = trim(strip_tags($value));
        $clean = preg_replace('/\s+/u', ' ', $clean) ?? $clean;

        return mb_convert_case($clean, MB_CASE_TITLE, 'UTF-8');
    }

    private function sanitizeCatalogInput(Request $request): void
    {
        $nombre = is_string($request->input('nombre'))
            ? $this->normalizeInlineCatalogName($request->input('nombre'))
            : $request->input('nombre');

        $request->merge([
            'nombre' => $nombre,
        ]);
    }

    private function normalizeInlineCatalogName(string $value): string
    {
        $clean = trim(strip_tags($value));

        return preg_replace('/\s+/u', ' ', $clean) ?? $clean;
    }

    private function ensureCategoryTextQuality(string $value): void
    {
        if ($this->hasMoreDigitsThanAllowed($value, 3)) {
            throw ValidationException::withMessages([
                'nombre' => 'La categoría puede contener como máximo 3 números.',
            ]);
        }

        if ($this->looksLikeGibberish($value, 18, 4)) {
            throw ValidationException::withMessages([
                'nombre' => 'La categoría no parece estar bien formulada. Evita texto aleatorio o sin sentido.',
            ]);
        }
    }

    private function ensureLocationTextQuality(string $value): void
    {
        if ($this->hasMoreDigitsThanAllowed($value, 3)) {
            throw ValidationException::withMessages([
                'nombre' => 'La ubicación puede contener como máximo 3 números.',
            ]);
        }

        if ($this->looksLikeGibberish($value, 24, 5)) {
            throw ValidationException::withMessages([
                'nombre' => 'La ubicación no parece válida. Usa una ubicación clara (ej: Oficina 2, Depósito A).',
            ]);
        }
    }

    private function looksLikeGibberish(string $text, int $maxWordLength, int $maxConsonantCluster): bool
    {
        $clean = $this->normalizeInlineCatalogName($text);

        if ($clean === '') {
            return false;
        }

        if (preg_match('/(.)\1{3,}/u', $clean)) {
            return true;
        }

        if (preg_match('/[bcdfghjklmnñpqrstvwxyz]{' . $maxConsonantCluster . ',}/iu', $clean)) {
            return true;
        }

        if (preg_match('/[\p{L}\d]{25,}/u', $clean)) {
            return true;
        }

        $words = preg_split('/[\s,.;:()\-\/#]+/u', $clean, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        foreach ($words as $word) {
            if (mb_strlen($word, 'UTF-8') > $maxWordLength) {
                return true;
            }

            if (preg_match('/(\p{L}{2,5})\1{2,}/iu', $word)) {
                return true;
            }

            if (
                mb_strlen($word, 'UTF-8') >= 14
                && preg_match('/(\p{L}{4,8}).*\1/iu', $word)
            ) {
                return true;
            }
        }

        return false;
    }

    private function hasMoreDigitsThanAllowed(string $text, int $maxDigits): bool
    {
        if ($text === '') {
            return false;
        }

        return preg_match_all('/\d/u', $text) > $maxDigits;
    }
}
