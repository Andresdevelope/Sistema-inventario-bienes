@extends('layouts.dashboard')

@section('content')
    <div class="space-y-4 w-full">
        {{-- Filtros de búsqueda (diseño compacto) --}}
        <form method="GET" action="{{ route('bitacora.index') }}" class="rounded-xl border border-slate-200 bg-white p-3 text-xs">
            <div class="flex flex-wrap items-end gap-2">
                <div class="flex flex-col">
                    <label class="text-[11px] font-medium text-slate-700">Buscar</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Descripción, módulo, acción..."
                           class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600 md:w-64">
                </div>
                <div class="flex flex-col">
                    <label class="text-[11px] font-medium text-slate-700">Módulo</label>
                    <select name="modulo" class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600 md:w-40">
                        <option value="">Todos</option>
                        @isset($modulos)
                            @foreach ($modulos as $mod)
                                <option value="{{ $mod }}" {{ request('modulo') === $mod ? 'selected' : '' }}>{{ $mod }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-[11px] font-medium text-slate-700">Acción</label>
                    <select name="accion" class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600 md:w-40">
                        <option value="">Todas</option>
                        @isset($acciones)
                            @foreach ($acciones as $acc)
                                <option value="{{ $acc }}" {{ request('accion') === $acc ? 'selected' : '' }}>{{ $acc }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-[11px] font-medium text-slate-700">Resultado</label>
                    <select name="resultado" class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600 md:w-36">
                        <option value="">Todos</option>
                        @isset($resultados)
                            @foreach ($resultados as $res)
                                <option value="{{ $res }}" {{ request('resultado') === $res ? 'selected' : '' }}>{{ ucfirst($res) }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <div class="flex items-center gap-2 ml-auto">
                    <button type="button" id="toggle-advanced" class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs text-slate-700 hover:bg-slate-50">Avanzado</button>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white shadow hover:bg-slate-800">Filtrar</button>
                    <a href="{{ route('bitacora.index') }}" class="text-slate-700 hover:text-slate-900 underline underline-offset-2">Limpiar</a>
                </div>
            </div>

            <div id="advanced-filters" class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-2 {{ (request('desde') || request('hasta') || request('user_id')) ? '' : 'hidden' }}">
                <div class="flex flex-col">
                    <label class="text-[11px] font-medium text-slate-700">Usuario</label>
                    <select name="user_id" class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                        <option value="">Todos</option>
                        @isset($usuarios)
                            @foreach ($usuarios as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-[11px] font-medium text-slate-700">Desde</label>
                    <input type="date" name="desde" value="{{ request('desde') }}"
                           class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                </div>
                <div class="flex flex-col">
                    <label class="text-[11px] font-medium text-slate-700">Hasta</label>
                    <input type="date" name="hasta" value="{{ request('hasta') }}"
                           class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-600 focus:border-slate-600">
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const btn = document.getElementById('toggle-advanced');
                    const adv = document.getElementById('advanced-filters');
                    if (!btn || !adv) return;
                    btn.addEventListener('click', function() {
                        adv.classList.toggle('hidden');
                    });
                });
            </script>
        </form>

        @php
            $hasFilters = request()->filled('q') || request()->filled('modulo') || request()->filled('accion') || request()->filled('resultado') || request()->filled('user_id') || request()->filled('desde') || request()->filled('hasta');
        @endphp
        @if($hasFilters)
            <div class="flex flex-wrap items-center gap-2 text-[11px]">
                <span class="text-slate-600">Filtros activos:</span>
                @if(request('q'))
                    <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-slate-700">Texto: "{{ request('q') }}"</span>
                @endif
                @if(request('modulo'))
                    <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-slate-700">Módulo: {{ request('modulo') }}</span>
                @endif
                @if(request('accion'))
                    <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-slate-700">Acción: {{ request('accion') }}</span>
                @endif
                @if(request('resultado'))
                    <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-slate-700">Resultado: {{ request('resultado') }}</span>
                @endif
                @if(request('user_id'))
                    @php($uSel = isset($usuarios) ? $usuarios->firstWhere('id', (int) request('user_id')) : null)
                    <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-slate-700">Usuario: {{ $uSel->name ?? request('user_id') }}</span>
                @endif
                @if(request('desde'))
                    <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-slate-700">Desde: {{ request('desde') }}</span>
                @endif
                @if(request('hasta'))
                    <span class="inline-flex items-center rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-slate-700">Hasta: {{ request('hasta') }}</span>
                @endif
            </div>
        @endif
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
