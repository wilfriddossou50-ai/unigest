<?php

namespace App\Services;

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
     * Calcul moyenne générale du semestre
     * = moyenne de TOUTES les matières du semestre
     */
    public function calculerMoyenne($etudiantId, $semestreId)
    {
        $modules = Module::where('semestre_id', $semestreId)->get();

        if ($modules->isEmpty()) {
            return 0;
        }

        $totalNotes = 0;
        $matieresEvaluees = 0;

        foreach ($modules as $module) {
            foreach ($module->matieres as $matiere) {
                $note = Note::where('etudiant_id', $etudiantId)
                    ->where('matiere_id', $matiere->id)
                    ->first();

                if ($note && $note->note_finale !== null) {
                    $totalNotes += $note->note_finale;
                    $matieresEvaluees++;
                }
            }
        }

        if ($matieresEvaluees === 0) {
            return 0;
        }

        return round($totalNotes / $matieresEvaluees, 2);
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
     * - admis = 100% modules validés ET moyenne >= 10/20
     * - ajourne = au moins un module non validé mais avec possibilité de rattrapage
     * - redoublant = conditions non remplies, redoublement recommandé
     */
    public function decision($etudiantId, $semestreId): string
    {
        // 1. Vérifier si l'étudiant a au moins une note dans ce semestre
        $hasNotes = Note::where('etudiant_id', $etudiantId)
            ->whereHas('matiere.module', function ($query) use ($semestreId) {
                $query->where('semestre_id', $semestreId);
            })->exists();

        // Si aucune note n'est saisie, on met en cours
        if (!$hasNotes) {
            return 'en_cours';
        }

        // 2. Calcul de la moyenne
        $moyenne = $this->calculerMoyenne($etudiantId, $semestreId);
        $tousValides = $this->tousModulesValides($etudiantId, $semestreId);

        // Tous les modules validés et moyenne >= 10 = admis
        if ($tousValides && $moyenne >= 10) {
            return 'admis';
        }

        // Vérifier s'il y a des modules en attente (rattrapage/reprise possible)
        $modules = Module::where('semestre_id', $semestreId)->get();
        $modulesEnAttente = 0;

        foreach ($modules as $module) {
            if ($this->moduleEnAttente($etudiantId, $module->id)) {
                $modulesEnAttente++;
            }
        }

        // S'il y a des modules en attente mais pas tous = ajourné
        if ($modulesEnAttente > 0 && $modulesEnAttente < $modules->count()) {
            return 'ajourne';
        }

        // Sinon = redoublant
        return 'redoublant';
    }
}
