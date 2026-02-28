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
                <a href="{{ route('bienes.create') }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-accent-400 to-accent-500 px-3.5 py-1.5 text-[11px] font-semibold text-brand-900 shadow-lg shadow-accent-900/15 transition hover:-translate-y-0.5 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                    Registrar bien
                    <span class="inline-flex items-center rounded-full border border-brand-900/10 bg-brand-50/80 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide text-brand-900">Nuevo</span>
                </a>
            </div>
            <ul class="relative">
                {{-- Línea de tiempo --}}
                <span class="absolute left-2 top-0 bottom-0 w-px bg-slate-200"></span>
                @forelse($ultimosBienes as $bien)
                    <li class="pl-6 py-2">
                        <span class="absolute left-0 mt-1.5 h-3 w-3 rounded-full bg-blue-600 ring-2 ring-blue-200"></span>
                        <p class="text-xs font-medium text-slate-900">{{ $bien->nombre }} <span class="text-[11px] text-slate-500">({{ $bien->codigo }})</span></p>
                        <p class="text-[11px] text-slate-500">{{ $bien->categoria }} • Estado: <span class="font-medium text-slate-700">{{ $bien->estado }}</span></p>
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
