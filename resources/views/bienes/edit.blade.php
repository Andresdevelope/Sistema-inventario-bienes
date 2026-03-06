@extends('layouts.dashboard')

@section('content')
    <div class="w-full max-w-4xl mx-auto">
        <div class="bg-white/90 backdrop-blur border border-slate-200 shadow-2xl rounded-2xl p-6 md:p-8 text-slate-900">
            <div class="mb-6">
                <h1 class="text-3xl font-bold tracking-tight mb-1">Editar bien</h1>
                <p class="text-xs text-slate-600">Modifica los datos del bien seleccionado y guarda los cambios.</p>
                <a href="{{ route('bienes.categorias.index') }}" class="mt-3 inline-flex items-center gap-2 rounded-2xl border border-brand-400/50 bg-brand-50/80 px-3 py-1.5 text-[11px] font-semibold text-brand-700 shadow-inner shadow-brand-200/60 transition duration-300 hover:-translate-y-0.5 hover:bg-brand-100/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                    Gestionar categorías
                </a>
            </div>

            @if ($errors->any())
                <div class="mb-4 text-xs text-red-700 border border-red-200 bg-red-50 rounded px-3 py-2" role="alert" aria-live="assertive">
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
                            minlength="3" maxlength="40"
                            pattern="^(?=.{3,40}$)(?=(?:.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ]){3,})[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,;:()\-\/]+$"
                            aria-describedby="nombre-help"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                        <p id="nombre-help" class="text-[11px] text-slate-500">Usa un nombre claro y descriptivo (máx. 40 caracteres).</p>
                    </div>
                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="codigo">Código de bien</label>
                        <input id="codigo" name="codigo" type="text" value="{{ old('codigo', $bien->codigo) }}" required
                            minlength="3" maxlength="20"
                            pattern="^(?=.{3,20}$)(?=.*\d)(?!.*[-\/]{2})[A-Za-z0-9]+(?:[-\/][A-Za-z0-9]+)*$"
                            aria-describedby="codigo-help"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                        <p id="codigo-help" class="text-[11px] text-slate-500">Formato sugerido: BIEN-001 o INV/2026/01.</p>
                    </div>

                    <div class="space-y-1 md:col-span-2">
                        <label class="block text-xs font-medium text-slate-700" for="descripcion">Descripción</label>
                        <input id="descripcion" name="descripcion" type="text" value="{{ old('descripcion', $bien->descripcion) }}" required
                            minlength="5" maxlength="70"
                            pattern="^(?=.{5,70}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,;:()\-\/]+$"
                            aria-describedby="descripcion-help"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                        <p id="descripcion-help" class="text-[11px] text-slate-500">Ej.: Equipo de cómputo, modelo 5510, 16GB RAM, SSD 512GB (máx. 70 caracteres).</p>
                    </div>

                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="categoria">Categoría</label>
                        <select id="categoria" name="categoria" required aria-describedby="categoria-help"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                            <option value="">Seleccione una categoría</option>
                            @foreach(($categoriasActivas ?? collect()) as $categoria)
                                <option value="{{ $categoria }}" {{ old('categoria', $bien->categoria) === $categoria ? 'selected' : '' }}>{{ $categoria }}</option>
                            @endforeach
                        </select>
                        <p id="categoria-help" class="text-[11px] text-slate-500">Selecciona una categoría del catálogo activo.</p>
                    </div>

                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="ubicacion">Ubicación</label>
                        <input id="ubicacion" name="ubicacion" type="text" value="{{ old('ubicacion', $bien->ubicacion) }}"
                            minlength="3" maxlength="50"
                            pattern="^(?=.{3,50}$)(?=.*[A-Za-zÁÉÍÓÚÜÑáéíóúüñ])[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9 .,\-#°]+$"
                            aria-describedby="ubicacion-help"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                        <p id="ubicacion-help" class="text-[11px] text-slate-500">Opcional. Ejemplos: Oficina 1, Depósito A, Laboratorio 2.</p>
                    </div>

                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="estado">Estado</label>
                        <select id="estado" name="estado"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                            <option value="bueno" {{ old('estado', $bien->estado) === 'bueno' ? 'selected' : '' }}>Bueno</option>
                            <option value="regular" {{ old('estado', $bien->estado) === 'regular' ? 'selected' : '' }}>Regular</option>
                            <option value="malo" {{ old('estado', $bien->estado) === 'malo' ? 'selected' : '' }}>Malo</option>
                                <option value="de_baja" {{ old('estado', $bien->estado) == 'de_baja' ? 'selected' : '' }}>Dado de baja</option>
                        </select>
                    </div>

                    <div class="space-y-1 md:col-span-1">
                        <label class="block text-xs font-medium text-slate-700" for="fecha_adquisicion">Fecha de adquisición</label>
                        <input id="fecha_adquisicion" name="fecha_adquisicion" type="date" value="{{ old('fecha_adquisicion', optional($bien->fecha_adquisicion)->format('Y-m-d')) }}"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                    </div>
                </div>

                <div class="pt-2 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <a href="{{ route('bienes.index') }}" class="w-full md:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2 text-[12px] font-semibold text-white shadow-lg shadow-brand-900/30 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                        Volver al listado
                    </a>
                    <button type="submit"
                        class="w-full md:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-accent-400 to-accent-500 px-4 py-2 text-[12px] font-semibold text-brand-900 shadow-lg shadow-accent-900/20 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-200 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
