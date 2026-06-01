<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Niveau;
use App\Models\ProgressionEtudiant;
use App\Services\ProgressionService;
use Illuminate\Http\Request;

class ProgressionEtudiantController extends Controller
{
    public function __construct(protected ProgressionService $progressionService)
    {
    }

    /**
     * Affiche uniquement le tableau de suivi de la progression
     */
    public function index()
    {
        // On charge aussi 'etudiant.user' pour optimiser l'affichage des noms/prénoms dans la vue
        $progressions = ProgressionEtudiant::with(['etudiant.user', 'niveau'])
            ->latest()
            ->paginate(10);

        return view('admin.progression.index', compact('progressions'));
    }

    /**
     * Met a jour la progression d'un etudiant a partir d'une decision academique.
     */
    public function calculer(Etudiant $etudiant, Niveau $niveau, string $decisionSemestre)
    {
        $decision = strtolower(trim($decisionSemestre));

        $statut = match ($decision) {
            'diplome' => 'diplome',
            'admis', 'passage', 'valide' => 'passage',
            default => $this->progressionService->determinerStatutFromDecision($decision),
        };

        ProgressionEtudiant::updateOrCreate(
            [
                'etudiant_id' => $etudiant->id,
                'annee_academique' => date('Y'),
            ],
            [
                'niveau_id' => $niveau->id,
                'statut' => $statut,
            ]
        );

        return back()->with('success', 'La progression de l\'etudiant a ete mise a jour.');
    }
}
