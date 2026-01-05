<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BienController;
use App\Http\Controllers\BitacoraController;
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
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// Rutas protegidas: solo usuarios autenticados
Route::middleware('auth')->group(function () {
    // Logout (se excluye del middleware de CSRF para evitar errores 419 al cerrar sesión)
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout')
        ->withoutMiddleware(VerifyCsrfTokenMiddleware::class);

    // Dashboard sencillo protegido
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil de usuario (vista simple por ahora)
    Route::get('/perfil', function () {
        return view('profile');
    })->name('profile');

    // Módulo de bienes (inventario):
    // - Solo accesible para usuarios autenticados con rol admin u operador (user).
    // - Permite registrar, consultar, editar y eliminar bienes.
    Route::middleware('role:admin,user')->group(function () {
        Route::get('/bienes', [BienController::class, 'index'])->name('bienes.index');
        Route::get('/bienes/crear', [BienController::class, 'create'])->name('bienes.create');
        Route::post('/bienes', [BienController::class, 'store'])->name('bienes.store');

        Route::get('/bienes/{bien}', [BienController::class, 'show'])->name('bienes.show');
        Route::get('/bienes/{bien}/editar', [BienController::class, 'edit'])->name('bienes.edit');
        Route::put('/bienes/{bien}', [BienController::class, 'update'])->name('bienes.update');
        Route::delete('/bienes/{bien}', [BienController::class, 'destroy'])->name('bienes.destroy');
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
Route::post('/password/recover', [AuthController::class, 'handleRecovery'])->name('password.recover.handle');
