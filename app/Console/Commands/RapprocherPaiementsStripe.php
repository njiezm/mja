<?php

namespace App\Console\Commands;

use App\Models\Adhesion;
use App\Services\StripeService;
use Illuminate\Console\Command;

/**
 * Rapproche les paiements Stripe des adhésions enregistrées.
 *
 * Trois cas sortent de ce rapprochement :
 *
 *   rattaché      le paiement porte déjà son adhésion en base. Rien à faire.
 *
 *   à reprendre   le lien n'existe que dans les metadata Stripe, écrites
 *                 après coup. L'adhésion existe, elle est simplement
 *                 dépourvue de la colonne. `--appliquer` la renseigne.
 *
 *   orphelin      de l'argent a été encaissé sans qu'aucune adhésion ne lui
 *                 corresponde. C'est le symptôme signalé : la carte est
 *                 débitée dans le formulaire, avant l'enregistrement, et tout
 *                 abandon entre les deux laisse un paiement sans demande.
 *                 Ceux-là se traitent à la main, avec l'email du payeur.
 *
 * À passer avant la migration qui pose l'index unique : si deux adhésions
 * partagent un même paiement, il faut le savoir avant que la base refuse
 * l'index.
 *
 * Usage :
 *   php artisan adhesions:rapprocher-stripe
 *   php artisan adhesions:rapprocher-stripe --jours=120 --appliquer
 */
class RapprocherPaiementsStripe extends Command
{
    protected $signature = 'adhesions:rapprocher-stripe
                            {--jours=90 : profondeur de l\'analyse, en jours}
                            {--appliquer : écrit en base les liens retrouvés dans les metadata}';

    protected $description = "Rapproche les paiements Stripe des adhésions et liste les encaissements orphelins";

    public function handle(): int
    {
        if (! StripeService::enabled()) {
            $this->error('Stripe n\'est pas configuré sur cette installation.');

            return self::FAILURE;
        }

        $jours = max(1, (int) $this->option('jours'));
        $depuis = now()->subDays($jours)->getTimestamp();
        $appliquer = (bool) $this->option('appliquer');

        $this->info("Paiements Stripe aboutis depuis {$jours} jours :");

        $intents = StripeService::listerPaymentIntents($depuis);

        if ($intents === null) {
            $this->error('Stripe n\'a pas répondu. Rien n\'a été modifié.');

            return self::FAILURE;
        }

        $rattaches = $repris = 0;
        $orphelins = [];
        $doublons = [];

        foreach ($intents as $intent) {
            if (($intent['status'] ?? null) !== 'succeeded') {
                continue;
            }

            $id = $intent['id'];
            $meta = $intent['metadata'] ?? [];

            // on ne s'occupe que des cotisations, pas des dons
            if (($meta['type'] ?? null) !== 'adhesion' && ! isset($meta['adhesion_id'])) {
                continue;
            }

            $parColonne = Adhesion::where('stripe_payment_intent_id', $id)->get();

            if ($parColonne->count() > 1) {
                $doublons[$id] = $parColonne->pluck('id')->all();
                continue;
            }

            if ($parColonne->count() === 1) {
                $rattaches++;
                continue;
            }

            $adhesion = isset($meta['adhesion_id']) ? Adhesion::find($meta['adhesion_id']) : null;

            if ($adhesion) {
                if ($appliquer) {
                    // whereNull : si deux paiements revendiquent la même
                    // adhésion, le second ne doit pas écraser le premier.
                    $ecrit = Adhesion::whereKey($adhesion->id)
                        ->whereNull('stripe_payment_intent_id')
                        ->update(['stripe_payment_intent_id' => $id,
                                  'paye_le' => $adhesion->paye_le ?? now()]);

                    if (! $ecrit) {
                        $this->warn("  adhésion #{$adhesion->id} porte déjà un autre paiement — {$id} laissé de côté");
                        continue;
                    }
                }
                $repris++;
                continue;
            }

            $orphelins[] = [
                'id'      => $id,
                'montant' => number_format(((int) ($intent['amount_received'] ?? 0)) / 100, 2, ',', ' '),
                'date'    => date('d/m/Y H:i', (int) ($intent['created'] ?? 0)),
                'email'   => $intent['receipt_email']
                    ?? ($intent['charges']['data'][0]['billing_details']['email'] ?? '—'),
            ];
        }

        $this->newLine();
        $this->line("  rattachés en base     : {$rattaches}");
        $this->line($appliquer
            ? "  liens écrits          : {$repris}"
            : "  liens à reprendre     : {$repris}  (relancer avec --appliquer)");
        $this->line('  encaissements orphelins : ' . count($orphelins));

        if ($doublons) {
            $this->newLine();
            $this->error('ATTENTION — des paiements sont rattachés à plusieurs adhésions.');
            $this->error("L'index unique de la migration refusera de se poser tant que ce n'est pas tranché.");
            foreach ($doublons as $id => $ids) {
                $this->line("  {$id} → adhésions " . implode(', ', $ids));
            }
        }

        if ($orphelins) {
            $this->newLine();
            $this->warn('Encaissements sans adhésion — à rattacher à la main :');
            $this->table(['Référence Stripe', 'Montant', 'Date', 'Email du payeur'],
                array_map('array_values', $orphelins));
        }

        return $doublons ? self::FAILURE : self::SUCCESS;
    }
}
