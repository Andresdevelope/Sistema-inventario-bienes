@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Panel de resumen rápido --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5 sm:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-slate-900">Bienvenido al panel de inventario</h2>
                        <p class="text-xs text-slate-500 mt-1 max-w-xl">
                            Desde aquí podrás controlar los bienes institucionales, registrar movimientos, consultar historiales y generar reportes.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tarjetas de estado del sistema --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-t from-violet-500/15 to-fuchsia-500/5 opacity-90 pointer-events-none"></div>
                    <p class="text-[11px] font-medium text-violet-700 uppercase tracking-wide">Bienes registrados</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">—</p>
                    <p class="mt-1 text-[11px] text-slate-500">Total de bienes actualmente en el sistema.</p>
                </div>

                <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-t from-emerald-500/15 to-teal-500/5 opacity-90 pointer-events-none"></div>
                    <p class="text-[11px] font-medium text-emerald-700 uppercase tracking-wide">Movimientos recientes</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">—</p>
                    <p class="mt-1 text-[11px] text-slate-500">Altas, bajas y transferencias recientes.</p>
                </div>

                <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:col-span-2 xl:col-span-1">
                    <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-t from-amber-500/15 to-orange-500/5 opacity-90 pointer-events-none"></div>
                    <p class="text-[11px] font-medium text-amber-700 uppercase tracking-wide">Alertas</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">0</p>
                    <p class="mt-1 text-[11px] text-slate-500">Aquí aparecerán advertencias importantes del sistema.</p>
                </div>
            </div>

            {{-- Accesos rápidos --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-600 mb-3">Accesos rápidos</p>
                <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3">
                    <a href="{{ route('bienes.create') }}" class="flex items-center gap-3 rounded-xl bg-gradient-to-r from-violet-600 to-fuchsia-600 px-3.5 py-3 text-left text-xs font-medium text-white shadow-md hover:from-violet-500 hover:to-fuchsia-500 transition cursor-pointer">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5l8.25-4.5 8.25 4.5M3 7.5l8.25 4.5M3 7.5v9l8.25 4.5M19.5 7.5l-8.25 4.5M19.5 7.5v9l-8.25 4.5" />
                            </svg>
                        </span>
                        <span>
                            Registrar nuevo bien
                            <span class="block text-[11px] font-normal text-violet-100/90">Crear un registro en inventario.</span>
                        </span>
                    </a>

                    <button type="button" class="flex items-center gap-3 rounded-xl bg-slate-50 px-3.5 py-3 text-left text-xs font-medium text-slate-800 border border-slate-200 hover:border-violet-400 hover:bg-violet-50/60 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-100 text-violet-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 4.5h19.5M2.25 9.75h19.5M9 15h12.75M9 19.5h12.75M4.5 15h.008v.008H4.5V15zm0 4.5h.008v.008H4.5v-.008z" />
                            </svg>
                        </span>
                        <span>
                            Consultar inventario
                            <span class="block text-[11px] font-normal text-slate-500">Buscar y filtrar bienes registrados.</span>
                        </span>
                    </button>

                    <button type="button" class="flex items-center gap-3 rounded-xl bg-slate-50 px-3.5 py-3 text-left text-xs font-medium text-slate-800 border border-slate-200 hover:border-fuchsia-400 hover:bg-fuchsia-50/60 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-fuchsia-100 text-fuchsia-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M3 9.75h18M3 15h18M3 19.5h18" />
                            </svg>
                        </span>
                        <span>
                            Generar reporte
                            <span class="block text-[11px] font-normal text-slate-500">Exportar información resumida.</span>
                        </span>
                    </button>
                </div>
            </div>

            {{-- Actividad del sistema --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm text-[11px] text-slate-500 space-y-2">
                <p class="font-semibold text-slate-800 text-xs">Actividad del sistema</p>
                <p class="text-slate-500">Aquí se mostrarán próximamente las últimas acciones realizadas en el sistema de inventario.</p>
            </div>
        </div>
    </div>
@endsection
