@extends('layouts.dashboard')

@section('content')
    <div class="space-y-5 w-full">
        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1.5 mb-1">
            <div>
                <h1 class="text-xl font-semibold tracking-tight">Gestión de usuarios</h1>
                <p class="text-sm text-slate-400">Lista de usuarios registrados en el sistema.</p>
            </div>
            <button type="button" data-open-create-user
                class="inline-flex items-center rounded-md bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow hover:from-blue-500 hover:to-indigo-500 cursor-pointer">
                Registrar usuario
            </button>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-900/70 shadow-xl">
            <table class="w-full table-auto text-sm">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400">
                    <tr>
                        <th class="px-4 py-2.5 text-left font-medium">ID</th>
                        <th class="px-4 py-2.5 text-left font-medium">Nombre</th>
                        <th class="px-4 py-2.5 text-left font-medium">Correo</th>
                        <th class="px-4 py-2.5 text-left font-medium">Rol</th>
                        <th class="px-4 py-2.5 text-right font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-900/80">
                            <td class="px-4 py-2.5 align-middle text-slate-400">{{ $user->id }}</td>
                            <td class="px-4 py-2.5 align-middle text-slate-100">{{ $user->name }}</td>
                            <td class="px-4 py-2.5 align-middle text-slate-300">{{ $user->email }}</td>
                            <td class="px-4 py-2.5 align-middle">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs
                                    {{ $user->role === 'admin' ? 'border-amber-400/60 text-amber-200 bg-amber-500/10' : 'border-slate-600/70 text-slate-200 bg-slate-700/30' }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 align-middle text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center rounded-md bg-slate-800 px-3 py-1.5 text-xs text-slate-100 hover:bg-slate-700 cursor-pointer">
                                        Editar
                                    </a>

                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" data-delete-user-form>
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="inline-flex items-center rounded-md border border-red-500/60 bg-red-500/10 px-3 py-1.5 text-xs text-red-200 hover:bg-red-500/20 cursor-pointer" data-delete-user-trigger>
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-slate-500 text-sm">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Modal para crear usuario --}}
        <div id="create-user-modal" class="hidden fixed inset-0 z-40 flex items-center justify-center bg-black/50">
            <div class="w-full max-w-lg rounded-2xl bg-slate-900 border border-slate-700 shadow-2xl p-6 text-xs text-slate-100">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="text-sm font-semibold">Registrar nuevo usuario</h2>
                        <p class="text-[11px] text-slate-400">Completa los datos para crear un usuario en el sistema.</p>
                    </div>
                    <button type="button" data-close-modal class="text-slate-400 hover:text-slate-200 cursor-pointer text-sm">✕</button>
                </div>

                <form method="POST" action="{{ route('users.store') }}" class="space-y-3">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-medium text-slate-300 mb-1">Nombre de usuario</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-1.5 text-xs text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-slate-300 mb-1">Correo electrónico</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-1.5 text-xs text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-medium text-slate-300 mb-1">Rol</label>
                            <select name="role"
                                class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-1.5 text-xs text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Usuario</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="relative">
                            <label class="block text-[11px] font-medium text-slate-300 mb-1">Contraseña</label>
                            <input type="password" name="password"
                                class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-1.5 pr-8 text-xs text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <button type="button" onclick="togglePassword('password', this)" tabindex="-1"
                                class="absolute right-2 top-7 text-slate-400 hover:text-blue-400 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                        <div class="relative">
                            <label class="block text-[11px] font-medium text-slate-300 mb-1">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation"
                                class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-1.5 pr-8 text-xs text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <button type="button" onclick="togglePassword('password_confirmation', this)" tabindex="-1"
                                class="absolute right-2 top-7 text-slate-400 hover:text-blue-400 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div>
                            <label class="block text-[11px] font-medium text-slate-300 mb-1">¿Cuál es tu color favorito?</label>
                            <input type="text" name="security_color_answer" value="{{ old('security_color_answer') }}"
                                class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-1.5 text-xs text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-slate-300 mb-1">¿Cuál es tu animal favorito?</label>
                            <input type="text" name="security_animal_answer" value="{{ old('security_animal_answer') }}"
                                class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-1.5 text-xs text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-slate-300 mb-1">¿Cuál es el nombre de tu padre?</label>
                            <input type="text" name="security_padre_answer" value="{{ old('security_padre_answer') }}"
                                class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-1.5 text-xs text-slate-100 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-end gap-2 text-[11px]">
                        <button type="button" data-close-modal
                            class="inline-flex items-center rounded-md border border-slate-600 bg-slate-800 px-3 py-1.5 text-slate-100 hover:bg-slate-700 cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="inline-flex items-center rounded-md bg-blue-600 px-4 py-1.5 text-white text-[11px] font-semibold hover:bg-blue-500 cursor-pointer">
                            Guardar usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal de confirmación para eliminar usuario --}}
        <div id="confirm-delete-user-modal" class="hidden fixed inset-0 z-40 flex items-center justify-center bg-black/50">
            <div class="w-full max-w-sm rounded-2xl bg-slate-900 border border-slate-700 shadow-2xl p-5 text-xs text-slate-100">
                <h2 class="text-sm font-semibold mb-2 flex items-center gap-2" data-modal-user-title>
                    Eliminar usuario
                </h2>
                <p class="text-[11px] text-slate-300 mb-4" data-modal-user-text>
                    ¿Seguro que deseas eliminar este usuario? Esta acción no se puede deshacer.
                </p>
                <div class="flex justify-end gap-2 text-[11px]">
                    <button type="button" data-modal-user-cancel class="inline-flex items-center rounded-md border border-slate-600 bg-slate-800 px-3 py-1.5 text-slate-100 hover:bg-slate-700 cursor-pointer">
                        Cancelar
                    </button>
                    <button type="button" data-modal-user-confirm class="inline-flex items-center rounded-md border border-red-500/60 bg-red-500/10 px-3 py-1.5 text-red-200 hover:bg-red-500/20 cursor-pointer">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>

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

                document.addEventListener('DOMContentLoaded', function () {
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
                    let currentForm = null;

                    function openDeleteModal(form) {
                        currentForm = form;
                        if (!deleteModal) return;
                        const userName = form.closest('tr')?.querySelectorAll('td')[1]?.innerText?.trim() || 'este usuario';
                        if (titleEl) titleEl.textContent = 'Eliminar usuario';
                        if (textEl) textEl.textContent = `¿Seguro que deseas eliminar "${userName}"? Esta acción no se puede deshacer.`;
                        deleteModal.classList.remove('hidden');
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
                            currentForm.submit();
                        }
                        closeDeleteModal();
                    });

                    deleteModal?.addEventListener('click', function (e) {
                        if (e.target === deleteModal) {
                            closeDeleteModal();
                        }
                    });
                });
            </script>
        @endpush
    </div>
@endsection
