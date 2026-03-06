{{-- Parcial: Actividad reciente del dashboard --}}
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <p class="text-xs font-medium text-slate-600">Actividad reciente</p>
       
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        {{-- Últimos bienes registrados --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold text-slate-900">Últimos bienes</h3>
             
            </div>
            <ul class="relative">
                {{-- Línea de tiempo --}}
                <span class="absolute left-2 top-0 bottom-0 w-px bg-slate-200"></span>
                @forelse($ultimosBienes as $bien)
                    <li class="pl-6 py-2">
                        <span class="absolute left-0 mt-1.5 h-3 w-3 rounded-full bg-blue-600 ring-2 ring-blue-200"></span>
                        @php($estado = strtolower(trim((string) $bien->estado)))
                        @php($estadoLabel = $estado === 'de_baja' ? 'Dado de baja' : ucfirst($estado))
                        <p class="text-xs font-medium text-slate-900">{{ $bien->nombre }} <span class="text-[11px] text-slate-500">({{ $bien->codigo }})</span></p>
                        <p class="text-[11px] text-slate-500">
                            {{ $bien->categoria }} • Estado:
                            <span @class([
                                'inline-flex items-center rounded-full border px-2 py-0.5 text-[10px] font-semibold',
                                'border-emerald-400/60 text-emerald-700 bg-emerald-50' => $estado === 'bueno',
                                'border-amber-400/60 text-amber-700 bg-amber-50' => $estado === 'regular',
                                'border-red-400/60 text-red-700 bg-red-50' => $estado === 'malo',
                                'border-slate-400/70 text-slate-700 bg-slate-200/70' => $estado === 'de_baja',
                                'border-slate-300 text-slate-700 bg-slate-100' => !in_array($estado, ['bueno', 'regular', 'malo', 'de_baja']),
                            ])>
                                {{ $estadoLabel }}
                            </span>
                        </p>
                        <p class="text-[10px] text-slate-400">{{ optional($bien->created_at)->diffForHumans() }}</p>
                    </li>
                @empty
                    <li class="py-2 text-[11px] text-slate-500">Aún no hay bienes registrados.</li>
                @endforelse
            </ul>
        </div>

        {{-- Bitácora: últimos movimientos --}}
        <div>
            <h3 class="text-sm font-semibold text-slate-900 mb-2">Últimos movimientos</h3>
            <ul class="relative">
                {{-- Línea de tiempo --}}
                <span class="absolute left-2 top-0 bottom-0 w-px bg-slate-200"></span>
                @forelse($ultimosEventos as $ev)
                    <li class="pl-6 py-2">
                        <span class="absolute left-0 mt-1.5 h-3 w-3 rounded-full bg-slate-400 ring-2 ring-slate-200"></span>
                        <p class="text-xs font-medium text-slate-900">{{ ucfirst($ev->accion) }} en {{ $ev->modulo }}</p>
                        <p class="text-[11px] text-slate-500">Por {{ $ev->user->name ?? 'Sistema' }} • Resultado: <span class="font-medium text-slate-700">{{ $ev->resultado }}</span></p>
                        <p class="text-[10px] text-slate-400">{{ optional($ev->created_at)->diffForHumans() }}</p>
                    </li>
                @empty
                    <li class="py-2 text-[11px] text-slate-500">Sin movimientos recientes.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
