<?php

namespace App\Services;

class ResultatAnnuelService
{
    /**
     * Calcul de la moyenne annuelle à partir d'une liste de moyennes de semestres.
     * Les valeurs non numériques sont ignorées pour garder un calcul dynamique.
     */
    public function calculerMoyenne(...$notes)
    {
        if (count($notes) === 1 && is_array($notes[0])) {
            $notes = $notes[0];
        }

        $notesValides = collect($notes)
            ->filter(fn ($note) => is_numeric($note))
            ->map(fn ($note) => (float) $note)
            ->values();

        if ($notesValides->isEmpty()) {
            return 0;
        }

        return round($notesValides->avg(), 2);
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
