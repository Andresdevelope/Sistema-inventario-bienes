@extends('layouts.app')

@section('content')
<div class="grid gap-10 md:grid-cols-2 items-center">
    {{-- Columna de bienvenida --}}
    <div class="space-y-3">
        <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-slate-900">Bienvenido al Sistema Inventario de Bienes</h1>
        <p class="text-sm text-slate-700 max-w-md">Inicia sesión con tu usuario y contraseña para gestionar los bienes del sistema. Mantén tus credenciales en un lugar seguro.</p>
        <ul class="text-sm text-slate-700 space-y-1 list-disc list-inside">
            <li>Accede rápidamente al panel principal.</li>
            <li>Registra y consulta movimientos de inventario.</li>
            <li>Protección adicional con preguntas de seguridad.</li>
        </ul>
    </div>

    {{-- Tarjeta de login --}}
    <div class="w-full max-w-md mx-auto bg-white/90 backdrop-blur border border-slate-200 shadow-2xl rounded-2xl p-6 md:p-8">
        <h2 class="text-2xl font-semibold mb-1 text-center">Iniciar sesión</h2>
        <p class="text-xs text-slate-600 mb-4 text-center">Introduce tu usuario y contraseña para continuar.</p>

        @if (session('status'))
            <div class="mb-4 text-xs text-emerald-300 border border-emerald-500/40 bg-emerald-900/30 rounded px-3 py-2">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-2 text-xs text-red-700 border border-red-200 bg-red-50 rounded px-3 py-2">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('lock_remaining'))
            <div class="mb-4 text-xs text-yellow-700 border border-yellow-200 bg-yellow-50 rounded px-3 py-2">
                {{ session('lock_remaining') }}
            </div>
            <div id="lock-box" class="mb-4 text-xs text-red-700 border border-red-200 bg-red-50 rounded px-3 py-2 flex items-center justify-between">
                <span>Tu cuenta está temporalmente bloqueada por intentos fallidos.</span>
                <span class="font-mono" id="lock-timer" data-remaining="{{ session('lock_remaining') }}"></span>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label class="block text-xs font-medium text-slate-700" for="name">Nombre de usuario</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
            </div>


            <div class="space-y-1 relative">
                <label class="block text-xs font-medium text-slate-700" for="password">Contraseña</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600 pr-10">
                <button type="button" onclick="togglePassword('password', this)" tabindex="-1"
                    class="absolute right-2 top-8 text-slate-500 hover:text-slate-700 focus:outline-none">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-medium text-slate-700">Verificación</label>
                <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                @error('recaptcha')
                    <p class="text-[11px] text-red-700 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2 space-y-3">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-slate-800 transition cursor-pointer ">
                    Entrar
                </button>

                <div class="flex items-center justify-between text-xs">
                    <a href="{{ route('password.recover', ['reset' => 1]) }}" class="text-slate-700 hover:text-slate-900 underline underline-offset-2">
                        ¿Olvidaste tu contraseña?
                    </a>
                    <div class="text-slate-600">
                        ¿No tienes cuenta?
                        <a href="{{ route('register') }}" class="text-slate-700 hover:text-slate-900 underline underline-offset-2">Regístrate</a>
                    </div>
                </div>
            </div>
        </form>

        {{-- Script reCAPTCHA --}}
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

// Contador visual para el bloqueo de usuario
document.addEventListener('DOMContentLoaded', () => {
    const timerEl = document.getElementById('lock-timer');
    if (!timerEl) return;

    let remaining = parseInt(timerEl.dataset.remaining, 10) || 0;
    const passwordInput = document.getElementById('password');
    const submitBtn = document.querySelector('button[type="submit"]');

    // Mientras exista tiempo de bloqueo, deshabilitamos el campo de contraseña (y el botón)
    if (remaining > 0) {
        if (passwordInput) passwordInput.disabled = true;
        if (submitBtn) submitBtn.disabled = true;
    }
    const format = (sec) => {
        const m = Math.floor(sec / 60);
        const s = sec % 60;
        return `${m}:${s.toString().padStart(2, '0')} (min:seg)`;
    };

    const update = () => {
        if (remaining <= 0) {
            // Cuando termina el tiempo, ocultamos completamente el mensaje de bloqueo
            const box = document.getElementById('lock-box');
            if (box) box.style.display = 'none';

            // Rehabilitar controles cuando ya no haya bloqueo
            if (passwordInput) passwordInput.disabled = false;
            if (submitBtn) submitBtn.disabled = false;
            return;
        }
        timerEl.textContent = format(remaining);
        remaining--;
        if (remaining >= 0) {
            setTimeout(update, 1000);
        }
    };

    update();
});
</script>
@endpush
</div>
@endsection
