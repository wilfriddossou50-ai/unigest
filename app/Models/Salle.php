<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_salle',
        'statut',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function programmeCours()
    {
        return $this->hasMany(ProgrammeCours::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActif($query)
    {
        return $query->where('statut', 'Actif');
    }

    public function scopeDisponible($query)
    {
        return $query->where('statut', 'Actif');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function estDisponible(): bool
    {
        return $this->statut === 'Actif';
    }
}
