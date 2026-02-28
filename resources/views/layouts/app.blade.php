<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema Inventario') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo-institucion.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo-institucion.jpg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
<!-- Layout general para páginas públicas/autenticación, ahora con tema claro -->
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-950 via-slate-950 to-brand-900 text-slate-50 antialiased">
    <!-- Header institucional -->
    <header class="sticky top-0 z-30 w-full backdrop-blur bg-slate-950/60 border-b border-brand-500/30">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo-institucion.jpg') }}" alt="Logo institucional" class="h-10 w-10 rounded-xl border border-white/20 bg-white/90 p-1 object-contain shadow-lg shadow-brand-900/40">
                <div>
                    <h1 class="text-sm font-semibold tracking-wide text-brand-50">Sistema Inventario de Bienes</h1>
                    <p class="text-[11px] text-brand-100/80">Gestión segura y eficiente</p>
                </div>
            
            </div>
           
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
