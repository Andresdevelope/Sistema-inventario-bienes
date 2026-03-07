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
use Illuminate\Support\Collection;
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
        $allowedPerPage = [10, 15, 25, 50, 100];
        $perPage = (int) $request->integer('per_page', 15);
        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 15;
        }

        $search = trim((string) $request->input('search', ''));
        $estado = trim((string) $request->input('estado', ''));
        $categoria = trim((string) $request->input('categoria', ''));
        $ubicacionId = $request->input('ubicacion');
        $estadosDisponibles = ['bueno', 'regular', 'malo', 'de_baja'];

        if (is_string($ubicacionId)) {
            $ubicacionId = trim($ubicacionId);
        }

        $ubicacionId = is_numeric($ubicacionId) ? (int) $ubicacionId : null;

        if (!in_array($estado, $estadosDisponibles, true)) {
            $estado = '';
        }

        $query = Bien::query()
            ->with('ubicacionCatalogo:id,nombre,estado')
            ->select(['id', 'nombre', 'codigo', 'descripcion', 'categoria', 'ubicacion', 'ubicacion_id', 'estado'])
            ->latest('id');

        // Filtro por búsqueda general (nombre, código, descripción, categoría, ubicación)
        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                  ->orWhere('codigo', 'like', "%$search%")
                  ->orWhere('descripcion', 'like', "%$search%")
                  ->orWhere('categoria', 'like', "%$search%")
                  ->orWhere('ubicacion', 'like', "%$search%")
                  ->orWhereHas('ubicacionCatalogo', function ($ubicacionQuery) use ($search) {
                      $ubicacionQuery->where('nombre', 'like', "%$search%");
                  });
            });
        }

        // Filtro por estado
        if ($estado !== '') {
            $query->where('estado', $estado);
        }

        // Filtro por categoría
        if ($categoria !== '') {
            $query->where('categoria', $categoria);
        }

        // Filtro por ubicación
        if ($ubicacionId !== null && $ubicacionId > 0) {
            $query->where('ubicacion_id', $ubicacionId);
        }

        $bienes = $query->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            return view('bienes.partials.tabla', compact('bienes'));
        }

        $categoriasActivas = $this->activeCategoryNames();
        $ubicacionesActivas = $this->activeLocationOptions($ubicacionId);
        $resumenCategorias = $this->categorySummary();
        $filtros = [
            'search' => $search,
            'estado' => $estado,
            'categoria' => $categoria,
            'ubicacion' => $ubicacionId,
            'per_page' => $perPage,
        ];

        return view('bienes.index', compact('bienes', 'categoriasActivas', 'ubicacionesActivas', 'resumenCategorias', 'filtros', 'estadosDisponibles', 'allowedPerPage'));
    }

    /**
     * Mostrar formulario para registrar un nuevo bien.
     */
    public function create(): View
    {
        $categoriasActivas = $this->activeCategoryNames();
        $ubicacionesActivas = $this->activeLocationOptions();

        return view('bienes.create', compact('categoriasActivas', 'ubicacionesActivas'));
    }

    /**
     * Guardar en base de datos un nuevo bien a partir del formulario.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->sanitizeBienInput($request);

        $validated = $request->validate(
            $this->bienValidationRules(),
            $this->bienValidationMessages()
        );

        $this->validateTextQuality($validated);
        $this->syncCategoryCatalog($validated['categoria'] ?? null);
        $validated = $this->resolveLocationPayload($validated);

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
        $bien->load('ubicacionCatalogo:id,nombre,estado');

        return view('bienes.show', compact('bien'));
    }

    /**
     * Mostrar formulario de edición para un bien específico.
     */
    public function edit(Bien $bien): View
    {
        $categoriasActivas = $this->activeCategoryNames();
        $ubicacionesActivas = $this->activeLocationOptions($bien->ubicacion_id);

        return view('bienes.edit', compact('bien', 'categoriasActivas', 'ubicacionesActivas'));
    }

    /**
     * Exporta reporte PDF general o filtrado por categoría.
     */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'categoria' => ['nullable', 'string', Rule::exists('categorias', 'nombre')],
        ]);

        $categoriaFiltro = $request->input('categoria');

        $query = Bien::query()
            ->with('ubicacionCatalogo:id,nombre')
            ->select(['id', 'nombre', 'codigo', 'categoria', 'estado', 'ubicacion', 'ubicacion_id'])
            ->latest('id');

        if (is_string($categoriaFiltro) && trim($categoriaFiltro) !== '') {
            $query->where('categoria', trim($categoriaFiltro));
            $categoriaFiltro = trim($categoriaFiltro);
        } else {
            $categoriaFiltro = null;
        }

        $bienes = $query->get();

        $resumenCategorias = Bien::query()
            ->selectRaw("COALESCE(NULLIF(TRIM(categoria), ''), 'Sin categoría') as categoria_nombre, COUNT(*) as total")
            ->when($categoriaFiltro, function ($q) use ($categoriaFiltro) {
                $q->where('categoria', $categoriaFiltro);
            })
            ->groupBy('categoria_nombre')
            ->orderBy('categoria_nombre')
            ->get();

        $titulo = $categoriaFiltro
            ? 'Reporte de bienes por categoría'
            : 'Reporte general de bienes';

        $viewData = [
            'titulo' => $titulo,
            'fechaGeneracion' => now()->format('d/m/Y H:i'),
            'filtroCategoria' => $categoriaFiltro,
            'totalBienes' => $bienes->count(),
            'resumenCategorias' => $resumenCategorias,
            'bienes' => $bienes,
        ];

        $suffix = $categoriaFiltro
            ? 'categoria-' . preg_replace('/[^A-Za-z0-9\-]+/', '-', mb_strtolower($categoriaFiltro, 'UTF-8'))
            : 'general';

        $pdfFacade = '\\Barryvdh\\DomPDF\\Facade\\Pdf';

        if (class_exists($pdfFacade)) {
            return $pdfFacade::loadView('bienes.reportes.pdf', $viewData)
                ->setPaper('a4', 'portrait')
                ->download('reporte-bienes-' . $suffix . '.pdf');
        }

        $html = view('bienes.reportes.pdf', $viewData)->render();

        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    /**
     * Actualizar en base de datos un bien existente.
     */
    public function update(Request $request, Bien $bien): RedirectResponse
    {
        $this->sanitizeBienInput($request);

        $rules = $this->bienValidationRules();
        $rules['codigo'] = ['required', 'string', 'min:3', 'max:20', 'regex:/^(?=.{3,20}$)(?=.*\d)(?!.*[-\/]{2})[A-Za-z0-9]+(?:[-\/][A-Za-z0-9]+)*$/', 'not_regex:/<[^>]*>/', 'unique:bienes,codigo,' . $bien->id];

        $validated = $request->validate($rules, $this->bienValidationMessages());

        $this->validateTextQuality($validated);
        $this->syncCategoryCatalog($validated['categoria'] ?? null);
        $validated = $this->resolveLocationPayload($validated);

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

    /**
     * Normaliza entradas de texto para reducir ruido y prevenir payloads HTML.
     */
    private function sanitizeBienInput(Request $request): void
    {
        $nombre = is_string($request->input('nombre'))
            ? $this->toTitleCase($this->normalizeSpaces(strip_tags($request->input('nombre'))))
            : $request->input('nombre');

        $codigo = is_string($request->input('codigo'))
            ? $this->normalizeSpaces(strip_tags($request->input('codigo')))
            : $request->input('codigo');

        if (is_string($codigo) && preg_match('/^[\p{L}]+$/u', $codigo)) {
            $codigo = mb_strtoupper($codigo, 'UTF-8');
        }

        $descripcion = is_string($request->input('descripcion'))
            ? $this->capitalizeFirstLetter($this->normalizeSpaces(strip_tags($request->input('descripcion'))))
            : $request->input('descripcion');

        $categoria = is_string($request->input('categoria'))
            ? $this->toTitleCase($this->normalizeSpaces(strip_tags($request->input('categoria'))))
            : $request->input('categoria');

        $ubicacion = is_string($request->input('ubicacion'))
            ? $this->capitalizeFirstLetter($this->normalizeSpaces(strip_tags($request->input('ubicacion'))))
            : $request->input('ubicacion');

        $ubicacionId = $request->input('ubicacion_id');

        if (is_string($ubicacionId)) {
            $ubicacionId = trim($ubicacionId);
            $ubicacionId = $ubicacionId === '' ? null : $ubicacionId;
        }

        $request->merge([
            'nombre' => $nombre,
            'codigo' => $codigo,
            'descripcion' => $descripcion,
            'categoria' => $categoria,
            'ubicacion' => $ubicacion,
            'ubicacion_id' => $ubicacionId,
        ]);
    }

    /**
     * Normaliza múltiples espacios internos a uno y recorta extremos.
     */
    private function normalizeSpaces(string $value): string
    {
        return preg_replace('/\s+/u', ' ', trim($value)) ?? trim($value);
    }

    /**
     * Convierte una cadena a formato título (iniciales en mayúscula).
     */
    private function toTitleCase(string $value): string
    {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Convierte la primera letra a mayúscula manteniendo el resto del texto.
     */
    private function capitalizeFirstLetter(string $value): string
    {
        if ($value === '') {
            return $value;
        }

        $first = mb_substr($value, 0, 1, 'UTF-8');
        $rest = mb_substr($value, 1, null, 'UTF-8');

        return mb_strtoupper($first, 'UTF-8') . $rest;
    }

    /**
     * Reglas de validación robustas para bienes.
     */
    private function bienValidationRules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3', 'max:40', 'regex:/^(?=.{3,40}$)(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,;:()\-\/]+$/u', 'not_regex:/<[^>]*>/'],
            'codigo' => ['required', 'string', 'min:3', 'max:20', 'regex:/^(?=.{3,20}$)(?=.*\d)(?!.*[-\/]{2})[A-Za-z0-9]+(?:[-\/][A-Za-z0-9]+)*$/', 'not_regex:/<[^>]*>/', 'unique:bienes,codigo'],
            'descripcion' => ['required', 'string', 'min:10', 'max:70', 'regex:/^(?=.{10,70}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,;:()\-\/]+$/u', 'not_regex:/<[^>]*>/'],
            'categoria' => ['required', 'string', 'min:3', 'max:30', 'regex:/^(?=.{3,30}$)(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ .\-]+$/u', 'not_regex:/<[^>]*>/'],
            'ubicacion_id' => ['nullable', 'integer', Rule::exists('ubicaciones', 'id')->where(fn ($query) => $query->where('estado', 'activo'))],
            'ubicacion' => ['nullable', 'string', 'min:3', 'max:50', 'regex:/^(?=.{3,50}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,\-#°]+$/u', 'not_regex:/<[^>]*>/'],
            'estado' => ['required', 'in:bueno,regular,malo,de_baja'],
            'fecha_adquisicion' => ['nullable', 'date'],
        ];
    }

    /**
     * Mensajes de error amigables para formulario de bienes.
     */
    private function bienValidationMessages(): array
    {
        return [
            'nombre.required' => 'El nombre del bien es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede superar los 40 caracteres.',
            'nombre.regex' => 'El nombre debe estar bien formulado (letras y texto válido).',
            'nombre.not_regex' => 'El nombre no puede contener etiquetas HTML o código.',

            'codigo.required' => 'El código del bien es obligatorio.',
            'codigo.min' => 'El código debe tener al menos 3 caracteres.',
            'codigo.max' => 'El código no puede superar los 20 caracteres.',
            'codigo.regex' => 'El código debe ser organizado y contener al menos un número (ej: BIEN-001 o INV/2026/01).',
            'codigo.unique' => 'Ya existe un bien con este código.',
            'codigo.not_regex' => 'El código no puede contener etiquetas HTML o código.',

            'descripcion.null' => 'La descripción del bien es obligatoria.',
            'descripcion.min' => 'La descripción debe tener al menos 5 caracteres.',
            'descripcion.max' => 'La descripción no puede superar los 70 caracteres.',
            'descripcion.regex' => 'La descripción contiene caracteres no permitidos.',
            'descripcion.not_regex' => 'La descripción no puede contener etiquetas HTML o código.',

            'categoria.required' => 'La categoría es obligatoria.',
            'categoria.min' => 'La categoría debe tener al menos 3 caracteres.',
            'categoria.max' => 'La categoría no puede superar los 30 caracteres.',
            'categoria.regex' => 'La categoría debe estar bien escrita (solo texto válido).',
            'categoria.not_regex' => 'La categoría no puede contener etiquetas HTML o código.',

            'ubicacion_id.integer' => 'La ubicación seleccionada no es válida.',
            'ubicacion_id.exists' => 'La ubicación seleccionada no está disponible en el catálogo activo.',

            'ubicacion.min' => 'La ubicación debe tener al menos 3 caracteres.',
            'ubicacion.max' => 'La ubicación no puede superar los 50 caracteres.',
            'ubicacion.regex' => 'La ubicación debe estar bien formulada (ej: Oficina 1, Depósito).',
            'ubicacion.not_regex' => 'La ubicación no puede contener etiquetas HTML o código.',

            'estado.required' => 'El estado del bien es obligatorio.',
            'estado.in' => 'El estado seleccionado no es válido. Debe ser bueno, regular, malo o dado de baja.',

            'fecha_adquisicion.date' => 'La fecha de adquisición no tiene un formato válido.',
        ];
    }

    /**
     * Validación heurística para bloquear entradas "a lo loco".
     */
    private function validateTextQuality(array $validated): void
    {
        $errors = [];

        if (isset($validated['nombre']) && $this->looksLikeGibberish($validated['nombre'], 16, 4)) {
            $errors['nombre'] = 'El nombre no parece adecuado para el bien. Evita texto aleatorio.';
        }

        if (!empty($validated['categoria']) && $this->looksLikeGibberish($validated['categoria'], 16, 3)) {
            $errors['categoria'] = 'La categoría no parece estar bien formulada. Usa palabras claras.';
        }

        if (isset($validated['descripcion']) && $this->looksLikeGibberish($validated['descripcion'], 20, 6)) {
            $errors['descripcion'] = 'La descripción parece texto aleatorio. Escribe una descripción coherente.';
        }

        if (!empty($validated['ubicacion']) && $this->looksLikeGibberish($validated['ubicacion'], 14, 4)) {
            $errors['ubicacion'] = 'La ubicación no parece válida. Usa una ubicación bien formulada (ej: Oficina 2, Depósito A).';
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Detecta posibles cadenas sin sentido (heurística).
     */
    private function looksLikeGibberish(string $text, int $maxWordLength, int $maxConsonantCluster): bool
    {
        $clean = $this->normalizeSpaces($text);

        if ($clean === '') {
            return false;
        }

        if (preg_match('/(.)\1{3,}/u', $clean)) {
            return true;
        }

        if (preg_match('/[bcdfghjklmnñpqrstvwxyz]{' . $maxConsonantCluster . ',}/iu', $clean)) {
            return true;
        }

        $words = preg_split('/[\s,.;:()\-\/#]+/u', $clean, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        foreach ($words as $word) {
            $len = mb_strlen($word, 'UTF-8');
            if ($len > $maxWordLength) {
                return true;
            }
        }

        return false;
    }

    /**
     * Nombres de categorías activas para formularios/selectores.
     */
    private function activeCategoryNames(): Collection
    {
        return Categoria::query()
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->pluck('nombre');
    }

    /**
     * Ubicaciones activas para formularios/selectores.
     */
    private function activeLocationOptions(?int $selectedId = null): Collection
    {
        return Ubicacion::query()
            ->select(['id', 'nombre', 'estado'])
            ->where(function ($query) use ($selectedId) {
                $query->where('estado', 'activo');

                if ($selectedId !== null) {
                    $query->orWhere('id', $selectedId);
                }
            })
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Resumen por categoría para visualización/soporte de reportes.
     */
    private function categorySummary(): Collection
    {
        return Bien::query()
            ->selectRaw("COALESCE(NULLIF(TRIM(categoria), ''), 'Sin categoría') as categoria_nombre, COUNT(*) as total")
            ->groupBy('categoria_nombre')
            ->orderByDesc('total')
            ->orderBy('categoria_nombre')
            ->get();
    }

    /**
     * Mantiene sincronizado el catálogo de categorías según datos guardados en bienes.
     */
    private function syncCategoryCatalog(?string $categoria): void
    {
        $nombre = is_string($categoria) ? trim($categoria) : '';

        if ($nombre === '') {
            return;
        }

        Categoria::query()->updateOrCreate(
            ['nombre' => $nombre],
            ['estado' => 'activo']
        );
    }

    /**
     * Mantiene sincronizado el catálogo de ubicaciones y alinea el payload con ubicacion_id.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function resolveLocationPayload(array $validated): array
    {
        $ubicacionId = isset($validated['ubicacion_id']) && $validated['ubicacion_id'] !== null
            ? (int) $validated['ubicacion_id']
            : null;

        if ($ubicacionId) {
            $ubicacion = Ubicacion::query()->find($ubicacionId);

            if ($ubicacion) {
                $validated['ubicacion_id'] = $ubicacion->id;
                $validated['ubicacion'] = $ubicacion->nombre;

                return $validated;
            }
        }

        $nombreLegacy = is_string($validated['ubicacion'] ?? null)
            ? trim($validated['ubicacion'])
            : '';

        if ($nombreLegacy === '') {
            $validated['ubicacion_id'] = null;
            $validated['ubicacion'] = null;

            return $validated;
        }

        $ubicacion = $this->syncLocationCatalog($nombreLegacy);

        $validated['ubicacion_id'] = $ubicacion?->id;
        $validated['ubicacion'] = $ubicacion?->nombre;

        return $validated;
    }

    private function syncLocationCatalog(?string $ubicacion): ?Ubicacion
    {
        $nombre = is_string($ubicacion) ? trim($ubicacion) : '';

        if ($nombre === '') {
            return null;
        }

        return Ubicacion::query()->updateOrCreate(
            ['nombre' => $nombre],
            ['estado' => 'activo']
        );
    }

}
