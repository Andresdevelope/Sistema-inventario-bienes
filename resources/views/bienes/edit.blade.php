@extends('layouts.dashboard')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-xl">
            <h1 class="text-lg font-semibold tracking-tight mb-1">Editar bien</h1>
            <p class="text-[11px] text-slate-400 mb-4">Modifica los datos del bien seleccionado.</p>

            @if ($errors->any())
                <div class="mb-4 text-xs text-red-300 border border-red-500/40 bg-red-900/30 rounded px-3 py-2">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('bienes.update', $bien) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="nombre">Nombre</label>
                        <input id="nombre" name="nombre" type="text" value="{{ old('nombre', $bien->nombre) }}" required
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="codigo">Código de bien</label>
                        <input id="codigo" name="codigo" type="text" value="{{ old('codigo', $bien->codigo) }}" required
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-slate-300" for="descripcion">Descripción</label>
                    <input id="descripcion" name="descripcion" type="text" value="{{ old('descripcion', $bien->descripcion) }}" required
                        class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="categoria">Categoría (opcional)</label>
                        <input id="categoria" name="categoria" type="text" value="{{ old('categoria', $bien->categoria) }}"
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="ubicacion">Ubicación (opcional)</label>
                        <input id="ubicacion" name="ubicacion" type="text" value="{{ old('ubicacion', $bien->ubicacion) }}"
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="estado">Estado</label>
                        <select id="estado" name="estado" class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="bueno" {{ old('estado', $bien->estado) === 'bueno' ? 'selected' : '' }}>Bueno</option>
                            <option value="regular" {{ old('estado', $bien->estado) === 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="malo" {{ old('estado', $bien->estado) === 'malo' ? 'selected' : '' }}>Malo</option>
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="fecha_adquisicion">Fecha de adquisición</label>
                        <input id="fecha_adquisicion" name="fecha_adquisicion" type="date" value="{{ old('fecha_adquisicion', optional($bien->fecha_adquisicion)->format('Y-m-d')) }}"
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-slate-300" for="valor">Valor estimado (opcional)</label>
                    <input id="valor" name="valor" type="number" step="0.01" min="0" value="{{ old('valor', $bien->valor) }}"
                        class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('bienes.index') }}" class="text-[11px] text-slate-400 hover:text-slate-200 underline underline-offset-2 cursor-pointer">Volver al listado</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow hover:from-blue-500 hover:to-indigo-500 cursor-pointer">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
