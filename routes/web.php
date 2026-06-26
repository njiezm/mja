<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// ─── Public ───────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/a-propos', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactStore'])->name('contact.store');

Route::get('/actualites', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/actualites/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/projets', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projets/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/evenements', [EventController::class, 'index'])->name('events.index');
Route::get('/evenements/{event:slug}', [EventController::class, 'show'])->name('events.show');

Route::get('/ressources', [ResourceController::class, 'index'])->name('resources.index');
Route::get('/sante-nutrition-sport', [HomeController::class, 'sns'])->name('sns');

// ─── Auth ─────────────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('articles', Admin\ArticleController::class)->except(['show']);
    Route::resource('projects', Admin\ProjectController::class)->except(['show']);
    Route::resource('events', Admin\EventController::class)->except(['show']);
    Route::resource('resources', Admin\ResourceController::class)->except(['show']);
    Route::resource('team', Admin\TeamController::class)->except(['show']);

    Route::get('contacts', [Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [Admin\ContactController::class, 'show'])->name('contacts.show');
    Route::delete('contacts/{contact}', [Admin\ContactController::class, 'destroy'])->name('contacts.destroy');
});
