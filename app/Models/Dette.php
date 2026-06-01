<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dette extends Model
{
    use HasFactory;

    protected $fillable = [

        'etudiant_id',
        'matiere_id',
        'module_id',
        'semestre_id',
        'statut',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }
}
