<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgressionEtudiant extends Model
{
    use HasFactory;

    protected $fillable = [

        'etudiant_id',
        'niveau_id',
        'annee_academique',
        'statut',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
}
