<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema Inventario') }} - Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
<!-- Layout del panel interno, actualizado a tema claro -->
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    <div class="flex min-h-screen">
        {{-- Sidebar de navegación --}}
        <aside class="hidden md:flex md:w-64 lg:w-72 border-r border-violet-800/70 bg-gradient-to-b from-violet-800 via-violet-900 to-purple-900 text-violet-50">
            <div class="flex flex-col w-full h-full">
                <div class="px-5 pt-5 pb-4 border-b border-violet-700/70">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-lg bg-white/10 flex items-center justify-center text-sm font-bold shadow-lg">
                            SI
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-violet-50 uppercase">Sistema de inventario</p>
                            <p class="text-[11px] text-violet-200/80">Bienes institucionales</p>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-violet-50 bg-white/10 shadow hover:bg-white/15 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-black/10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875A2.25 2.25 0 0112 13.875v0a2.25 2.25 0 012.25 2.25v4.875h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
                            </svg>
                        </span>
                        <span class="flex-1">
                            <span class="block font-medium leading-tight">Inicio</span>
                            <span class="block text-[11px] text-violet-100/90">Resumen general del sistema</span>
                        </span>
                    </a>

                    <a href="{{ route('bienes.index') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-violet-100 hover:bg-white/10 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-white/10 group-hover:bg-white/15">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-15.75 0v-12A1.125 1.125 0 016 6.375h3.375M4.875 19.5h4.5m0 0V8.25A1.125 1.125 0 0110.5 7.125H14.25m-4.875 12.375h9.75m0 0V10.125A1.125 1.125 0 0018 9h-3.375" />
                            </svg>
                        </span>
                        <span class="flex-1">
                            <span class="block font-medium leading-tight">Bienes</span>
                            <span class="block text-[11px] text-violet-100/80">Registro y control de bienes</span>
                        </span>
                    </a>

                    
                    <a href="{{ route('users.index') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-violet-100 hover:bg-white/10 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-white/10 group-hover:bg-white/15">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a6.75 6.75 0 0113.5 0" />
                            </svg>
                        </span>
                        <span class="flex-1">
                            <span class="block font-medium leading-tight">Usuarios</span>
                            <span class="block text-[11px] text-violet-100/80">Gestión de responsables</span>
                        </span>
                    </a>

                    <a href="{{ route('bitacora.index') }}" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-violet-100 hover:bg-white/10 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-white/10 group-hover:bg-white/15">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h15.75c.621 0 1.125.504 1.125 1.125v5.25A1.125 1.125 0 0119.875 19.5H4.125A1.125 1.125 0 013 18.375v-5.25z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12V6.75a3.75 3.75 0 017.5 0V12" />
                            </svg>
                        </span>
                        <span class="flex-1">
                            <span class="block font-medium leading-tight">Bitacora</span>
                            <span class="block text-[11px] text-violet-100/80">Registro de movimientos</span>
                        </span>
                    </a>
                </nav>

                {{-- Pie del sidebar (vacío por ahora, sin texto de sesión) --}}
                <div class="px-4 py-4 border-t border-violet-700/70 text-[11px] text-violet-200/80">
                </div>
            </div>
        </aside>

        {{-- Contenido principal --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="border-b border-slate-200 bg-white">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-semibold tracking-[0.2em] text-blue-600 uppercase">Panel principal</p>
                        <h1 class="text-xl md:text-2xl font-bold tracking-tight text-slate-900">Sistema inventario de bienes</h1>
                        <p class="text-xs text-slate-500 mt-1">Visión general de los bienes y operaciones del sistema.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex flex-col items-end">
                            <p class="text-xs text-slate-500">Bienvenido,</p>
                            <p class="text-sm font-medium text-slate-900 truncate max-w-[160px]">
                                {{ auth()->user()->name ?? 'Usuario' }}
                            </p>
                        </div>

                        <div class="relative" data-profile-menu-wrapper>
                            <button type="button" onclick="toggleProfileMenu(this)" class="h-10 w-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-500 flex items-center justify-center text-sm font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400/60 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a6.75 6.75 0 0113.5 0" />
                                </svg>
                            </button>

                            <div class="hidden absolute right-0 mt-2 w-44 rounded-xl border border-slate-200 bg-white shadow-xl text-xs z-20" data-profile-menu>
                                <div class="px-3 pt-2 pb-2 border-b border-slate-200">
                                    <p class="text-[11px] text-slate-500">Conectado como</p>
                                    <p class="text-[11px] font-semibold text-slate-900 truncate max-w-[150px]">
                                        {{ auth()->user()->name ?? 'Usuario' }}
                                    </p>
                                </div>
                                <a href="{{ route('profile') }}" class="flex items-center gap-2 px-3 py-2 text-slate-700 hover:bg-slate-100 cursor-pointer">
                                    <span class="h-5 w-5 rounded-md bg-slate-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a6.75 6.75 0 0113.5 0" />
                                        </svg>
                                    </span>
                                    <span>Ir a perfil</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-200">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-left text-red-600 hover:bg-red-50">
                                        <span class="h-5 w-5 rounded-md bg-red-100 flex items-center justify-center text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H9.75" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9l3-3m0 0l3 3m-3-3v12" />
                                            </svg>
                                        </span>
                                        <span>Cerrar sesión</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                {{-- Mensajes de estado / error globales --}}
                @if (session('status'))
                    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-800">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>


    <!-- Modales globales (moved here to ensure fixed positioning and correct centering) -->
    <div id="create-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-lg max-h-[calc(100vh-6rem)] overflow-auto rounded-2xl bg-white/90 backdrop-blur border border-slate-200 shadow-2xl p-6 text-xs text-slate-900">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">Registrar nuevo usuario</h2>
                    <p class="text-[11px] text-slate-600">Completa los datos para crear un usuario en el sistema.</p>
                </div>
                <button type="button" data-close-modal class="text-slate-500 hover:text-slate-700 cursor-pointer text-sm">✕</button>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="space-y-3" id="create-user-form">
                <div id="create-user-errors" class="mb-2 text-xs text-red-700 border border-red-200 bg-red-50 rounded px-3 py-2 hidden"></div>
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-medium text-slate-700 mb-1">Nombre de usuario</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-slate-700 mb-1">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-medium text-slate-700 mb-1">Rol</label>
                        <select name="role"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Usuario</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="relative">
                        <label class="block text-[11px] font-medium text-slate-700 mb-1">Contraseña <span class="text-red-600">(mínimo 16 caracteres)</span></label>
                        <input type="password" name="password" id="create-user-password"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 pr-8 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                        <button type="button" onclick="togglePassword('password', this)" tabindex="-1"
                            class="absolute right-2 top-7 text-slate-500 hover:text-slate-700 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                    <div class="relative">
                        <label class="block text-[11px] font-medium text-slate-700 mb-1">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" id="create-user-password-confirm"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 pr-8 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                        <button type="button" onclick="togglePassword('password_confirmation', this)" tabindex="-1"
                            class="absolute right-2 top-7 text-slate-500 hover:text-slate-700 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                    <script>
                    // Validación frontend para el modal de crear usuario
                    document.addEventListener('DOMContentLoaded', function () {
                        const form = document.getElementById('create-user-form');
                        if (!form) return;
                        form.addEventListener('submit', function (e) {
                            const password = document.getElementById('create-user-password');
                            const passwordConfirm = document.getElementById('create-user-password-confirm');
                            const errorBox = document.getElementById('create-user-errors');
                            let errors = [];
                            if (password.value.length < 16) {
                                errors.push('La contraseña debe tener al menos 16 caracteres.');
                            }
                            if (password.value !== passwordConfirm.value) {
                                errors.push('La confirmación de la contraseña no coincide.');
                            }
                            if (errors.length > 0) {
                                e.preventDefault();
                                errorBox.innerHTML = '<ul><li>' + errors.join('</li><li>') + '</li></ul>';
                                errorBox.classList.remove('hidden');
                                password.classList.add('border-red-500');
                                passwordConfirm.classList.add('border-red-500');
                            } else {
                                errorBox.classList.add('hidden');
                                password.classList.remove('border-red-500');
                                passwordConfirm.classList.remove('border-red-500');
                            }
                        });
                    });
                    </script>
                </div>

                <div class="space-y-2">
                    <div>
                        <label class="block text-[11px] font-medium text-slate-700 mb-1">¿Cuál es tu color favorito?</label>
                        <input type="text" name="security_color_answer" value="{{ old('security_color_answer') }}"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-slate-700 mb-1">¿Cuál es tu animal favorito?</label>
                        <input type="text" name="security_animal_answer" value="{{ old('security_animal_answer') }}"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                    <div>
                        <label class="block text-[11px] font-medium text-slate-700 mb-1">¿Cuál es el nombre de tu padre?</label>
                        <input type="text" name="security_padre_answer" value="{{ old('security_padre_answer') }}"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-end gap-2 text-[11px]">
                    <button type="button" data-close-modal
                        class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-slate-700 hover:bg-slate-100 cursor-pointer">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="inline-flex items-center rounded-md bg-slate-900 px-4 py-1.5 text-white text-[11px] font-semibold hover:bg-slate-800 cursor-pointer">
                        Guardar usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="confirm-delete-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-sm max-h-[calc(100vh-6rem)] overflow-auto rounded-2xl bg-white/90 backdrop-blur border border-slate-200 shadow-2xl p-5 text-xs text-slate-900">
            <h2 class="text-sm font-semibold mb-2 flex items-center gap-2" data-modal-user-title>
                Eliminar usuario
            </h2>
            <p class="text-[11px] text-slate-600 mb-4" data-modal-user-text>
                ¿Seguro que deseas eliminar este usuario? Esta acción no se puede deshacer.
            </p>
            <div class="mb-4">
                <label class="block text-[11px] font-medium text-slate-700 mb-1">Confirma tu contraseña</label>
                <input type="password" data-modal-admin-password class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
            </div>
            <div class="flex justify-end gap-2 text-[11px]">
                <button type="button" data-modal-user-cancel class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-slate-700 hover:bg-slate-100 cursor-pointer">
                    Cancelar
                </button>
                <button type="button" data-modal-user-confirm class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-white hover:bg-red-500 cursor-pointer">
                    Eliminar
                </button>
            </div>
        </div>
    </div>

    <div id="confirm-unlock-user-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-sm max-h-[calc(100vh-6rem)] overflow-auto rounded-2xl bg-white/90 backdrop-blur border border-slate-200 shadow-2xl p-5 text-xs text-slate-900">
            <h2 class="text-sm font-semibold mb-2 flex items-center gap-2">
                Desbloquear usuario
            </h2>
            <p class="text-[11px] text-slate-600 mb-4" data-modal-unlock-text>
                Para desbloquear este usuario, ingresa tu contraseña.
            </p>
            <div class="mb-4">
                <label class="block text-[11px] font-medium text-slate-700 mb-1">Confirma tu contraseña</label>
                <input type="password" data-modal-admin-password-unlock class="w-full rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
            </div>
            <div class="flex justify-end gap-2 text-[11px]">
                <button type="button" data-modal-unlock-cancel class="inline-flex items-center rounded-md border border-slate-300 bg-white px-3 py-1.5 text-slate-700 hover:bg-slate-100 cursor-pointer">
                    Cancelar
                </button>
                <button type="button" data-modal-unlock-confirm class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-1.5 text-white hover:bg-emerald-500 cursor-pointer">
                    Desbloquear
                </button>
            </div>
        </div>
    </div>

    @stack('scripts')
    <script>
        function toggleProfileMenu(btn) {
            const wrapper = btn.closest('[data-profile-menu-wrapper]');
            if (!wrapper) return;
            const menu = wrapper.querySelector('[data-profile-menu]');
            if (!menu) return;

            const isHidden = menu.classList.contains('hidden');
            // Cerrar cualquier otro menú abierto
            document.querySelectorAll('[data-profile-menu]').forEach(m => m.classList.add('hidden'));
            if (isHidden) {
                menu.classList.remove('hidden');
            }
        }

        document.addEventListener('click', (event) => {
            if (!event.target.closest('[data-profile-menu-wrapper]')) {
                document.querySelectorAll('[data-profile-menu]').forEach(m => m.classList.add('hidden'));
            }
        });
    </script>
</body>
</html>
