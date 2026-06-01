<?php

namespace App\Models;

use App\Models\Matiere;
use App\Models\EmploiDuTemps;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Professeur extends Model
{
    use HasFactory;

    /**
     * Champs autorisés
     */
    protected $fillable = [

        'code',

        'nom',
        'prenom',

        'sexe',

        'date_naissance',

        'email',
        'telephone',

        'grade',

        'specialite',

        'statut',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'date_naissance' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Un professeur peut enseigner plusieurs matières
     */
    public function matieres()
    {
        return $this->belongsToMany(
            Matiere::class,
            'professeur_matiere'
        )
        ->withTimestamps();
    }

    /**
     * Un professeur peut avoir plusieurs emplois du temps
     */
    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Nom complet
     */
    public function getNomCompletAttribute(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }
}