<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /** Clés dont la valeur est chiffrée au repos (secrets). */
    public const ENCRYPTED = ['stripe_secret_key', 'stripe_webhook_secret'];

    /** Cache mémoire par requête. */
    protected static array $cache = [];

    public static function get(string $key, $default = null)
    {
        if (! array_key_exists($key, static::$cache)) {
            $row = static::query()->where('key', $key)->first();
            $val = $row?->value;

            if ($val !== null && in_array($key, self::ENCRYPTED, true)) {
                try {
                    $val = Crypt::decryptString($val);
                } catch (\Throwable $e) {
                    $val = null;
                }
            }
            static::$cache[$key] = $val;
        }

        return static::$cache[$key] ?? $default;
    }

    public static function set(string $key, $value): void
    {
        $stored = ($value !== null && $value !== '' && in_array($key, self::ENCRYPTED, true))
            ? Crypt::encryptString($value)
            : $value;

        static::updateOrCreate(['key' => $key], ['value' => $stored]);
        static::$cache[$key] = $value;
    }

    public static function has(string $key): bool
    {
        return static::get($key) !== null && static::get($key) !== '';
    }

    /**
     * Liste des adresses email destinataires des notifications admin.
     * Repli sur l'adresse de contact historique si aucune n'est configurée.
     */
    public static function notificationEmails(): array
    {
        $raw = (string) static::get('notification_emails', '');
        $emails = preg_split('/[\s,;]+/', $raw, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $emails = array_filter($emails, fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL));
        $emails = array_values(array_unique($emails));

        return $emails ?: ['contact@njiezm.fr'];
    }
}
