<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\MemberPasswordReset;
use App\Models\Adhesion;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Gestion super admin des comptes « espace adhérent » : consultation du mot de
 * passe, génération d'un compte, régénération. Aucun email n'est envoyé —
 * l'identifiant est transmis de la main à la main par l'association.
 */
class MemberAccountController extends Controller
{
    public function index(Request $request)
    {
        $recherche = trim((string) $request->input('q'));

        $comptes = Member::with('adhesion.period')
            // Jointure pour trier/filtrer sur l'adhérent tout en paginant côté SQL.
            ->join('adhesions', 'adhesions.id', '=', 'members.adhesion_id')
            ->select('members.*')
            ->when($recherche !== '', function ($q) use ($recherche) {
                $terme = '%' . Str::lower($recherche) . '%';
                $q->where(function ($sub) use ($terme) {
                    $sub->whereRaw('LOWER(members.email) LIKE ?', [$terme])
                        ->orWhereRaw('LOWER(adhesions.nom) LIKE ?', [$terme])
                        ->orWhereRaw('LOWER(adhesions.prenom) LIKE ?', [$terme]);
                });
            })
            ->orderBy('adhesions.nom')
            ->orderBy('adhesions.prenom')
            ->paginate(25, pageName: 'page')
            ->withQueryString();

        // Adhésions payées qui n'ont pas encore de compte : on peut leur en générer un.
        $sansCompte = Adhesion::whereDoesntHave('member')
            ->where('statut', 'payee')
            ->orderBy('nom')->orderBy('prenom')
            ->paginate(25, pageName: 'sans')
            ->withQueryString();

        // Compté sur l'ensemble des comptes, pas seulement la page courante.
        $illisibles = Member::whereNull('password_encrypted')->count();

        return view('admin.members.index', compact('comptes', 'sansCompte', 'recherche', 'illisibles'));
    }

    /** Génère un compte + un mot de passe pour une adhésion qui n'en a pas. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'adhesion_id' => 'required|exists:adhesions,id',
        ]);

        $adhesion = Adhesion::findOrFail($validated['adhesion_id']);

        if ($adhesion->member()->exists()) {
            return back()->with('error', 'Cet adhérent a déjà un compte.');
        }

        $email = Str::lower(trim($adhesion->email));

        if (Member::withTrashed()->where('email', $email)->exists()) {
            return back()->with('error', "Un compte existe déjà avec l'adresse {$email}.");
        }

        $plain = Member::motDePasseTemporaire();

        $member = new Member();
        $member->adhesion_id = $adhesion->id;
        $member->email = $email;
        $member->show_in_directory = true;
        $member->setPasswordAndCopy($plain);
        $member->save();

        $envoye = $this->envoyerIdentifiants($member, $plain, nouveauCompte: true);

        return back()->with('success', "Compte créé pour {$adhesion->prenom} {$adhesion->nom} — mot de passe : {$plain}"
            . ($envoye ? ' (envoyé par email)' : " — l'email n'a pas pu être envoyé, transmettez-le manuellement."));
    }

    /** Régénère le mot de passe d'un compte adhérent et le lui envoie par email. */
    public function resetPassword(Member $member)
    {
        $plain = Member::motDePasseTemporaire();

        $member->setPasswordAndCopy($plain);
        $member->save();

        $nom = $member->adhesion ? $member->adhesion->prenom . ' ' . $member->adhesion->nom : $member->email;
        $envoye = $this->envoyerIdentifiants($member, $plain);

        return back()->with('success', "Nouveau mot de passe de {$nom} : {$plain}"
            . ($envoye ? ' (envoyé par email)' : " — l'email n'a pas pu être envoyé, transmettez-le manuellement."));
    }

    /** Envoie les identifiants à l'adhérent. Un échec d'envoi ne bloque pas l'action. */
    private function envoyerIdentifiants(Member $member, string $plain, bool $nouveauCompte = false): bool
    {
        try {
            Mail::to($member->email)->send(new MemberPasswordReset($member, $plain, $nouveauCompte));

            return true;
        } catch (\Throwable $e) {
            Log::error('[MemberPasswordReset] envoi échoué pour ' . $member->email . ' : ' . $e->getMessage());

            return false;
        }
    }

    /** Affiche ou masque l'adhérent dans le trombinoscope. */
    public function toggleDirectory(Member $member)
    {
        $member->show_in_directory = ! $member->show_in_directory;
        $member->save();

        return back()->with('success', $member->show_in_directory
            ? 'Adhérent affiché dans le trombinoscope.'
            : 'Adhérent retiré du trombinoscope.');
    }

    /** Export CSV des identifiants (mot de passe inclus quand il est lisible). */
    public function export()
    {
        $comptes = Member::with('adhesion')->get()
            ->sortBy(fn ($m) => Str::lower($m->adhesion?->nom . ' ' . $m->adhesion?->prenom));

        $nomFichier = 'identifiants-adherents-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($comptes) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8 pour Excel
            fputcsv($out, ['Nom', 'Prénom', 'Email', 'Mot de passe', 'Trombinoscope'], ';');

            foreach ($comptes as $m) {
                fputcsv($out, [
                    $m->adhesion?->nom,
                    $m->adhesion?->prenom,
                    $m->email,
                    $m->getDecryptedPassword() ?? 'non lisible — régénérer',
                    $m->show_in_directory ? 'Oui' : 'Non',
                ], ';');
            }

            fclose($out);
        }, $nomFichier, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
