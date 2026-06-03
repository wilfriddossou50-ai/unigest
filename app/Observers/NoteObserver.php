<?php

namespace App\Observers;

use App\Models\Matiere;
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
        $this->recalculerResultatSemestre($note);
        $this->detteService->verifierDette($note);
    }

    public function deleted(Note $note)
    {
        $this->recalculerResultatSemestre($note);
        $this->detteService->supprimerDette($note);
    }

    protected function recalculerResultatSemestre(Note $note): void
    {
        $matiere = $note->relationLoaded('matiere')
            ? $note->matiere
            : Matiere::with('module')->find($note->matiere_id);

        if (! $matiere || ! $matiere->module) {
            return;
        }

        $semestreId = $matiere->module->semestre_id;
        $etudiantId = $note->etudiant_id;

        $moyenne = $this->service->calculerMoyenne($etudiantId, $semestreId);
        $decision = $this->service->decision($etudiantId, $semestreId);

        ResultatSemestre::updateOrCreate(
            ['etudiant_id' => $etudiantId, 'semestre_id' => $semestreId],
            ['moyenne' => $moyenne, 'decision' => $decision]
        );
    }
}
