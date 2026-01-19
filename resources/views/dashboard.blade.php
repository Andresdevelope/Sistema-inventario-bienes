@extends('layouts.dashboard')

@section('content')
    <div class="space-y-8">
        {{-- Encabezado --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-slate-900">Panel de control</h1>
                    <p class="text-xs text-slate-500 mt-1 max-w-2xl">Gestiona los tres módulos principales del sistema: Bienes, Usuarios y Bitácora. Accede rápido y mantén el control, todo en azul y blanco.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('bienes.index') }}" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow hover:bg-blue-500">Ver inventario</a>
                    <a href="{{ route('bienes.create') }}" class="inline-flex items-center rounded-md bg-slate-900 px-3 py-2 text-xs font-semibold text-white shadow hover:bg-slate-800">Registrar bien</a>
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
