<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema Inventario') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
<!-- Layout general para páginas públicas/autenticación, ahora con tema claro -->
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-slate-200 to-slate-300 text-slate-900 antialiased">
    <!-- Header institucional -->
    <header class="sticky top-0 z-30 w-full backdrop-blur bg-white/70 border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="h-8 w-8 rounded-lg bg-slate-900 text-white flex items-center justify-center font-semibold">IB</span>
                <div>
                    <h1 class="text-sm font-semibold tracking-wide">Sistema Inventario de Bienes</h1>
                    <p class="text-[11px] text-slate-500">Gestión segura y eficiente</p>
                </div>
            </div>
            <nav class="hidden sm:flex items-center gap-4 text-xs text-slate-600">
                <span class="px-2 py-1 rounded-md bg-slate-100 border border-slate-200">Inicio</span>
                <span class="px-2 py-1 rounded-md">Soporte</span>
            </nav>
        </div>
    </header>

    <!-- Contenido principal centrado -->
    <main class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-6xl">
            @yield('content')
        </div>
    </main>
    @stack('scripts')
</body>
</html>
