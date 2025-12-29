@extends('layouts.dashboard')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-xl">
            <h1 class="text-lg font-semibold tracking-tight mb-1">Editar usuario</h1>
            <p class="text-[11px] text-slate-400 mb-4">Modifica los datos del usuario seleccionado.</p>

            @if ($errors->any())
                <div class="mb-4 text-xs text-red-300 border border-red-500/40 bg-red-900/30 rounded px-3 py-2">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="name">Nombre de usuario</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="email">Correo electronico</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="role">Rol</label>
                        <select id="role" name="role" class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Usuario</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>
                    <div class="space-y-1 relative">
                        <label class="block text-xs font-medium text-slate-300" for="password">Nueva contraseña (opcional)</label>
                        <input id="password" name="password" type="password"
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 pr-8 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Dejar en blanco para no cambiar">
                        <button type="button" onclick="togglePassword('password', this)" tabindex="-1"
                            class="absolute right-2 top-8 text-slate-400 hover:text-blue-400 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="security_color_answer">Color favorito</label>
                        <input id="security_color_answer" name="security_color_answer" type="text" value="{{ old('security_color_answer', $user->security_color_answer) }}" required
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="security_animal_answer">Animal favorito</label>
                        <input id="security_animal_answer" name="security_animal_answer" type="text" value="{{ old('security_animal_answer', $user->security_animal_answer) }}" required
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="security_padre_answer">Nombre de tu padre</label>
                        <input id="security_padre_answer" name="security_padre_answer" type="text" value="{{ old('security_padre_answer', $user->security_padre_answer) }}" required
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('users.index') }}" class="text-[11px] text-slate-400 hover:text-slate-200 underline underline-offset-2 cursor-pointer">Volver al listado</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow hover:from-blue-500 hover:to-indigo-500 cursor-pointer">
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
