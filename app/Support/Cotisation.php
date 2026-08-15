<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Source unique de vérité sur le montant de la cotisation.
 *
 * Le paiement par carte passe par Stripe, qui prélève une commission sur
 * chaque transaction. Pour que l'association encaisse bien le montant net de
 * la cotisation, cette commission est ajoutée au prix affiché et réglée par le
 * payeur — c'est le rôle de `fraisCarte()` / `montantCarte()`.
 */
class Cotisation
{
    /** Valeurs par défaut : tarification Stripe cartes européennes (1,5 % + 0,25 €). */
    private const FRAIS_POURCENT_DEFAUT = 1.5;
    private const FRAIS_FIXE_DEFAUT     = 0.25;

    /** Les frais sont arrondis au multiple supérieur de ce pas (marge de sécurité). */
    private const PAS_ARRONDI_DEFAUT    = 0.05;

    /** Cotisation nette souhaitée par l'association, en euros. */
    public static function montant(): float
    {
        return round((float) Setting::get('cotisation_amount', 20), 2);
    }

    /** Les frais de transaction sont-ils répercutés sur le payeur ? */
    public static function fraisRepercutes(): bool
    {
        // Activé par défaut : sans cela, l'association reçoit moins que la cotisation.
        $valeur = Setting::get('stripe_fee_passthrough');

        return $valeur === null ? true : (bool) $valeur;
    }

    /**
     * Frais de transaction à la charge du payeur, en euros.
     *
     * On résout `net = brut - (pourcent × brut + fixe)` pour trouver le brut qui
     * laisse exactement la cotisation à l'association, puis on arrondit au pas
     * supérieur : un arrondi vers le bas ferait encaisser moins que prévu.
     */
    public static function fraisCarte(): float
    {
        if (! self::fraisRepercutes()) {
            return 0.0;
        }

        $pourcent = (float) Setting::get('stripe_fee_percent', self::FRAIS_POURCENT_DEFAUT) / 100;
        $fixe     = (float) Setting::get('stripe_fee_fixed', self::FRAIS_FIXE_DEFAUT);
        $pas      = (float) Setting::get('stripe_fee_round_to', self::PAS_ARRONDI_DEFAUT);

        // Un pourcentage ≥ 100 % rendrait l'équation insoluble : on ignore le réglage.
        if ($pourcent < 0 || $pourcent >= 1) {
            $pourcent = self::FRAIS_POURCENT_DEFAUT / 100;
        }

        $net   = self::montant();
        $brut  = ($net + $fixe) / (1 - $pourcent);
        $frais = $brut - $net;

        if ($pas > 0) {
            $frais = ceil($frais / $pas) * $pas;
        }

        return round($frais, 2);
    }

    /** Montant total débité par carte : cotisation + frais de transaction. */
    public static function montantCarte(): float
    {
        return round(self::montant() + self::fraisCarte(), 2);
    }

    /** Montant total carte, en centimes (unité attendue par Stripe). */
    public static function montantCarteCents(): int
    {
        return (int) round(self::montantCarte() * 100);
    }

    // ─── Formatage ────────────────────────────────────────────────────────────

    /** Formate un montant à la française : « 20 € », « 20,60 € ». */
    public static function format(float $montant): string
    {
        $texte = number_format($montant, 2, ',', ' ');
        // Un montant rond s'écrit sans décimales ; sinon on les garde toutes les deux
        // (« 20,6 € » ne se lit pas comme un prix).
        $texte = preg_replace('/,00$/', '', $texte);

        return $texte . ' €';
    }

    /** Cotisation nette, formatée. */
    public static function formatee(): string
    {
        return self::format(self::montant());
    }

    /** Frais de transaction, formatés. */
    public static function fraisFormates(): string
    {
        return self::format(self::fraisCarte());
    }

    /** Montant total carte, formaté. */
    public static function carteFormatee(): string
    {
        return self::format(self::montantCarte());
    }
}
