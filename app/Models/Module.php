<?php

namespace App\Models;

use App\Models\Semestre;
use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Professeur;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;

    /**
     * Champs autorisés
     */
    protected $fillable = [

        'semestre_id',
        'filiere_id',

        'code',
        'libelle',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Un module appartient à un semestre
     */
    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

    /**
     * Un module appartient à une filière
     */
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    /**
     * Un module possède plusieurs matières
     */
    public function matieres()
    {
        return $this->hasMany(Matiere::class);
    }

    /**
     * Professeurs liés via matières
     */
    public function professeurs()
    {
        return $this->belongsToMany(
            Professeur::class,
            'professeur_matiere'
        )->withTimestamps();
    }
}
