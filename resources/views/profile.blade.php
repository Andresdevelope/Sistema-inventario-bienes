@extends('layouts.dashboard')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-xl">
            <div class="flex items-center gap-4 mb-4">
                <div class="h-14 w-14 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-500 flex items-center justify-center text-sm font-semibold shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a6.75 6.75 0 0113.5 0" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs text-slate-400">Perfil de usuario</p>
                    <p class="text-lg font-semibold text-slate-100 truncate">
                        {{ auth()->user()->name ?? 'Usuario' }}
                    </p>
                    <p class="text-[11px] text-slate-400">Rol: <span class="text-emerald-300">{{ auth()->user()->role ?? 'Usuario' }}</span></p>
                </div>
            </div>

            <div class="grid gap-4 text-sm text-slate-200">
                <div>
                    <p class="text-xs text-slate-400">Correo electrónico</p>
                    <p>{{ auth()->user()->email ?? '—' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4 text-[11px] text-slate-300">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Último acceso</span>
                        <span class="font-medium text-slate-100">—</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400">Sesiones activas</span>
                        <span class="font-medium text-slate-100">1</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-2">
                <button type="button" class="inline-flex items-center justify-center rounded-md bg-slate-800 px-3 py-1.5 text-[11px] font-medium text-slate-100 hover:bg-slate-700 transition">
                    Editar datos básicos
                </button>
                <button type="button" class="inline-flex items-center justify-center rounded-md border border-slate-700 px-3 py-1.5 text-[11px] font-medium text-slate-200 hover:border-blue-500 hover:text-blue-200 transition">
                    Cambiar contraseña
                </button>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-800 bg-slate-900/70 p-5 shadow-xl text-[11px] text-slate-300 space-y-2">
            <p class="font-semibold text-slate-100 text-xs">Notas</p>
            <p class="text-slate-400">En esta sección podrás gestionar tu información personal relacionada con el sistema de inventario de bienes.</p>
        </div>
    </div>
@endsection
