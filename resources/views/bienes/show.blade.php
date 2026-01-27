@extends('layouts.dashboard')

@section('content')
    <div class="w-full max-w-4xl mx-auto">
        <div class="bg-white/90 backdrop-blur border border-slate-200 shadow-2xl rounded-2xl p-6 md:p-8 text-slate-900">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight mb-1">Detalle del bien</h1>
                    <p class="text-xs text-slate-600">Información registrada para el bien seleccionado.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('bienes.edit', $bien) }}" class="inline-flex items-center gap-1.5 rounded-md bg-indigo-600 px-3 py-1.5 text-xs text-white hover:bg-indigo-500 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l2.651 2.651M5.25 15.75l9.513-9.513a1.5 1.5 0 012.121 0l1.379 1.379a1.5 1.5 0 010 2.121L8.75 19.25H5.25v-3.5z" />
                        </svg>
                        Editar
                    </a>
                    <a href="{{ route('bienes.index') }}" class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-700 hover:bg-slate-100 cursor-pointer">Volver</a>
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
                    <p class="text-sm">{{ $bien->ubicacion ?? '—' }}</p>
                </div>

                <div class="space-y-1">
                    <p class="text-[11px] font-medium text-slate-700">Estado</p>
                    <p>
                        <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs
                            @class([
                                'border-emerald-400/60 text-emerald-700 bg-emerald-50' => $bien->estado === 'bueno',
                                'border-amber-400/60 text-amber-700 bg-amber-50' => $bien->estado === 'regular',
                                'border-red-400/60 text-red-700 bg-red-50' => $bien->estado === 'malo',
                            ])">
                            {{ ucfirst($bien->estado) }}
                        </span>
                    </p>
                </div>
                <div class="space-y-1">
                    <p class="text-[11px] font-medium text-slate-700">Fecha de adquisición</p>
                    <p class="text-sm">{{ optional($bien->fecha_adquisicion)->format('d/m/Y') ?? '—' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[11px] font-medium text-slate-700">Valor estimado</p>
                    <p class="text-sm">
                        @if (!is_null($bien->valor))
                            {{ number_format($bien->valor, 2, ',', '.') }}
                        @else
                            —
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
