@extends('layouts.dashboard')

@section('content')
    <div class="w-full max-w-4xl mx-auto">
        <div class="bg-white/90 backdrop-blur border border-slate-200 shadow-2xl rounded-2xl p-6 md:p-8 text-slate-900">
            <div class="mb-6">
                <h1 class="text-3xl font-bold tracking-tight mb-1">Editar bien</h1>
                <p class="text-xs text-slate-600">Modifica los datos del bien seleccionado y guarda los cambios.</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 text-xs text-red-700 border border-red-200 bg-red-50 rounded px-3 py-2">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('bienes.update', $bien) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="nombre">Nombre del bien</label>
                        <input id="nombre" name="nombre" type="text" value="{{ old('nombre', $bien->nombre) }}" required
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="codigo">Código de bien</label>
                        <input id="codigo" name="codigo" type="text" value="{{ old('codigo', $bien->codigo) }}" required
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>

                    <div class="space-y-1 md:col-span-2">
                        <label class="block text-xs font-medium text-slate-700" for="descripcion">Descripción</label>
                        <input id="descripcion" name="descripcion" type="text" value="{{ old('descripcion', $bien->descripcion) }}" required
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                        <p class="text-[11px] text-slate-500">Ej.: Equipo de cómputo, modelo 5510, 16GB RAM, SSD 512GB.</p>
                    </div>

                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="categoria">Categoría</label>
                        <input id="categoria" name="categoria" type="text" value="{{ old('categoria', $bien->categoria) }}"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600"
                            placeholder="Ej: Computadoras, Mobiliario">
                    </div>

                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="ubicacion">Ubicación</label>
                        <input id="ubicacion" name="ubicacion" type="text" value="{{ old('ubicacion', $bien->ubicacion) }}"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>

                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="estado">Estado</label>
                        <select id="estado" name="estado"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                            <option value="bueno" {{ old('estado', $bien->estado) === 'bueno' ? 'selected' : '' }}>Bueno</option>
                            <option value="regular" {{ old('estado', $bien->estado) === 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="malo" {{ old('estado', $bien->estado) === 'malo' ? 'selected' : '' }}>Malo</option>
                        </select>
                    </div>

                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="fecha_adquisicion">Fecha de adquisición</label>
                        <input id="fecha_adquisicion" name="fecha_adquisicion" type="date" value="{{ old('fecha_adquisicion', optional($bien->fecha_adquisicion)->format('Y-m-d')) }}"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>

                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="valor">Valor estimado</label>
                        <input id="valor" name="valor" type="number" step="0.01" min="0" value="{{ old('valor', $bien->valor) }}"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600"
                            placeholder="Ej: 1500.00">
                    </div>
                </div>

                <div class="pt-2 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <a href="{{ route('bienes.index') }}" class="text-xs text-slate-700 hover:text-slate-900 underline underline-offset-2">Volver al listado</a>
                    <button type="submit"
                        class="w-full md:w-auto inline-flex items-center justify-center rounded-md bg-slate-900 px-6 py-2 text-sm font-semibold text-white shadow hover:bg-slate-800 transition">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
