<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResultatSemestre extends Model
{
    use HasFactory;

    protected $table = 'resultats_semestre';

    protected $fillable = [

        'etudiant_id',
        'semestre_id',
        'moyenne',
        'decision',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }
}
