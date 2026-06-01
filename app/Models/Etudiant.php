<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Etudiant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filiere_id',
        'niveau_id',
        'numero_etudiant',
        'created_by_admin',
        'statut',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
    public function dettes()
    {
        return $this->hasMany(Dette::class);
    }
    public function resultatsSemestre()
    {
        return $this->hasMany(ResultatSemestre::class);
    }
    public function resultatsAnnuel()
    {
        return $this->hasMany(ResultatAnnuel::class);
    }
    public function resultats()
    {
        return $this->hasMany(ResultatSemestre::class);
    }
}
