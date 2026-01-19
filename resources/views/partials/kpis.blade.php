{{-- Parcial: KPIs del dashboard (datos provistos por el controlador) --}}
<div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-3">
    {{-- Bienes --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:shadow-md hover:-translate-y-0.5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-medium text-blue-700 uppercase tracking-wide">Bienes</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $counts['bienes'] ?? 0 }}</p>
                <p class="mt-1 text-[11px] text-slate-500">Total de bienes registrados.</p>
            </div>
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-700 ring-1 ring-blue-200">
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
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:shadow-md hover:-translate-y-0.5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-medium text-blue-700 uppercase tracking-wide">Usuarios</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $counts['usuarios'] ?? 0 }}</p>
                <p class="mt-1 text-[11px] text-slate-500">Usuarios con acceso al sistema.</p>
            </div>
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-700 ring-1 ring-blue-200">
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
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:shadow-md hover:-translate-y-0.5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[11px] font-medium text-blue-700 uppercase tracking-wide">Bitácora</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ $counts['bitacora'] ?? 0 }}</p>
                <p class="mt-1 text-[11px] text-slate-500">Registros de movimientos.</p>
            </div>
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-700 ring-1 ring-blue-200">
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
