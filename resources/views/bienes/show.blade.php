@extends('layouts.dashboard')

@section('content')
    <div class="w-full max-w-4xl mx-auto">
        <div class="bg-white/90 backdrop-blur border border-slate-200 shadow-2xl rounded-2xl p-6 md:p-8 text-slate-900">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-1">Detalle del bien</h1>
                    <p class="text-xs text-slate-600">Información registrada para el bien seleccionado.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('bienes.edit', $bien) }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-accent-400 to-accent-500 px-4 py-2 text-[12px] font-semibold text-brand-900 shadow-lg shadow-accent-900/20 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-200 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l2.651 2.651M5.25 15.75l9.513-9.513a1.5 1.5 0 012.121 0l1.379 1.379a1.5 1.5 0 010 2.121L8.75 19.25H5.25v-3.5z" />
                        </svg>
                        Editar
                    </a>
                    <a href="{{ route('bienes.index') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2 text-[12px] font-semibold text-white shadow-lg shadow-brand-900/30 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Volver
                    </a>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-1">
                    <p class="text-[11px] font-medium text-slate-700">Nombre</p>
                    <p class="text-sm">{{ $bien->nombre }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[11px] font-medium text-slate-700">Código</p>
                    <p class="text-sm">{{ $bien->codigo }}</p>
                </div>

                <div class="space-y-1 md:col-span-2">
                    <p class="text-[11px] font-medium text-slate-700">Descripción</p>
                    <p class="text-sm">{{ $bien->descripcion }}</p>
                </div>

                <div class="space-y-1">
                    <p class="text-[11px] font-medium text-slate-700">Categoría</p>
                    <p class="text-sm">{{ $bien->categoria ?? '—' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[11px] font-medium text-slate-700">Ubicación</p>
                    <p class="text-sm">{{ $bien->ubicacion_nombre ?? '—' }}</p>
                </div>

                <div class="space-y-1">
                    <p class="text-[11px] font-medium text-slate-700">Estado</p>
                    <p>
                        @php($estado = strtolower(trim((string) $bien->estado)))
                        @php($estadoLabel = $estado === 'de_baja' ? 'Dado de baja' : ucfirst($estado))
                        <span @class([
                            'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold',
                            'border-emerald-400/60 text-emerald-700 bg-emerald-50' => $estado === 'bueno',
                            'border-amber-400/60 text-amber-700 bg-amber-50' => $estado === 'regular',
                            'border-red-400/60 text-red-700 bg-red-50' => $estado === 'malo',
                            'border-slate-400/70 text-slate-700 bg-slate-200/70' => $estado === 'de_baja',
                            'border-slate-300 text-slate-700 bg-slate-100' => !in_array($estado, ['bueno', 'regular', 'malo', 'de_baja']),
                        ])>
                            {{ $estadoLabel }}
                        </span>
                    </p>
                </div>
                <div class="space-y-1">
                    <p class="text-[11px] font-medium text-slate-700">Fecha de adquisición</p>
                    <p class="text-sm">{{ optional($bien->fecha_adquisicion)->format('d/m/Y') ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
