<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResultatAnnuel extends Model
{
    use HasFactory;

    protected $table = 'resultats_annuels';

    protected $fillable = [

        'etudiant_id',
        'niveau_id',
        'annee_academique',

        'moyenne_s1',
        'moyenne_s2',
        'moyenne_annuelle',

        'decision',
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
