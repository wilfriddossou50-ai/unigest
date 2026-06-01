<?php

namespace App\Services;

use App\Models\Matiere;
use App\Models\Note;

class ModuleService
{
    /**
     * Calcule la moyenne d'un étudiant pour un module donné.
     * (Moyenne arithmétique des notes finales des matières du module)
     */
    public function calculerMoyenne($etudiantId, $moduleId)
    {
        // 1. Récupérer les IDs de toutes les matières du module
        $matiereIds = Matiere::where('module_id', $moduleId)->pluck('id');

        if ($matiereIds->isEmpty()) {
            return null; // Aucun module ou aucune matière configurée
        }

        // 2. Récupérer toutes les notes finales de l'étudiant pour ces matières
        $notes = Note::where('etudiant_id', $etudiantId)
            ->whereIn('matiere_id', $matiereIds)
            ->whereNotNull('note_finale')
            ->pluck('note_finale');

        // Si l'étudiant n'a encore aucune note saisie dans ce module
        if ($notes->isEmpty()) {
            return null;
        }

        // 3. Calculer et retourner la moyenne arrondie à 2 décimales
        return round($notes->avg(), 2);
    }

    /**
     * Vérifie si un module est validé par l'étudiant.
     * RÈGLE : Validé SI ET SEULEMENT SI chaque matière a une note >= 10.
     */
    public function estValide($etudiantId, $moduleId)
    {
        // 1. Récupérer toutes les matières du module
        $matieres = Matiere::where('module_id', $moduleId)->get();

        // S'il n'y a pas de matières, le module ne peut pas être validé
        if ($matieres->isEmpty()) {
            return false;
        }

        // 2. Vérifier chaque matière une par une
        foreach ($matieres as $matiere) {
            $note = Note::where('etudiant_id', $etudiantId)
                ->where('matiere_id', $matiere->id)
                ->first();

            // RÈGLE STRICTE (Pas de compensation) :
            // Si une note est absente OU si la note finale est inférieure à 10,
            // le module est invalidé immédiatement (Dette).
            if (!$note || $note->note_finale === null || $note->note_finale < 10) {
                return false;
            }
        }

        // Si la boucle se termine sans avoir rencontré de note < 10, le module est validé
        return true;
    }
}
