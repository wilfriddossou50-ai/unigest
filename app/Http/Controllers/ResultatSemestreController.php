<?php

namespace App\Http\Controllers;

use App\Models\ResultatSemestre;
use App\Models\Etudiant;
use App\Models\Semestre;
use App\Services\ResultatSemestreService;

use Illuminate\Http\Request;

class ResultatSemestreController extends Controller
{
    protected $service;

    public function __construct(ResultatSemestreService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        // On récupère les étudiants avec leurs résultats et l'utilisateur lié
        $query = Etudiant::with(['user', 'resultats.semestre']);

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%");
            });
        }

        $etudiants = $query->paginate(10)->withQueryString();
        $semestres = Semestre::all(); // Pour avoir les colonnes en haut

        $this->genererResultatsSemestreManquants($etudiants->items(), $semestres);
        $etudiants->getCollection()->each->load('resultats.semestre');

        $resultatsMap = [];
        foreach ($etudiants as $etudiant) {
            foreach ($etudiant->resultats as $resultat) {
                $resultatsMap[$etudiant->id][$resultat->semestre_id] = $resultat;
            }
        }

        return view('admin.resultats_semestre.index', compact('etudiants', 'semestres', 'resultatsMap'));
    }

    protected function genererResultatsSemestreManquants($etudiants, $semestres)
    {
        foreach ($etudiants as $etudiant) {
            foreach ($semestres as $semestre) {
                $resultat = $etudiant->resultats->firstWhere('semestre_id', $semestre->id);

                if (!$resultat || $resultat->moyenne === null || $resultat->decision === null) {
                    $moyenne = $this->service->calculerMoyenne($etudiant->id, $semestre->id);
                    $decision = $this->service->decision($etudiant->id, $semestre->id);

                    ResultatSemestre::updateOrCreate(
                        [
                            'etudiant_id' => $etudiant->id,
                            'semestre_id' => $semestre->id,
                        ],
                        [
                            'moyenne' => $moyenne,
                            'decision' => $decision,
                        ]
                    );
                }
            }
        }
    }

    public function calculer(Etudiant $etudiant, Semestre $semestre)
    {
        // 1. Calcul de la moyenne (ça, c'est bon)
        $moyenne = $this->service->calculerMoyenne(
            $etudiant->id,
            $semestre->id
        );

        // 2. CORRECTION : On passe l'étudiant et le semestre au service
        $decision = $this->service->decision($etudiant->id, $semestre->id);

        // 3. Mise à jour (ça, c'est bon)
        ResultatSemestre::updateOrCreate(
            [
                'etudiant_id' => $etudiant->id,
                'semestre_id' => $semestre->id,
            ],
            [
                'moyenne' => $moyenne,
                'decision' => $decision,
            ]
        );

        return back()->with('success', 'Résultat semestre calculé avec succès.');
    }
    public function toutCalculer()
    {
        $service = $this->service;
        $etudiants = Etudiant::all();
        $semestres = Semestre::all();

        foreach ($etudiants as $etudiant) {
            foreach ($semestres as $semestre) {
                $moyenne = $service->calculerMoyenne($etudiant->id, $semestre->id);
                $decision = $service->decision($etudiant->id, $semestre->id);

                ResultatSemestre::updateOrCreate(
                    ['etudiant_id' => $etudiant->id, 'semestre_id' => $semestre->id],
                    ['moyenne' => $moyenne, 'decision' => $decision]
                );
            }
        }

        return back()->with('success', 'Tous les résultats ont été mis à jour.');
    }
}
