@extends('layouts.dashboard')

@section('content')
    <div class="space-y-8">
        {{-- Encabezado --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-slate-900">Panel de control</h1>
                    <p class="text-xs text-slate-500 mt-1 max-w-2xl">Gestiona los tres módulos principales del sistema: Bienes, Usuarios y Bitácora. Accede rápido y mantén el control.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('bienes.index') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2 text-[12px] font-semibold text-white shadow-lg shadow-brand-900/30 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.25l9 6 9-6M4.5 6h15a1.5 1.5 0 011.5 1.5v9a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 16.5v-9A1.5 1.5 0 014.5 6z" />
                        </svg>
                        Ver inventario
                        <span class="inline-flex items-center rounded-full bg-white/90 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-brand-700">
                            SIGB
                        </span>
                    </a>
                    <a href="{{ route('bienes.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-accent-400 to-accent-500 px-4 py-2 text-[12px] font-semibold text-brand-900 shadow-lg shadow-accent-900/20 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                        Registrar bien
                        <span class="inline-flex items-center rounded-full border border-brand-900/20 bg-brand-900/5 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-brand-900">
                            +1
                        </span>
                    </a>
                </div>
            </div>
        </div>

        {{-- KPIs del sistema --}}
        @include('partials.kpis', ['counts' => $counts ?? []])

        {{-- Actividad reciente --}}
        @include('partials.recent-activity', [
            'ultimosBienes' => $ultimosBienes ?? collect(),
            'ultimosEventos' => $ultimosEventos ?? collect(),
        ])

    </div>
@endsection
