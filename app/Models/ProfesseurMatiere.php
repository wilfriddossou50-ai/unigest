<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfesseurMatiere extends Model
{
    use HasFactory;

    protected $table = 'professeur_matiere';

    protected $fillable = [
        'professeur_id',
        'matiere_id',
    ];

    public function professeur()
    {
        return $this->belongsTo(Professeur::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}
