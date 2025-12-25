<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema Inventario') }} - Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 text-slate-100 antialiased">
    <div class="flex min-h-screen">
        {{-- Sidebar de navegación --}}
        <aside class="hidden md:flex md:w-64 lg:w-72 border-r border-slate-800 bg-slate-950/70 backdrop-blur-xl">
            <div class="flex flex-col w-full h-full">
                <div class="px-5 pt-5 pb-4 border-b border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-lg bg-gradient-to-tr from-blue-500 to-indigo-500 flex items-center justify-center text-sm font-bold shadow-lg">
                            SI
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-slate-300 uppercase">Sistema de inventario</p>
                            <p class="text-[11px] text-slate-400">Bienes institucionales</p>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
                    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-slate-100 bg-gradient-to-r from-blue-600/80 to-indigo-600/80 shadow hover:from-blue-500 hover:to-indigo-500 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-950/30">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875A2.25 2.25 0 0112 13.875v0a2.25 2.25 0 012.25 2.25v4.875h4.125c.621 0 1.125-.504 1.125-1.125V9.75" />
                            </svg>
                        </span>
                        <span class="flex-1">
                            <span class="block font-medium leading-tight">Inicio</span>
                            <span class="block text-[11px] text-slate-300/80">Resumen general del sistema</span>
                        </span>
                    </a>

                    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-slate-300 hover:bg-slate-900/70 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-900/70 group-hover:bg-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-15.75 0v-12A1.125 1.125 0 016 6.375h3.375M4.875 19.5h4.5m0 0V8.25A1.125 1.125 0 0110.5 7.125H14.25m-4.875 12.375h9.75m0 0V10.125A1.125 1.125 0 0018 9h-3.375" />
                            </svg>
                        </span>
                        <span class="flex-1">
                            <span class="block font-medium leading-tight">Bienes</span>
                            <span class="block text-[11px] text-slate-400">Registro y control de bienes</span>
                        </span>
                    </a>

                    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-slate-300 hover:bg-slate-900/70 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-900/70 group-hover:bg-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                            </svg>
                        </span>
                        <span class="flex-1">
                            <span class="block font-medium leading-tight">Categorías</span>
                            <span class="block text-[11px] text-slate-400">Clasificación de bienes</span>
                        </span>
                    </a>

                    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-slate-300 hover:bg-slate-900/70 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-900/70 group-hover:bg-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a6.75 6.75 0 0113.5 0" />
                            </svg>
                        </span>
                        <span class="flex-1">
                            <span class="block font-medium leading-tight">Usuarios</span>
                            <span class="block text-[11px] text-slate-400">Gestión de responsables</span>
                        </span>
                    </a>

                    <a href="#" class="group flex items-center gap-3 rounded-lg px-3 py-2 text-slate-300 hover:bg-slate-900/70 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-md bg-slate-900/70 group-hover:bg-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h15.75c.621 0 1.125.504 1.125 1.125v5.25A1.125 1.125 0 0119.875 19.5H4.125A1.125 1.125 0 013 18.375v-5.25z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12V6.75a3.75 3.75 0 017.5 0V12" />
                            </svg>
                        </span>
                        <span class="flex-1">
                            <span class="block font-medium leading-tight">Bitacora</span>
                            <span class="block text-[11px] text-slate-400">Registro de movimientos</span>
                        </span>
                    </a>
                </nav>

                {{-- Pie del sidebar (vacío por ahora, sin texto de sesión) --}}
                <div class="px-4 py-4 border-t border-slate-800 text-[11px] text-slate-500">
                </div>
            </div>
        </aside>

        {{-- Contenido principal --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="border-b border-slate-800 bg-slate-950/60 backdrop-blur-xl">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-[11px] font-semibold tracking-[0.2em] text-blue-300 uppercase">Panel principal</p>
                        <h1 class="text-xl md:text-2xl font-bold tracking-tight">Sistema inventario de bienes</h1>
                        <p class="text-xs text-slate-400 mt-1">Vision general de los bienes y operaciones del sistema.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex flex-col items-end">
                            <p class="text-xs text-slate-400">Bienvenido,</p>
                            <p class="text-sm font-medium text-slate-100 truncate max-w-[160px]">
                                {{ auth()->user()->name ?? 'Usuario' }}
                            </p>
                        </div>

                        <div class="relative" data-profile-menu-wrapper>
                            <button type="button" onclick="toggleProfileMenu(this)" class="h-10 w-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-500 flex items-center justify-center text-sm font-semibold shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400/60 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a6.75 6.75 0 0113.5 0" />
                                </svg>
                            </button>

                            <div class="hidden absolute right-0 mt-2 w-44 rounded-xl border border-slate-800 bg-slate-950/95 shadow-xl text-xs z-20" data-profile-menu>
                                <div class="px-3 pt-2 pb-2 border-b border-slate-800">
                                    <p class="text-[11px] text-slate-400">Conectado como</p>
                                    <p class="text-[11px] font-semibold text-slate-100 truncate max-w-[150px]">
                                        {{ auth()->user()->name ?? 'Usuario' }}
                                    </p>
                                </div>
                                <a href="{{ route('profile') }}" class="flex items-center gap-2 px-3 py-2 text-slate-100 hover:bg-slate-800/80 cursor-pointer">
                                    <span class="h-5 w-5 rounded-md bg-slate-800 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a6.75 6.75 0 0113.5 0" />
                                        </svg>
                                    </span>
                                    <span>Ir a perfil</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-800">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-left text-red-300 hover:bg-red-500/10">
                                        <span class="h-5 w-5 rounded-md bg-red-500/20 flex items-center justify-center text-red-300">
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
                @yield('content')
            </main>
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
