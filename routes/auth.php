<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/connexion', [LoginController::class, 'login'])->name('login.post')->middleware('guest');
Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');
