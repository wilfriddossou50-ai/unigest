<?php

namespace App\Models;

use App\Models\Etudiant;
use App\Models\Module;
use App\Models\Inscription;
use App\Models\Semestre; // IMPORT AJOUTÉ ICI

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Filiere extends Model
{
    use HasFactory;

    /**
     * Champs autorisés
     */
    protected $fillable = [
        'code',
        'libelle',
        'description',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Une filière possède plusieurs étudiants
     */
    public function etudiants()
    {
        return $this->hasMany(Etudiant::class);
    }

    /**
     * Une filière possède plusieurs modules
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    /**
     * Une filière possède plusieurs semestres
     * (Relation ajoutée pour corriger l'erreur de votre Dashboard)
     */
    /**
     * Une filière possède plusieurs semestres à travers ses modules
     */
    /**
     * Une filière possède plusieurs semestres à travers les niveaux
     */
    public function semestres()
    {
        // Les semestres sont reliés à la filière via les modules
        return $this->belongsToMany(Semestre::class, 'modules', 'filiere_id', 'semestre_id')->distinct();
    }
    /**
     * Une filière possède plusieurs inscriptions
     */
    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }
}
