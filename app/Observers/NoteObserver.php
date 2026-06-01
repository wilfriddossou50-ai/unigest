<?php

namespace App\Observers;

use App\Models\Note;
use App\Services\ResultatSemestreService;
use App\Models\ResultatSemestre;
use App\Services\DetteService;

class NoteObserver
{
    protected $service;
    protected DetteService $detteService;

    public function __construct(ResultatSemestreService $service, DetteService $detteService)
    {
        $this->service = $service;
        $this->detteService = $detteService;
    }

    public function saved(Note $note)
    {
        // On récupère le semestre de la matière associée
        $matiere = $note->matiere;
        if ($matiere && $matiere->module) {
            $semestreId = $matiere->module->semestre_id;
            $etudiantId = $note->etudiant_id;

            // Recalcul automatique
            $moyenne = $this->service->calculerMoyenne($etudiantId, $semestreId);
            $decision = $this->service->decision($etudiantId, $semestreId);

            ResultatSemestre::updateOrCreate(
                ['etudiant_id' => $etudiantId, 'semestre_id' => $semestreId],
                ['moyenne' => $moyenne, 'decision' => $decision]
            );
        }

        $this->detteService->verifierDette($note);
    }
}
