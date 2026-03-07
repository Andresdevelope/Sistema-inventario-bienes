@extends('layouts.dashboard')

@section('content')
    <div class="space-y-5 w-full">
        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1.5 mb-1">
            <div>
                <h1 class="text-xl font-semibold tracking-tight">Inventario de bienes</h1>
                <p class="text-sm text-slate-400">Listado básico de bienes registrados en el sistema.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('bienes.categorias.index') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-brand-900/30 transition duration-300 hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5m-16.5 5.25h16.5m-16.5 5.25h16.5" />
                    </svg>
                    Gestionar catálogos
                </a>
                <a href="{{ route('bienes.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-accent-400 to-accent-500 px-4 py-2 text-xs font-semibold text-brand-900 shadow-lg shadow-accent-900/20 transition duration-300 hover:-translate-y-0.5 hover:shadow-accent-900/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                    Registrar nuevo bien
                    <span class="inline-flex items-center rounded-full border border-brand-900/20 bg-brand-900/5 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-brand-900">
                        +1
                    </span>
                </a>
            </div>
        </div>
        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4 md:p-5" aria-label="Acciones de reportes">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-1">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-brand-600">Reportes</p>
                    <h2 class="text-sm md:text-base font-semibold text-slate-900">Exportación de inventario en PDF</h2>
                    <p class="text-xs text-slate-500">Genera reporte general o por categoría para revisión y soporte administrativo.</p>
                </div>

                <div class="w-full lg:w-auto grid gap-2 sm:grid-cols-[minmax(220px,1fr)_auto_auto] lg:min-w-[760px]">
                    <div class="space-y-1">
                        <label for="reporte-categoria" class="block text-[11px] font-medium text-slate-600">Categoría para reporte específico</label>
                        <select id="reporte-categoria" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400">
                            <option value="">Seleccione una categoría…</option>
                            @foreach(($categoriasActivas ?? collect()) as $categoria)
                                <option value="{{ $categoria }}">{{ $categoria }}</option>
                            @endforeach
                        </select>
                    </div>

                    <a href="{{ route('bienes.reportes.pdf') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-brand-900/30 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v4.125c0 .621-.504 1.125-1.125 1.125H5.625A1.125 1.125 0 014.5 18.375V5.625C4.5 5.004 5.004 4.5 5.625 4.5H12" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75h3.75m0 0V7.5m0-3.75L10.5 13.5" />
                        </svg>
                        PDF general
                    </a>

                    <button type="button" id="btn-reporte-categoria" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-accent-400 to-accent-500 px-4 py-2 text-xs font-semibold text-brand-900 shadow-lg shadow-accent-900/20 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-200 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5M3.75 9h16.5M3.75 13.5h9.75m-9.75 4.5h9.75" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75h4.5m0 0l-2.25-2.25m2.25 2.25l-2.25 2.25" />
                        </svg>
                        PDF por categoría
                    </button>
                </div>
            </div>
        </section>
        <!-- buscador y filtros para tabla de bienes -->
        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4 md:p-5" aria-label="Buscador y filtros de bienes">
            <form method="GET" action="{{ route('bienes.index') }}" class="space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3">
                    <div class="xl:col-span-2">
                        <label for="search-bien" class="block text-[11px] font-medium text-slate-600 mb-1">Búsqueda general</label>
                        <input
                            id="search-bien"
                            type="text"
                            name="search"
                            value="{{ $filtros['search'] ?? '' }}"
                            placeholder="Nombre, código, descripción, categoría o ubicación"
                            autocomplete="off"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400"
                        >
                    </div>

                    <div>
                        <label for="estado-bien" class="block text-[11px] font-medium text-slate-600 mb-1">Estado</label>
                        <select id="estado-bien" name="estado" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400">
                            <option value="">Todos</option>
                            @foreach(($estadosDisponibles ?? []) as $estado)
                                <option value="{{ $estado }}" {{ ($filtros['estado'] ?? '') === $estado ? 'selected' : '' }}>
                                    {{ $estado === 'de_baja' ? 'Dado de baja' : ucfirst($estado) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="categoria-bien" class="block text-[11px] font-medium text-slate-600 mb-1">Categoría</label>
                        <select id="categoria-bien" name="categoria" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400">
                            <option value="">Todas</option>
                            @foreach(($categoriasActivas ?? collect()) as $categoria)
                                <option value="{{ $categoria }}" {{ ($filtros['categoria'] ?? '') === $categoria ? 'selected' : '' }}>
                                    {{ $categoria }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="ubicacion-bien" class="block text-[11px] font-medium text-slate-600 mb-1">Ubicación</label>
                        <select id="ubicacion-bien" name="ubicacion" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400">
                            <option value="">Todas</option>
                            @foreach(($ubicacionesActivas ?? collect()) as $ubicacion)
                                <option value="{{ $ubicacion->id }}" {{ (string)($filtros['ubicacion'] ?? '') === (string)$ubicacion->id ? 'selected' : '' }}>
                                    {{ $ubicacion->nombre }}{{ $ubicacion->estado !== 'activo' ? ' (inactiva)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
                    <div class="w-full sm:w-auto">
                        <label for="per-page-bienes" class="block text-[11px] font-medium text-slate-600 mb-1">Registros por página</label>
                        <select id="per-page-bienes" name="per_page" class="w-full sm:w-44 rounded-xl border border-slate-300 bg-white px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400">
                            @foreach(($allowedPerPage ?? [10, 15, 25, 50, 100]) as $size)
                                <option value="{{ $size }}" {{ (int)($filtros['per_page'] ?? 15) === (int)$size ? 'selected' : '' }}>
                                    {{ $size }} registros
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('bienes.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-300 bg-white px-4 py-2 text-[12px] font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-300 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Limpiar filtros
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2 text-[12px] font-semibold text-white shadow-lg shadow-brand-900/30 transition hover:-translate-y-0.5 hover:shadow-brand-900/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.33 1.12 7.216 3.006m0 0V3.75m0 2.256H16.96M12 21a9 9 0 01-7.216-3.606m0 0V20.25m0-2.856h2.256" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 12a6.75 6.75 0 0012.447 3.652M18.75 12A6.75 6.75 0 006.303 8.348" />
                            </svg>
                            Aplicar filtros
                        </button>
                    </div>
                </div>
            </form>
        </section>

        <div id="tabla-bienes">
            @include('bienes.partials.tabla', ['bienes' => $bienes])
        </div>

        <!-- La paginación ya está incluida en la vista parcial -->
        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoriaSelect = document.getElementById('reporte-categoria');
            const btnReporteCategoria = document.getElementById('btn-reporte-categoria');

            btnReporteCategoria?.addEventListener('click', () => {
                const categoria = categoriaSelect?.value || '';
                if (!categoria) {
                    alert('Selecciona una categoría para exportar el PDF por categoría.');
                    return;
                }

                const url = new URL(`{{ route('bienes.reportes.pdf') }}`, window.location.origin);
                url.searchParams.set('categoria', categoria);
                window.location.href = url.toString();
            });
        });
        </script>
        @endpush
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = document.getElementById('confirm-delete-modal');
                const modalTitle = modal?.querySelector('[data-modal-title]');
                const modalText = modal?.querySelector('[data-modal-text]');
                const cancelBtn = modal?.querySelector('[data-modal-cancel]');
                const confirmBtn = modal?.querySelector('[data-modal-confirm]');
                let currentForm = null;

                function openModal(form) {
                    currentForm = form;
                    if (!modal) return;
                    const row = form.closest('[data-bien-nombre]');
                    const bienNombre = row?.getAttribute('data-bien-nombre') || 'este bien';
                    if (modalTitle) modalTitle.textContent = 'Eliminar bien';
                    if (modalText) modalText.textContent = `¿Seguro que deseas eliminar "${bienNombre}"? Esta acción no se puede deshacer.`;
                    modal.classList.remove('hidden');
                }

                function closeModal() {
                    if (!modal) return;
                    modal.classList.add('hidden');
                    currentForm = null;
                }

                document.addEventListener('click', function (e) {
                    const trigger = e.target.closest('[data-delete-trigger]');
                    if (!trigger) return;

                    const form = trigger.closest('form[data-delete-form]');
                    if (!form) return;

                    openModal(form);
                });

                cancelBtn?.addEventListener('click', function () {
                    closeModal();
                });

                confirmBtn?.addEventListener('click', function () {
                    if (currentForm) {
                        currentForm.submit();
                    }
                    closeModal();
                });

                modal?.addEventListener('click', function (e) {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
            });
        </script>

        <div id="confirm-delete-modal" class="hidden fixed inset-0 z-40 flex items-center justify-center bg-black/50" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title" aria-describedby="delete-modal-text">
            <div class="w-full max-w-sm rounded-2xl bg-slate-900 border border-slate-700 shadow-2xl p-5 text-xs text-slate-100">
                <h2 id="delete-modal-title" class="text-sm font-semibold mb-2 flex items-center gap-2" data-modal-title>
                    Eliminar bien
                </h2>
                <p id="delete-modal-text" class="text-[11px] text-slate-300 mb-4" data-modal-text>
                    ¿Seguro que deseas eliminar este bien? Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-end gap-2 text-[11px]">
                    <button type="button" data-modal-cancel class="inline-flex items-center rounded-md border border-slate-600 bg-slate-800 px-3 py-1.5 text-slate-100 hover:bg-slate-700 cursor-pointer">
                        Cancelar
                    </button>
                    <button type="button" data-modal-confirm class="inline-flex items-center rounded-md border border-red-500/60 bg-red-500/10 px-3 py-1.5 text-red-200 hover:bg-red-500/20 cursor-pointer">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endpush
    </div>
@endsection
