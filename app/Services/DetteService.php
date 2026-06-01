<?php

namespace App\Services;

use App\Models\Note;
use App\Models\Dette;

class DetteService
{
    /**
     * Créer dette si matière non validée
     */
    public function verifierDette(Note $note)
    {
        if ($note->statut === 'echec' || $note->statut === 'reprise') {
            Dette::updateOrCreate(
                [
                    'etudiant_id' => $note->etudiant_id,
                    'matiere_id' => $note->matiere_id,
                ],
                [
                    'module_id' => $note->matiere->module_id,
                    'semestre_id' => $note->matiere->module->semestre_id,
                    'statut' => 'en_cours',
                ]
            );

            return;
        }

        if (in_array($note->statut, ['validee', 'rattrapage_valide', 'reprise_valide'], true)) {
            Dette::where([
                'etudiant_id' => $note->etudiant_id,
                'matiere_id' => $note->matiere_id,
            ])->update([
                'statut' => 'levee',
            ]);
        }
    }

    /**
     * Lever dette manuellement et injecter la note de validation (10/20)
     */
    public function leverDette(Dette $dette)
    {
        // 1. On met à jour le statut de la dette administrative
        $dette->update([
            'statut' => 'levee'
        ]);

        // 2. On injecte une validation académique simple pour régulariser le parcours
        Note::updateOrCreate(
            [
                'etudiant_id' => $dette->etudiant_id,
                'matiere_id'  => $dette->matiere_id,
            ],
            [
                'note_calculee' => 10.00,
                'note_finale'   => 10.00,
                'statut'        => 'validee',
            ]
        );
    }
}
