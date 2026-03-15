@extends('layouts.dashboard')

@section('content')
    <div class="w-full max-w-4xl mx-auto">
        <div class="bg-white/90 backdrop-blur border border-slate-200 shadow-2xl rounded-2xl p-6 md:p-8 text-slate-900">
            <div class="mb-6">
                <h1 class="text-3xl font-bold tracking-tight mb-1">Editar usuario</h1>
                <p class="text-xs text-slate-600">Modifica los datos del usuario seleccionado y guarda los cambios.</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 text-xs text-red-700 border border-red-200 bg-red-50 rounded px-3 py-2">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6" id="edit-user-form">
                <div id="edit-user-errors" class="mb-2 text-xs text-red-700 border border-red-200 bg-red-50 rounded px-3 py-2 hidden"></div>
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="name">Nombre de usuario</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="email">Correo electrónico</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>

                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="role">Rol</label>
                        @php
                            $adminCount = $adminCount ?? \App\Models\User::where('role', \App\Models\User::ROLE_ADMIN)->count();
                            $canPromoteToAdmin = $user->role === 'admin' || $adminCount < 2;
                            $isSuperAdmin = $user->id === 1;
                        @endphp
                        <select id="role" name="role" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Usuario</option>
                            @if ($canPromoteToAdmin)
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                            @endif
                        </select>
                        @if (! $canPromoteToAdmin)
                            <p class="text-[11px] text-amber-700">Ya existe el máximo de administradores (superadmin y admin).</p>
                        @endif
                        @if ($isSuperAdmin)
                            <p class="text-[11px] text-slate-500">Este usuario es el superadmin principal y no puede cambiar a otro rol.</p>
                        @endif
                    </div>
                    <div class="space-y-1 md:col-span-1 relative">
                        <label class="block text-xs font-medium text-slate-700" for="password">Nueva contraseña (opcional, mínimo 16 caracteres)</label>
                        <input id="edit-password" name="password" type="password"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 pr-8 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600"
                            placeholder="Dejar en blanco para no cambiar">
                        <button type="button" onclick="togglePassword('password', this)" tabindex="-1"
                            class="absolute right-2 top-8 text-slate-500 hover:text-slate-700 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="edit-admin-password-wrap" class="hidden space-y-1">
                    <label class="block text-xs font-medium text-slate-700" for="edit_admin_password">Contraseña del administrador actual (verificación)</label>
                    <input id="edit_admin_password" name="admin_password" type="password" minlength="16" maxlength="40" autocomplete="current-password"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    <p class="text-[11px] text-slate-500">Se solicita solo cuando se asciende de Usuario a Administrador.</p>
                </div>

                <div class="space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-3">
                    @php
                        $selectedPermissions = old('permissions', $user->resolvedPermissions());
                        $permissionLabels = [
                            'bienes.ver' => 'Ver bienes',
                            'bienes.crear' => 'Crear bienes',
                            'bienes.editar' => 'Editar bienes',
                            'bienes.eliminar' => 'Eliminar bienes',
                            'categorias.ver' => 'Ver categorías',
                            'categorias.gestionar' => 'Gestionar categorías',
                            'ubicaciones.ver' => 'Ver ubicaciones',
                            'ubicaciones.gestionar' => 'Gestionar ubicaciones',
                            'reportes.exportar' => 'Exportar reportes',
                        ];
                    @endphp

                    <div class="flex items-center justify-between gap-2">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-700">Permisos del usuario</p>
                        <span id="edit-permissions-admin-hint" class="hidden text-[10px] text-emerald-700 font-medium">
                            Administrador: acceso completo automático
                        </span>
                    </div>

                    <input type="hidden" name="permissions_present" value="1">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2" id="edit-user-permissions-box">
                        @foreach (\App\Models\User::availablePermissions() as $permission)
                            <label class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-2 py-1.5 text-[11px] text-slate-700">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission }}"
                                    class="rounded border-slate-300 text-slate-900 focus:ring-slate-600"
                                    {{ in_array($permission, $selectedPermissions, true) ? 'checked' : '' }}
                                    data-edit-permission-checkbox
                                >
                                <span>{{ $permissionLabels[$permission] ?? $permission }}</span>
                            </label>
                        @endforeach
                    </div>

                    <p class="text-[10px] text-slate-500">
                        Ajusta aquí los permisos operativos del usuario.
                    </p>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="security_color_answer">Color favorito</label>
                        <input id="security_color_answer" name="security_color_answer" type="text" value="{{ old('security_color_answer') }}" required placeholder="Ingresa una nueva respuesta"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="security_animal_answer">Animal favorito</label>
                        <input id="security_animal_answer" name="security_animal_answer" type="text" value="{{ old('security_animal_answer') }}" required placeholder="Ingresa una nueva respuesta"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="security_padre_answer">Nombre de tu padre</label>
                        <input id="security_padre_answer" name="security_padre_answer" type="text" value="{{ old('security_padre_answer') }}" required placeholder="Ingresa una nueva respuesta"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                </div>

                <div class="pt-2 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-2 text-[12px] font-semibold text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-300">Volver al listado</a>
                    <button type="submit"
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-accent-400 to-accent-500 px-6 py-2 text-[12px] font-semibold text-brand-900 shadow-lg shadow-accent-900/20 transition hover:-translate-y-0.5 hover:shadow-accent-900/30 focus:outline-none cursor-pointer focus-visible:ring-2 focus-visible:ring-accent-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

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
        const editForm = document.getElementById('edit-user-form');
        const errorBox = document.getElementById('edit-user-errors');
        const roleSelect = document.getElementById('role');
        const oldRole = @json(old('role', $user->role));
        const permissionChecks = Array.from(document.querySelectorAll('[data-edit-permission-checkbox]'));
        const adminHint = document.getElementById('edit-permissions-admin-hint');
        const editAdminPasswordWrap = document.getElementById('edit-admin-password-wrap');
        const editAdminPasswordInput = document.getElementById('edit_admin_password');

        function refreshPermissionUI() {
            const isAdmin = roleSelect?.value === 'admin';
            const isPromotion = oldRole !== 'admin' && isAdmin;

            permissionChecks.forEach((check) => {
                check.disabled = isAdmin;
                if (isAdmin) {
                    check.checked = true;
                }
            });

            adminHint?.classList.toggle('hidden', !isAdmin);
            editAdminPasswordWrap?.classList.toggle('hidden', !isPromotion);
            if (editAdminPasswordInput) {
                editAdminPasswordInput.required = isPromotion;
                if (!isPromotion) {
                    editAdminPasswordInput.value = '';
                }
            }
        }

        roleSelect?.addEventListener('change', refreshPermissionUI);
        refreshPermissionUI();

        editForm?.addEventListener('submit', function (e) {
            const isAdmin = roleSelect?.value === 'admin';
            const isPromotion = oldRole !== 'admin' && isAdmin;
            let errors = [];

            if (isPromotion) {
                const passwordValue = editAdminPasswordInput?.value ?? '';

                if (!passwordValue) {
                    errors.push('Debes ingresar tu contraseña para confirmar el ascenso a administrador.');
                    editAdminPasswordInput?.classList.add('border-red-500');
                } else if (passwordValue.length < 16) {
                    errors.push('La contraseña de verificación debe tener al menos 16 caracteres.');
                    editAdminPasswordInput?.classList.add('border-red-500');
                } else if (passwordValue.length > 40) {
                    errors.push('La contraseña de verificación no puede superar los 40 caracteres.');
                    editAdminPasswordInput?.classList.add('border-red-500');
                } else {
                    editAdminPasswordInput?.classList.remove('border-red-500');
                }
            } else {
                editAdminPasswordInput?.classList.remove('border-red-500');
            }

            if (errors.length > 0) {
                e.preventDefault();
                if (errorBox) {
                    errorBox.innerHTML = '<ul><li>' + errors.join('</li><li>') + '</li></ul>';
                    errorBox.classList.remove('hidden');
                }
            } else {
                errorBox?.classList.add('hidden');
            }
        });
    });
</script>
@endpush
