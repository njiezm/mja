<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Member extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = ['adhesion_id', 'email', 'password', 'password_encrypted', 'show_in_directory', 'restore_token'];
    protected $hidden   = ['password', 'password_encrypted', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'          => 'hashed',
            'show_in_directory' => 'boolean',
        ];
    }

    public function adhesion(): BelongsTo
    {
        return $this->belongsTo(Adhesion::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\MemberResetPassword($token));
    }

    // ─── Mot de passe (copie chiffrée réversible pour le super admin) ──────────

    /** Mot de passe temporaire lisible, au format MJA-XXXX-0000. */
    public static function motDePasseTemporaire(): string
    {
        return 'MJA-' . Str::upper(Str::random(4)) . '-' . random_int(1000, 9999);
    }

    /** Définit le mot de passe (haché) + conserve une copie chiffrée réversible. */
    public function setPasswordAndCopy(string $plain): void
    {
        $this->password = $plain; // haché via le cast 'hashed'
        $this->password_encrypted = Crypt::encryptString($plain);
    }

    /** Mot de passe en clair (déchiffré) ou null si indisponible. */
    public function getDecryptedPassword(): ?string
    {
        if (empty($this->password_encrypted)) {
            return null;
        }

        try {
            return Crypt::decryptString($this->password_encrypted);
        } catch (\Throwable $e) {
            // Clé APP_KEY différente de celle du chiffrement : illisible.
            return null;
        }
    }
}
