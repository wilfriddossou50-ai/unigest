<?php

namespace App\Services;

class ResultatAnnuelService
{
    /**
     * Calcul de la moyenne annuelle à partir de deux notes de semestres
     */
    public function calculerMoyenne($s1, $s2)
    {
        $note1 = is_numeric($s1) ? $s1 : null;
        $note2 = is_numeric($s2) ? $s2 : null;

        if ($note1 === null && $note2 === null) {
            return 0;
        }

        if ($note1 === null) {
            return round($note2, 2);
        }

        if ($note2 === null) {
            return round($note1, 2);
        }

        return round(($note1 + $note2) / 2, 2);
    }

    /**
     * Décision académique basée sur la moyenne et le niveau
     */
    public function decision($moyenne, $niveau)
    {
        // Extraction sécurisée du nom du niveau
        $codeNiveau = is_object($niveau) ? ($niveau->code ?? $niveau->libelle) : $niveau;
        $nettoye = strtoupper(str_replace(' ', '', $codeNiveau));

        if ($moyenne >= 10) {
            // Si c'est la dernière année de licence, il est diplômé, sinon il est juste admis au niveau supérieur
            if ($nettoye === 'L3' || $nettoye === 'LICENCE3') {
                return 'diplome';
            }
            return 'admis';
        }

        if ($moyenne >= 5) {
            return 'ajourne';
        }

        return 'redoublant';
    }
}
