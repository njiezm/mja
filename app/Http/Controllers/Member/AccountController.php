<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Adhesion;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /** Résout une adhésion à partir d'un jeton de création valide. */
    private function resolve(string $token): ?Adhesion
    {
        $adhesion = Adhesion::where('account_token', $token)->whereDoesntHave('member')->first();

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
            'password'          => 'required|string|min:8|confirmed',
            'show_in_directory' => 'boolean',
        ], [
            'password.required'  => 'Choisissez un mot de passe.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $member = Member::create([
            'adhesion_id'       => $adhesion->id,
            'email'             => $adhesion->email,
            'password'          => $validated['password'],
            'show_in_directory' => $request->boolean('show_in_directory', true),
        ]);

        // Le jeton est consommé.
        $adhesion->update(['account_token' => null, 'account_token_expires_at' => null]);

        Auth::guard('member')->login($member, true);

        return redirect()->route('member.dashboard')
            ->with('success', 'Bienvenue ! Votre espace membre est prêt.');
    }

    /** Restauration d'un compte supprimé (dans les 30 jours). */
    public function restore(string $token)
    {
        $member = Member::onlyTrashed()->where('restore_token', $token)->first();

        if (! $member) {
            return redirect()->route('member.login')
                ->with('error', 'Lien de restauration invalide.');
        }

        // Au-delà de 30 jours : suppression définitive, restauration impossible.
        if ($member->deleted_at->lt(now()->subDays(30))) {
            $member->forceDelete();
            return redirect()->route('member.login')
                ->with('error', 'Le délai de 30 jours est dépassé, ce compte a été définitivement supprimé.');
        }

        $member->restore();
        $member->update(['restore_token' => null]);

        return redirect()->route('member.login')
            ->with('success', 'Votre compte a été restauré. Vous pouvez vous reconnecter.');
    }
}
