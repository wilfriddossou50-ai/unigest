<?php

namespace App\Services;

use App\Models\NotificationCustom;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\ProgrammeCours;

class NotificationService
{
    /**
     * Créer une notification pour un utilisateur
     */
    public function creer(int $userId, string $titre, string $message, string $type = 'general'): NotificationCustom
    {
        return NotificationCustom::create([
            'user_id' => $userId,
            'titre' => $titre,
            'message' => $message,
            'type' => $type,
        ]);
    }

    /**
     * Notifier tous les étudiants d'un groupe (filière + niveau + semestre)
     */
    public function notifierGroupe(?int $filiereId, ?int $niveauId, string $titre, string $message, string $type = 'general'): int
    {
        $query = Etudiant::with('user');

        if ($filiereId) {
            $query->where('filiere_id', $filiereId);
        }
        if ($niveauId) {
            $query->where('niveau_id', $niveauId);
        }

        $etudiants = $query->get();
        $count = 0;

        foreach ($etudiants as $etudiant) {
            if ($etudiant->user) {
                $this->creer($etudiant->user_id, $titre, $message, $type);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Notification : Cours programmé
     */
    public function notifierCoursProgramme(ProgrammeCours $cours): int
    {
        $matiere = $cours->matiere->libelle ?? 'N/A';
        $prof = $cours->professeur->nom_complet ?? 'N/A';
        $salle = $cours->salle->nom_salle ?? 'N/A';
        $creneau = $cours->creneau->libelle ?? 'N/A';
        $date = $cours->date_programme->format('d/m/Y');

        $message = "Votre cours a été programmé :\n"
            . "- Matière: {$matiere}\n"
            . "- Enseignant: {$prof}\n"
            . "- Date: {$cours->jour_semaine} {$date}\n"
            . "- Horaire: {$creneau}\n"
            . "- Salle: {$salle}";

        return $this->notifierGroupe(
            $cours->filiere_id,
            $cours->niveau_id,
            "Cours programmé : {$matiere}",
            $message,
            'cours_programme'
        );
    }

    /**
     * Notification : Changement de salle
     */
    public function notifierChangementSalle(ProgrammeCours $cours, string $ancienneSalle): int
    {
        $matiere = $cours->matiere->libelle ?? 'N/A';
        $nouvelleSalle = $cours->salle->nom_salle ?? 'N/A';
        $date = $cours->date_programme->format('d/m/Y');
        $creneau = $cours->creneau->libelle ?? 'N/A';

        $message = "⚠️ CHANGEMENT DE SALLE :\n"
            . "Cours de \"{$matiere}\" :\n"
            . "- Ancienne salle: {$ancienneSalle}\n"
            . "- Nouvelle salle: {$nouvelleSalle}\n"
            . "- Date/Heure: {$cours->jour_semaine} {$date} à {$creneau}";

        return $this->notifierGroupe(
            $cours->filiere_id,
            $cours->niveau_id,
            "Changement de salle : {$matiere}",
            $message,
            'changement_salle'
        );
    }

    /**
     * Notification : Annulation de cours
     */
    public function notifierAnnulationCours(ProgrammeCours $cours): int
    {
        $matiere = $cours->matiere->libelle ?? 'N/A';
        $date = $cours->date_programme->format('d/m/Y');
        $creneau = $cours->creneau->libelle ?? 'N/A';

        $message = "❌ COURS ANNULÉ :\n"
            . "- Matière: {$matiere}\n"
            . "- Date: {$cours->jour_semaine} {$date}\n"
            . "- Horaire: {$creneau}";

        return $this->notifierGroupe(
            $cours->filiere_id,
            $cours->niveau_id,
            "Cours annulé : {$matiere}",
            $message,
            'annulation_cours'
        );
    }

    /**
     * Notification : Rattrapage proposé
     */
    public function notifierRattrapage(int $userId, string $matiere): NotificationCustom
    {
        return $this->creer(
            $userId,
            "Rattrapage proposé : {$matiere}",
            "Vous êtes convoqué au rattrapage pour la matière \"{$matiere}\". Consultez votre espace pour plus de détails.",
            'rattrapage_propose'
        );
    }

    /**
     * Notification : Résultats publiés
     */
    public function notifierResultats(int $userId, string $matiere, string $statut): NotificationCustom
    {
        return $this->creer(
            $userId,
            "Résultats publiés : {$matiere}",
            "Les résultats de \"{$matiere}\" ont été publiés. Statut : {$statut}.",
            'resultats_publies'
        );
    }

    /**
     * Notification : Décision de reprise
     */
    public function notifierReprise(int $userId, string $matiere): NotificationCustom
    {
        return $this->creer(
            $userId,
            "Décision de reprise : {$matiere}",
            "Vous êtes placé en reprise pour la matière \"{$matiere}\". L'administration programmera les dates.",
            'decision_reprise'
        );
    }

    /**
     * Notification : Progression de niveau
     */
    public function notifierProgression(int $userId, string $ancienNiveau, string $nouveauNiveau): NotificationCustom
    {
        return $this->creer(
            $userId,
            "Passage au niveau supérieur !",
            "Félicitations ! Vous passez de {$ancienNiveau} à {$nouveauNiveau}.",
            'progression_niveau'
        );
    }

    /**
     * Obtenir les notifications non lues d'un utilisateur
     */
    public function getNonLues(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return NotificationCustom::where('user_id', $userId)
            ->nonLu()
            ->recent()
            ->get();
    }

    /**
     * Compter les notifications non lues
     */
    public function compterNonLues(int $userId): int
    {
        return NotificationCustom::where('user_id', $userId)->nonLu()->count();
    }
}
