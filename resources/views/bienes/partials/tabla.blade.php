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
                <th class="px-4 py-2.5 text-right font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800/80">
            @forelse ($bienes as $bien)
                <tr class="hover:bg-slate-900/80">
                    <td class="px-4 py-2.5 align-middle text-slate-100">{{ $bien->nombre }}</td>
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
                    <td class="px-4 py-2.5 align-middle text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('bienes.show', $bien) }}" class="inline-flex items-center gap-1.5 rounded-2xl border border-brand-400/50 bg-brand-50/80 px-3 py-1.5 text-xs font-semibold text-brand-700 shadow-inner shadow-brand-200/60 transition duration-300 hover:-translate-y-0.5 hover:bg-brand-100/90 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Ver
                            </a>
                            <a href="{{ route('bienes.edit', $bien) }}" class="inline-flex items-center gap-1.5 rounded-2xl bg-gradient-to-r from-brand-500 to-accent-500 px-3 py-1.5 text-xs font-semibold text-white shadow-lg shadow-brand-900/20 transition duration-300 hover:-translate-y-0.5 hover:shadow-brand-900/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-300 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l2.651 2.651M5.25 15.75l9.513-9.513a1.5 1.5 0 012.121 0l1.379 1.379a1.5 1.5 0 010 2.121L8.75 19.25H5.25v-3.5z" />
                                </svg>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('bienes.destroy', $bien) }}" class="inline" data-delete-form>
                                @csrf
                                @method('DELETE')
                                <button type="button" class="inline-flex items-center gap-1.5 rounded-2xl border border-red-500/50 bg-red-500/10 px-3 py-1.5 text-xs font-semibold text-red-200 shadow-inner shadow-red-900/20 transition duration-300 hover:-translate-y-0.5 hover:bg-red-500/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-300 cursor-pointer" data-delete-trigger>
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
    <div>
        {{ $bienes->links() }}
    </div>
</div>
