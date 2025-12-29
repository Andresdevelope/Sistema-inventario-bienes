@extends('layouts.dashboard')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-xl">
            <h1 class="text-lg font-semibold tracking-tight mb-1">Detalle del bien</h1>
            <p class="text-[11px] text-slate-400 mb-4">Información básica del bien registrado en el inventario.</p>

            <dl class="space-y-3 text-sm text-slate-200">
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-400 w-32">Nombre</dt>
                    <dd class="flex-1 text-right">{{ $bien->nombre }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-400 w-32">Código</dt>
                    <dd class="flex-1 text-right">{{ $bien->codigo }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-400 w-32">Descripción</dt>
                    <dd class="flex-1 text-right">{{ $bien->descripcion }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-400 w-32">Categoría</dt>
                    <dd class="flex-1 text-right">{{ $bien->categoria ?? '—' }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-400 w-32">Ubicación</dt>
                    <dd class="flex-1 text-right">{{ $bien->ubicacion ?? '—' }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-400 w-32">Estado</dt>
                    <dd class="flex-1 text-right">{{ ucfirst($bien->estado) }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-400 w-32">Fecha adquisición</dt>
                    <dd class="flex-1 text-right">{{ optional($bien->fecha_adquisicion)->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div class="flex justify-between gap-4">
                    <dt class="text-slate-400 w-32">Valor estimado</dt>
                    <dd class="flex-1 text-right">
                        @if (!is_null($bien->valor))
                            {{ number_format($bien->valor, 2, ',', '.') }}
                        @else
                            —
                        @endif
                    </dd>
                </div>
            </dl>

            <div class="mt-6 flex items-center justify-between text-xs">
                <a href="{{ route('bienes.index') }}" class="text-slate-400 hover:text-slate-200 underline underline-offset-2 cursor-pointer">Volver al listado</a>
                <div class="flex items-center gap-2">
                    <a href="{{ route('bienes.edit', $bien) }}" class="inline-flex items-center rounded-md bg-slate-800 px-3 py-1.5 text-xs text-slate-100 hover:bg-slate-700 cursor-pointer">Editar</a>
                    <form method="POST" action="{{ route('bienes.destroy', $bien) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este bien?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center rounded-md border border-red-500/60 bg-red-500/10 px-3 py-1.5 text-xs text-red-200 hover:bg-red-500/20 cursor-pointer">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
