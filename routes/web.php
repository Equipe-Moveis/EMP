<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// A raiz manda para o dashboard; se não estiver logado, o middleware 'auth' leva ao login.
Route::redirect('/', '/dashboard');

// ---------- Visitantes (não logados) ----------
// 'guest': quem já está logado e tenta abrir /login é redirecionado para o dashboard.
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');

    // throttle:5,1 = no máximo 5 tentativas por minuto.
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

// ---------- Logados ----------
// 'auth': sem login, o Laravel redireciona para a rota chamada 'login'.
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', fn () => view('DashboardController'))->name('dashboard');

    // ---------- Só admin ----------
    Route::middleware('can:gerenciar-usuarios')->group(function () {
        Route::resource('usuarios', UserController::class)
            ->except('show')
            ->parameters(['usuarios' => 'usuario']);
    });
});