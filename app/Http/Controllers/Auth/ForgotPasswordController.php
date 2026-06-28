<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(
            ['email' => 'required|email'],
            ['email.required' => "L'adresse email est obligatoire.", 'email.email' => "L'adresse email est invalide."]
        );

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', "Un lien de réinitialisation a été envoyé à votre adresse email.");
        }

        return back()->withErrors(['email' => "Aucun compte ne correspond à cette adresse email."]);
    }
}
