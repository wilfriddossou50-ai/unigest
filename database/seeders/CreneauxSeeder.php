<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CreneauHoraire;

class CreneauxSeeder extends Seeder
{
    public function run()
    {
        $slots = [
            ['08:00:00', '10:00:00'],
            ['15:00:00', '17:00:00'],
            ['16:00:00', '19:00:00'],
            ['12:00:00', '13:30:00'],
            ['18:00:00', '20:00:00'],
        ];

        foreach ($slots as [$debut, $fin]) {
            CreneauHoraire::updateOrCreate(
                ['heure_debut' => $debut, 'heure_fin' => $fin],
                ['est_actif' => true]
            );
        }
    }
}
