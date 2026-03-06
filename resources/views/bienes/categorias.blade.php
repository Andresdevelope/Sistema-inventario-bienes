@extends('layouts.dashboard')

@section('content')
    @php
        $totalCategorias = $categorias->total();
        $activos = $categorias->getCollection()->where('estado', 'activo')->count();
        $inactivos = $categorias->getCollection()->where('estado', 'inactivo')->count();
    @endphp

    <div class="space-y-6 w-full">
        <section class="rounded-3xl border border-slate-200 bg-gradient-to-br from-white via-slate-50/80 to-white shadow-sm p-5 md:p-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start">
                <div class="space-y-1.5">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-brand-600">Configuración de bienes</p>
                    <h1 class="text-xl md:text-2xl font-semibold tracking-tight text-slate-900">Gestión de categorías</h1>
                    <p class="text-sm text-slate-500">Administra el catálogo para mantener registro y reportes más limpios y consistentes.</p>
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

            <div class="mt-5 overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/70 shadow-xl">
                <table class="w-full table-fixed text-sm">
                    <caption class="sr-only">Resumen rápido de categorías: total, activas e inactivas en la página actual.</caption>
                    <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400">
                        <tr>
                            <th scope="col" class="px-4 py-2.5 text-left font-medium w-[44%]">Métrica</th>
                            <th scope="col" class="px-4 py-2.5 text-left font-medium w-[20%]">Valor</th>
                            <th scope="col" class="px-4 py-2.5 text-left font-medium w-[36%]">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/80 text-slate-200">
                        <tr class="hover:bg-slate-900/80">
                            <td class="px-4 py-2.5">Total categorías</td>
                            <td class="px-4 py-2.5 font-semibold text-slate-100">{{ $totalCategorias }}</td>
                            <td class="px-4 py-2.5 text-slate-400">Registradas en el catálogo</td>
                        </tr>
                        <tr class="hover:bg-slate-900/80">
                            <td class="px-4 py-2.5">Activas (página actual)</td>
                            <td class="px-4 py-2.5">
                                <span class="inline-flex items-center rounded-full border border-green-400/70 text-green-200 bg-green-500/20 px-2.5 py-0.5 text-xs font-semibold">
                                    {{ $activos }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-slate-400">Disponibles para seleccionar</td>
                        </tr>
                        <tr class="hover:bg-slate-900/80">
                            <td class="px-4 py-2.5">Inactivas (página actual)</td>
                            <td class="px-4 py-2.5">
                                <span class="inline-flex items-center rounded-full border border-yellow-400/70 text-yellow-200 bg-yellow-500/20 px-2.5 py-0.5 text-xs font-semibold">
                                    {{ $inactivos }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-slate-400">No disponibles en formularios</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5 md:p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-3">
                <h2 class="text-sm font-semibold text-slate-900">Crear nueva categoría</h2>
                <span class="text-[11px] text-slate-500">Nombre único, entre 3 y 30 caracteres</span>
            </div>
            <form method="POST" action="{{ route('bienes.categorias.store') }}" class="grid gap-3 md:grid-cols-[1fr_auto]">
                @csrf
                <input type="text" name="nombre" value="{{ old('nombre') }}" required minlength="3" maxlength="30"
                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400"
                    placeholder="Ej: Computadoras, Mobiliario, Proyectores">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-accent-400 to-accent-500 px-4 py-2.5 text-[12px] font-semibold text-brand-900 shadow-lg shadow-accent-900/20 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                    Guardar categoría
                </button>
            </form>
        </section>

        <section class="space-y-3">
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

                        <div class="mt-3 space-y-2">
                            <form method="POST" action="{{ route('bienes.categorias.update', $categoria) }}" class="grid grid-cols-[1fr_auto] gap-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="nombre" value="{{ $categoria->nombre }}" required minlength="3" maxlength="30"
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
                    </article>
                @empty
                    <div class="rounded-2xl border border-slate-800 bg-slate-900/70 px-4 py-4 text-center text-slate-500 text-sm">No hay categorías registradas.</div>
                @endforelse
            </div>

            <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/70 shadow-xl">
                <table class="w-full table-fixed text-sm">
                    <caption class="sr-only">Listado de categorías con estado, cantidad de bienes asociados y acciones de administración.</caption>
                    <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400">
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
                            <tr class="hover:bg-slate-900/80">
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
                                    <div class="inline-flex items-center justify-end gap-2 ml-auto w-full">
                                        <form method="POST" action="{{ route('bienes.categorias.update', $categoria) }}" class="inline-flex items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="nombre" value="{{ $categoria->nombre }}" required minlength="3" maxlength="30"
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-slate-500 text-sm">No hay categorías registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $categorias->onEachSide(1)->links() }}
        </div>
    </div>
@endsection
