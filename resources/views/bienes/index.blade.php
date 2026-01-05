@extends('layouts.dashboard')

@section('content')
    <div class="space-y-5 w-full">
        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1.5 mb-1">
            <div>
                <h1 class="text-xl font-semibold tracking-tight">Inventario de bienes</h1>
                <p class="text-sm text-slate-400">Listado básico de bienes registrados en el sistema.</p>
            </div>
            <a href="{{ route('bienes.create') }}" class="inline-flex items-center rounded-md bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow hover:from-blue-500 hover:to-indigo-500 cursor-pointer">
                Registrar nuevo bien
            </a>
        </div>
        <!-- buscador o filtrador de bienes -->
        <div>
            <form method="GET" action="{{ route('bienes.index') }}" class="mb-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
                    <input type="text" name="search" placeholder="Buscar bien por nombre, código o descripción" value="{{ request('search') }}"
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
            let timeout = null;
            if (input && tablaBienes) {
                input.addEventListener('input', function () {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        fetch(`{{ route('bienes.index') }}?search=${encodeURIComponent(input.value)}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(res => res.text())
                        .then(html => {
                            tablaBienes.innerHTML = html;
                        });
                    }, 300); // Espera 300ms tras dejar de escribir
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
                    const bienNombre = form.closest('tr')?.querySelector('td')?.innerText?.trim() || 'este bien';
                    if (modalTitle) modalTitle.textContent = 'Eliminar bien';
                    if (modalText) modalText.textContent = `¿Seguro que deseas eliminar "${bienNombre}"? Esta acción no se puede deshacer.`;
                    modal.classList.remove('hidden');
                }

                function closeModal() {
                    if (!modal) return;
                    modal.classList.add('hidden');
                    currentForm = null;
                }

                document.querySelectorAll('form[data-delete-form] [data-delete-trigger]').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const form = this.closest('form[data-delete-form]');
                        if (!form) return;
                        openModal(form);
                    });
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

        <div id="confirm-delete-modal" class="hidden fixed inset-0 z-40 flex items-center justify-center bg-black/50">
            <div class="w-full max-w-sm rounded-2xl bg-slate-900 border border-slate-700 shadow-2xl p-5 text-xs text-slate-100">
                <h2 class="text-sm font-semibold mb-2 flex items-center gap-2" data-modal-title>
                    Eliminar bien
                </h2>
                <p class="text-[11px] text-slate-300 mb-4" data-modal-text>
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
