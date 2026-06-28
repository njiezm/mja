<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/connexion', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/mot-de-passe-oublie', [ForgotPasswordController::class, 'showForm'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reinitialiser-mot-de-passe/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('/reinitialiser-mot-de-passe', [ResetPasswordController::class, 'reset'])->name('password.update');
});
