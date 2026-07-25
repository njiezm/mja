<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Autorise les administrateurs (admin ou super_admin) actifs.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user || ! $user->is_active) {
            return $this->reject($request);
        }

        if (! $user->isAdmin()) {
            abort(403, 'Accès réservé à l\'administration.');
        }

        return $next($request);
    }

    protected function reject(Request $request): Response
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withErrors(['email' => 'Votre accès a été désactivé. Contactez un administrateur.']);
    }
}
