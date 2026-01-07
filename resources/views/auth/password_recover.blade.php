@extends('layouts.app')

@section('content')
@php($step = $step ?? 1)
@php($showThirdQuestion = $showThirdQuestion ?? false)
<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white/90 backdrop-blur border border-slate-200 shadow-2xl rounded-2xl p-6 md:p-8 text-slate-900">
        <h1 class="text-2xl font-semibold mb-1 text-center">
            @if ($step === 1)
                Recuperar contraseña
            @elseif ($step === 2)
                Verificación de seguridad
            @else
                Definir nueva contraseña
            @endif
        </h1>
        <p class="text-xs text-slate-600 mb-4 text-center">
            @if ($step === 1)
                Ingresa el correo electrónico asociado a tu cuenta para comenzar la recuperación.
            @elseif ($step === 2)
                Responde tus preguntas de seguridad para verificar tu identidad.
                @if ($showThirdQuestion)
                    <br>Alguna respuesta fue incorrecta, ahora también debes responder la tercera pregunta.
                @endif
            @else
                Elige una nueva contraseña para tu cuenta.
            @endif
        </p>

        @if ($errors->any())
            <div class="mb-4 text-xs text-red-700 border border-red-200 bg-red-50 rounded px-3 py-2">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($step === 1)
            {{-- Paso 1: correo --}}
            <form method="POST" action="{{ route('password.recover.handle') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="step" value="1">

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-slate-700" for="email">Correo electrónico</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-slate-700">Verificación</label>
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    @error('recaptcha')
                        <p class="text-[11px] text-red-700 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2 flex items-center justify-between text-xs">
                    <a href="{{ route('login') }}" class="text-slate-700 hover:text-slate-900 underline underline-offset-2">Volver al login</a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-slate-800 transition">
                        Continuar
                    </button>
                </div>
            </form>
        @elseif ($step === 2)
            {{-- Paso 2: preguntas de seguridad --}}
            <form method="POST" action="{{ route('password.recover.handle') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="step" value="2">

                @if (! $showThirdQuestion)
                    {{-- Primer intento: dos primeras preguntas --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-700" for="security_color_answer">¿Cuál es tu color favorito?</label>
                        <input id="security_color_answer" name="security_color_answer" type="text" value="{{ old('security_color_answer') }}" required
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-700" for="security_animal_answer">¿Cuál es tu animal favorito?</label>
                        <input id="security_animal_answer" name="security_animal_answer" type="text" value="{{ old('security_animal_answer') }}" required
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                @else
                    {{-- Segundo intento: solo la tercera pregunta --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-700" for="security_padre_answer">Nombre de tu padre</label>
                        <input id="security_padre_answer" name="security_padre_answer" type="text" value="{{ old('security_padre_answer') }}" required
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                @endif

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-slate-700">Verificación</label>
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    @error('recaptcha')
                        <p class="text-[11px] text-red-700 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2 flex items-center justify-between text-xs">
                    <a href="{{ route('login') }}" class="text-slate-700 hover:text-slate-900 underline underline-offset-2">Cancelar</a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-slate-800 transition">
                        Verificar y continuar
                    </button>
                </div>
            </form>
        @else
            {{-- Paso 3: nueva contraseña --}}
            <form method="POST" action="{{ route('password.recover.handle') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="step" value="3">

                <div class="space-y-1 relative">
                    <label class="block text-xs font-medium text-slate-700" for="password">Nueva contraseña</label>
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


                <div class="space-y-1 relative">
                    <label class="block text-xs font-medium text-slate-700" for="password_confirmation">Confirmar contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600 pr-10">
                    <button type="button" onclick="togglePassword('password_confirmation', this)" tabindex="-1"
                        class="absolute right-2 top-8 text-slate-500 hover:text-slate-700 focus:outline-none">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

                <div class="pt-2 flex items-center justify-between text-xs">
                    <a href="{{ route('login') }}" class="text-slate-700 hover:text-slate-900 underline underline-offset-2">Cancelar</a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow hover:bg-slate-800 transition">
                        Guardar contraseña
                    </button>
                </div>
            </form>
        @endif
    </div>
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
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@endsection
