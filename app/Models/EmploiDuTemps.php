<?php

namespace App\Models;

use App\Models\Niveau;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmploiDuTemps extends Model
{
    use HasFactory;

    protected $table = 'emploi_du_temps';

    protected $fillable = [

        'matiere_id',
        'professeur_id',
        'semestre_id',
        'niveau_id',

        'salle',
        'jour',
        'heure_debut',
        'heure_fin',

        'code_seance',
    ];

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function professeur()
    {
        return $this->belongsTo(Professeur::class);
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }
}
