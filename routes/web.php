<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BienController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as VerifyCsrfTokenMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Registro
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post')
    ->middleware('throttle:login');
// Rutas protegidas: solo usuarios autenticados
Route::middleware('auth')->group(function () {
    // Logout (se excluye del middleware de CSRF para evitar errores 419 al cerrar sesión)
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Dashboard protegido (usando controlador y caché)
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Perfil de usuario (vista simple por ahora)
    Route::get('/perfil', function () {
        return view('profile');
    })->name('profile');

    // Módulo de bienes (inventario):
    // - Solo accesible para usuarios autenticados con rol admin u operador (user).
    // - Permite registrar, consultar, editar y eliminar bienes.
    Route::middleware('role:admin,user')->group(function () {
        Route::get('/bienes', [BienController::class, 'index'])
            ->middleware('permission:bienes.ver')
            ->name('bienes.index');
        Route::get('/bienes/reportes/pdf', [BienController::class, 'exportPdf'])
            ->middleware('permission:reportes.exportar')
            ->name('bienes.reportes.pdf');

        Route::get('/bienes/categorias', [CategoriaController::class, 'index'])
            ->middleware('permission:categorias.ver')
            ->name('bienes.categorias.index');
        Route::post('/bienes/categorias', [CategoriaController::class, 'store'])
            ->middleware('permission:categorias.gestionar')
            ->name('bienes.categorias.store');
        Route::put('/bienes/categorias/{categoria}', [CategoriaController::class, 'update'])
            ->middleware('permission:categorias.gestionar')
            ->name('bienes.categorias.update');
        Route::patch('/bienes/categorias/{categoria}/toggle', [CategoriaController::class, 'toggle'])
            ->middleware('permission:categorias.gestionar')
            ->name('bienes.categorias.toggle');

        Route::get('/bienes/crear', [BienController::class, 'create'])
            ->middleware('permission:bienes.crear')
            ->name('bienes.create');
        Route::post('/bienes', [BienController::class, 'store'])
            ->middleware('permission:bienes.crear')
            ->name('bienes.store');
        
        Route::get('/bienes', [BienController::class, 'index'])
            ->middleware('permission:bienes.ver')
            ->name('bienes.index');
        Route::get('/bienes/{bien}', [BienController::class, 'show'])
            ->middleware('permission:bienes.ver')
            ->name('bienes.show');
        Route::get('/bienes/{bien}/editar', [BienController::class, 'edit'])
            ->middleware('permission:bienes.editar')
            ->name('bienes.edit');
        Route::put('/bienes/{bien}', [BienController::class, 'update'])
            ->middleware('permission:bienes.editar')
            ->name('bienes.update');
        Route::delete('/bienes/{bien}', [BienController::class, 'destroy'])
            ->middleware('permission:bienes.eliminar')
            ->name('bienes.destroy');
    });
});

// Gestión de usuarios (panel interno) - solo admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
    Route::get('/usuarios/{user}/editar', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/usuarios/{user}/unlock', [UserController::class, 'unlock'])->name('users.unlock');

    // Bitácora del sistema (solo administrador)
    Route::get('/bitacora', [BitacoraController::class, 'index'])->name('bitacora.index');
});

// Recuperación de contraseña por preguntas de seguridad
// Recuperación de contraseña (solo vista unificada)
Route::get('/password/recover', [AuthController::class, 'showRecoveryForm'])->name('password.recover');
Route::post('/password/recover', [AuthController::class, 'handleRecovery'])
    ->name('password.recover.handle')
    ->middleware('throttle:recover');
