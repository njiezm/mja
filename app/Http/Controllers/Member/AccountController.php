<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Adhesion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /** Résout une adhésion à partir d'un jeton de création valide. */
    private function resolve(string $token): ?Adhesion
    {
        $adhesion = Adhesion::where('account_token', $token)->whereNull('user_id')->first();

        if (! $adhesion) {
            return null;
        }
        if ($adhesion->account_token_expires_at && $adhesion->account_token_expires_at->isPast()) {
            return null;
        }

        return $adhesion;
    }

    public function showCreate(string $token)
    {
        $adhesion = $this->resolve($token);

        if (! $adhesion) {
            return redirect()->route('member.login')
                ->with('error', 'Ce lien de création de compte est invalide ou expiré.');
        }

        return view('member.create', compact('adhesion', 'token'));
    }

    public function store(Request $request, string $token)
    {
        $adhesion = $this->resolve($token);

        if (! $adhesion) {
            return redirect()->route('member.login')
                ->with('error', 'Ce lien de création de compte est invalide ou expiré.');
        }

        $validated = $request->validate([
            'email'             => ['required', 'email', 'max:150', Rule::unique('users', 'email')],
            'password'          => 'required|string|min:8|confirmed',
            'show_in_directory' => 'boolean',
        ], [
            'email.required'     => "L'adresse email est obligatoire.",
            'email.unique'       => 'Un compte existe déjà avec cette adresse email. Connectez-vous, ou utilisez « mot de passe oublié ».',
            'password.required'  => 'Choisissez un mot de passe.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        // Emails stockés en minuscules (comparaison `=` sensible à la casse sous PostgreSQL).
        $email = Str::lower(trim($validated['email']));

        $user = new User([
            'name'              => trim($adhesion->prenom . ' ' . $adhesion->nom),
            'email'             => $email,
            'adhesion_id'       => $adhesion->id,
            'role'              => User::ROLE_MEMBER,
            'is_active'         => true,
            'show_in_directory' => $request->boolean('show_in_directory', true),
        ]);
        $user->setPasswordAndCopy($validated['password']);
        $user->save();

        // Le compte est lié par le jeton/adhésion : on synchronise l'email de
        // l'adhésion et on consomme le jeton.
        $adhesion->update([
            'email'                    => $email,
            'user_id'                  => $user->id,
            'account_token'            => null,
            'account_token_expires_at' => null,
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('member.dashboard')
            ->with('success', 'Bienvenue ! Votre espace adhérent est prêt.');
    }

    /** Restauration d'un compte supprimé (dans les 30 jours). */
    public function restore(string $token)
    {
        $user = User::onlyTrashed()->where('restore_token', $token)->first();

        if (! $user) {
            return redirect()->route('member.login')
                ->with('error', 'Lien de restauration invalide.');
        }

        // Au-delà de 30 jours : suppression définitive, restauration impossible.
        if ($user->deleted_at->lt(now()->subDays(30))) {
            $user->forceDelete();

            return redirect()->route('member.login')
                ->with('error', 'Le délai de 30 jours est dépassé, ce compte a été définitivement supprimé.');
        }

        $user->restore();
        $user->update(['restore_token' => null]);

        return redirect()->route('member.login')
            ->with('success', 'Votre compte a été restauré. Vous pouvez vous reconnecter.');
    }
}
