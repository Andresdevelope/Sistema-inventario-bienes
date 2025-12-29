@extends('layouts.dashboard')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-xl">
            <h1 class="text-lg font-semibold tracking-tight mb-1">Registrar nuevo bien</h1>
            <p class="text-[11px] text-slate-400 mb-4">Formulario base para el ingreso de bienes al sistema. Más adelante se podrán ajustar los campos.</p>

            @if ($errors->any())
                <div class="mb-4 text-xs text-red-300 border border-red-500/40 bg-red-900/30 rounded px-3 py-2">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('bienes.store') }}" class="space-y-4">
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="codigo">nombre</label>
                        <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ej: Laptop Dell Inspiron">
                    </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="codigo">Código de bien</label>
                        <input id="codigo" name="codigo" type="text" value="{{ old('codigo') }}" required
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ej: BIEN-001">
                    </div>
                    

                <div class="space-y-1">
                    <label class="block text-xs font-medium text-slate-300" for="descripcion">Descripción</label>
                    <input id="descripcion" name="descripcion" type="text" value="{{ old('descripcion') }}" required
                        class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Descripción breve del bien">
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="ubicacion">Ubicación</label>
                        <input id="ubicacion" name="ubicacion" type="text" value="{{ old('ubicacion') }}"
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ej: Oficina 1, Depósito...">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="estado">Estado</label>
                        <select id="estado" name="estado" class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="bueno" {{ old('estado') === 'bueno' ? 'selected' : '' }}>Bueno</option>
                            <option value="regular" {{ old('estado') === 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="malo" {{ old('estado') === 'malo' ? 'selected' : '' }}>Malo</option>
                        </select>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="fecha_adquisicion">Fecha de adquisición</label>
                        <input id="fecha_adquisicion" name="fecha_adquisicion" type="date" value="{{ old('fecha_adquisicion') }}"
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-medium text-slate-300" for="valor">Valor estimado</label>
                        <input id="valor" name="valor" type="number" step="0.01" min="0" value="{{ old('valor') }}"
                            class="w-full rounded-md border border-slate-700 bg-slate-900/70 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ej: 1500.00">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('bienes.index') }}" class="text-[11px] text-slate-400 hover:text-slate-200 underline underline-offset-2 cursor-pointer">Volver al listado</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow hover:from-blue-500 hover:to-indigo-500 cursor-pointer">
                        Guardar bien
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
