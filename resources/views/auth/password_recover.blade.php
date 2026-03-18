@extends('layouts.app')

@section('content')
@php($step = $step ?? 1)
@php($showThirdQuestion = $showThirdQuestion ?? false)
@php($tokenRemainingMs = (int) ($tokenRemainingMs ?? 0))
@php($resendRemainingMs = (int) ($resendRemainingMs ?? 0))
@php($resendCount = (int) ($resendCount ?? 0))
@php($maxResends = (int) ($maxResends ?? 3))
@php($canResend = (bool) ($canResend ?? true))
<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white/90 backdrop-blur border border-slate-200 shadow-2xl rounded-2xl p-6 md:p-8 text-slate-900">
        <h1 class="text-2xl font-semibold mb-1 text-center">
            @if ($step === 1)
                Recuperar contraseña
            @elseif ($step === 2)
                Verificación de seguridad
            @elseif ($step === 3)
                Verificar token por correo
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
            @elseif ($step === 3)
                Te enviamos un token de 6 dígitos a tu correo. Ingrésalo para continuar.
            @else
                Elige una nueva contraseña para tu cuenta.
            @endif
        </p>

        @if (session('status'))
            <div class="mb-4 text-xs text-emerald-700 border border-emerald-200 bg-emerald-50 rounded px-3 py-2">
                {{ session('status') }}
            </div>
        @endif

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
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-brand-400/50 bg-brand-50/80 px-4 py-2 font-semibold text-brand-700 shadow-inner shadow-brand-200/60 transition duration-300 hover:-translate-y-0.5 hover:bg-brand-100/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                        Volver al login
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-4 py-2 text-[12px] font-semibold text-white shadow-lg shadow-brand-900/30 transition duration-300 hover:-translate-y-0.5 hover:shadow-brand-900/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M6 12h12m-9 7.5h6" />
                        </svg>
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
                        <input id="security_color_answer" name="security_color_answer" type="text" value="{{ old('security_color_answer') }}" required maxlength="40"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-700" for="security_animal_answer">¿Cuál es tu animal favorito?</label>
                        <input id="security_animal_answer" name="security_animal_answer" type="text" value="{{ old('security_animal_answer') }}" required maxlength="40"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                @else
                    {{-- Segundo intento: solo la tercera pregunta --}}
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-700" for="security_padre_answer">Nombre de tu padre</label>
                        <input id="security_padre_answer" name="security_padre_answer" type="text" value="{{ old('security_padre_answer') }}" required maxlength="40"
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
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-brand-400/50 bg-brand-50/80 px-4 py-2 font-semibold text-brand-700 shadow-inner shadow-brand-200/60 transition duration-300 hover:-translate-y-0.5 hover:bg-brand-100/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-4 py-2 text-[12px] font-semibold text-white shadow-lg shadow-brand-900/30 transition duration-300 hover:-translate-y-0.5 hover:shadow-brand-900/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12l-7.5 7.5m-10.5 0L10.5 12 3 4.5" />
                        </svg>
                        Verificar y continuar
                    </button>
                </div>
            </form>
        @elseif ($step === 3)
            {{-- Paso 3: token enviado por correo --}}
            <form method="POST" action="{{ route('password.recover.handle') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="step" value="3">

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-slate-700" for="token">Token de verificación</label>
                    <input id="token" name="token" type="text" inputmode="numeric" pattern="\d{6}" maxlength="6" required value="{{ old('token') }}"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm tracking-[0.25em] text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600"
                        placeholder="000000">
                    <p id="token-timer"
                        data-remaining-ms="{{ $tokenRemainingMs }}"
                        class="text-[11px] text-slate-500">
                        Tiempo restante: --:--
                    </p>
                    <p id="resend-timer"
                        data-remaining-ms="{{ $resendRemainingMs }}"
                        data-resend-count="{{ $resendCount }}"
                        data-max-resends="{{ $maxResends }}"
                        class="text-[11px] {{ $canResend ? 'text-emerald-700' : 'text-slate-500' }}">
                        @if ($resendCount >= $maxResends)
                            Límite de reenvíos alcanzado para este intento.
                        @elseif ($resendRemainingMs > 0)
                            Podrás reenviar cuando termine la espera.
                        @else
                            Puedes reenviar un nuevo token si no recibiste el correo.
                        @endif
                    </p>
                </div>

                <div class="pt-2 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-2 text-xs">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-brand-400/50 bg-brand-50/80 px-4 py-2 font-semibold text-brand-700 shadow-inner shadow-brand-200/60 transition duration-300 hover:-translate-y-0.5 hover:bg-brand-100/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-4 py-2 text-[12px] font-semibold text-white shadow-lg shadow-brand-900/30 transition duration-300 hover:-translate-y-0.5 hover:shadow-brand-900/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m6 2.25a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Validar token
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('password.recover.handle') }}" class="mt-2">
                @csrf
                <input type="hidden" name="step" value="3">
                <input type="hidden" name="resend_token" value="1">
                <button id="resend-button" type="submit"
                    @disabled(! $canResend)
                    class="w-full inline-flex items-center justify-center gap-2 rounded-2xl border border-accent-400/50 bg-accent-50/80 px-4 py-2 text-[12px] font-semibold text-accent-700 shadow-inner shadow-accent-200/60 transition duration-300 hover:-translate-y-0.5 hover:bg-accent-100/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-300 disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Reenviar token
                </button>
            </form>
        @else
            {{-- Paso 4: nueva contraseña --}}
            <form id="recover-reset-form" method="POST" action="{{ route('password.recover.handle') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="step" value="4">

                <div class="space-y-1 relative">
                    <label class="block text-xs font-medium text-slate-700" for="password">Nueva contraseña</label>
                    <input id="password" name="password" type="password" required minlength="16" autocomplete="new-password"
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
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="16" autocomplete="new-password"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600 pr-10">
                    <button type="button" onclick="togglePassword('password_confirmation', this)" tabindex="-1"
                        class="absolute right-2 top-8 text-slate-500 hover:text-slate-700 focus:outline-none">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>
                </div>

                <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 space-y-2">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-semibold text-slate-700">Seguridad de contraseña</span>
                        <span id="password-strength-label" class="font-semibold text-slate-500">—</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-slate-200 overflow-hidden">
                        <div id="password-strength-bar" class="h-full w-0 bg-slate-400 transition-all duration-300"></div>
                    </div>
                    <ul class="space-y-1 text-[11px] text-slate-600">
                        <li id="pwd-check-length">• Mínimo 16 caracteres</li>
                        <li id="pwd-check-numeric">• No puede ser solo números.</li>
                        <li id="pwd-check-repeat">• No puede repetir el mismo carácter muchas veces.</li>
                        <li id="pwd-check-gibberish">• Evita texto aleatorio o secuencias débiles.</li>
                        <li id="pwd-check-match">• Debe coincidir con la confirmación.</li>
                    </ul>
                </div>

                <div class="pt-2 flex items-center justify-between text-xs">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-brand-400/50 bg-brand-50/80 px-4 py-2 font-semibold text-brand-700 shadow-inner shadow-brand-200/60 transition duration-300 hover:-translate-y-0.5 hover:bg-brand-100/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-4 py-2 text-[12px] font-semibold text-white shadow-lg shadow-brand-900/30 transition duration-300 hover:-translate-y-0.5 hover:shadow-brand-900/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17.25v1.5A2.25 2.25 0 0 1 12.75 21h-7.5A2.25 2.25 0 0 1 3 18.75v-7.5A2.25 2.25 0 0 1 5.25 9h1.5m4.5-6H18a3 3 0 0 1 3 3v6.75m-9-3 9-9" />
                        </svg>
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

document.addEventListener('DOMContentLoaded', () => {
    const formatTimer = (ms) => {
        const safeMs = Math.max(0, Math.floor(ms));
        const totalSeconds = Math.floor(safeMs / 1000);
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = totalSeconds % 60;
        return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    };

    const tokenTimer = document.getElementById('token-timer');
    if (tokenTimer) {
        let remainingMs = Number(tokenTimer.dataset.remainingMs ?? 0);

        const renderTimer = () => {
            tokenTimer.textContent = remainingMs > 0
                ? `Tiempo restante: ${formatTimer(remainingMs)}`
                : 'Token expirado. Reenvía un nuevo token para continuar.';

            tokenTimer.classList.toggle('text-red-700', remainingMs <= 0);
            tokenTimer.classList.toggle('text-slate-500', remainingMs > 0);
        };

        renderTimer();

        const interval = setInterval(() => {
            remainingMs -= 1000;
            if (remainingMs <= 0) {
                remainingMs = 0;
                renderTimer();
                clearInterval(interval);
                return;
            }
            renderTimer();
        }, 1000);
    }

    const resendTimer = document.getElementById('resend-timer');
    const resendButton = document.getElementById('resend-button');
    if (resendTimer && resendButton) {
        let resendRemainingMs = Number(resendTimer.dataset.remainingMs ?? 0);
        const resendCount = Number(resendTimer.dataset.resendCount ?? 0);
        const maxResends = Number(resendTimer.dataset.maxResends ?? 0);
        const maxReached = resendCount >= maxResends;

        const renderResendTimer = () => {
            if (maxReached) {
                resendButton.disabled = true;
                resendTimer.textContent = 'Límite de reenvíos alcanzado para este intento.';
                resendTimer.classList.add('text-red-700');
                resendTimer.classList.remove('text-slate-500', 'text-emerald-700');
                return;
            }

            if (resendRemainingMs > 0) {
                resendButton.disabled = true;
                resendTimer.textContent = `Podrás reenviar en ${formatTimer(resendRemainingMs)}.`;
                resendTimer.classList.add('text-slate-500');
                resendTimer.classList.remove('text-red-700', 'text-emerald-700');
                return;
            }

            resendButton.disabled = false;
            resendTimer.textContent = 'Ya puedes reenviar un nuevo token.';
            resendTimer.classList.add('text-emerald-700');
            resendTimer.classList.remove('text-red-700', 'text-slate-500');
        };

        renderResendTimer();

        if (!maxReached && resendRemainingMs > 0) {
            const resendInterval = setInterval(() => {
                resendRemainingMs -= 1000;
                if (resendRemainingMs <= 0) {
                    resendRemainingMs = 0;
                    renderResendTimer();
                    clearInterval(resendInterval);
                    return;
                }
                renderResendTimer();
            }, 1000);
        }
    }

    const form = document.getElementById('recover-reset-form');
    if (!form) return;

    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthLabel = document.getElementById('password-strength-label');

    const checks = {
        length: document.getElementById('pwd-check-length'),
        numeric: document.getElementById('pwd-check-numeric'),
        repeat: document.getElementById('pwd-check-repeat'),
        gibberish: document.getElementById('pwd-check-gibberish'),
        match: document.getElementById('pwd-check-match'),
    };

    const looksLikeGibberish = (text, maxWordLength = 30, maxConsonantCluster = 7) => {
        const clean = String(text ?? '').trim();
        if (!clean) return false;

        if (/(.)\1{3,}/u.test(clean)) return true;
        if (new RegExp(`[bcdfghjklmnñpqrstvwxyz]{${maxConsonantCluster},}`, 'iu').test(clean)) return true;
        if (/[\p{L}\d]{25,}/u.test(clean)) return true;

        const words = clean.split(/[\s,.;:()\-/#]+/u).filter(Boolean);
        return words.some(word => [...word].length > maxWordLength);
    };

    const paintCheck = (el, ok) => {
        if (!el) return;
        el.classList.toggle('text-emerald-700', !!ok);
        el.classList.toggle('font-semibold', !!ok);
        el.classList.toggle('text-slate-600', !ok);
    };

    const updateStrength = () => {
        if (!passwordInput || !passwordConfirmationInput) return;

        const pwd = String(passwordInput.value ?? '').trim();
        const conf = String(passwordConfirmationInput.value ?? '').trim();
        const hasPassword = pwd.length > 0;
        const hasConfirmation = conf.length > 0;

        const checksState = {
            length: hasPassword && pwd.length >= 16,
            numeric: hasPassword && !/^\d+$/u.test(pwd),
            repeat: hasPassword && !/^(.)\1{5,}$/u.test(pwd),
            gibberish: hasPassword && !looksLikeGibberish(pwd, 30, 7),
            match: hasPassword && hasConfirmation && conf === pwd,
        };

        paintCheck(checks.length, checksState.length);
        paintCheck(checks.numeric, checksState.numeric);
        paintCheck(checks.repeat, checksState.repeat);
        paintCheck(checks.gibberish, checksState.gibberish);
        paintCheck(checks.match, checksState.match);

        const score = Object.values(checksState).filter(Boolean).length;

        if (strengthBar && strengthLabel) {
            const widths = ['0%', '20%', '40%', '60%', '80%', '100%'];
            const colors = ['bg-slate-400', 'bg-red-500', 'bg-orange-500', 'bg-amber-500', 'bg-lime-500', 'bg-emerald-500'];
            const labels = ['—', 'Muy débil', 'Débil', 'Media', 'Buena', 'Fuerte'];

            const uiScore = hasPassword ? score : 0;

            strengthBar.classList.remove('bg-slate-400', 'bg-red-500', 'bg-orange-500', 'bg-amber-500', 'bg-lime-500', 'bg-emerald-500');
            strengthBar.classList.add(colors[uiScore]);
            strengthBar.style.width = widths[uiScore];
            strengthLabel.textContent = labels[uiScore];
            strengthLabel.className = uiScore >= 4
                ? 'font-semibold text-emerald-700'
                : uiScore >= 3
                    ? 'font-semibold text-amber-700'
                    : uiScore === 0
                        ? 'font-semibold text-slate-500'
                        : 'font-semibold text-red-600';
        }

        passwordInput.setCustomValidity('');
        passwordConfirmationInput.setCustomValidity('');

        if (!hasPassword) {
            return;
        }

        if (!checksState.length) {
            passwordInput.setCustomValidity('La contraseña debe tener al menos 16 caracteres.');
        } else if (!checksState.numeric) {
            passwordInput.setCustomValidity('La contraseña no puede estar compuesta solo por números.');
        } else if (!checksState.repeat) {
            passwordInput.setCustomValidity('La contraseña no puede ser una repetición del mismo carácter.');
        } else if (!checksState.gibberish) {
            passwordInput.setCustomValidity('La contraseña no parece segura. Evita texto aleatorio o secuencias repetitivas.');
        }

        if (conf && !checksState.match) {
            passwordConfirmationInput.setCustomValidity('La confirmación de la contraseña no coincide.');
        }
    };

    passwordInput?.addEventListener('input', updateStrength);
    passwordConfirmationInput?.addEventListener('input', updateStrength);

    form.addEventListener('submit', (event) => {
        updateStrength();
        const firstInvalid = [passwordInput, passwordConfirmationInput].find((input) => input && !input.checkValidity());
        if (firstInvalid) {
            event.preventDefault();
            firstInvalid.reportValidity();
        }
    });

    updateStrength();
});
</script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@endsection
