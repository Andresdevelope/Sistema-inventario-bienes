@extends('layouts.app')

@section('content')
<div class="grid gap-10 md:grid-cols-2 items-center">
    {{-- Columna de bienvenida --}}
    <div class="space-y-4">
        <h1 class="text-3xl md:text-4xl font-bold tracking-tight">Bienvenido al sistema de inventario de bienes</h1>
        <p class="text-sm text-slate-300 max-w-md">
            Inicia sesión con tu usuario y contraseña para gestionar los bienes del sistema. Mantén tus credenciales en
            un lugar seguro.
        </p>
        <ul class="text-sm text-slate-300 space-y-1 list-disc list-inside">
            <li>Accede rápidamente al panel principal.</li>
            <li>Registra y consulta movimientos de inventario.</li>
            <li>Protegido con preguntas de seguridad personalizadas.</li>
        </ul>
    </div>

    {{-- Tarjeta de login --}}
    <div class="w-full max-w-md mx-auto bg-slate-900/70 border border-slate-700 shadow-2xl rounded-2xl p-6 md:p-8">
        <h2 class="text-2xl font-semibold mb-2 text-center">Iniciar sesión</h2>
        <p class="text-xs text-slate-400 mb-4 text-center">Introduce tu usuario y contraseña para continuar.</p>

        @if (session('status'))
            <div class="mb-4 text-xs text-emerald-300 border border-emerald-500/40 bg-emerald-900/30 rounded px-3 py-2">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-xs text-red-300 border border-red-500/40 bg-red-900/30 rounded px-3 py-2">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label class="block text-xs font-medium text-slate-300" for="name">Nombre de usuario</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
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

            <div class="pt-2 space-y-3">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center rounded-md bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:from-blue-500 hover:to-indigo-500 transition">
                    Entrar
                </button>

                <div class="flex items-center justify-between text-xs">
                    <a href="{{ route('password.recover', ['reset' => 1]) }}" class="text-blue-300 hover:text-blue-200 underline underline-offset-2">
                        ¿Olvidaste tu contraseña?
                    </a>
                    <div class="text-slate-400">
                        ¿No tienes cuenta?
                        <a href="{{ route('register') }}" class="text-blue-300 hover:text-blue-200 underline underline-offset-2">Regístrate</a>
                    </div>
                </div>
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
</div>
@endsection
