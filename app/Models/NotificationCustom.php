<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationCustom extends Model
{
    use HasFactory;

    protected $table = 'notifications_custom';

    protected $fillable = [
        'user_id',
        'titre',
        'message',
        'type',
        'lu',
        'lu_at',
    ];

    protected $casts = [
        'lu' => 'boolean',
        'lu_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeNonLu($query)
    {
        return $query->where('lu', false);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function marquerCommeLu(): void
    {
        $this->update([
            'lu' => true,
            'lu_at' => now(),
        ]);
    }

    public function getIconeAttribute(): string
    {
        return match ($this->type) {
            'cours_programme' => 'fa-calendar-plus',
            'changement_salle' => 'fa-exchange-alt',
            'annulation_cours' => 'fa-times-circle',
            'examen_programme' => 'fa-file-alt',
            'resultats_publies' => 'fa-chart-bar',
            'rattrapage_propose' => 'fa-redo',
            'decision_reprise' => 'fa-exclamation-triangle',
            'progression_niveau' => 'fa-arrow-up',
            default => 'fa-bell',
        };
    }

    public function getCouleurAttribute(): string
    {
        return match ($this->type) {
            'cours_programme' => 'blue',
            'changement_salle' => 'orange',
            'annulation_cours' => 'red',
            'examen_programme' => 'purple',
            'resultats_publies' => 'green',
            'rattrapage_propose' => 'yellow',
            'decision_reprise' => 'red',
            'progression_niveau' => 'green',
            default => 'gray',
        };
    }
}
