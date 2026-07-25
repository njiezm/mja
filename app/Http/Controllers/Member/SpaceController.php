<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Mail\AccountDeleted;
use App\Support\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SpaceController extends Controller
{
    public function dashboard()
    {
        $member = Auth::guard('member')->user();
        $adhesion = $member->adhesion;

        return view('member.dashboard', compact('member', 'adhesion'));
    }

    public function trombinoscope()
    {
        $me = Auth::guard('member')->user();

        // Adhérents ayant un compte et ayant accepté de figurer au trombinoscope.
        $membres = \App\Models\Member::where('show_in_directory', true)
            ->with('adhesion:id,prenom,nom,photo')
            ->get()
            ->filter(fn ($m) => $m->adhesion !== null)
            ->sortBy(fn ($m) => $m->adhesion->prenom)
            ->values();

        return view('member.trombinoscope', compact('membres', 'me'));
    }

    public function editProfile()
    {
        $member = Auth::guard('member')->user();
        $adhesion = $member->adhesion;

        return view('member.profile', compact('member', 'adhesion'));
    }

    public function updateProfile(Request $request)
    {
        $member = Auth::guard('member')->user();
        $adhesion = $member->adhesion;

        $validated = $request->validate([
            'prenom'          => 'required|string|max:100',
            'nom'             => 'required|string|max:100',
            'email'           => ['required', 'email', 'max:150', Rule::unique('members', 'email')->ignore($member->id)],
            'profession'      => 'required|string|max:150',
            'telephone'       => 'required|string|max:40',
            'adresse_postale' => 'required|string',
            'taille_tshirt'   => 'required|in:S,M,L,XL,2XL,3XL',
            'problemes_sante' => 'nullable|string',
            'urgence_contact' => 'required|string|max:300',
            'photo'           => 'nullable|image|max:5120',
            'password'        => 'nullable|string|min:8|confirmed',
            'show_in_directory' => 'boolean',
        ], [
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        // Photo
        if ($request->hasFile('photo')) {
            if ($adhesion->photo) {
                Storage::disk('public')->delete($adhesion->photo);
            }
            $adhesion->photo = $request->file('photo')->store('adhesions/photos', 'public');
        }

        // Informations d'adhésion
        $adhesion->fill([
            'prenom'          => $validated['prenom'],
            'nom'             => $validated['nom'],
            'email'           => $validated['email'],
            'profession'      => $validated['profession'],
            'telephone'       => $validated['telephone'],
            'adresse_postale' => $validated['adresse_postale'],
            'taille_tshirt'   => $validated['taille_tshirt'],
            'problemes_sante' => $validated['problemes_sante'] ?? null,
            'urgence_contact' => $validated['urgence_contact'],
        ])->save();

        // Compte membre
        $member->email = $validated['email'];
        $member->show_in_directory = $request->boolean('show_in_directory');
        if (! empty($validated['password'])) {
            $member->password = $validated['password'];
        }
        $member->save();

        return redirect()->route('member.dashboard')->with('success', 'Vos informations ont été mises à jour.');
    }

    public function destroy(Request $request)
    {
        $member = Auth::guard('member')->user();

        // Jeton de restauration + suppression douce (période de grâce de 30 jours).
        $member->restore_token = Token::generate(48);
        $member->save();
        $member->delete(); // soft delete

        $purgeDate = now()->addDays(30);
        try {
            Mail::to($member->email)->send(new AccountDeleted(
                $member,
                route('member.account.restore', $member->restore_token),
                $purgeDate->locale('fr')->isoFormat('D MMMM Y'),
            ));
        } catch (\Throwable $e) {
            Log::error('Mail suppression compte échoué : ' . $e->getMessage());
        }

        Auth::guard('member')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Votre compte a été supprimé. Vous avez 30 jours pour le restaurer via le lien envoyé par email.');
    }
}
