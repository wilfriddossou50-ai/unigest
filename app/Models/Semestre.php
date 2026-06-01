<?php

namespace App\Models;

use App\Models\Module;
use App\Models\Dette;
use App\Models\EmploiDuTemps;
use App\Models\ResultatSemestre;
use App\Models\Niveau; // 👈 Importation du modèle Niveau

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // 👈 Importation pour le typage propre

class Semestre extends Model
{
    use HasFactory;

    /**
     * Champs autorisés
     */
    protected $fillable = [
        'code',
        'libelle',
        'niveau_id', // 👈 Ajouté pour autoriser l'assignation de masse
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * NOUVEAU : Un semestre appartient à un niveau
     */
    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class, 'niveau_id');
    }

    /**
     * Un semestre possède plusieurs modules
     */
    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    /**
     * Un semestre possède plusieurs dettes
     */
    public function dettes()
    {
        return $this->hasMany(Dette::class);
    }

    /**
     * Un semestre possède plusieurs emplois du temps
     */
    public function emploisDuTemps()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }

    /**
     * Résultats académiques du semestre
     */
    public function resultatsSemestre()
    {
        return $this->hasMany(ResultatSemestre::class);
    }
}
