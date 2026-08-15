<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Connexion à l'espace adhérent. Depuis la fusion des comptes, c'est la même
 * identité que le back-office : un administrateur peut se connecter ici et
 * basculer ensuite vers l'administration, sans second mot de passe.
 */
class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('member.dashboard');
        }

        return view('member.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Les emails sont stockés en minuscules ; PostgreSQL compare `=` de façon
        // sensible à la casse, donc on normalise avant la recherche du compte.
        $credentials['email'] = Str::lower(trim($credentials['email']));

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Identifiants incorrects.'])->onlyInput('email');
        }

        $user = Auth::user();

        // Un accès révoqué ne doit pas ouvrir de session, même avec le bon mot de passe.
        if (! $user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['email' => 'Votre accès a été désactivé. Contactez un administrateur.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // Un compte purement administrateur n'a pas d'espace adhérent à afficher.
        $destination = $user->isMember() ? route('member.dashboard') : route('admin.dashboard');

        return redirect()->intended($destination);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
