<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /** Rôles disponibles. */
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN       = 'admin';
    public const ROLE_CONTENT     = 'gestionnaire_contenu';

    public const ROLES = [
        self::ROLE_SUPER_ADMIN => 'Super administrateur',
        self::ROLE_ADMIN       => 'Administrateur',
        self::ROLE_CONTENT     => 'Gestionnaire de contenu',
    ];

    protected $fillable = ['name', 'email', 'password', 'password_encrypted', 'role', 'is_active'];
    protected $hidden   = ['password', 'password_encrypted', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ─── Rôles ────────────────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /** Admin « complet » = admin ou super admin. */
    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN], true);
    }

    public function isContentManager(): bool
    {
        return $this->role === self::ROLE_CONTENT;
    }

    public function roleLabel(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    // ─── Permissions ────────────────────────────────────────────────────────────

    /** Accès à la partie « contenu » du back-office (les 3 rôles). */
    public function canManageContent(): bool
    {
        return in_array($this->role, [self::ROLE_CONTENT, self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN], true);
    }

    /** Accès à la gestion (adhésions, messages) : admin et super admin. */
    public function canAccessManagement(): bool
    {
        return $this->isAdmin();
    }

    /** Peut ouvrir la section « comptes » : admin (gestionnaires seulement) et super admin (tous). */
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    /** Peut créer/gérer des comptes admin et super admin : super admin uniquement. */
    public function canManageAdmins(): bool
    {
        return $this->isSuperAdmin();
    }

    /** Rôles que cet utilisateur a le droit d'attribuer. */
    public function assignableRoles(): array
    {
        if ($this->isSuperAdmin()) {
            return [self::ROLE_CONTENT, self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN];
        }
        if ($this->isAdmin()) {
            return [self::ROLE_CONTENT];
        }
        return [];
    }

    /** Peut-il gérer (voir/modifier/supprimer) le compte cible ? */
    public function canManage(User $target): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        // Un admin ne gère que les gestionnaires de contenu.
        return $this->isAdmin() && $target->isContentManager();
    }

    // ─── Mot de passe (chiffrement réversible pour affichage super admin) ──────

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
            return null;
        }
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
