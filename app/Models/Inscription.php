<?php

namespace App\Models;

use App\Models\Niveau;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'filiere_id',
        'niveau_id',
        'nom',
        'prenom',
        'sexe',
        'date_naissance',
        'statut',
        'motif_refus',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
