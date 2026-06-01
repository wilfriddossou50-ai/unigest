<?php

namespace App\Services;

class ProgressionService
{
    /**
     * Détermine le statut de progression ('passage' ou 'redoublement') basé sur la décision annuelle
     */
    public function determinerStatutFromDecision(string $decision): string
    {
        // Si l'étudiant est admis ou diplômé, il passe au niveau supérieur
        if (in_array(strtolower($decision), ['admis', 'diplome', 'passage'])) {
            return 'passage';
        }

        // Dans tous les autres cas (ex: redoublant, exclu...), il redouble
        return 'redoublement';
    }
}
