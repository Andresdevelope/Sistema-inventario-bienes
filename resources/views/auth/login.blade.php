@extends('layouts.app')

@section('content')
<div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] items-center">
    {{-- Columna de bienvenida --}}
    <div class="space-y-5 text-brand-50">
        <div class="inline-flex items-center gap-2 rounded-full border border-brand-500/40 bg-brand-500/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-brand-100">
            <span class="h-2 w-2 rounded-full bg-accent-400 animate-pulse"></span>
            Acceso institucional
        </div>
        <div class="space-y-3">
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight text-white lg:text-5xl">
                Bienvenido al Sistema Inventario de Bienes
            </h1>
            <p class="text-sm text-brand-100/80 max-w-xl">
                Autentícate para coordinar el inventario, supervisar movimientos y mantener la trazabilidad de todos los activos institucionales.
            </p>
        </div>
        <ul class="text-sm text-brand-100/80 space-y-2">
            <li class="flex items-center gap-2">
                <span class="h-1.5 w-1.5 rounded-full bg-accent-400"></span>
                Panel ejecutivo en tiempo real.
            </li>
            <li class="flex items-center gap-2">
                <span class="h-1.5 w-1.5 rounded-full bg-accent-400"></span>
                Control granular de bienes y bitácora.
            </li>
        
        </ul>
        
    </div>

    {{-- Tarjeta de login --}}
    <div class="w-full max-w-md ml-auto bg-white/95 backdrop-blur border border-brand-500/20 shadow-2xl shadow-brand-900/30 rounded-3xl p-6 md:p-8 text-slate-900">
        <div class="text-center mb-4 space-y-1">
            <div class="inline-flex items-center gap-2 rounded-full border border-brand-500/20 bg-brand-500/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-brand-600">
                Acceso seguro
            </div>
        <h2 class="text-2xl font-semibold">Iniciar sesión</h2>
        <p class="text-xs text-slate-500">Introduce tus credenciales para continuar.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 flex items-start gap-3 rounded-2xl border border-brand-300/60 bg-gradient-to-r from-brand-50 to-accent-50 px-3.5 py-3 text-xs text-slate-700 shadow-sm" role="status" aria-live="polite">
                <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-brand-100 text-brand-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5">
                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm8.706-2.544a.75.75 0 00-1.06 1.06l1.5 1.5a.75.75 0 001.06 0l3-3a.75.75 0 10-1.06-1.06l-2.47 2.47-0.97-.97z" clip-rule="evenodd" />
                    </svg>
                </span>
                <div class="leading-relaxed">
                    <p class="font-semibold text-brand-700">Operación completada</p>
                    <p>{{ session('status') }}</p>
                </div>
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

        <form id="login-form" method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label class="block text-xs font-medium text-slate-700" for="name">Nombre de usuario</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required minlength="3" maxlength="30" autocomplete="username" data-sanitize="username"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
            </div>


            <div class="space-y-1 relative">
                <label class="block text-xs font-medium text-slate-700" for="password">Contraseña</label>
                <input id="password" name="password" type="password" required minlength="16" autocomplete="current-password"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600 pr-10">
                <button type="button" onclick="togglePassword('password', this)" tabindex="-1"
                    class="absolute right-2 top-8 text-slate-500 hover:text-slate-700 focus:outline-none">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>

            @if (config('services.recaptcha.enabled'))
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-slate-700">Verificación</label>
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    <p id="recaptcha-client-error" class="hidden text-[11px] text-red-700 mt-1">Completa el reCAPTCHA para continuar.</p>
                    @error('recaptcha')
                        <p class="text-[11px] text-red-700 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <div class="pt-2 space-y-3">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-900/30 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Entrar
                </button>

                <div class="grid gap-3 text-xs sm:grid-cols-2">
                    <a href="{{ route('password.recover', ['reset' => 1]) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-brand-400/50 bg-brand-50/80 px-4 py-2 font-semibold text-brand-700 shadow-inner shadow-brand-200/60 transition duration-300 hover:-translate-y-0.5 hover:bg-brand-100/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m11.356 10.498A2.25 2.25 0 0116.5 21h-9a2.25 2.25 0 01-2.25-2.002l-.487-7.303A2.25 2.25 0 016.006 9h11.988a2.25 2.25 0 012.243 2.695l-.487 7.303z" />
                        </svg>
                        Recuperar acceso
                    </a>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-4 py-2 font-semibold text-white shadow-lg shadow-brand-900/20 transition duration-300 hover:-translate-y-0.5 hover:shadow-brand-900/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a7.5 7.5 0 0115 0" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25v4.5m2.25-2.25H17.25" />
                        </svg>
                        Crear cuenta segura
                    </a>
                </div>
            </div>
        </form>

        @if (config('services.recaptcha.enabled'))
            {{-- reCAPTCHA se carga de forma diferida desde JS para no impactar el render inicial --}}
        @endif
    </div>

    <div id="no-freeze-spinner" class="fixed inset-0 z-50 hidden items-center justify-center" aria-live="polite" aria-busy="true">
        <div class="spinner-backdrop absolute inset-0"></div>
        <div class="spinner-shell relative z-10">
            <div class="spinner-core">
                <img src="{{ asset('logo-institucion.jpg') }}" alt="Logo institucional" width="50" height="50" loading="lazy" decoding="async" class="spinner-logo">
                <div class="spinner-ring"></div>

                <svg viewBox="0 0 24 24" class="spinner-icon icon-1" aria-hidden="true">
                    <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm0 5a2.5 2.5 0 1 1-2.5 2.5A2.5 2.5 0 0 1 12 7Zm0 12a7 7 0 0 1-5.74-3 5.9 5.9 0 0 1 11.48 0A7 7 0 0 1 12 19Z" />
                </svg>
                <svg viewBox="0 0 24 24" class="spinner-icon icon-2" aria-hidden="true">
                    <path d="M12 3 2 9l10 6 8-4.8V17h2V9L12 3Zm0 9.69L6.04 9 12 5.31 17.96 9 12 12.69Z" />
                </svg>
                <svg viewBox="0 0 24 24" class="spinner-icon icon-3" aria-hidden="true">
                    <path d="M20 6h-4V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v11a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V8a2 2 0 0 0-2-2ZM10 4h4v2h-4Zm10 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8h16Z" />
                </svg>
            </div>

            <p class="spinner-title">Validando credenciales</p>
            <p class="spinner-subtitle">Estamos verificando tu acceso institucional…</p>
        </div>
    </div>

@push('scripts')
<style>
    #no-freeze-spinner {
        opacity: 0;
        pointer-events: none;
        transition: opacity .18s ease;
    }

    #no-freeze-spinner.active {
        display: flex;
        opacity: 1;
        pointer-events: all;
    }

    .spinner-backdrop {
        background: rgba(0, 0, 0, .86);
        backdrop-filter: blur(6px);
    }

    .spinner-shell {
        text-align: center;
        font-family: Inter, 'Segoe UI', Roboto, Arial, sans-serif;
    }

    .spinner-core {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto;
        border-radius: 9999px;
        background: rgba(15, 23, 42, .92);
        border: 1px solid rgba(148, 163, 184, .35);
        box-shadow: 0 18px 60px rgba(0, 0, 0, .45);
    }

    .spinner-logo {
        position: absolute;
        inset: 0;
        margin: auto;
        width: 50px;
        height: 50px;
        object-fit: contain;
        border-radius: 12px;
        z-index: 2;
    }

    .spinner-ring {
        position: absolute;
        inset: 10px;
        border-radius: 9999px;
        border: 5px solid rgba(203, 213, 225, .28);
        border-top-color: rgba(34, 211, 238, .95);
        border-right-color: rgba(59, 130, 246, .95);
        animation: spinRing 1s linear infinite;
    }

    .spinner-icon {
        position: absolute;
        inset: 0;
        margin: auto;
        width: 24px;
        height: 24px;
        fill: #e2e8f0;
        opacity: 0;
        transform: scale(0);
        animation: loadicons 3s infinite ease-in-out;
    }

    .spinner-icon.icon-2 {
        animation-delay: 1s;
    }

    .spinner-icon.icon-3 {
        animation-delay: 2s;
    }

    .spinner-title {
        margin-top: 16px;
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: .01em;
        color: #fff;
    }

    .spinner-subtitle {
        margin-top: 4px;
        font-size: .95rem;
        color: #cbd5e1;
    }

    @keyframes spinRing {
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes loadicons {
        0% {
            opacity: 0;
            transform: scale(0);
        }

        11% {
            opacity: 1;
            transform: scale(1.18);
        }

        22% {
            opacity: 1;
            transform: scale(1);
        }

        33% {
            opacity: 0;
            transform: scale(0);
        }

        100% {
            opacity: 0;
            transform: scale(0);
        }
    }
</style>
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

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');
    const overlay = document.getElementById('no-freeze-spinner');
    const recaptchaClientError = document.getElementById('recaptcha-client-error');
    const recaptchaEnabled = @json((bool) config('services.recaptcha.enabled'));
    let recaptchaScriptRequested = false;

    const sanitizeText = (value, collapseSpaces = true) => {
        let clean = String(value ?? '').replace(/<[^>]*>/g, '');
        clean = clean.replace(/[\u0000-\u001F\u007F]/g, '');
        if (collapseSpaces) {
            clean = clean.replace(/\s+/g, ' ');
        }
        return clean.trim();
    };

    const requestRecaptchaScript = () => {
        if (!recaptchaEnabled || recaptchaScriptRequested || typeof grecaptcha !== 'undefined') {
            return;
        }

        recaptchaScriptRequested = true;
        const script = document.createElement('script');
        script.src = 'https://www.google.com/recaptcha/api.js';
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    };

    if (!form || !overlay) return;

    const usernameInput = form.querySelector('input[data-sanitize="username"]');
    usernameInput?.addEventListener('input', () => {
        usernameInput.value = sanitizeText(usernameInput.value, true);
    });

    if (recaptchaEnabled) {
        // Difiere carga de tercero para priorizar LCP/FCP del login.
        window.addEventListener('load', () => {
            window.setTimeout(requestRecaptchaScript, 1200);
        }, { once: true });

        form.addEventListener('focusin', requestRecaptchaScript, { once: true });
        form.addEventListener('pointerenter', requestRecaptchaScript, { once: true });
    }

    form.addEventListener('submit', (event) => {
        if (usernameInput) {
            usernameInput.value = sanitizeText(usernameInput.value, true);
        }

        if (recaptchaClientError) {
            recaptchaClientError.classList.add('hidden');
        }

        if (recaptchaEnabled) {
            if (typeof grecaptcha === 'undefined') {
                event.preventDefault();
                requestRecaptchaScript();
                if (recaptchaClientError) {
                    recaptchaClientError.textContent = 'Estamos cargando reCAPTCHA. Espera un momento y vuelve a intentarlo.';
                    recaptchaClientError.classList.remove('hidden');
                }
                return;
            }

            const token = grecaptcha.getResponse();
            if (!token) {
                event.preventDefault();
                if (recaptchaClientError) {
                    recaptchaClientError.textContent = 'Completa el reCAPTCHA para continuar.';
                    recaptchaClientError.classList.remove('hidden');
                }
                return;
            }
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
        }

        overlay.classList.add('active');
        document.body.classList.add('overflow-hidden');
    });
});
</script>
@endpush
</div>
@endsection
