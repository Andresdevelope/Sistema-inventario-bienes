<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistema Inventario') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 text-slate-100 antialiased flex items-center justify-center p-4">
    <div class="w-full max-w-5xl">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>
