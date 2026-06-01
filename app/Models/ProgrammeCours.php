<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgrammeCours extends Model
{
    use HasFactory;

    protected $table = 'programme_cours';

    protected $fillable = [
        'matiere_id',
        'professeur_id',
        'salle_id',
        'creneau_id',
        'jour_semaine',
        'date_programme',
        'semestre_id',
        'niveau_id',
        'filiere_id',
        'type_cours',
        'duree_heures',
        'statut',
        'notes',
    ];

    protected $casts = [
        'date_programme' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function professeur()
    {
        return $this->belongsTo(Professeur::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function creneau()
    {
        return $this->belongsTo(CreneauHoraire::class, 'creneau_id');
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeProgramme($query)
    {
        return $query->where('statut', 'Programmé');
    }

    public function scopeSemaine($query, $dateDebut)
    {
        $dateFin = \Carbon\Carbon::parse($dateDebut)->addDays(5);
        return $query->whereBetween('date_programme', [$dateDebut, $dateFin]);
    }

    public function scopeJour($query, $jour)
    {
        return $query->where('jour_semaine', $jour);
    }
}
