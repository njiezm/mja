<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AdhesionController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SourceTrackController;
use App\Http\Controllers\Member\AccountController as MemberAccountController;
use App\Http\Controllers\Member\AuthController as MemberAuthController;
use App\Http\Controllers\Member\SpaceController as MemberSpaceController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

// ─── Public ───────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
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
Route::get('/adhesion/paiement/succes', [AdhesionController::class, 'paiementSucces'])->name('adhesion.paiement.succes');
Route::get('/adhesion/paiement/annule', [AdhesionController::class, 'paiementAnnule'])->name('adhesion.paiement.annule');

Route::get('/mentions-legales', [HomeController::class, 'mentionsLegales'])->name('mentions-legales');
Route::get('/politique-de-confidentialite', [HomeController::class, 'confidentialite'])->name('confidentialite');

// ─── Espace membre (adhérents) ──────────────────────────────────────────────────
Route::prefix('espace')->name('member.')->group(function () {
    // Création de compte via lien reçu par email
    Route::get('creer/{token}', [MemberAccountController::class, 'showCreate'])->name('account.create');
    Route::post('creer/{token}', [MemberAccountController::class, 'store'])->name('account.store');

    // Restauration d'un compte supprimé (lien reçu par email, 30 jours)
    Route::get('restaurer/{token}', [MemberAccountController::class, 'restore'])->name('account.restore');

    // Connexion
    Route::get('connexion', [MemberAuthController::class, 'showLogin'])->name('login');
    Route::post('connexion', [MemberAuthController::class, 'login'])->name('login.post');
    Route::post('deconnexion', [MemberAuthController::class, 'logout'])->name('logout');

    // Espace protégé
    Route::middleware('auth:member')->group(function () {
        Route::get('/', [MemberSpaceController::class, 'dashboard'])->name('dashboard');
        Route::get('trombinoscope', [MemberSpaceController::class, 'trombinoscope'])->name('trombinoscope');
        Route::get('profil', [MemberSpaceController::class, 'editProfile'])->name('profile.edit');
        Route::put('profil', [MemberSpaceController::class, 'updateProfile'])->name('profile.update');
        Route::delete('compte', [MemberSpaceController::class, 'destroy'])->name('account.destroy');
    });
});

// ─── Auth ─────────────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // ── Contenu ── gestionnaire de contenu, admin, super admin
    Route::middleware('content')->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('articles', Admin\ArticleController::class)->except(['show']);
        Route::resource('projects', Admin\ProjectController::class)->except(['show']);
        Route::resource('events', Admin\EventController::class)->except(['show']);
        Route::resource('resources', Admin\ResourceController::class)->except(['show']);
        Route::resource('team', Admin\TeamController::class)->except(['show']);
        Route::resource('partenaires', Admin\PartenaireController::class)->except(['show']);
    });

    // ── Gestion (adhésions, messages) ── admin et super admin
    Route::middleware('admin')->group(function () {
        Route::get('contacts', [Admin\ContactController::class, 'index'])->name('contacts.index');
        Route::get('contacts/{contact}', [Admin\ContactController::class, 'show'])->name('contacts.show');
        Route::delete('contacts/{contact}', [Admin\ContactController::class, 'destroy'])->name('contacts.destroy');

        Route::get('adhesions', [Admin\AdhesionController::class, 'index'])->name('adhesions.index');
        Route::get('adhesions/{adhesion}', [Admin\AdhesionController::class, 'show'])->name('adhesions.show');
        Route::patch('adhesions/{adhesion}/statut', [Admin\AdhesionController::class, 'updateStatut'])->name('adhesions.statut');
        Route::delete('adhesions/{adhesion}', [Admin\AdhesionController::class, 'destroy'])->name('adhesions.destroy');

        // Sources d'acquisition & statistiques de tracking
        Route::get('sources', [Admin\SourceController::class, 'index'])->name('sources.index');
        Route::get('sources/export', [Admin\SourceController::class, 'export'])->name('sources.export');
        Route::post('sources', [Admin\SourceController::class, 'store'])->name('sources.store');
        Route::get('sources/{source}/edit', [Admin\SourceController::class, 'edit'])->name('sources.edit');
        Route::put('sources/{source}', [Admin\SourceController::class, 'update'])->name('sources.update');
        Route::delete('sources/{source}', [Admin\SourceController::class, 'destroy'])->name('sources.destroy');
    });

    // ── Comptes ── admin (gestionnaires seulement) et super admin (tous).
    // La hiérarchie fine est vérifiée dans le contrôleur.
    Route::middleware('admin')->group(function () {
        Route::resource('users', Admin\UserController::class)->except(['show']);
        Route::patch('users/{user}/reset-password', [Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::patch('users/{user}/toggle-active', [Admin\UserController::class, 'toggleActive'])->name('users.toggle-active');
    });

    // ── Paramètres (clés Stripe, cotisation) ── super admin uniquement
    Route::middleware('super_admin')->group(function () {
        Route::get('parametres', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('parametres', [Admin\SettingController::class, 'update'])->name('settings.update');
    });
});

// ─── Tracking des sources d'acquisition ─────────────────────────────────────────
// DOIT rester en dernier : n'intercepte qu'un segment unique non déjà routé,
// et 404 si le slug n'est pas une source enregistrée.
Route::get('/{source}', [SourceTrackController::class, 'handle'])
    ->where('source', '[A-Za-z0-9._-]+')
    ->name('source.track');
