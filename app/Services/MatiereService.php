<?php

namespace App\Services;

class MatiereService
{
    public function estValidee(string $statut): bool
    {
        return in_array($statut, [
            'validee',
            'rattrapage_valide',
            'reprise_valide'
        ]);
    }

    public function estEnDette(string $statut): bool
    {
        return in_array($statut, [
            'rattrapage',
            'reprise',
            'echec'
        ]);
    }
}
