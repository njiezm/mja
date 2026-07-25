<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    private function broker()
    {
        return Password::broker('members');
    }

    public function showLinkRequest()
    {
        return view('member.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = $this->broker()->sendResetLink($request->only('email'));

        // Message neutre (ne révèle pas si l'email existe)
        return back()->with('status', "Si un compte existe pour cette adresse, un lien de réinitialisation vient d'être envoyé.");
    }

    public function showReset(Request $request, string $token)
    {
        return view('member.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $status = $this->broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($member, $password) {
                $member->forceFill(['password' => Hash::make($password)])->setRememberToken(Str::random(60));
                $member->save();
                event(new PasswordReset($member));
            }
        );

        if ($status === Password::PasswordReset) {
            return redirect()->route('member.login')->with('success', 'Votre mot de passe a été réinitialisé, vous pouvez vous connecter.');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Lien invalide ou expiré. Veuillez refaire une demande.']);
    }
}
