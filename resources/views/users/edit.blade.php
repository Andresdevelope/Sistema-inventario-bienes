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
                        <select id="role" name="role" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Usuario</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
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

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="security_color_answer">Color favorito</label>
                        <input id="security_color_answer" name="security_color_answer" type="text" value="{{ old('security_color_answer', $user->security_color_answer) }}" required
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="security_animal_answer">Animal favorito</label>
                        <input id="security_animal_answer" name="security_animal_answer" type="text" value="{{ old('security_animal_answer', $user->security_animal_answer) }}" required
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="security_padre_answer">Nombre de tu padre</label>
                        <input id="security_padre_answer" name="security_padre_answer" type="text" value="{{ old('security_padre_answer', $user->security_padre_answer) }}" required
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                </div>

                <div class="pt-2 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <a href="{{ route('users.index') }}" class="text-xs text-slate-700 hover:text-slate-900 underline underline-offset-2">Volver al listado</a>
                    <button type="submit"
                        class="w-full md:w-auto inline-flex items-center justify-center rounded-md bg-slate-900 px-6 py-2 text-sm font-semibold text-white shadow hover:bg-slate-800 transition">
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
</script>
@endpush
