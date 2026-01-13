@extends('layouts.dashboard')

@section('content')
    <div class="space-y-8">
        {{-- Encabezado --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-slate-900">Panel de control</h1>
                    <p class="text-xs text-slate-500 mt-1 max-w-2xl">Gestiona los tres módulos principales del sistema: Bienes, Usuarios y Bitácora. Accede rápido y mantén el control, todo en azul y blanco.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('bienes.index') }}" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow hover:bg-blue-500">Ver inventario</a>
                    <a href="{{ route('bienes.create') }}" class="inline-flex items-center rounded-md bg-slate-900 px-3 py-2 text-xs font-semibold text-white shadow hover:bg-slate-800">Registrar bien</a>
                </div>
            </div>
        </div>

        {{-- Resumen por módulos --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            {{-- Bienes --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-medium text-blue-700 uppercase tracking-wide">Bienes</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ \App\Models\Bien::count() }}</p>
                        <p class="mt-1 text-[11px] text-slate-500">Total de bienes registrados.</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5l8.25-4.5 8.25 4.5M3 7.5l8.25 4.5M3 7.5v9l8.25 4.5M19.5 7.5l-8.25 4.5M19.5 7.5v9l-8.25 4.5" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <a href="{{ route('bienes.index') }}" class="inline-flex items-center text-xs font-semibold text-blue-700 hover:text-blue-900">Ir a bienes →</a>
                </div>
            </div>

            {{-- Usuarios --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-medium text-blue-700 uppercase tracking-wide">Usuarios</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ \App\Models\User::count() }}</p>
                        <p class="mt-1 text-[11px] text-slate-500">Usuarios con acceso al sistema.</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a6.75 6.75 0 0113.5 0" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center text-xs font-semibold text-blue-700 hover:text-blue-900">Gestionar usuarios →</a>
                </div>
            </div>

            {{-- Bitácora --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-medium text-blue-700 uppercase tracking-wide">Bitácora</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ \App\Models\Bitacora::count() }}</p>
                        <p class="mt-1 text-[11px] text-slate-500">Registros de movimientos.</p>
                    </div>
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M3 9.75h18M3 15h18M3 19.5h18" />
                        </svg>
                    </span>
                </div>
                <div class="mt-4">
                    <a href="{{ route('bitacora.index') }}" class="inline-flex items-center text-xs font-semibold text-blue-700 hover:text-blue-900">Ver bitácora →</a>
                </div>
            </div>
        </div>

        {{-- Acciones rápidas --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-medium text-slate-600 mb-3">Acciones rápidas</p>
            <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3">
                <a href="{{ route('bienes.create') }}" class="flex items-center gap-3 rounded-xl bg-blue-600 px-3.5 py-3 text-left text-xs font-medium text-white shadow hover:bg-blue-500">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </span>
                    <span>
                        Registrar bien
                        <span class="block text-[11px] font-normal text-blue-100/90">Crear un nuevo registro.</span>
                    </span>
                </a>

                <a href="{{ route('bienes.index') }}" class="flex items-center gap-3 rounded-xl bg-slate-50 px-3.5 py-3 text-left text-xs font-medium text-slate-800 border border-slate-200 hover:border-blue-400 hover:bg-blue-50/60">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 4.5h19.5M2.25 9.75h19.5M9 15h12.75M9 19.5h12.75" />
                        </svg>
                    </span>
                    <span>
                        Consultar inventario
                        <span class="block text-[11px] font-normal text-slate-500">Buscar y filtrar registros.</span>
                    </span>
                </a>

                <a href="{{ route('users.index') }}" class="flex items-center gap-3 rounded-xl bg-slate-50 px-3.5 py-3 text-left text-xs font-medium text-slate-800 border border-slate-200 hover:border-blue-400 hover:bg-blue-50/60">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a6.75 6.75 0 0113.5 0" />
                        </svg>
                    </span>
                    <span>
                        Gestionar usuarios
                        <span class="block text-[11px] font-normal text-slate-500">Roles y permisos básicos.</span>
                    </span>
                </a>

                <a href="{{ route('bitacora.index') }}" class="flex items-center gap-3 rounded-xl bg-slate-50 px-3.5 py-3 text-left text-xs font-medium text-slate-800 border border-slate-200 hover:border-blue-400 hover:bg-blue-50/60">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h18M3 9.75h18M3 15h18M3 19.5h18" />
                        </svg>
                    </span>
                    <span>
                        Ver bitácora
                        <span class="block text-[11px] font-normal text-slate-500">Movimientos del sistema.</span>
                    </span>
                </a>
            </div>
        </div>

        
    </div>
@endsection
