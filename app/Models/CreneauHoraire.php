<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreneauHoraire extends Model
{
    use HasFactory;

    protected $table = 'creneaux_horaires';

    protected $fillable = [
        'heure_debut',
        'heure_fin',
        'est_actif',
    ];

    protected $casts = [
        'est_actif' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function programmeCours()
    {
        return $this->hasMany(ProgrammeCours::class, 'creneau_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActif($query)
    {
        return $query->where('est_actif', true);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function getLibelleAttribute(): string
    {
        return substr($this->heure_debut, 0, 5) . ' - ' . substr($this->heure_fin, 0, 5);
    }
}
