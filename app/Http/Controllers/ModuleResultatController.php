<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Etudiant;
use App\Services\ModuleService;

class ModuleResultatController extends Controller
{
    protected $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    /**
     * Voir les notes et la moyenne de tous les étudiants pour un module précis
     */
    public function show($id)
    {
        // 1. On récupère le module avec ses matières
        $module = Module::with('matieres')->findOrFail($id);

        // 2. On récupère les étudiants de la filière avec leurs infos utilisateur ET leurs notes (Optimisé !)
        $etudiants = Etudiant::where('filiere_id', $module->filiere_id)
            ->with(['user', 'notes'])
            ->get();

        // 3. On applique ton ModuleService pour calculer les moyennes dynamiquement
        foreach ($etudiants as $etudiant) {
            $etudiant->moyenne_module = $this->moduleService->calculerMoyenne($etudiant->id, $module->id);
            $etudiant->module_valide = $this->moduleService->estValide($etudiant->id, $module->id);
        }

        return view('admin.modules.resultats', compact('module', 'etudiants'));
    }
}
