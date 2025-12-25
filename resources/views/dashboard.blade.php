@extends('layouts.dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Panel de resumen rápido --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-800/80 bg-slate-900/60 shadow-xl p-5 sm:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight">Bienvenido al panel de inventario</h2>
                        <p class="text-xs text-slate-400 mt-1 max-w-xl">
                            Desde aquí podrás controlar los bienes institucionales, registrar movimientos, consultar historiales y generar reportes.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tarjetas de estado del sistema --}}
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/70 p-4 shadow-lg">
                    <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-t from-blue-600/40 to-indigo-600/10 opacity-60 pointer-events-none"></div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Bienes registrados</p>
                    <p class="mt-2 text-2xl font-bold text-slate-50">—</p>
                    <p class="mt-1 text-[11px] text-slate-400">Total de bienes actualmente en el sistema.</p>
                </div>

                <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/70 p-4 shadow-lg">
                    <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-t from-emerald-500/40 to-teal-500/10 opacity-60 pointer-events-none"></div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Movimientos recientes</p>
                    <p class="mt-2 text-2xl font-bold text-slate-50">—</p>
                    <p class="mt-1 text-[11px] text-slate-400">Altas, bajas y transferencias recientes.</p>
                </div>

                <div class="relative overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/70 p-4 shadow-lg sm:col-span-2 xl:col-span-1">
                    <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-t from-amber-500/40 to-orange-500/10 opacity-60 pointer-events-none"></div>
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wide">Alertas</p>
                    <p class="mt-2 text-2xl font-bold text-slate-50">0</p>
                    <p class="mt-1 text-[11px] text-slate-400">Aquí aparecerán advertencias importantes del sistema.</p>
                </div>
            </div>

            {{-- Accesos rápidos --}}
            <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5 shadow-xl">
                <p class="text-xs font-medium text-slate-300 mb-3">Accesos rápidos</p>
                <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3">
                    <button type="button" class="flex items-center gap-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-3.5 py-3 text-left text-xs font-medium text-white shadow-lg hover:from-blue-500 hover:to-indigo-500 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-950/30">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5l8.25-4.5 8.25 4.5M3 7.5l8.25 4.5M3 7.5v9l8.25 4.5M19.5 7.5l-8.25 4.5M19.5 7.5v9l-8.25 4.5" />
                            </svg>
                        </span>
                        <span>
                            Registrar nuevo bien
                            <span class="block text-[11px] font-normal text-blue-100/90">Crear un registro en inventario.</span>
                        </span>
                    </button>

                    <button type="button" class="flex items-center gap-3 rounded-xl bg-slate-900/80 px-3.5 py-3 text-left text-xs font-medium text-slate-100 border border-slate-800 hover:border-blue-500/60 hover:bg-slate-900 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-800/80">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 4.5h19.5M2.25 9.75h19.5M9 15h12.75M9 19.5h12.75M4.5 15h.008v.008H4.5V15zm0 4.5h.008v.008H4.5v-.008z" />
                            </svg>
                        </span>
                        <span>
                            Consultar inventario
                            <span class="block text-[11px] font-normal text-slate-400">Buscar y filtrar bienes registrados.</span>
                        </span>
                    </button>

                    <button type="button" class="flex items-center gap-3 rounded-xl bg-slate-900/80 px-3.5 py-3 text-left text-xs font-medium text-slate-100 border border-slate-800 hover:border-indigo-500/60 hover:bg-slate-900 transition">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-800/80">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M3 9.75h18M3 15h18M3 19.5h18" />
                            </svg>
                        </span>
                        <span>
                            Generar reporte
                            <span class="block text-[11px] font-normal text-slate-400">Exportar información resumida.</span>
                        </span>
                    </button>
                </div>
            </div>

            {{-- Actividad del sistema --}}
            <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5 shadow-xl text-[11px] text-slate-300 space-y-2">
                <p class="font-semibold text-slate-100 text-xs">Actividad del sistema</p>
                <p class="text-slate-400">Aquí se mostrarán próximamente las últimas acciones realizadas en el sistema de inventario.</p>
            </div>
        </div>
    </div>
@endsection
