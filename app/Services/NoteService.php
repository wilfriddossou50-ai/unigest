<?php

namespace App\Services;

class NoteService
{
    /**
     * Calcul de la moyenne matière : (CC × 0.4) + (Partiel × 0.6)
     */
    public function calculer(?float $cc, ?float $examen): ?float
    {
        if ($cc === null || $examen === null) {
            return null;
        }
        return round(($cc * 0.4) + ($examen * 0.6), 2);
    }

    /**
     * Déterminer le statut initial d'une matière
     * Validée si moyenne >= 10, sinon accès automatique au rattrapage
     */
    public function statut(?float $note): string
    {
        if ($note === null) return 'en_cours';
        if ($note >= 10) return 'validee';
        return 'rattrapage';
    }

    /**
     * Appliquer le rattrapage :
     * - Note rattrapage = 10/20 FIXE (ignore CC et Partiel)
     * - Si note >= 10 → matière validée avec note_finale = 10
     * - Sinon → en reprise
     * - Rattrapage ILLIMITÉ
     */
    public function appliquerRattrapage(float $noteRattrapage): array
    {
        if ($noteRattrapage >= 10) {
            return [
                'statut' => 'rattrapage_valide',
                'note_finale' => 10.00,
            ];
        }

        return [
            'statut' => 'reprise',
            'note_finale' => null,
        ];
    }

    /**
     * Appliquer la reprise :
     * - Note reprise = 10/20 FIXE
     * - Si note >= 10 → matière validée avec note_finale = 10
     * - Sinon → échec
     */
    public function appliquerReprise(float $noteReprise): array
    {
        if ($noteReprise >= 10) {
            return [
                'statut' => 'reprise_valide',
                'note_finale' => 10.00,
            ];
        }

        return [
            'statut' => 'echec',
            'note_finale' => $noteReprise,
        ];
    }

    /**
     * Vérifier si une matière est validée (moyenne >= 10/20)
     */
    public function estValidee(?float $noteFinale): bool
    {
        return $noteFinale !== null && $noteFinale >= 10;
    }
}
