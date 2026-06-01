<?php

namespace App\Models;

use App\Models\Module;
use App\Models\Professeur;
use App\Models\Note;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matiere extends Model
{
    use HasFactory;

    /**
     * Champs autorisés
     */
    protected $fillable = [

        'module_id',
        'code',
        'libelle',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Une matière appartient à un module
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Une matière peut être enseignée par plusieurs professeurs
     */
    public function professeurs()
    {
        return $this->belongsToMany(
            Professeur::class,
            'professeur_matiere'
        )->withTimestamps();
    }

    /**
     * Une matière possède plusieurs notes
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
