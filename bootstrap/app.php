<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Capture des visites UTM sur tout le site public.
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisit::class,
        ]);

        $middleware->alias([
            'honeypot'    => \App\Http\Middleware\Honeypot::class,
            'content'     => \App\Http\Middleware\EnsureContentManager::class,
            'admin'       => \App\Http\Middleware\EnsureAdmin::class,
            'super_admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
        ]);

        // Redirection des visiteurs non authentifiés : espace membre vs administration.
        $middleware->redirectGuestsTo(function (Request $request) {
            return $request->is('espace', 'espace/*')
                ? route('member.login')
                : route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
