<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureContentManager
{
    /**
     * Autorise l'accès à la partie « contenu » : gestionnaire de contenu,
     * admin ou super_admin, à condition que le compte soit actif.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user || ! $user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Votre accès a été désactivé. Contactez un administrateur.']);
        }

        if (! $user->canManageContent()) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
