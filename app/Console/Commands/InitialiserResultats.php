<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InitialiserResultats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialiser-resultats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = app(\App\Services\ResultatSemestreService::class);
        $etudiants = \App\Models\Etudiant::all();
        $semestres = \App\Models\Semestre::all();

        foreach ($etudiants as $etudiant) {
            foreach ($semestres as $semestre) {
                $moyenne = $service->calculerMoyenne($etudiant->id, $semestre->id);
                $decision = $service->decision($etudiant->id, $semestre->id);

                \App\Models\ResultatSemestre::updateOrCreate(
                    ['etudiant_id' => $etudiant->id, 'semestre_id' => $semestre->id],
                    ['moyenne' => $moyenne, 'decision' => $decision]
                );
            }
        }
        $this->info('Tous les résultats ont été initialisés avec succès !');
    }
}
