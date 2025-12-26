@extends('layouts.dashboard')

@section('content')
    <div class="space-y-5 w-full">
        <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1.5 mb-1">
            <h1 class="text-xl font-semibold tracking-tight">Gestión de usuarios</h1>
            <p class="text-sm text-slate-400">Lista de usuarios registrados en el sistema.</p>
        </div>

        @if (session('status'))
            <div class="mb-3 text-xs text-emerald-300 border border-emerald-500/40 bg-emerald-900/30 rounded px-3 py-2">
                {{ session('status') }}
            </div>
        @endif

        <div class="overflow-x-auto rounded-2xl border border-slate-800 bg-slate-900/70 shadow-xl">
            <table class="w-full table-auto text-sm">
                <thead class="bg-slate-900/80 border-b border-slate-800 text-slate-400">
                    <tr>
                        <th class="px-4 py-2.5 text-left font-medium">ID</th>
                        <th class="px-4 py-2.5 text-left font-medium">Nombre</th>
                        <th class="px-4 py-2.5 text-left font-medium">Correo</th>
                        <th class="px-4 py-2.5 text-left font-medium">Rol</th>
                        <th class="px-4 py-2.5 text-right font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-900/80">
                            <td class="px-4 py-2.5 align-middle text-slate-400">{{ $user->id }}</td>
                            <td class="px-4 py-2.5 align-middle text-slate-100">{{ $user->name }}</td>
                            <td class="px-4 py-2.5 align-middle text-slate-300">{{ $user->email }}</td>
                            <td class="px-4 py-2.5 align-middle">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs
                                    {{ $user->role === 'admin' ? 'border-amber-400/60 text-amber-200 bg-amber-500/10' : 'border-slate-600/70 text-slate-200 bg-slate-700/30' }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 align-middle text-right">
                                <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center rounded-md bg-slate-800 px-3 py-1.5 text-xs text-slate-100 hover:bg-slate-700 cursor-pointer">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-slate-500 text-sm">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
