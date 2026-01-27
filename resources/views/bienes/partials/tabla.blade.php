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
                    <td class="px-4 py-2.5 align-middle text-right text-slate-300 hidden lg:table-cell">
                        @if (!is_null($bien->valor))
                            {{ number_format($bien->valor, 2, ',', '.') }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="px-4 py-2.5 align-middle text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('bienes.show', $bien) }}" class="inline-flex items-center gap-1.5 rounded-md bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-500 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Ver
                            </a>
                            <a href="{{ route('bienes.edit', $bien) }}" class="inline-flex items-center gap-1.5 rounded-md bg-indigo-600 px-3 py-1.5 text-xs text-white hover:bg-indigo-500 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l2.651 2.651M5.25 15.75l9.513-9.513a1.5 1.5 0 012.121 0l1.379 1.379a1.5 1.5 0 010 2.121L8.75 19.25H5.25v-3.5z" />
                                </svg>
                                Editar
                            </a>
                            <form method="POST" action="{{ route('bienes.destroy', $bien) }}" class="inline" data-delete-form>
                                @csrf
                                @method('DELETE')
                                <button type="button" class="inline-flex items-center gap-1.5 rounded-md bg-red-600 px-3 py-1.5 text-xs text-white hover:bg-red-500 cursor-pointer" data-delete-trigger>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                        <path fill-rule="evenodd" d="M16.5 4.5a.75.75 0 01.75.75V6h3a.75.75 0 010 1.5h-.638l-1.018 11.2a2.25 2.25 0 01-2.245 2.05H7.652a2.25 2.25 0 01-2.245-2.05L4.39 7.5H3.75A.75.75 0 013 6h3V5.25a.75.75 0 01.75-.75h9.75zm-7.5 4.5a.75.75 0 00-1.5 0v9a.75.75 0 001.5 0v-9zm7.5 0a.75.75 0 00-1.5 0v9a.75.75 0 001.5 0v-9z" clip-rule="evenodd" />
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
