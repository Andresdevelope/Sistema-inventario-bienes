@extends('layouts.dashboard')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="rounded-3xl border border-brand-200 bg-white shadow-xl shadow-brand-900/10 overflow-hidden">
            <div class="h-2 w-full bg-gradient-to-r from-brand-500 via-brand-600 to-brand-700"></div>

            <div class="p-6 md:p-8 space-y-6">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex flex-1 flex-col gap-5">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="h-20 w-20 rounded-3xl bg-gradient-to-br from-brand-500 via-brand-600 to-brand-700 flex items-center justify-center text-white shadow-lg shadow-brand-900/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-10 w-10">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5a6.75 6.75 0 0113.5 0" />
                                    </svg>
                                </div>
                                <span class="absolute -bottom-1 -right-1 inline-flex items-center gap-1 rounded-full bg-emerald-500 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-white shadow">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12A9 9 0 113 12a9 9 0 0118 0z" />
                                    </svg>
                                    Activo
                                </span>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs uppercase tracking-[0.2em] text-brand-600">Ficha de identidad digital</p>
                                <p class="text-2xl font-bold text-slate-900 truncate">
                                    {{ auth()->user()->name ?? 'Usuario' }}
                                </p>
                                <p class="mt-1 text-[12px] text-slate-600 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 text-brand-500">
                                        <path d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm.75 4.5a.75.75 0 10-1.5 0v6a.75.75 0 001.5 0v-6zm-.75 9a1 1 0 100 2 1 1 0 000-2z" />
                                    </svg>
                                    Identificador interno #{{ auth()->user()->id ?? '0000' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2.5 text-[11px]">
                            <span class="inline-flex items-center gap-1 rounded-full border border-brand-200 bg-brand-50 px-3 py-1 font-semibold uppercase tracking-wide text-brand-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75A2.25 2.25 0 0014.25 4.5h-4.5A2.25 2.25 0 007.5 6.75v10.5A2.25 2.25 0 009.75 19.5h4.5A2.25 2.25 0 0016.5 17.25V13.5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12l9-3-9-3v6zm0 0l-9-3 9-3v6z" />
                                </svg>
                                Rol: <span class="font-bold text-brand-900">{{ auth()->user()->role ?? 'Usuario' }}</span>
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 font-semibold uppercase tracking-wide text-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5l16.5 7.5-16.5 7.5 3-7.5-3-7.5z" />
                                </svg>
                                Acceso verificado
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full border border-accent-200 bg-accent-50 px-3 py-1 font-semibold uppercase tracking-wide text-accent-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-3.5 w-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                Inventario & logística
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-3 pt-1">
                            <a href="{{ route('dashboard') }}"
                               class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-brand-500 to-brand-600 px-4 py-2 text-[12px] font-semibold text-white shadow-lg shadow-brand-900/30 transition hover:-translate-y-0.5 hover:shadow-brand-900/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4.5 9.75V21h15V9.75" />
                                </svg>
                                Volver al panel
                            </a>
                            <a href="{{ route('bienes.create') }}"
                               class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-accent-400 to-accent-500 px-4 py-2 text-[12px] font-semibold text-brand-900 shadow-lg shadow-accent-900/20 transition hover:-translate-y-0.5 hover:shadow-accent-900/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                Registrar nuevo bien
                            </a>
                        </div>
                    </div>

                    <div class="w-full lg:w-72">
                        <div class="rounded-2xl border border-brand-200 bg-gradient-to-br from-brand-50 to-white p-5 flex flex-col gap-4">
                            <p class="text-xs uppercase tracking-[0.3em] text-brand-700">Sello institucional</p>
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('logo-institucion.jpg') }}" alt="Logo institucional" class="h-14 w-14 rounded-2xl border border-brand-200 bg-white p-2 object-contain shadow">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">Antequera</p>
                                    <p class="text-[11px] text-slate-600">Identidad oficial del sistema</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 text-[10px]">
                                <span class="inline-flex items-center gap-1 rounded-full border border-brand-200 bg-white px-3 py-1 text-brand-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>
                                    Uso autorizado
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full border border-brand-200 bg-white px-3 py-1 text-brand-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-brand-400"></span>
                                    Alta seguridad
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs text-slate-500">Correo corporativo</p>
                        <div class="mt-1 flex items-center gap-2 text-sm text-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-brand-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.25l9 6 9-6M4.5 6h15a1.5 1.5 0 011.5 1.5v9a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 16.5v-9A1.5 1.5 0 014.5 6z" />
                            </svg>
                            {{ auth()->user()->email ?? '—' }}
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs text-slate-500">Último acceso registrado</p>
                        <div class="mt-1 flex items-center gap-2 text-sm text-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-brand-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0z" />
                            </svg>
                            —
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs text-slate-500">Sesiones activas</p>
                        <div class="mt-1 flex items-center gap-2 text-sm text-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4 text-emerald-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25l1.5 1.5 3-3M4.5 19.5A1.5 1.5 0 003 18V6a1.5 1.5 0 011.5-1.5h15A1.5 1.5 0 0121 6v12a1.5 1.5 0 01-1.5 1.5h-15z" />
                            </svg>
                            1
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
