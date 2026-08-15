<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Mail\AccountDeleted;
use App\Models\Adhesion;
use App\Models\AdhesionPeriod;
use App\Models\User;
use App\Support\Telephone;
use App\Support\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SpaceController extends Controller
{
    /** Compte connecté. Le nom `$member` est conservé côté vues. */
    private function membre(): User
    {
        return Auth::user();
    }

    public function dashboard()
    {
        $member   = $this->membre();
        $adhesion = $member->adhesion;

        // Un compte sans adhésion (administrateur, par exemple) garde son espace :
        // « Mon espace » et « Admin » mènent à deux endroits distincts. La vue
        // s'adapte et propose d'adhérer plutôt que d'afficher une fiche vide.
        return view('member.dashboard', [
            'member'        => $member,
            'adhesion'      => $adhesion,
            'historique'    => $member->adhesions()->with('period')->get(),
            'periode'       => AdhesionPeriod::current(),
            'aRenouveler'   => $this->doitRenouveler($adhesion),
        ]);
    }

    /**
     * L'adhésion couvre-t-elle encore la saison en cours ?
     * Si la saison courante a changé depuis la dernière adhésion, il est temps
     * de renouveler — c'est ce qui déclenche l'encart dans l'espace adhérent.
     */
    private function doitRenouveler(?Adhesion $adhesion): bool
    {
        if (! $adhesion || ! $adhesion->isAdherent()) {
            return false;
        }

        $courante = AdhesionPeriod::current();

        return $courante !== null && $adhesion->period_id !== $courante->id;
    }

    public function trombinoscope()
    {
        $me = $this->membre();

        // Le trombinoscope reste réservé aux adhérents et à l'équipe du site.
        abort_unless($me->isMember() || $me->canAccessBackOffice(), 403);

        // Comptes rattachés à une adhésion et ayant accepté d'y figurer —
        // administrateurs compris, puisqu'ils sont aussi adhérents.
        $membres = User::where('show_in_directory', true)
            ->whereNotNull('adhesion_id')
            ->where('is_active', true)
            ->with('adhesion:id,prenom,nom,photo,reseaux_sociaux')
            ->get()
            ->filter(fn ($m) => $m->adhesion !== null)
            ->sortBy(fn ($m) => $m->adhesion->prenom)
            ->values();

        return view('member.trombinoscope', compact('membres', 'me'));
    }

    public function card()
    {
        $member   = $this->membre();
        $adhesion = $member->adhesion;

        abort_unless($adhesion && $adhesion->isAdherent(), 403, "L'attestation est réservée aux adhérents à jour de cotisation.");

        return view('member.card', compact('member', 'adhesion'));
    }

    public function editProfile()
    {
        $member   = $this->membre();
        $adhesion = $member->adhesion;

        abort_unless($adhesion !== null, 404);

        [$indicatif, $numero] = Telephone::separer($adhesion->telephone);

        return view('member.profile', compact('member', 'adhesion', 'indicatif', 'numero'));
    }

    public function updateProfile(Request $request)
    {
        $member   = $this->membre();
        $adhesion = $member->adhesion;

        abort_unless($adhesion !== null, 404);

        $validated = $request->validate([
            'prenom'            => 'required|string|max:100',
            'nom'               => 'required|string|max:100',
            'email'             => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($member->id)],
            'profession'        => 'required|string|max:150',
            'indicatif'         => 'nullable|string|max:6',
            'telephone'         => 'required|string|max:40',
            'reseaux_sociaux'   => 'nullable|array',
            'reseaux_sociaux.*' => 'nullable|string|max:150',
            'taille_tshirt'     => 'required|in:S,M,L,XL,2XL,3XL',
            'problemes_sante'   => 'nullable|string',
            'urgence_contact'   => 'required|string|max:300',
            'photo'             => 'nullable|image|max:5120',
            'password'          => 'nullable|string|min:8|confirmed',
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
            'telephone'       => Telephone::complet($request->input('indicatif'), $validated['telephone']),
            'reseaux_sociaux' => $this->reseauxNettoyes($request),
            'taille_tshirt'   => $validated['taille_tshirt'],
            'problemes_sante' => $validated['problemes_sante'] ?? null,
            'urgence_contact' => $validated['urgence_contact'],
        ])->save();

        // Compte
        $member->email = $validated['email'];
        $member->name = trim($validated['prenom'] . ' ' . $validated['nom']);
        $member->show_in_directory = $request->boolean('show_in_directory');
        if (! empty($validated['password'])) {
            $member->setPasswordAndCopy($validated['password']);
        }
        $member->save();

        return redirect()->route('member.dashboard')->with('success', 'Vos informations ont été mises à jour.');
    }

    /** @return array<string, string>|null */
    private function reseauxNettoyes(Request $request): ?array
    {
        $connus = array_keys(Adhesion::RESEAUX);
        $propre = [];

        foreach ((array) $request->input('reseaux_sociaux', []) as $cle => $valeur) {
            $valeur = trim((string) $valeur);

            if ($valeur !== '' && in_array($cle, $connus, true)) {
                $propre[$cle] = ltrim($valeur, '@');
            }
        }

        return $propre ?: null;
    }

    public function destroy(Request $request)
    {
        $member = $this->membre();

        // Un compte qui administre le site ne peut pas se supprimer depuis
        // l'espace adhérent : la suppression passe par un autre administrateur.
        if ($member->canAccessBackOffice()) {
            return back()->with('error', "Votre compte dispose d'accès à l'administration : demandez à un autre administrateur de le supprimer.");
        }

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

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Votre compte a été supprimé. Vous avez 30 jours pour le restaurer via le lien envoyé par email.');
    }
}
