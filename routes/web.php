<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AdhesionController;
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

Route::get('/adhesion', [AdhesionController::class, 'create'])->name('adhesion');
Route::post('/adhesion', [AdhesionController::class, 'store'])->name('adhesion.store');

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
    Route::resource('partenaires', Admin\PartenaireController::class)->except(['show']);

    Route::middleware('super_admin')->group(function () {
        Route::resource('users', Admin\UserController::class)->except(['show']);
    });

    Route::get('contacts', [Admin\ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [Admin\ContactController::class, 'show'])->name('contacts.show');
    Route::delete('contacts/{contact}', [Admin\ContactController::class, 'destroy'])->name('contacts.destroy');

    Route::get('adhesions', [Admin\AdhesionController::class, 'index'])->name('adhesions.index');
    Route::get('adhesions/{adhesion}', [Admin\AdhesionController::class, 'show'])->name('adhesions.show');
    Route::patch('adhesions/{adhesion}/statut', [Admin\AdhesionController::class, 'updateStatut'])->name('adhesions.statut');
    Route::delete('adhesions/{adhesion}', [Admin\AdhesionController::class, 'destroy'])->name('adhesions.destroy');
});
