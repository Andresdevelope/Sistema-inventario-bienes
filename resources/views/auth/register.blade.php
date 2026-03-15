@extends('layouts.app')

@section('content')
<div class="w-full max-w-3xl mx-auto bg-white/90 backdrop-blur border border-slate-200 shadow-2xl rounded-2xl p-6 md:p-8 text-slate-900">
    <div class="mb-6 text-center">
        <h1 class="text-3xl font-bold tracking-tight mb-1">Crear cuenta</h1>
        <p class="text-xs text-slate-600">Registra tu usuario para acceder al Sistema Inventario de Bienes.</p>
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

    <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-1 md:col-span-2">
                <label class="block text-xs font-medium text-slate-700" for="name">Nombre de usuario</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required minlength="3" maxlength="30" autocomplete="username" data-sanitize="username"
                    pattern="^(?=.{3,30}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9._\- ]+$"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
            </div>

            <div class="space-y-1 md:col-span-2">
                <label class="block text-xs font-medium text-slate-700" for="email">Correo electrónico</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="80" autocomplete="email" data-sanitize="email"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
            </div>


            <div class="space-y-1 relative">
                <label class="block text-xs font-medium text-slate-700" for="password">Contraseña</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" required minlength="16"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600 pr-10">
                <button type="button" onclick="togglePassword('password', this)" tabindex="-1"
                    class="absolute right-2 top-8 text-slate-500 hover:text-slate-700 focus:outline-none">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>

                <div class="mt-2 space-y-1.5 rounded-lg border border-slate-200 bg-slate-50/90 p-2.5">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] font-medium text-slate-600">Fortaleza de contraseña</span>
                        <span id="password-strength-label" class="text-[11px] font-semibold text-slate-500">Sin evaluar</span>
                    </div>
                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-200">
                        <div id="password-strength-bar" class="h-full w-0 rounded-full bg-slate-300 transition-all duration-300"></div>
                    </div>
                    <ul id="password-rules" class="space-y-0.5 text-[11px] text-slate-600">
                        <!-- <li data-rule="len" class="transition-colors">• Entre 16 y 40 caracteres.</li> -->
                        <li data-rule="mixed" class="transition-colors">• Incluye letras y números.</li>
                        <li data-rule="not-only-number" class="transition-colors">• No debe ser solo números.</li>
                        <li data-rule="not-repetitive" class="transition-colors">• Evita repeticiones o patrones débiles.</li>
                        <li data-rule="match" class="transition-colors">• Debe coincidir con la confirmación.</li>
                    </ul>
                </div>
            </div>


            <div class="space-y-1 relative">
                <label class="block text-xs font-medium text-slate-700" for="password_confirmation">Confirmar contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" required minlength="16"
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600 pr-10">
                <button type="button" onclick="togglePassword('password_confirmation', this)" tabindex="-1"
                    class="absolute right-2 top-8 text-slate-500 hover:text-slate-700 focus:outline-none">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="border-t border-slate-200 pt-4 mt-2">
            <h2 class="text-sm font-semibold text-slate-900 mb-1">Preguntas de seguridad</h2>
            <p class="text-xs text-slate-600 mb-3">Se utilizarán para recuperar tu cuenta en caso de olvidar la contraseña.</p>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="space-y-1 md:col-span-1">
                    <label class="block text-xs font-medium text-slate-700" for="security_color_answer">Color favorito</label>
                    <input id="security_color_answer" name="security_color_answer" type="text" value="{{ old('security_color_answer') }}" required minlength="2" maxlength="40" data-sanitize="text"
                        pattern="^(?=.{2,40}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ .,\-]+$"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                </div>

                <div class="space-y-1 md:col-span-1">
                    <label class="block text-xs font-medium text-slate-700" for="security_animal_answer">Animal favorito</label>
                    <input id="security_animal_answer" name="security_animal_answer" type="text" value="{{ old('security_animal_answer') }}" required minlength="2" maxlength="40" data-sanitize="text"
                        pattern="^(?=.{2,40}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ .,\-]+$"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                </div>

                <div class="space-y-1 md:col-span-1">
                    <label class="block text-xs font-medium text-slate-700" for="security_padre_answer">Nombre de tu padre</label>
                    <input id="security_padre_answer" name="security_padre_answer" type="text" value="{{ old('security_padre_answer') }}" required minlength="2" maxlength="40" data-sanitize="text"
                        pattern="^(?=.{2,40}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ .,\-]+$"
                        class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                </div>
            </div>
        </div>

        <div class="pt-2 space-y-3">
            <button type="submit"
                class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-900/30 transition duration-300 hover:-translate-y-0.5 hover:shadow-brand-900/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a7.5 7.5 0 0115 0" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25v4.5m2.25-2.25H17.25" />
                </svg>
                Crear cuenta segura
            </button>

            <div class="grid gap-3 text-xs sm:grid-cols-2 items-center">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl border border-brand-400/50 bg-brand-50/80 px-4 py-2 font-semibold text-brand-700 shadow-inner shadow-brand-200/60 transition duration-300 hover:-translate-y-0.5 hover:bg-brand-100/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-9a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 004.5 21h9a2.25 2.25 0 002.25-2.25V15" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 12h-9m0 0l3-3m-3 3l3 3" />
                    </svg>
                    Ya tengo cuenta
                </a>

                <p class="text-xs text-slate-600 text-center sm:text-right">
                    ¿Ya estás registrado? <span class="font-semibold text-slate-700">Inicia sesión</span>
                </p>
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

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('register-form');
    if (!form) return;

    const sanitizeText = (value, collapseSpaces = true) => {
        let clean = String(value ?? '').replace(/<[^>]*>/g, '');
        clean = clean.replace(/[\u0000-\u001F\u007F]/g, '');
        if (collapseSpaces) {
            clean = clean.replace(/\s+/g, ' ');
        }
        return clean.trim();
    };

    const usernameInput = form.querySelector('input[data-sanitize="username"]');
    const emailInput = form.querySelector('input[data-sanitize="email"]');
    const textInputs = Array.from(form.querySelectorAll('input[data-sanitize="text"]'));
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const strengthLabel = document.getElementById('password-strength-label');
    const strengthBar = document.getElementById('password-strength-bar');
    const rulesList = document.getElementById('password-rules');

    const looksLikeGibberish = (text, maxWordLength = 18, maxConsonantCluster = 4) => {
        const clean = sanitizeText(text, true);
        if (!clean) return false;

        if (/(.)\1{3,}/u.test(clean)) return true;
        if (new RegExp(`[bcdfghjklmnñpqrstvwxyz]{${maxConsonantCluster},}`, 'iu').test(clean)) return true;
        if (/[\p{L}\d]{25,}/u.test(clean)) return true;

        const words = clean.split(/[\s,.;:()\-/#]+/u).filter(Boolean);
        return words.some(word => [...word].length > maxWordLength);
    };

    const hasSuspiciousEmailLocalPart = (email) => {
        const clean = sanitizeText(email, true).toLowerCase();
        const local = clean.split('@')[0] ?? '';
        if (!local) return true;
        if (/^\d+$/u.test(local)) return true;
        if (/^(.)\1{5,}$/u.test(local)) return true;
        return false;
    };

    const validateField = (input) => {
        if (!input) return;
        input.setCustomValidity('');
        const value = sanitizeText(input.value, true);

        if (input === usernameInput) {
            if (looksLikeGibberish(value, 18, 4) || /^\d+$/u.test(value)) {
                input.setCustomValidity('El nombre de usuario no parece válido. Evita texto repetitivo o numérico.');
            }
            return;
        }

        if (input === emailInput) {
            if (hasSuspiciousEmailLocalPart(value)) {
                input.setCustomValidity('El correo no parece válido. Usa un identificador real antes de @.');
            }
            return;
        }

        if (/\d/u.test(value)) {
            input.setCustomValidity('Las respuestas de seguridad no deben contener números.');
            return;
        }

        if (looksLikeGibberish(value, 18, 4)) {
            input.setCustomValidity('La respuesta no parece válida. Evita texto aleatorio o repetitivo.');
        }
    };

    const validatePasswordPair = () => {
        if (!passwordInput || !passwordConfirmationInput) return;

        const password = String(passwordInput.value ?? '').trim();
        const confirmation = String(passwordConfirmationInput.value ?? '').trim();

        passwordInput.setCustomValidity('');
        passwordConfirmationInput.setCustomValidity('');

        if (/^\d+$/u.test(password)) {
            passwordInput.setCustomValidity('La contraseña no puede estar compuesta solo por números.');
        } else if (/^(.)\1{5,}$/u.test(password)) {
            passwordInput.setCustomValidity('La contraseña no puede ser una repetición del mismo carácter.');
        } else if (looksLikeGibberish(password, 30, 7)) {
            passwordInput.setCustomValidity('La contraseña no parece segura. Evita texto aleatorio o secuencias repetitivas.');
        }

        if (confirmation && confirmation !== password) {
            passwordConfirmationInput.setCustomValidity('La confirmación de la contraseña no coincide.');
        }

        const meetsLen = password.length >= 16 && password.length <= 40;
        const hasLetter = /[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]/u.test(password);
        const hasNumber = /\d/u.test(password);
        const notOnlyNumber = !/^\d+$/u.test(password);
        const notRepetitive = !/^(.)\1{5,}$/u.test(password) && !looksLikeGibberish(password, 30, 7);
        const matches = confirmation.length > 0 ? confirmation === password : false;

        const checks = {
            len: meetsLen,
            mixed: hasLetter && hasNumber,
            'not-only-number': notOnlyNumber,
            'not-repetitive': notRepetitive,
            match: matches,
        };

        if (rulesList) {
            Array.from(rulesList.querySelectorAll('[data-rule]')).forEach((item) => {
                const key = item.getAttribute('data-rule') || '';
                const ok = Boolean(checks[key]);
                item.classList.toggle('text-emerald-700', ok);
                item.classList.toggle('font-medium', ok);
                item.classList.toggle('text-slate-600', !ok);
            });
        }

        const score = Object.values(checks).filter(Boolean).length;
        const level = !password
            ? { text: 'Sin evaluar', width: '0%', cls: 'bg-slate-300 text-slate-500' }
            : score <= 2
                ? { text: 'Débil', width: '35%', cls: 'bg-red-500 text-red-600' }
                : score <= 4
                    ? { text: 'Media', width: '70%', cls: 'bg-amber-500 text-amber-600' }
                    : { text: 'Fuerte', width: '100%', cls: 'bg-emerald-500 text-emerald-600' };

        if (strengthBar) {
            strengthBar.style.width = level.width;
            strengthBar.classList.remove('bg-slate-300', 'bg-red-500', 'bg-amber-500', 'bg-emerald-500');
            strengthBar.classList.add(level.cls.split(' ')[0]);
        }

        if (strengthLabel) {
            strengthLabel.textContent = level.text;
            strengthLabel.classList.remove('text-slate-500', 'text-red-600', 'text-amber-600', 'text-emerald-600');
            strengthLabel.classList.add(level.cls.split(' ')[1]);
        }
    };

    usernameInput?.addEventListener('input', () => {
        usernameInput.value = sanitizeText(usernameInput.value, true);
        validateField(usernameInput);
    });

    emailInput?.addEventListener('input', () => {
        emailInput.value = sanitizeText(emailInput.value, true).toLowerCase();
        validateField(emailInput);
    });

    textInputs.forEach((input) => {
        input.addEventListener('input', () => {
            input.value = sanitizeText(input.value, true);
            validateField(input);
        });
    });

    passwordInput?.addEventListener('input', validatePasswordPair);
    passwordConfirmationInput?.addEventListener('input', validatePasswordPair);
    validatePasswordPair();

    form.addEventListener('submit', (event) => {
        if (usernameInput) {
            usernameInput.value = sanitizeText(usernameInput.value, true);
            validateField(usernameInput);
        }
        if (emailInput) {
            emailInput.value = sanitizeText(emailInput.value, true).toLowerCase();
            validateField(emailInput);
        }
        textInputs.forEach((input) => {
            input.value = sanitizeText(input.value, true);
            validateField(input);
        });

        validatePasswordPair();

        const firstInvalid = [usernameInput, emailInput, passwordInput, passwordConfirmationInput, ...textInputs].find((input) => input && !input.checkValidity());
        if (firstInvalid) {
            event.preventDefault();
            firstInvalid.reportValidity();
        }
    });
});
</script>
@endpush
@endsection
