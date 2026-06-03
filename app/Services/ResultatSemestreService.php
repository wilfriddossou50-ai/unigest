<?php

namespace App\Services;

use App\Models\Etudiant;
use App\Models\Module;
use App\Models\Note;

class ResultatSemestreService
{
    protected $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    /**
     * Calcul de la moyenne du semestre.
     * Règle métier: moyenne des moyennes des modules du semestre.
     * Les modules sans note ne sont pas comptés pour permettre un calcul
     * dynamique: dès qu'un module est saisi, il entre automatiquement dans la moyenne.
     */
    public function calculerMoyenne($etudiantId, $semestreId)
    {
        $modules = $this->modulesDuSemestrePourEtudiant($etudiantId, $semestreId);

        if ($modules->isEmpty()) {
            return 0;
        }

        $totalMoyennesModules = 0;
        $modulesEvalues = 0;

        foreach ($modules as $module) {
            $moyenneModule = $this->moduleService->calculerMoyenne($etudiantId, $module->id);
            if ($moyenneModule === null) {
                continue;
            }

            $totalMoyennesModules += $moyenneModule;
            $modulesEvalues++;
        }

        if ($modulesEvalues === 0) {
            return 0;
        }

        return round($totalMoyennesModules / $modulesEvalues, 2);
    }

    /**
     * Vérifier si TOUS les modules du semestre sont validés
     * Module validé = TOUTES ses matières validées
     */
    public function tousModulesValides($etudiantId, $semestreId): bool
    {
        $modules = Module::where('semestre_id', $semestreId)->with('matieres')->get();

        foreach ($modules as $module) {
            if (!$this->moduleEstValide($etudiantId, $module->id)) {
                return false;
            }
        }

        return $modules->isNotEmpty();
    }

    /**
     * Vérifier si un module est validé (TOUTES ses matières validées)
     */
    public function moduleEstValide($etudiantId, $moduleId): bool
    {
        $module = Module::with('matieres')->find($moduleId);
        if (!$module || $module->matieres->isEmpty()) {
            return false;
        }

        foreach ($module->matieres as $matiere) {
            $note = Note::where('etudiant_id', $etudiantId)
                ->where('matiere_id', $matiere->id)
                ->first();

            if (!$note) return false;

            $statutsValides = ['validee', 'rattrapage_valide', 'reprise_valide'];
            if (!in_array($note->statut, $statutsValides)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Vérifier si un module a au moins une matière en rattrapage
     */
    public function moduleEnAttente($etudiantId, $moduleId): bool
    {
        $module = Module::with('matieres')->find($moduleId);
        if (!$module) return false;

        foreach ($module->matieres as $matiere) {
            $note = Note::where('etudiant_id', $etudiantId)
                ->where('matiere_id', $matiere->id)
                ->first();

            if ($note && in_array($note->statut, ['rattrapage', 'reprise'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Décision académique du semestre :
     * - admis = moyenne des moyennes des modules >= 10/20
     * - redoublant = moyenne strictement inférieure à 10/20
     * - en_cours = aucune note saisie pour ce semestre
     */
    public function decision($etudiantId, $semestreId): string
    {
        // Vérifier si l'étudiant a au moins une note dans ce semestre
        $etudiant = Etudiant::find($etudiantId);
        if (! $etudiant) {
            return 'en_cours';
        }

        $hasNotes = Note::where('etudiant_id', $etudiantId)
            ->whereHas('matiere.module', function ($query) use ($semestreId, $etudiant) {
                $query->where('semestre_id', $semestreId)
                    ->where('filiere_id', $etudiant->filiere_id);
            })->exists();

        if (!$hasNotes) {
            return 'en_cours';
        }

        $moyenne = $this->calculerMoyenne($etudiantId, $semestreId);

        if ($moyenne >= 10) {
            return 'admis';
        }

        return 'redoublant';
    }

    protected function modulesDuSemestrePourEtudiant($etudiantId, $semestreId)
    {
        $etudiant = Etudiant::find($etudiantId);
        if (! $etudiant || ! $etudiant->filiere_id) {
            return collect();
        }

        return Module::where('semestre_id', $semestreId)
            ->where('filiere_id', $etudiant->filiere_id)
            ->with('matieres')
            ->get();
    }
}
