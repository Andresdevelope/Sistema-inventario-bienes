@extends('layouts.dashboard')

@section('content')
    <div class="space-y-5 w-full">
        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1.5 mb-1">
            <div>
                <h1 class="text-xl font-semibold tracking-tight">Inventario de bienes</h1>
                <p class="text-sm text-slate-400">Listado básico de bienes registrados en el sistema.</p>
            </div>
            <a href="{{ route('bienes.create') }}" class="inline-flex items-center rounded-md bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2 text-xs font-semibold text-white shadow hover:from-blue-500 hover:to-indigo-500 cursor-pointer">
                Registrar nuevo bien
            </a>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-900/70 shadow-xl">
            <table class="w-full table-auto text-sm">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400">
                    <tr>
                        <th class="px-4 py-2.5 text-left font-medium">Nombre</th>
                        <th class="px-4 py-2.5 text-left font-medium">Código</th>
                        <th class="px-4 py-2.5 text-left font-medium">Descripción</th>
                        <th class="px-4 py-2.5 text-left font-medium hidden md:table-cell">Categoría</th>
                        <th class="px-4 py-2.5 text-left font-medium hidden md:table-cell">Ubicación</th>
                        <th class="px-4 py-2.5 text-left font-medium">Estado</th>
                        <th class="px-4 py-2.5 text-right font-medium hidden lg:table-cell">Valor</th>
                        <th class="px-4 py-2.5 text-right font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse ($bienes as $bien)
                        <tr 
                            class="hover:bg-slate-900/80">
                            <td class="px-4 py-2.5 align-middle text-slate-100">{{ $bien->nombre }}</td
                            class="hover:bg-slate-900/80">
                            <td class="px-4 py-2.5 align-middle text-slate-100">{{ $bien->codigo }}</td>
                            <td class="px-4 py-2.5 align-middle text-slate-300">{{ $bien->descripcion }}</td>
                            <td class="px-4 py-2.5 align-middle text-slate-400 hidden md:table-cell">{{ $bien->categoria ?? '—' }}</td>
                            <td class="px-4 py-2.5 align-middle text-slate-400 hidden md:table-cell">{{ $bien->ubicacion ?? '—' }}</td>
                            <td class="px-4 py-2.5 align-middle">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs
                                    @class([
                                        'border-emerald-400/60 text-emerald-200 bg-emerald-500/10' => $bien->estado === 'bueno',
                                        'border-amber-400/60 text-amber-200 bg-amber-500/10' => $bien->estado === 'regular',
                                        'border-red-400/60 text-red-200 bg-red-500/10' => $bien->estado === 'malo',
                                    ])">
                                    {{ ucfirst($bien->estado) }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 align-middle text-right text-slate-300 hidden lg:table-cell">
                                @if (!is_null($bien->valor))
                                    {{ number_format($bien->valor, 2, ',', '.') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-2.5 align-middle text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('bienes.show', $bien) }}" class="inline-flex items-center rounded-md bg-slate-800 px-3 py-1.5 text-xs text-slate-100 hover:bg-slate-700 cursor-pointer">Ver</a>
                                    <a href="{{ route('bienes.edit', $bien) }}" class="inline-flex items-center rounded-md bg-slate-800 px-3 py-1.5 text-xs text-slate-100 hover:bg-slate-700 cursor-pointer">Editar</a>
                                    <form method="POST" action="{{ route('bienes.destroy', $bien) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este bien?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-md border border-red-500/60 bg-red-500/10 px-3 py-1.5 text-xs text-red-200 hover:bg-red-500/20 cursor-pointer">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-slate-500 text-sm">No hay bienes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $bienes->links() }}
        </div>
    </div>
@endsection
