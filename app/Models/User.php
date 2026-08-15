<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/**
 * Compte unique du site : une personne, un email, un mot de passe.
 *
 * Un compte peut être adhérent (rattaché à une adhésion), administrateur, ou
 * les deux. Le rôle décide de ce qui est accessible dans le back-office ;
 * `adhesion_id` décide de ce qui est accessible dans l'espace adhérent.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /** Rôles disponibles, du moins au plus étendu. */
    public const ROLE_MEMBER      = 'membre';
    public const ROLE_CONTENT     = 'gestionnaire_contenu';
    public const ROLE_ADMIN       = 'admin';
    public const ROLE_SUPER_ADMIN = 'super_admin';

    public const ROLES = [
        self::ROLE_MEMBER      => 'Adhérent',
        self::ROLE_CONTENT     => 'Gestionnaire de contenu',
        self::ROLE_ADMIN       => 'Administrateur',
        self::ROLE_SUPER_ADMIN => 'Super administrateur',
    ];

    protected $fillable = [
        'name', 'email', 'password', 'password_encrypted', 'role', 'is_active',
        'adhesion_id', 'show_in_directory', 'restore_token',
    ];

    protected $hidden = ['password', 'password_encrypted', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'show_in_directory' => 'boolean',
        ];
    }

    // ─── Relations ────────────────────────────────────────────────────────────

    /** Adhésion en cours (la plus récente rattachée au compte). */
    public function adhesion(): BelongsTo
    {
        return $this->belongsTo(Adhesion::class);
    }

    /** Historique complet : une adhésion par saison. */
    public function adhesions(): HasMany
    {
        return $this->hasMany(Adhesion::class)->orderByDesc('created_at');
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

    /** Compte sans aucun accès au back-office. */
    public function isMemberOnly(): bool
    {
        return ! $this->canManageContent();
    }

    /** Le compte est-il rattaché à une adhésion ? */
    public function isMember(): bool
    {
        return $this->adhesion_id !== null;
    }

    /** Adhérent à jour de cotisation ? */
    public function isAdherent(): bool
    {
        return $this->adhesion?->isAdherent() ?? false;
    }

    public function roleLabel(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    /** Nom affiché : celui de l'adhésion s'il existe, sinon le nom du compte. */
    public function displayName(): string
    {
        $adhesion = $this->adhesion;

        if ($adhesion) {
            $nom = trim($adhesion->prenom . ' ' . $adhesion->nom);

            if ($nom !== '') {
                return $nom;
            }
        }

        return $this->name ?: $this->email;
    }

    // ─── Permissions ──────────────────────────────────────────────────────────

    /** Accès à la partie « contenu » du back-office. */
    public function canManageContent(): bool
    {
        return in_array($this->role, [self::ROLE_CONTENT, self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN], true);
    }

    /** Le compte ouvre-t-il une porte vers le back-office ? */
    public function canAccessBackOffice(): bool
    {
        return $this->canManageContent();
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

    /**
     * Peut voir les mots de passe en clair des comptes adhérents.
     * Réservé au super admin : un admin voit la liste, pas les secrets.
     */
    public function canSeeMemberPasswords(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Rôles attribuables à la création d'un compte de back-office.
     * « Adhérent » n'y figure pas : un adhérent naît d'une adhésion, pas de
     * cet écran. Il reste attribuable en promotion (voir assignableRolesForMember).
     */
    public function assignableRoles(): array
    {
        if ($this->isSuperAdmin()) {
            return [self::ROLE_CONTENT, self::ROLE_ADMIN, self::ROLE_SUPER_ADMIN];
        }
        if ($this->isAdmin()) {
            // Un admin nomme des gestionnaires et des admins, jamais un super
            // admin : le rang qui peut tout reprendre reste hors de portée.
            return [self::ROLE_CONTENT, self::ROLE_ADMIN];
        }

        return [];
    }

    /**
     * Rôles attribuables à un adhérent existant : son rôle actuel d'adhérent,
     * plus ceux que l'on a le droit de conférer. C'est ainsi qu'un adhérent
     * devient gestionnaire de contenu ou administrateur — et redevient
     * simple adhérent.
     */
    public function assignableRolesForMember(): array
    {
        return $this->assignableRoles() ? array_merge([self::ROLE_MEMBER], $this->assignableRoles()) : [];
    }

    /** Peut-il gérer (voir/modifier/supprimer) le compte cible ? */
    public function canManage(User $target): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        if (! $this->isAdmin()) {
            return false;
        }

        // Un admin gère ses pairs et les rangs en dessous, jamais un super
        // admin. Pouvoir nommer un administrateur sans pouvoir le rétrograder
        // ensuite créerait une porte à sens unique.
        return ! $target->isSuperAdmin();
    }

    // ─── Mot de passe (chiffrement réversible pour affichage super admin) ──────

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

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
