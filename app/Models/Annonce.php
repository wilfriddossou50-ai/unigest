<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'contenu',
        'type',
        'actif',
        'date_publication',
        'date_expiration',
        'filiere_id',
        'niveau_id',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'date_publication' => 'date',
        'date_expiration' => 'date',
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }
}
