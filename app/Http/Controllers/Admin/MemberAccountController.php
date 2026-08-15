<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MemberPasswordReset;
use App\Models\Adhesion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Gestion des comptes adhérents depuis le back-office : liste, génération d'un
 * compte, régénération du mot de passe, visibilité au trombinoscope.
 *
 * Un administrateur voit la liste et peut agir dessus ; seul le super admin
 * voit les mots de passe en clair et peut les exporter.
 */
class MemberAccountController extends Controller
{
    public function index(Request $request)
    {
        $recherche = trim((string) $request->input('q'));

        $comptes = User::with('adhesion.period')
            ->whereNotNull('adhesion_id')
            // Jointure pour trier/filtrer sur l'adhérent tout en paginant côté SQL.
            ->join('adhesions', 'adhesions.id', '=', 'users.adhesion_id')
            ->select('users.*')
            ->when($recherche !== '', function ($q) use ($recherche) {
                $terme = '%' . Str::lower($recherche) . '%';
                $q->where(function ($sub) use ($terme) {
                    $sub->whereRaw('LOWER(users.email) LIKE ?', [$terme])
                        ->orWhereRaw('LOWER(adhesions.nom) LIKE ?', [$terme])
                        ->orWhereRaw('LOWER(adhesions.prenom) LIKE ?', [$terme]);
                });
            })
            ->orderBy('adhesions.nom')
            ->orderBy('adhesions.prenom')
            ->paginate(25, pageName: 'page')
            ->withQueryString();

        // Adhésions payées qui n'ont pas encore de compte : on peut leur en générer un.
        $sansCompte = Adhesion::whereNull('user_id')
            ->where('statut', 'payee')
            ->orderBy('nom')->orderBy('prenom')
            ->paginate(25, pageName: 'sans')
            ->withQueryString();

        // Compté sur l'ensemble des comptes, pas seulement la page courante.
        $illisibles = User::whereNotNull('adhesion_id')->whereNull('password_encrypted')->count();

        $peutVoirMotsDePasse = $request->user()->canSeeMemberPasswords();

        return view('admin.members.index', compact(
            'comptes', 'sansCompte', 'recherche', 'illisibles', 'peutVoirMotsDePasse'
        ));
    }

    /** Génère un compte + un mot de passe pour une adhésion qui n'en a pas. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'adhesion_id' => 'required|exists:adhesions,id',
        ]);

        $adhesion = Adhesion::findOrFail($validated['adhesion_id']);

        if ($adhesion->user_id) {
            return back()->with('error', 'Cet adhérent a déjà un compte.');
        }

        $email = Str::lower(trim($adhesion->email));

        // Le compte existe peut-être déjà côté back-office : dans ce cas on ne
        // crée rien, on rattache simplement l'adhésion à l'identité existante.
        $existant = User::withTrashed()->whereRaw('LOWER(email) = ?', [$email])->first();

        if ($existant) {
            $existant->adhesion_id = $adhesion->id;
            $existant->save();
            $adhesion->update(['user_id' => $existant->id]);

            return back()->with('success', "L'adhésion de {$adhesion->prenom} {$adhesion->nom} a été rattachée au compte existant ({$email}) — son mot de passe habituel donne accès à l'espace adhérent.");
        }

        $plain = User::motDePasseTemporaire();

        $user = new User([
            'name'              => trim($adhesion->prenom . ' ' . $adhesion->nom),
            'email'             => $email,
            'adhesion_id'       => $adhesion->id,
            'role'              => User::ROLE_MEMBER,
            'is_active'         => true,
            'show_in_directory' => true,
        ]);
        $user->setPasswordAndCopy($plain);
        $user->save();

        $adhesion->update(['user_id' => $user->id]);

        $envoye = $this->envoyerIdentifiants($user, $plain, nouveauCompte: true);

        return back()->with('success', "Compte créé pour {$adhesion->prenom} {$adhesion->nom} — mot de passe : {$plain}"
            . ($envoye ? ' (envoyé par email)' : " — l'email n'a pas pu être envoyé, transmettez-le manuellement."));
    }

    /** Régénère le mot de passe d'un compte adhérent et le lui envoie par email. */
    public function resetPassword(Request $request, User $user)
    {
        // Un administrateur ne réinitialise pas le mot de passe d'un compte
        // plus haut placé que lui : cela passe par la section « Comptes ».
        abort_unless($request->user()->canManage($user), 403);

        $plain = User::motDePasseTemporaire();

        $user->setPasswordAndCopy($plain);
        $user->save();

        $nom    = $user->displayName();
        $envoye = $this->envoyerIdentifiants($user, $plain);

        return back()->with('success', "Nouveau mot de passe de {$nom} : {$plain}"
            . ($envoye ? ' (envoyé par email)' : " — l'email n'a pas pu être envoyé, transmettez-le manuellement."));
    }

    /** Envoie les identifiants à l'adhérent. Un échec d'envoi ne bloque pas l'action. */
    private function envoyerIdentifiants(User $user, string $plain, bool $nouveauCompte = false): bool
    {
        try {
            Mail::to($user->email)->send(new MemberPasswordReset($user, $plain, $nouveauCompte));

            return true;
        } catch (\Throwable $e) {
            Log::error('[MemberPasswordReset] envoi échoué pour ' . $user->email . ' : ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Confie (ou retire) un rôle de back-office à un adhérent.
     *
     * C'est le chemin normal pour nommer un adhérent gestionnaire de contenu
     * ou administrateur : son compte existe déjà, on ne fait qu'élargir ce
     * qu'il peut faire. Un admin ne peut conférer que le rôle de gestionnaire ;
     * seul le super admin nomme des administrateurs.
     */
    public function updateRole(Request $request, User $user)
    {
        $acteur = $request->user();

        abort_unless($acteur->canManage($user), 403);

        // On ne modifie pas son propre rôle : c'est le meilleur moyen de se
        // verrouiller hors du back-office.
        if ($acteur->is($user)) {
            return back()->with('error', "Vous ne pouvez pas modifier votre propre rôle.");
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in($acteur->assignableRolesForMember())],
        ], ['role.in' => "Ce rôle dépasse vos droits d'attribution."]);

        $user->role = $validated['role'];
        $user->save();

        return back()->with('success', sprintf(
            '%s est désormais : %s.',
            $user->displayName(),
            $user->roleLabel(),
        ));
    }

    /** Affiche ou masque l'adhérent dans le trombinoscope. */
    public function toggleDirectory(User $user)
    {
        $user->show_in_directory = ! $user->show_in_directory;
        $user->save();

        return back()->with('success', $user->show_in_directory
            ? 'Adhérent affiché dans le trombinoscope.'
            : 'Adhérent retiré du trombinoscope.');
    }

    /** Export CSV des identifiants (mot de passe inclus, super admin uniquement). */
    public function export(Request $request)
    {
        abort_unless($request->user()->canSeeMemberPasswords(), 403);

        $comptes = User::with('adhesion')->whereNotNull('adhesion_id')->get()
            ->sortBy(fn ($m) => Str::lower($m->adhesion?->nom . ' ' . $m->adhesion?->prenom));

        $nomFichier = 'identifiants-adherents-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($comptes) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 pour Excel
            fputcsv($out, ['Nom', 'Prénom', 'Email', 'Mot de passe', 'Rôle', 'Trombinoscope'], ';');

            foreach ($comptes as $m) {
                fputcsv($out, [
                    $m->adhesion?->nom,
                    $m->adhesion?->prenom,
                    $m->email,
                    $m->getDecryptedPassword() ?? 'non lisible — régénérer',
                    $m->roleLabel(),
                    $m->show_in_directory ? 'Oui' : 'Non',
                ], ';');
            }

            fclose($out);
        }, $nomFichier, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
