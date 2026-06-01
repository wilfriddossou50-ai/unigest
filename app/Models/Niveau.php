<?php

namespace App\Models;

use App\Models\Etudiant;
use App\Models\ResultatAnnuel;
use App\Models\ProgressionEtudiant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Niveau extends Model
{
    use HasFactory;

    /**
     * Champs autorisés
     */
    protected $fillable = [

        'code',
        'libelle',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Un niveau possède plusieurs étudiants
     */
    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }

    /**
     * Semestres rattachés à ce niveau
     */
    public function semestres()
    {
        return $this->hasMany(Semestre::class);
    }

    /**
     * Résultats annuels liés au niveau
     */
    public function resultatsAnnuels()
    {
        return $this->hasMany(ResultatAnnuel::class);
    }

    /**
     * Progressions où ce niveau est niveau actuel
     */
    public function progressionsActuelles()
    {
        return $this->hasMany(
            ProgressionEtudiant::class,
            'niveau_id_actuel'
        );
    }

    /**
     * Progressions où ce niveau est niveau suivant
     */
    public function progressionsSuivantes()
    {
        return $this->hasMany(
            ProgressionEtudiant::class,
            'niveau_id_suivant'
        );
    }
}
