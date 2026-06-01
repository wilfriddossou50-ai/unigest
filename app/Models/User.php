<?php

namespace App\Models;

use App\Models\Etudiant;
use App\Models\Inscription;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @mixin \Eloquent
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Inscription[] $inscriptions
 * @property-read \App\Models\Etudiant|null $etudiant
 * @method \Illuminate\Database\Eloquent\Relations\HasMany inscriptions()
 * @method \Illuminate\Database\Eloquent\Relations\HasOne etudiant()
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Champs autorisés
     */
    protected $fillable = [
        'nom',
        'prenom',
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Champs masqués
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Un utilisateur peut avoir un profil étudiant
     */
    public function etudiant(): HasOne
    {
        return $this->hasOne(Etudiant::class);
    }

    /**
     * Un utilisateur peut faire plusieurs inscriptions
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    public function latestInscription(): ?Inscription
    {
        return $this->inscriptions()->latest()->first();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEtudiant(): bool
    {
        return $this->role === 'etudiant';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function getNameAttribute(): string
    {
        return trim(($this->nom ?? '') . ' ' . ($this->prenom ?? ''));
    }

    public function setNameAttribute($value): void
    {
        $parts = preg_split('/\s+/', trim($value));

        $this->attributes['nom'] = $parts[0] ?? '';
        $this->attributes['prenom'] = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '';
    }

    public function hasRefusedInscription(): bool
    {
        return $this->latestInscription()?->statut === 'refusee';
    }

    public function canAccessStudentSpace(): bool
    {
        if (! $this->isEtudiant()) {
            return false;
        }

        if ($this->hasRefusedInscription()) {
            return false;
        }

        // Admin-created students have a profile immediately, without an inscription record.
        if ($this->etudiant()->exists()) {
            return true;
        }

        return $this->latestInscription()?->statut === 'approuvee';
    }

    /**
     * Nom complet
     */
    public function getNomCompletAttribute(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }
}
