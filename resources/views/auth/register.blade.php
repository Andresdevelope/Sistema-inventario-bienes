@extends('layouts.app')

@section('content')
<div class="w-full max-w-3xl mx-auto bg-slate-900/70 border border-slate-700 shadow-2xl rounded-2xl p-6 md:p-8">
    <div class="mb-6 text-center">
        <h1 class="text-3xl font-bold tracking-tight mb-1">Crear cuenta</h1>
        <p class="text-xs text-slate-400">Registra tu usuario para acceder al sistema de inventario.</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 text-xs text-red-300 border border-red-500/40 bg-red-900/30 rounded px-3 py-2">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-1 md:col-span-2">
                <label class="block text-xs font-medium text-slate-300" for="name">Nombre de usuario</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="space-y-1 md:col-span-2">
                <label class="block text-xs font-medium text-slate-300" for="email">Correo electrónico</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>


            <div class="space-y-1 relative">
                <label class="block text-xs font-medium text-slate-300" for="password">Contraseña</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10">
                <button type="button" onclick="togglePassword('password', this)" tabindex="-1"
                    class="absolute right-2 top-8 text-slate-400 hover:text-blue-400 focus:outline-none">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>


            <div class="space-y-1 relative">
                <label class="block text-xs font-medium text-slate-300" for="password_confirmation">Confirmar contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10">
                <button type="button" onclick="togglePassword('password_confirmation', this)" tabindex="-1"
                    class="absolute right-2 top-8 text-slate-400 hover:text-blue-400 focus:outline-none">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="border-t border-slate-700/60 pt-4 mt-2">
            <h2 class="text-sm font-semibold text-slate-100 mb-1">Preguntas de seguridad</h2>
            <p class="text-xs text-slate-400 mb-3">Se utilizarán para recuperar tu cuenta en caso de olvidar la contraseña.</p>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="space-y-1 md:col-span-1">
                    <label class="block text-xs font-medium text-slate-300" for="security_color_answer">Color favorito</label>
                    <input id="security_color_answer" name="security_color_answer" type="text" value="{{ old('security_color_answer') }}" required
                        class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="space-y-1 md:col-span-1">
                    <label class="block text-xs font-medium text-slate-300" for="security_animal_answer">Animal favorito</label>
                    <input id="security_animal_answer" name="security_animal_answer" type="text" value="{{ old('security_animal_answer') }}" required
                        class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="space-y-1 md:col-span-1">
                    <label class="block text-xs font-medium text-slate-300" for="security_padre_answer">Nombre de tu padre</label>
                    <input id="security_padre_answer" name="security_padre_answer" type="text" value="{{ old('security_padre_answer') }}" required
                        class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>

        <div class="pt-2 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <button type="submit"
                class="w-full md:w-auto inline-flex items-center justify-center rounded-md bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-2 text-sm font-semibold text-white shadow hover:from-emerald-400 hover:to-teal-400 transition">
                Registrarse
            </button>

            <p class="text-xs text-slate-400 text-center md:text-right w-full">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-blue-300 hover:text-blue-200 underline underline-offset-2">Inicia sesión</a>
            </p>
        </div>
    </form>
</div>

@push('scripts')
<script>
function togglePassword(_, btn) {
    // Busca el input anterior al botón (en el mismo contenedor)
    const input = btn.parentElement.querySelector('input[type="password"], input[type="text"]');
    const icon = btn.querySelector('svg');
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `<path stroke-linecap='round' stroke-linejoin='round' d='M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z'/><path stroke-linecap='round' stroke-linejoin='round' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'/><path stroke-linecap='round' stroke-linejoin='round' d='M4.5 4.5l15 15' />`;
    } else {
        input.type = 'password';
        icon.innerHTML = `<path stroke-linecap='round' stroke-linejoin='round' d='M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z'/><path stroke-linecap='round' stroke-linejoin='round' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'/>`;
    }
}
</script>
@endpush
@endsection
