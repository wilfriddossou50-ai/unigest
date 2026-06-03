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
    public function index(Request $request)
    {
        $progressionsQuery = ProgressionEtudiant::with(['etudiant.user', 'niveau'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = trim($request->q);

                $query->where(function ($searchQuery) use ($search) {
                    $searchQuery->whereHas('etudiant.user', function ($userQuery) use ($search) {
                        $userQuery->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })->orWhereHas('etudiant', function ($etudiantQuery) use ($search) {
                        $etudiantQuery->where('numero_etudiant', 'like', "%{$search}%");
                    });
                });
            })
            ->when($request->filled('niveau'), function ($query) use ($request) {
                $query->where('niveau_id', $request->niveau);
            })
            ->when($request->filled('annee'), function ($query) use ($request) {
                $query->where('annee_academique', $request->annee);
            })
            ->when($request->filled('statut'), function ($query) use ($request) {
                $query->where('statut', $request->statut);
            });

        $progressions = $progressionsQuery
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $niveaux = Niveau::orderBy('code')->get();
        $annees = ProgressionEtudiant::query()
            ->select('annee_academique')
            ->distinct()
            ->orderByDesc('annee_academique')
            ->pluck('annee_academique');

        return view('admin.progression.index', compact('progressions', 'niveaux', 'annees'));
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
