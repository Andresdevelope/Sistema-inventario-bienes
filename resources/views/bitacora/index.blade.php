@extends('layouts.dashboard')

@section('content')
    <div class="space-y-4 w-full">
        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1.5 mb-1">
            <div>
                <h1 class="text-xl font-semibold tracking-tight">Bitácora del sistema</h1>
                <p class="text-sm text-slate-400">Registro de acciones realizadas en el sistema.</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-900/70 shadow-xl">
            <table class="w-full table-auto text-sm">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400">
                    <tr>
                        <th class="px-4 py-2.5 text-left font-medium">Fecha y hora</th>
                        <th class="px-4 py-2.5 text-left font-medium">Usuario</th>
                        <th class="px-4 py-2.5 text-left font-medium">Módulo</th>
                        <th class="px-4 py-2.5 text-left font-medium">Acción</th>
                        <th class="px-4 py-2.5 text-left font-medium">Resultado</th>
                        <th class="px-4 py-2.5 text-left font-medium">Detalle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse ($registros as $registro)
                        <tr class="hover:bg-slate-900/80">
                            <td class="px-4 py-2.5 align-middle text-slate-100">
                                {{ $registro->created_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-2.5 align-middle text-slate-200">
                                {{ $registro->user->name ?? 'Sistema' }}
                            </td>
                            <td class="px-4 py-2.5 align-middle text-slate-300">
                                {{ $registro->modulo }}
                            </td>
                            <td class="px-4 py-2.5 align-middle text-slate-300">
                                {{ $registro->accion }}
                            </td>
                            <td class="px-4 py-2.5 align-middle text-slate-300">
                                {{ $registro->resultado }}
                            </td>
                            <td class="px-4 py-2.5 align-middle text-slate-400 max-w-md">
                                <span class="block truncate" title="{{ $registro->descripcion }}">
                                    {{ $registro->descripcion ?? '—' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-slate-500 text-sm">Aún no hay registros en la bitácora.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $registros->links() }}
        </div>
    </div>
@endsection
