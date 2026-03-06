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
                    Gestionar categorías
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
                        PDF general
                    </a>

                    <button type="button" id="btn-reporte-categoria" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-accent-400 to-accent-500 px-4 py-2 text-xs font-semibold text-brand-900 shadow-lg shadow-accent-900/20 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-200 cursor-pointer">
                        PDF por categoría
                    </button>
                </div>
            </div>
        </section>
        <!-- buscador o filtrador de bienes -->
        <div>
            <form method="GET" action="{{ route('bienes.index') }}" class="mb-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                    <label for="search-bien" class="sr-only">Buscar bien por nombre, código o descripción</label>
                    <input id="search-bien" type="text" name="search" placeholder="Buscar bien por nombre, código o descripción" value="{{ request('search') }}"
                        autocomplete="off"
                        class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="mt-2 sm:mt-0 inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-blue-500 cursor-pointer">
                        Buscar
                    </button>
                </div>
            </form>
        </div>

        <div id="tabla-bienes">
            @include('bienes.partials.tabla', ['bienes' => $bienes])
        </div>

        <!-- La paginación ya está incluida en la vista parcial -->
        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.querySelector('input[name="search"]');
            const tablaBienes = document.getElementById('tabla-bienes');
            const categoriaSelect = document.getElementById('reporte-categoria');
            const btnReporteCategoria = document.getElementById('btn-reporte-categoria');
            let timeout = null;
            let abortController = null;
            let lastQuery = (input?.value || '').trim();

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

            function fetchBienes(query) {
                if (!tablaBienes) return;

                if (abortController) {
                    abortController.abort();
                }

                abortController = new AbortController();

                const params = new URLSearchParams(window.location.search);
                if (query) {
                    params.set('search', query);
                } else {
                    params.delete('search');
                }
                params.delete('page');

                fetch(`{{ route('bienes.index') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    signal: abortController.signal,
                })
                .then(res => res.text())
                .then(html => {
                    tablaBienes.innerHTML = html;
                })
                .catch(err => {
                    if (err.name !== 'AbortError') {
                        console.error('Error cargando bienes:', err);
                    }
                });
            }

            if (input && tablaBienes) {
                input.addEventListener('input', function () {
                    clearTimeout(timeout);
                    const query = this.value.trim();

                    timeout = setTimeout(() => {
                        if (query === lastQuery) return;

                        // Evita peticiones por entradas demasiado cortas; permite limpiar a vacío
                        if (query.length > 0 && query.length < 2) return;

                        lastQuery = query;
                        fetchBienes(query);
                    }, 350);
                });
            }
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
