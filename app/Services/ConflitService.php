<?php

namespace App\Services;

use App\Models\ProgrammeCours;
use Carbon\Carbon;

class ConflitService
{
    /**
     * Vérifier tous les conflits potentiels pour un cours à programmer
     */
    public function detecterConflits(array $data, ?int $excludeId = null): array
    {
        $conflits = [];
        // On s'assure d'avoir une date propre dès le départ
        $date = Carbon::parse($data['date_programme'])->format('Y-m-d');

        // Conflit 1 : Même salle, même date, même créneau
        if (!empty($data['salle_id'])) {
            $conflit = $this->conflitSalle($data['salle_id'], $date, $data['creneau_id'], $excludeId);
            if ($conflit) $conflits[] = $conflit;
        }

        // Conflit 2 : Même enseignant, même date, même créneau
        $conflit = $this->conflitProfesseur($data['professeur_id'], $date, $data['creneau_id'], $excludeId);
        if ($conflit) $conflits[] = $conflit;

        // Conflit 3 : Même groupe, même date, même créneau
        $conflit = $this->conflitGroupe(
            $data['filiere_id'] ?? null,
            $data['niveau_id'] ?? null,
            $data['semestre_id'] ?? null,
            $date,
            $data['creneau_id'],
            $excludeId
        );
        if ($conflit) $conflits[] = $conflit;

        return $conflits;
    }

    protected function conflitSalle(int $salleId, string $date, int $creneauId, ?int $excludeId): ?array
    {
        $query = ProgrammeCours::where('salle_id', $salleId)
            ->whereDate('date_programme', $date)
            ->where('creneau_id', $creneauId)
            ->where('statut', '!=', 'Annulé');

        if ($excludeId) $query->where('id', '!=', $excludeId);

        $existing = $query->with(['matiere', 'salle', 'creneau'])->first();

        return $existing ? [
            'type' => 'salle',
            'message' => "La salle \"{$existing->salle->nom_salle}\" est déjà réservée le {$existing->date_programme->format('d/m/Y')} à {$existing->creneau->libelle} pour \"{$existing->matiere->libelle}\"."
        ] : null;
    }

    protected function conflitProfesseur(int $professeurId, string $date, int $creneauId, ?int $excludeId): ?array
    {
        $query = ProgrammeCours::where('professeur_id', $professeurId)
            ->whereDate('date_programme', $date)
            ->where('creneau_id', $creneauId)
            ->where('statut', '!=', 'Annulé');

        if ($excludeId) $query->where('id', '!=', $excludeId);

        $existing = $query->with(['matiere', 'professeur', 'creneau'])->first();

        return $existing ? [
            'type' => 'professeur',
            'message' => "Le professeur \"{$existing->professeur->nom_complet}\" a déjà un cours le {$existing->date_programme->format('d/m/Y')} à {$existing->creneau->libelle} pour \"{$existing->matiere->libelle}\"."
        ] : null;
    }

    protected function conflitGroupe(?int $filiereId, ?int $niveauId, ?int $semestreId, string $date, int $creneauId, ?int $excludeId): ?array
    {
        if (!$filiereId || !$niveauId) return null;

        $query = ProgrammeCours::where('filiere_id', $filiereId)
            ->where('niveau_id', $niveauId)
            ->whereDate('date_programme', $date)
            ->where('creneau_id', $creneauId)
            ->where('statut', '!=', 'Annulé');

        if ($semestreId) $query->where('semestre_id', $semestreId);
        if ($excludeId) $query->where('id', '!=', $excludeId);

        $existing = $query->with(['matiere', 'filiere', 'niveau', 'creneau'])->first();

        return $existing ? [
            'type' => 'groupe',
            'message' => "Le groupe ({$existing->filiere->libelle} {$existing->niveau->libelle}) a déjà un cours le {$existing->date_programme->format('d/m/Y')} à {$existing->creneau->libelle} pour \"{$existing->matiere->libelle}\"."
        ] : null;
    }
}
