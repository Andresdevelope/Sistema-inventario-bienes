<div class="space-y-3">
    <div class="md:hidden space-y-2">
        @forelse ($bienes as $bien)
            @php($estado = strtolower(trim((string) $bien->estado)))
            @php($estadoLabel = $estado === 'de_baja' ? 'Dado de baja' : ucfirst($estado))
            <article class="rounded-2xl border border-slate-800 bg-slate-900/70 p-3 shadow" data-bien-nombre="{{ $bien->nombre }}">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-100 truncate" title="{{ $bien->nombre }}">{{ \Illuminate\Support\Str::limit($bien->nombre, 24, '...') }}</p>
                        <p class="text-[11px] text-slate-400 truncate" title="{{ $bien->codigo }}">Código: {{ \Illuminate\Support\Str::limit($bien->codigo, 18, '...') }}</p>
                    </div>
                    <span @class([
                        'inline-flex items-center rounded-full border px-2 py-0.5 text-[11px] font-semibold whitespace-nowrap',
                        'border-green-400/70 text-green-200 bg-green-500/20' => $estado === 'bueno',
                        'border-yellow-400/70 text-yellow-200 bg-yellow-500/20' => $estado === 'regular',
                        'border-red-400/70 text-red-200 bg-red-500/20' => $estado === 'malo',
                        'border-slate-300/70 text-slate-100 bg-slate-500/20' => $estado === 'de_baja',
                        'border-slate-500/50 text-slate-200 bg-slate-500/10' => !in_array($estado, ['bueno', 'regular', 'malo', 'de_baja']),
                    ])>{{ $estadoLabel }}</span>
                </div>

                <p class="mt-2 text-[12px] text-slate-300 truncate" title="{{ $bien->descripcion }}">
                    {{ \Illuminate\Support\Str::limit($bien->descripcion, 48, '...') }}
                </p>
                <p class="mt-1 text-[11px] text-slate-400 truncate" title="{{ $bien->categoria ?? '—' }}">Categoría: {{ \Illuminate\Support\Str::limit($bien->categoria ?? '—', 20, '...') }}</p>
                <p class="text-[11px] text-slate-400 truncate" title="{{ $bien->ubicacion ?? '—' }}">Ubicación: {{ \Illuminate\Support\Str::limit($bien->ubicacion ?? '—', 20, '...') }}</p>

                <div class="mt-3 inline-flex items-center gap-1.5 w-full justify-end">
                    <a href="{{ route('bienes.show', $bien) }}" class="inline-flex items-center gap-1 rounded-2xl border border-brand-400/50 bg-brand-50/80 px-2.5 py-1 text-[11px] font-semibold text-brand-700 shadow-inner shadow-brand-200/60">Ver</a>
                    <a href="{{ route('bienes.edit', $bien) }}" class="inline-flex items-center gap-1 rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-2.5 py-1 text-[11px] font-semibold text-white shadow">Editar</a>
                    <form method="POST" action="{{ route('bienes.destroy', $bien) }}" class="inline" data-delete-form>
                        @csrf
                        @method('DELETE')
                        <button type="button" class="inline-flex items-center gap-1 rounded-2xl border border-red-500/50 bg-red-500/10 px-2.5 py-1 text-[11px] font-semibold text-red-200" data-delete-trigger>Eliminar</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-slate-800 bg-slate-900/70 px-4 py-4 text-center text-slate-500 text-sm">No hay bienes registrados.</div>
        @endforelse
    </div>

    <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/70 shadow-xl">
    <table class="w-full table-fixed text-sm">
        <caption class="sr-only">Listado de bienes registrados con nombre, código, descripción, categoría, ubicación, estado y acciones.</caption>
        <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400">
            <tr>
                <th scope="col" class="px-4 py-2.5 text-left font-medium w-[14%]">Nombre</th>
                <th scope="col" class="px-4 py-2.5 text-left font-medium w-[10%]">Código</th>
                <th scope="col" class="px-4 py-2.5 text-left font-medium w-[18%]">Descripción</th>
                <th scope="col" class="px-4 py-2.5 text-left font-medium hidden md:table-cell w-[12%]">Categoría</th>
                <th scope="col" class="px-4 py-2.5 text-left font-medium hidden md:table-cell w-[12%]">Ubicación</th>
                <th scope="col" class="px-4 py-2.5 text-left font-medium w-[10%]">Estado</th>
                <th scope="col" class="px-4 py-2.5 text-right font-medium w-[24%]">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/80">
            @forelse ($bienes as $bien)
                <tr class="hover:bg-slate-900/80" data-bien-nombre="{{ $bien->nombre }}">
                    <td class="px-4 py-2.5 align-middle text-slate-100">
                        <span class="block w-full truncate" title="{{ $bien->nombre }}">{{ \Illuminate\Support\Str::limit($bien->nombre, 18, '...') }}</span>
                    </td>
                    <td class="px-4 py-2.5 align-middle text-slate-100">
                        <span class="block w-full truncate" title="{{ $bien->codigo }}">{{ \Illuminate\Support\Str::limit($bien->codigo, 12, '...') }}</span>
                    </td>
                    <td class="px-4 py-2.5 align-middle text-slate-300">
                        <span class="block w-full truncate" title="{{ $bien->descripcion }}">{{ \Illuminate\Support\Str::limit($bien->descripcion, 24, '...') }}</span>
                    </td>
                    <td class="px-4 py-2.5 align-middle text-slate-400 hidden md:table-cell">
                        <span class="block w-full truncate" title="{{ $bien->categoria ?? '—' }}">{{ \Illuminate\Support\Str::limit($bien->categoria ?? '—', 14, '...') }}</span>
                    </td>
                    <td class="px-4 py-2.5 align-middle text-slate-400 hidden md:table-cell">
                        <span class="block w-full truncate" title="{{ $bien->ubicacion ?? '—' }}">{{ \Illuminate\Support\Str::limit($bien->ubicacion ?? '—', 14, '...') }}</span>
                    </td>
                    <td class="px-4 py-2.5 align-middle">
                        @php($estado = strtolower(trim((string) $bien->estado)))
                        @php($estadoLabel = $estado === 'de_baja' ? 'Dado de baja' : ucfirst($estado))
                        <span @class([
                            'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold',
                            'border-green-400/70 text-green-200 bg-green-500/20' => $estado === 'bueno',
                            'border-yellow-400/70 text-yellow-200 bg-yellow-500/20' => $estado === 'regular',
                            'border-red-400/70 text-red-200 bg-red-500/20' => $estado === 'malo',
                            'border-slate-300/70 text-slate-100 bg-slate-500/20' => $estado === 'de_baja',
                            'border-slate-500/50 text-slate-200 bg-slate-500/10' => !in_array($estado, ['bueno', 'regular', 'malo', 'de_baja']),
                        ])>
                            {{ $estadoLabel }}
                        </span>
                    </td>
                    <td class="px-4 py-2.5 align-middle text-right whitespace-nowrap">
                        <div class="inline-flex items-center justify-end gap-1.5 ml-auto whitespace-nowrap w-full">
                            <a href="{{ route('bienes.show', $bien) }}" class="inline-flex items-center gap-1 rounded-2xl border border-brand-400/50 bg-brand-50/80 px-2.5 py-1 text-[11px] font-semibold text-brand-700 shadow-inner shadow-brand-200/60 transition duration-300 hover:-translate-y-0.5 hover:bg-brand-100/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 cursor-pointer whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Ver
                            </a>
                            <a href="{{ route('bienes.edit', $bien) }}" class="inline-flex items-center gap-1 rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-2.5 py-1 text-[11px] font-semibold text-white shadow-lg shadow-brand-900/20 transition duration-300 hover:-translate-y-0.5 hover:shadow-brand-900/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-300 cursor-pointer whitespace-nowrap">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l2.651 2.651M5.25 15.75l9.513-9.513a1.5 1.5 0 012.121 0l1.379 1.379a1.5 1.5 0 010 2.121L8.75 19.25H5.25v-3.5z" />
                                </svg>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('bienes.destroy', $bien) }}" class="inline" data-delete-form>
                                @csrf
                                @method('DELETE')
                                <button type="button" class="inline-flex items-center gap-1 rounded-2xl border border-red-500/50 bg-red-500/10 px-2.5 py-1 text-[11px] font-semibold text-red-200 shadow-inner shadow-red-900/20 transition duration-300 hover:-translate-y-0.5 hover:bg-red-500/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-300 cursor-pointer whitespace-nowrap" data-delete-trigger>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.245-2.327L4.772 5.79m14.456 0A48.108 48.108 0 0 0 15.75 5.25m3.478.54c-1.156-.175-2.33-.296-3.478-.36m0 0V4.5c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0C9.16 2.336 8.25 3.32 8.25 4.5v.93m3.75 0a48.667 48.667 0 0 0-3.478.36" />
                                    </svg>
                                    Eliminar
                                </button>
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
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between pt-1">
        <p class="text-[11px] text-slate-500">
            @if($bienes->total() > 0)
                Mostrando <span class="font-semibold text-slate-700">{{ $bienes->firstItem() }}</span>
                a <span class="font-semibold text-slate-700">{{ $bienes->lastItem() }}</span>
                de <span class="font-semibold text-slate-700">{{ $bienes->total() }}</span> bienes
            @else
                No hay resultados para los filtros actuales.
            @endif
        </p>
        {{ $bienes->onEachSide(1)->links() }}
    </div>
</div>
