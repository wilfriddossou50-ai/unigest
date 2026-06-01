<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [

        'etudiant_id',
        'matiere_id',

        'cc',
        'examen',

        'rattrapage',
        'reprise',

        'note_calculee',
        'note_finale',

        'statut',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}
