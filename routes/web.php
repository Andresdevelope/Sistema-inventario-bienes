<?php

use App\Http\Controllers\AuthController;
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

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard sencillo protegido
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Perfil de usuario (vista simple por ahora)
Route::get('/perfil', function () {
    return view('profile');
})->name('profile');

// Recuperación de contraseña por preguntas de seguridad
// Recuperación de contraseña (solo vista unificada)
Route::get('/password/recover', [AuthController::class, 'showRecoveryForm'])->name('password.recover');
Route::post('/password/recover', [AuthController::class, 'handleRecovery'])->name('password.recover.handle');
