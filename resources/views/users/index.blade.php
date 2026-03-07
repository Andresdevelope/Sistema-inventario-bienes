@extends('layouts.dashboard')

@section('content')
    <div class="space-y-5 w-full">
        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1.5 mb-1">
            <div>
                <h1 class="text-xl font-semibold tracking-tight">Gestión de usuarios</h1>
                <p class="text-sm text-slate-400">Lista de usuarios registrados en el sistema.</p>
            </div>
            <button type="button" data-open-create-user
                class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2 text-[12px] font-semibold text-white shadow-lg shadow-brand-900/30 transition hover:-translate-y-0.5 hover:shadow-brand-900/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                </svg>
                Registrar usuario
            </button>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-800/90 bg-slate-900/75 shadow-xl ring-1 ring-slate-700/40">
            <table class="w-full table-auto text-sm">
                <thead class="bg-gradient-to-r from-slate-900 to-slate-800 border-b border-slate-700 text-slate-300 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2.5 text-left font-medium">ID</th>
                        <th class="px-4 py-2.5 text-left font-medium">Nombre</th>
                        <th class="px-4 py-2.5 text-left font-medium">Correo</th>
                        <th class="px-4 py-2.5 text-left font-medium">Rol</th>
                        <th class="px-4 py-2.5 text-left font-medium">Estado</th>
                        <th class="px-4 py-2.5 text-right font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse ($users as $user)
                        <tr class="odd:bg-slate-900/30 even:bg-slate-900/55 hover:bg-slate-800/80 transition-colors duration-200">
                            <td class="px-4 py-2.5 align-middle text-slate-300">
                                <span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-950/70 px-2 py-0.5 text-[11px] font-semibold text-slate-200">#{{ $user->id }}</span>
                            </td>
                            <td class="px-4 py-2.5 align-middle text-slate-100">{{ $user->name }}</td>
                            <td class="px-4 py-2.5 align-middle text-slate-300">{{ $user->email }}</td>
                            <td class="px-4 py-2.5 align-middle">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs
                                    {{ $user->role === 'admin' ? 'border-amber-400/60 text-amber-200 bg-amber-500/10' : 'border-slate-600/70 text-slate-200 bg-slate-700/30' }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 align-middle">
                                @if($user->locked_until)
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs border-red-500/60 text-red-200 bg-red-500/10">Bloqueado</span>
                                @else
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs border-emerald-500/60 text-emerald-200 bg-emerald-500/10">Activo</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 align-middle text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-accent-400 to-accent-500 px-4 py-2 text-[12px] font-semibold text-brand-900 shadow-lg shadow-accent-900/20 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-200 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l2.651 2.651M5.25 15.75l9.513-9.513a1.5 1.5 0 012.121 0l1.379 1.379a1.5 1.5 0 010 2.121L8.75 19.25H5.25v-3.5z" />
                                        </svg>
                                        Editar
                                    </a>

                                    @if($user->locked_until)
                                        <form method="POST" action="{{ route('users.unlock', $user) }}" data-unlock-user-form>
                                            @csrf
                                            <button type="button" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2 text-[12px] font-semibold text-white shadow-lg shadow-brand-900/30 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 cursor-pointer" data-unlock-user-trigger>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4">
                                                    <path fill-rule="evenodd" d="M3 13.125C3 12.504 3.504 12 4.125 12h15.75c.621 0 1.125.504 1.125 1.125v5.25A1.125 1.125 0 0119.875 19.5H4.125A1.125 1.125 0 013 18.375v-5.25zm5.25-1.875V6.75a3.75 3.75 0 117.5 0V12" clip-rule="evenodd" />
                                                </svg>
                                                Desbloquear
                                            </button>
                                        </form>
                                    @endif

                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" data-delete-user-form>
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="inline-flex items-center gap-1.5 rounded-2xl border border-red-500/50 bg-red-500/10 px-3 py-1.5 text-xs font-semibold text-red-200 shadow-inner shadow-red-900/20 transition duration-300 hover:-translate-y-0.5 hover:bg-red-500/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-300 cursor-pointer" data-delete-user-trigger>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                                    <path fill-rule="evenodd" d="M16.5 4.5a.75.75 0 01.75.75V6h3a.75.75 0 010 1.5h-.638l-1.018 11.2a2.25 2.25 0 01-2.245 2.05H7.652a2.25 2.25 0 01-2.245-2.05L4.39 7.5H3.75A.75.75 0 013 6h3V5.25a.75.75 0 01.75-.75h9.75zm-7.5 4.5a.75.75 0 00-1.5 0v9a.75.75 0 001.5 0v-9zm7.5 0a.75.75 0 00-1.5 0v9a.75.75 0 001.5 0v-9z" clip-rule="evenodd" />
                                                </svg>
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-400 text-sm">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Modales movidos al layout para asegurar posicionamiento fixed y centrado --}}

        @push('scripts')
            <script>
                function togglePassword(_, btn) {
                    const input = btn.parentElement.querySelector('input[type="password"], input[type="text"]');
                    const icon = btn.querySelector('svg');
                    if (!input) return;
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.innerHTML = "<path stroke-linecap='round' stroke-linejoin='round' d='M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z'/><path stroke-linecap='round' stroke-linejoin='round' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'/><path stroke-linecap='round' stroke-linejoin='round' d='M4.5 4.5l15 15' />";
                    } else {
                        input.type = 'password';
                        icon.innerHTML = "<path stroke-linecap='round' stroke-linejoin='round' d='M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z'/><path stroke-linecap='round' stroke-linejoin='round' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'/>";
                    }
                }

                (function initUserModals() {
                    // Modal crear usuario
                    const createModal = document.getElementById('create-user-modal');
                    const openBtn = document.querySelector('[data-open-create-user]');

                    function openCreateModal() {
                        if (createModal) createModal.classList.remove('hidden');
                    }

                    function closeCreateModal() {
                        if (createModal) createModal.classList.add('hidden');
                    }

                    openBtn?.addEventListener('click', openCreateModal);

                    createModal?.querySelectorAll('[data-close-modal]').forEach(btn => {
                        btn.addEventListener('click', closeCreateModal);
                    });

                    createModal?.addEventListener('click', function (e) {
                        if (e.target === createModal) {
                            closeCreateModal();
                        }
                    });

                    // Modal eliminar usuario (mismo estilo que bienes)
                    const deleteModal = document.getElementById('confirm-delete-user-modal');
                    const titleEl = deleteModal?.querySelector('[data-modal-user-title]');
                    const textEl = deleteModal?.querySelector('[data-modal-user-text]');
                    const cancelBtn = deleteModal?.querySelector('[data-modal-user-cancel]');
                    const confirmBtn = deleteModal?.querySelector('[data-modal-user-confirm]');
                    const adminPasswordInput = deleteModal?.querySelector('[data-modal-admin-password]');
                    let currentForm = null;

                    function openDeleteModal(form) {
                        currentForm = form;
                        if (!deleteModal) return;
                        const userName = form.closest('tr')?.querySelectorAll('td')[1]?.innerText?.trim() || 'este usuario';
                        if (titleEl) titleEl.textContent = 'Eliminar usuario';
                        if (textEl) textEl.textContent = `¿Seguro que deseas eliminar "${userName}"? Esta acción no se puede deshacer.`;
                        deleteModal.classList.remove('hidden');
                        if (adminPasswordInput) adminPasswordInput.value = '';
                    }

                    function closeDeleteModal() {
                        if (!deleteModal) return;
                        deleteModal.classList.add('hidden');
                        currentForm = null;
                    }

                    document.querySelectorAll('form[data-delete-user-form] [data-delete-user-trigger]').forEach(btn => {
                        btn.addEventListener('click', function () {
                            const form = this.closest('form[data-delete-user-form]');
                            if (!form) return;
                            openDeleteModal(form);
                        });
                    });

                    cancelBtn?.addEventListener('click', closeDeleteModal);

                    confirmBtn?.addEventListener('click', function () {
                        if (currentForm) {
                            // Inyectar contraseña como campo oculto en el formulario
                            const hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = 'admin_password';
                            hidden.value = adminPasswordInput?.value || '';
                            currentForm.appendChild(hidden);
                            currentForm.submit();
                        }
                        closeDeleteModal();
                    });

                    deleteModal?.addEventListener('click', function (e) {
                        if (e.target === deleteModal) {
                            closeDeleteModal();
                        }
                    });
                })();

                (function initUnlockModal() {
                    const unlockModal = document.getElementById('confirm-unlock-user-modal');
                    const cancelBtn = unlockModal?.querySelector('[data-modal-unlock-cancel]');
                    const confirmBtn = unlockModal?.querySelector('[data-modal-unlock-confirm]');
                    const adminPasswordInput = unlockModal?.querySelector('[data-modal-admin-password-unlock]');
                    let currentUnlockForm = null;

                    function openUnlockModal(form) {
                        currentUnlockForm = form;
                        if (!unlockModal) return;
                        unlockModal.classList.remove('hidden');
                        if (adminPasswordInput) adminPasswordInput.value = '';
                    }

                    function closeUnlockModal() {
                        if (!unlockModal) return;
                        unlockModal.classList.add('hidden');
                        currentUnlockForm = null;
                    }

                    document.querySelectorAll('form[data-unlock-user-form] [data-unlock-user-trigger]').forEach(btn => {
                        btn.addEventListener('click', function () {
                            const form = this.closest('form[data-unlock-user-form]');
                            if (!form) return;
                            openUnlockModal(form);
                        });
                    });

                    cancelBtn?.addEventListener('click', closeUnlockModal);

                    confirmBtn?.addEventListener('click', function () {
                        if (currentUnlockForm) {
                            const hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = 'admin_password';
                            hidden.value = adminPasswordInput?.value || '';
                            currentUnlockForm.appendChild(hidden);
                            currentUnlockForm.submit();
                        }
                        closeUnlockModal();
                    });

                    unlockModal?.addEventListener('click', function (e) {
                        if (e.target === unlockModal) {
                            closeUnlockModal();
                        }
                    });
                })();
            </script>
        @endpush
    </div>
@endsection
