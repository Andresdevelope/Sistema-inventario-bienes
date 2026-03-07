@extends('layouts.dashboard')

@section('content')
    @php
        $puedeVerCategorias = auth()->user()?->hasPermission('categorias.ver') ?? false;
        $puedeGestionarCategorias = auth()->user()?->hasPermission('categorias.gestionar') ?? false;
        $puedeVerUbicaciones = auth()->user()?->hasPermission('ubicaciones.ver') ?? false;
        $puedeGestionarUbicaciones = auth()->user()?->hasPermission('ubicaciones.gestionar') ?? false;

        $tabsDisponibles = collect([
            [
                'key' => 'categorias',
                'label' => 'Categorías',
                'enabled' => $puedeVerCategorias || $puedeGestionarCategorias,
            ],
            [
                'key' => 'ubicaciones',
                'label' => 'Ubicaciones',
                'enabled' => $puedeVerUbicaciones || $puedeGestionarUbicaciones,
            ],
        ])->where('enabled');

        $tabActual = $tabsDisponibles->pluck('key')->contains($tab)
            ? $tab
            : ($tabsDisponibles->first()['key'] ?? 'categorias');

        $esTabCategorias = $tabActual === 'categorias';
        $catalogo = $esTabCategorias ? $categorias : $ubicaciones;
        $totalItems = $catalogo->total();
        $activos = $catalogo->getCollection()->where('estado', 'activo')->count();
        $inactivos = $catalogo->getCollection()->where('estado', 'inactivo')->count();
        $tituloCatalogo = $esTabCategorias ? 'categoría' : 'ubicación';
        $tituloCatalogoPlural = $esTabCategorias ? 'categorías' : 'ubicaciones';
        $routeStore = $esTabCategorias ? route('bienes.categorias.store') : route('bienes.ubicaciones.store');
        $maxLength = $esTabCategorias ? 30 : 50;
        $placeholder = $esTabCategorias
            ? 'Ej: Computadoras, Mobiliario, Proyectores'
            : 'Ej: Oficina 1, Depósito A, Laboratorio 2';
        $hint = $esTabCategorias
            ? 'Nombre único, entre 3 y 30 caracteres'
            : 'Nombre único, entre 3 y 50 caracteres';
        $nombrePattern = $esTabCategorias
            ? '^(?=.{3,30}$)(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ .\-]+$'
            : '^(?=.{3,50}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,\-#°]+$';
        $descripcion = $esTabCategorias
            ? 'Administra las categorías para mantener el inventario y reportes más consistentes.'
            : 'Administra las ubicaciones para normalizar la ubicación física de los bienes.';
    @endphp

    <div class="space-y-6 w-full">
        <section class="rounded-3xl border border-slate-200 bg-gradient-to-br from-white via-slate-50/80 to-white shadow-sm p-5 md:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start">
                <div class="space-y-1.5">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-brand-600">Configuración de bienes</p>
                    <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-slate-900">Gestión de catálogos</h1>
                    <p class="text-sm text-slate-500">Centraliza categorías y ubicaciones en un mismo módulo sin perder claridad. Menos caos, más orden; la normalización sonríe.</p>
                </div>
                <div class="flex justify-end lg:ml-auto">
                    <a href="{{ route('bienes.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2 text-[12px] font-semibold text-white shadow-lg shadow-brand-900/30 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Volver a bienes
                    </a>
                </div>
            </div>

            <div class="mt-5 flex flex-wrap gap-2">
                @foreach($tabsDisponibles as $tabItem)
                    <a href="{{ route('bienes.categorias.index', ['tab' => $tabItem['key']]) }}"
                        @class([
                            'inline-flex items-center rounded-2xl px-4 py-2 text-xs font-semibold transition focus:outline-none focus-visible:ring-2',
                            'bg-gradient-to-r from-brand-500 to-brand-600 text-white shadow-lg shadow-brand-900/30 focus-visible:ring-brand-300' => $tabActual === $tabItem['key'],
                            'border border-slate-300 bg-white text-slate-700 shadow-sm hover:-translate-y-0.5 hover:bg-slate-50 focus-visible:ring-slate-300' => $tabActual !== $tabItem['key'],
                        ])>
                        {{ $tabItem['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="mt-5 overflow-hidden rounded-2xl border border-slate-800/90 bg-slate-900/75 shadow-xl ring-1 ring-slate-700/40">
                <table class="w-full table-fixed text-sm">
                    <caption class="sr-only">Resumen rápido del catálogo activo.</caption>
                    <thead class="bg-gradient-to-r from-slate-900 to-slate-800 border-b border-slate-700 text-slate-300">
                        <tr>
                            <th scope="col" class="px-4 py-2.5 text-left font-medium w-[44%]">Métrica</th>
                            <th scope="col" class="px-4 py-2.5 text-left font-medium w-[20%]">Valor</th>
                            <th scope="col" class="px-4 py-2.5 text-left font-medium w-[36%]">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80 text-slate-200">
                        <tr class="odd:bg-slate-900/30 even:bg-slate-900/55 hover:bg-slate-800/80 transition-colors duration-200">
                            <td class="px-4 py-2.5">Total {{ $tituloCatalogoPlural }}</td>
                            <td class="px-4 py-2.5 font-semibold text-slate-100">{{ $totalItems }}</td>
                            <td class="px-4 py-2.5 text-slate-400">Registradas en el catálogo</td>
                        </tr>
                        <tr class="odd:bg-slate-900/30 even:bg-slate-900/55 hover:bg-slate-800/80 transition-colors duration-200">
                            <td class="px-4 py-2.5">Activas (página actual)</td>
                            <td class="px-4 py-2.5">
                                <span class="inline-flex items-center rounded-full border border-green-400/70 text-green-200 bg-green-500/20 px-2.5 py-0.5 text-xs font-semibold">
                                    {{ $activos }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-slate-400">Disponibles para seleccionar en bienes</td>
                        </tr>
                        <tr class="odd:bg-slate-900/30 even:bg-slate-900/55 hover:bg-slate-800/80 transition-colors duration-200">
                            <td class="px-4 py-2.5">Inactivas (página actual)</td>
                            <td class="px-4 py-2.5">
                                <span class="inline-flex items-center rounded-full border border-yellow-400/70 text-yellow-200 bg-yellow-500/20 px-2.5 py-0.5 text-xs font-semibold">
                                    {{ $inactivos }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-slate-400">Se conservan para histórico, pero no aparecen en formularios nuevos</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        @if(($esTabCategorias && $puedeGestionarCategorias) || (!$esTabCategorias && $puedeGestionarUbicaciones))
            <section class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5 md:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-3">
                    <h2 class="text-sm font-semibold text-slate-900">Crear nueva {{ $tituloCatalogo }}</h2>
                    <span class="text-[11px] text-slate-500">{{ $hint }}</span>
                </div>
                <p class="mb-3 text-xs text-slate-500">{{ $descripcion }}</p>
                <form method="POST" action="{{ $routeStore }}" class="grid gap-3 md:grid-cols-[1fr_auto]" data-catalog-kind="{{ $esTabCategorias ? 'categoria' : 'ubicacion' }}">
                    @csrf
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required minlength="3" maxlength="{{ $maxLength }}"
                        pattern="{{ $nombrePattern }}"
                        class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400"
                        placeholder="{{ $placeholder }}">
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-accent-400 to-accent-500 px-4 py-2.5 text-[12px] font-semibold text-brand-900 shadow-lg shadow-accent-900/20 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-200 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                        Guardar {{ $tituloCatalogo }}
                    </button>
                </form>
            </section>
        @endif

        <section class="space-y-3">
            @if($esTabCategorias)
                <div class="md:hidden space-y-2">
                    @forelse($categorias as $categoria)
                        @php($estado = strtolower(trim((string) $categoria->estado)))
                        <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-3 shadow">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-100 truncate" title="{{ $categoria->nombre }}">{{ $categoria->nombre }}</p>
                                    <p class="text-[11px] text-slate-400">Bienes asociados: {{ $usoCategorias[$categoria->nombre] ?? 0 }}</p>
                                </div>
                                <span @class([
                                    'inline-flex items-center rounded-full border px-2 py-0.5 text-[11px] font-semibold whitespace-nowrap',
                                    'border-green-400/70 text-green-200 bg-green-500/20' => $estado === 'activo',
                                    'border-slate-500/50 text-slate-200 bg-slate-500/10' => $estado !== 'activo',
                                ])>{{ ucfirst($estado) }}</span>
                            </div>

                            @if($puedeGestionarCategorias)
                                <div class="mt-3 space-y-2">
                                    <form method="POST" action="{{ route('bienes.categorias.update', $categoria) }}" class="grid grid-cols-[1fr_auto] gap-2" data-catalog-kind="categoria">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="nombre" value="{{ $categoria->nombre }}" required minlength="3" maxlength="30"
                                            pattern="^(?=.{3,30}$)(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ .\-]+$"
                                            class="rounded-md border border-slate-700 bg-slate-950 px-2.5 py-1.5 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-400">
                                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-3 py-1.5 text-[11px] font-semibold text-white shadow cursor-pointer">Renombrar</button>
                                    </form>

                                    <form method="POST" action="{{ route('bienes.categorias.toggle', $categoria) }}" class="flex justify-end">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-2xl border border-slate-500/60 bg-slate-800 px-2.5 py-1 text-[11px] font-semibold text-slate-100 cursor-pointer">
                                            {{ $categoria->estado === 'activo' ? 'Inactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 px-4 py-4 text-center text-slate-500 text-sm">No hay categorías registradas.</div>
                    @endforelse
                </div>

                <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-800/90 bg-slate-900/75 shadow-xl ring-1 ring-slate-700/40">
                    <table class="w-full table-fixed text-sm">
                        <caption class="sr-only">Listado de categorías con estado, cantidad de bienes asociados y acciones de administración.</caption>
                        <thead class="bg-gradient-to-r from-slate-900 to-slate-800 border-b border-slate-700 text-slate-300 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-4 py-2.5 text-left font-medium w-[24%]">Nombre</th>
                                <th scope="col" class="px-4 py-2.5 text-left font-medium w-[14%]">Estado</th>
                                <th scope="col" class="px-4 py-2.5 text-left font-medium w-[16%]">Bienes asociados</th>
                                <th scope="col" class="px-4 py-2.5 text-right font-medium w-[46%]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            @forelse($categorias as $categoria)
                                @php($estado = strtolower(trim((string) $categoria->estado)))
                                <tr class="odd:bg-slate-900/30 even:bg-slate-900/55 hover:bg-slate-800/80 transition-colors duration-200">
                                    <td class="px-4 py-2.5 align-middle text-slate-100">
                                        <span class="block w-full truncate" title="{{ $categoria->nombre }}">{{ $categoria->nombre }}</span>
                                    </td>
                                    <td class="px-4 py-2.5 align-middle">
                                        <span @class([
                                            'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold',
                                            'border-green-400/70 text-green-200 bg-green-500/20' => $estado === 'activo',
                                            'border-slate-500/50 text-slate-200 bg-slate-500/10' => $estado !== 'activo',
                                        ])>
                                            {{ ucfirst($estado) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 align-middle text-slate-300">{{ $usoCategorias[$categoria->nombre] ?? 0 }}</td>
                                    <td class="px-4 py-2.5 align-middle text-right">
                                        @if($puedeGestionarCategorias)
                                            <div class="inline-flex items-center justify-end gap-2 ml-auto w-full">
                                                <form method="POST" action="{{ route('bienes.categorias.update', $categoria) }}" class="inline-flex items-center gap-2" data-catalog-kind="categoria">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="nombre" value="{{ $categoria->nombre }}" required minlength="3" maxlength="30"
                                                        pattern="^(?=.{3,30}$)(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ .\-]+$"
                                                        class="w-44 rounded-md border border-slate-700 bg-slate-950 px-2 py-1 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-400">
                                                    <button type="submit" class="inline-flex items-center rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-2.5 py-1 text-[11px] font-semibold text-white shadow-lg shadow-brand-900/20 transition duration-300 hover:-translate-y-0.5 cursor-pointer">Renombrar</button>
                                                </form>

                                                <form method="POST" action="{{ route('bienes.categorias.toggle', $categoria) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center rounded-2xl border border-slate-500/60 bg-slate-800 px-2.5 py-1 text-[11px] font-semibold text-slate-100 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-700 cursor-pointer">
                                                        {{ $categoria->estado === 'activo' ? 'Inactivar' : 'Activar' }}
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-500">Solo lectura</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-400 text-sm">No hay categorías registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $categorias->onEachSide(1)->links() }}
                </div>
            @else
                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-900">
                    Las ubicaciones se pueden inactivar, pero no conviene eliminarlas si ya tienen bienes asociados. Así preservas el histórico sin romper referencias.
                </div>

                <div class="md:hidden space-y-2">
                    @forelse($ubicaciones as $ubicacion)
                        @php($estado = strtolower(trim((string) $ubicacion->estado)))
                        <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-3 shadow">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-100 truncate" title="{{ $ubicacion->nombre }}">{{ $ubicacion->nombre }}</p>
                                    <p class="text-[11px] text-slate-400">Bienes asociados: {{ $usoUbicaciones[$ubicacion->id] ?? 0 }}</p>
                                </div>
                                <span @class([
                                    'inline-flex items-center rounded-full border px-2 py-0.5 text-[11px] font-semibold whitespace-nowrap',
                                    'border-green-400/70 text-green-200 bg-green-500/20' => $estado === 'activo',
                                    'border-slate-500/50 text-slate-200 bg-slate-500/10' => $estado !== 'activo',
                                ])>{{ ucfirst($estado) }}</span>
                            </div>

                            @if($puedeGestionarUbicaciones)
                                <div class="mt-3 space-y-2">
                                    <form method="POST" action="{{ route('bienes.ubicaciones.update', $ubicacion) }}" class="grid grid-cols-[1fr_auto] gap-2" data-catalog-kind="ubicacion">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="nombre" value="{{ $ubicacion->nombre }}" required minlength="3" maxlength="50"
                                            pattern="^(?=.{3,50}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,\-#°]+$"
                                            class="rounded-md border border-slate-700 bg-slate-950 px-2.5 py-1.5 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-400">
                                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-3 py-1.5 text-[11px] font-semibold text-white shadow cursor-pointer">Renombrar</button>
                                    </form>

                                    <form method="POST" action="{{ route('bienes.ubicaciones.toggle', $ubicacion) }}" class="flex justify-end">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-2xl border border-slate-500/60 bg-slate-800 px-2.5 py-1 text-[11px] font-semibold text-slate-100 cursor-pointer">
                                            {{ $ubicacion->estado === 'activo' ? 'Inactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 px-4 py-4 text-center text-slate-500 text-sm">No hay ubicaciones registradas.</div>
                    @endforelse
                </div>

                <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-800/90 bg-slate-900/75 shadow-xl ring-1 ring-slate-700/40">
                    <table class="w-full table-fixed text-sm">
                        <caption class="sr-only">Listado de ubicaciones con estado, cantidad de bienes asociados y acciones de administración.</caption>
                        <thead class="bg-gradient-to-r from-slate-900 to-slate-800 border-b border-slate-700 text-slate-300 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-4 py-2.5 text-left font-medium w-[24%]">Nombre</th>
                                <th scope="col" class="px-4 py-2.5 text-left font-medium w-[14%]">Estado</th>
                                <th scope="col" class="px-4 py-2.5 text-left font-medium w-[16%]">Bienes asociados</th>
                                <th scope="col" class="px-4 py-2.5 text-right font-medium w-[46%]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80">
                            @forelse($ubicaciones as $ubicacion)
                                @php($estado = strtolower(trim((string) $ubicacion->estado)))
                                <tr class="odd:bg-slate-900/30 even:bg-slate-900/55 hover:bg-slate-800/80 transition-colors duration-200">
                                    <td class="px-4 py-2.5 align-middle text-slate-100">
                                        <span class="block w-full truncate" title="{{ $ubicacion->nombre }}">{{ $ubicacion->nombre }}</span>
                                    </td>
                                    <td class="px-4 py-2.5 align-middle">
                                        <span @class([
                                            'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold',
                                            'border-green-400/70 text-green-200 bg-green-500/20' => $estado === 'activo',
                                            'border-slate-500/50 text-slate-200 bg-slate-500/10' => $estado !== 'activo',
                                        ])>
                                            {{ ucfirst($estado) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2.5 align-middle text-slate-300">{{ $usoUbicaciones[$ubicacion->id] ?? 0 }}</td>
                                    <td class="px-4 py-2.5 align-middle text-right">
                                        @if($puedeGestionarUbicaciones)
                                            <div class="inline-flex items-center justify-end gap-2 ml-auto w-full">
                                                <form method="POST" action="{{ route('bienes.ubicaciones.update', $ubicacion) }}" class="inline-flex items-center gap-2" data-catalog-kind="ubicacion">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="text" name="nombre" value="{{ $ubicacion->nombre }}" required minlength="3" maxlength="50"
                                                        pattern="^(?=.{3,50}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,\-#°]+$"
                                                        class="w-44 rounded-md border border-slate-700 bg-slate-950 px-2 py-1 text-xs text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-400">
                                                    <button type="submit" class="inline-flex items-center rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-2.5 py-1 text-[11px] font-semibold text-white shadow-lg shadow-brand-900/20 transition duration-300 hover:-translate-y-0.5 cursor-pointer">Renombrar</button>
                                                </form>

                                                <form method="POST" action="{{ route('bienes.ubicaciones.toggle', $ubicacion) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center rounded-2xl border border-slate-500/60 bg-slate-800 px-2.5 py-1 text-[11px] font-semibold text-slate-100 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-700 cursor-pointer">
                                                        {{ $ubicacion->estado === 'activo' ? 'Inactivar' : 'Activar' }}
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-500">Solo lectura</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-slate-400 text-sm">No hay ubicaciones registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $ubicaciones->onEachSide(1)->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = Array.from(document.querySelectorAll('form[data-catalog-kind]'));

        function normalizeSpaces(value) {
            return (value || '').replace(/\s+/g, ' ').trim();
        }

        function looksLikeGibberish(text, maxWordLength, maxConsonantCluster) {
            const clean = normalizeSpaces(text);

            if (!clean) {
                return false;
            }

            if (/(.)\1{3,}/u.test(clean)) {
                return true;
            }

            const consonantRegex = new RegExp(`[bcdfghjklmnñpqrstvwxyz]{${maxConsonantCluster},}`, 'iu');
            if (consonantRegex.test(clean)) {
                return true;
            }

            if (/[\p{L}\d]{25,}/u.test(clean)) {
                return true;
            }

            const words = clean.split(/[\s,.;:()\-/#]+/u).filter(Boolean);
            return words.some(word => [...word].length > maxWordLength);
        }

        forms.forEach((form) => {
            const input = form.querySelector('input[name="nombre"]');
            if (!input) {
                return;
            }

            const kind = form.getAttribute('data-catalog-kind') || 'categoria';

            const cfg = kind === 'ubicacion'
                ? {
                    maxLen: 50,
                    gibberishWordMax: 24,
                    consonantCluster: 5,
                    emptyMessage: 'El nombre de la ubicación es obligatorio.',
                    gibberishMessage: 'La ubicación no parece válida. Usa un nombre claro (ej: Oficina 2, Depósito A).',
                  }
                : {
                    maxLen: 30,
                    gibberishWordMax: 18,
                    consonantCluster: 4,
                    emptyMessage: 'El nombre de la categoría es obligatorio.',
                    gibberishMessage: 'La categoría no parece estar bien formulada. Evita texto aleatorio o sin sentido.',
                  };

            input.addEventListener('input', () => {
                input.setCustomValidity('');
            });

            form.addEventListener('submit', (event) => {
                const normalized = normalizeSpaces(input.value);
                input.value = normalized;
                input.setCustomValidity('');

                if (!normalized) {
                    input.setCustomValidity(cfg.emptyMessage);
                } else if (normalized.length > cfg.maxLen) {
                    input.setCustomValidity(`No puede superar los ${cfg.maxLen} caracteres.`);
                } else if (/<[^>]*>/.test(normalized)) {
                    input.setCustomValidity('No se permiten etiquetas HTML o código.');
                } else if (looksLikeGibberish(normalized, cfg.gibberishWordMax, cfg.consonantCluster)) {
                    input.setCustomValidity(cfg.gibberishMessage);
                }

                if (!input.checkValidity()) {
                    event.preventDefault();
                    input.reportValidity();
                }
            });
        });
    });
</script>
@endpush
