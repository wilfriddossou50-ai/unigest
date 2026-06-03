<?php

namespace App\Http\Controllers;

use App\Models\ResultatAnnuel;
use App\Models\ResultatSemestre;
use App\Models\Etudiant;
use App\Models\Niveau;
use App\Models\ProgressionEtudiant;
use App\Services\ResultatAnnuelService;
use App\Services\ProgressionService;
use Illuminate\Http\Request;

class ResultatAnnuelController extends Controller
{
    protected $annuelService;
    protected $progressionService;

    public function __construct(ResultatAnnuelService $annuelService, ProgressionService $progressionService)
    {
        $this->annuelService = $annuelService;
        $this->progressionService = $progressionService;
    }

    /**
     * Affichage du tableau
     */
    public function index(Request $request)
    {
        $this->genererResultatsAnnuelManquants();

        $resultats = ResultatAnnuel::with(['etudiant.user', 'niveau'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = trim($request->q);
                $query->whereHas('etudiant.user', function ($userQuery) use ($search) {
                    $userQuery->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('decision'), function ($query) use ($request) {
                $query->where('decision', $request->decision);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.resultats_annuels.index', compact('resultats'));
    }

    protected function genererResultatsAnnuelManquants()
    {
        $etudiantIdsWithNotes = ResultatSemestre::pluck('etudiant_id')->unique();
        $etudiants = Etudiant::whereIn('id', $etudiantIdsWithNotes)->get();
        $annee = date('Y');

        foreach ($etudiants as $etudiant) {
            $notes = ResultatSemestre::where('etudiant_id', $etudiant->id)
                ->join('semestres', 'resultats_semestre.semestre_id', '=', 'semestres.id')
                ->orderBy('semestres.code')
                ->select('resultats_semestre.*')
                ->get();

            if ($notes->isEmpty()) {
                continue;
            }

            $premierSemestre = $notes->first()->semestre;
            $niveau = $premierSemestre ? $premierSemestre->niveau : $etudiant->niveau;
            if (!$niveau) {
                continue;
            }

            $moyennesSemestres = $notes
                ->filter(fn ($resultat) => $resultat->decision !== 'en_cours')
                ->pluck('moyenne')
                ->filter(fn ($moyenne) => is_numeric($moyenne))
                ->values()
                ->all();

            if (empty($moyennesSemestres)) {
                continue;
            }

            $moyennesSemestres = array_pad($moyennesSemestres, 2, 0);

            $moyenne = $this->annuelService->calculerMoyenne($moyennesSemestres);
            $decision = $this->annuelService->decision($moyenne, $niveau);

            ResultatAnnuel::updateOrCreate(
                [
                    'etudiant_id' => $etudiant->id,
                    'niveau_id' => $niveau->id,
                    'annee_academique' => $annee,
                ],
                [
                    'moyenne_s1' => $moyennesSemestres[0] ?? 0,
                    'moyenne_s2' => $moyennesSemestres[1] ?? 0,
                    'moyenne_annuelle' => $moyenne,
                    'decision' => $decision,
                ]
            );
        }
    }

    /**
     * Calcule le bilan et nettoie DIRECTEMENT le tableau
     */
    public function toutCalculer()
    {
        // On vide physiquement la table des bilans pour repartir sur du propre
        ResultatAnnuel::truncate();

        // CORRECTION DE L'ERREUR : On trouve d'abord les IDs des étudiants qui ont des notes de semestres
        $etudiantIdsWithNotes = ResultatSemestre::pluck('etudiant_id')->unique();

        // Ensuite, on récupère ces étudiants sans passer par une relation manquante
        $etudiants = Etudiant::whereIn('id', $etudiantIdsWithNotes)->get();

        if ($etudiants->isEmpty()) {
            return back()->with('error', 'Aucun résultat de semestre trouvé.');
        }

        $compteur = 0;

        foreach ($etudiants as $etudiant) {
            // On récupère ses notes en ordre de semestre pour bien distinguer S1 / S2
            $notes = ResultatSemestre::where('etudiant_id', $etudiant->id)
                ->with('semestre.niveau')
                ->join('semestres', 'resultats_semestre.semestre_id', '=', 'semestres.id')
                ->orderBy('semestres.code')
                ->select('resultats_semestre.*')
                ->get();

            if ($notes->isEmpty()) {
                continue;
            }

            // On détecte son niveau
            $premierSemestre = $notes->first()->semestre;
            $niveau = $premierSemestre ? $premierSemestre->niveau : null;

            if (!$niveau) {
                $niveau = Niveau::first();
            }

            if (!$niveau) {
                continue;
            }

            // On extrait ses deux moyennes de semestres
            $moyennesSemestres = $notes
                ->filter(fn ($resultat) => $resultat->decision !== 'en_cours')
                ->pluck('moyenne')
                ->filter(fn ($moyenne) => is_numeric($moyenne))
                ->values()
                ->all();

            if (empty($moyennesSemestres)) {
                continue;
            }

            $moyennesSemestres = array_pad($moyennesSemestres, 2, 0);

            // On crée l'enregistrement unique
            $this->enregistrerLeBilan($etudiant, $niveau, $moyennesSemestres);
            $compteur++;
        }

        return back()->with('success', "Le tableau a été nettoyé et recalculé avec succès !");
    }

    /**
     * Calcul pour un seul étudiant
     */
    public function calculer(Etudiant $etudiant, Niveau $niveau)
    {
        $notes = ResultatSemestre::where('etudiant_id', $etudiant->id)
            ->join('semestres', 'resultats_semestre.semestre_id', '=', 'semestres.id')
            ->orderBy('semestres.code')
            ->select('resultats_semestre.*')
            ->get();

        $moyennesSemestres = $notes
            ->filter(fn ($resultat) => $resultat->decision !== 'en_cours')
            ->pluck('moyenne')
            ->filter(fn ($moyenne) => is_numeric($moyenne))
            ->values()
            ->all();

        if (empty($moyennesSemestres)) {
            return back()->with('error', 'Aucune moyenne de semestre disponible pour ce calcul.');
        }

        $moyennesSemestres = array_pad($moyennesSemestres, 2, 0);

        ResultatAnnuel::where('etudiant_id', $etudiant->id)->where('niveau_id', $niveau->id)->delete();

        $this->enregistrerLeBilan($etudiant, $niveau, $moyennesSemestres);

        return back()->with('success', 'Le résultat annuel de l\'étudiant a été mis à jour.');
    }

    /**
     * Sauvegarde
     */
    private function enregistrerLeBilan(Etudiant $etudiant, Niveau $niveau, array $moyennesSemestres)
    {
        $moyenne = $this->annuelService->calculerMoyenne($moyennesSemestres);
        $decision = $this->annuelService->decision($moyenne, $niveau);

        ResultatAnnuel::create([
            'etudiant_id' => $etudiant->id,
            'niveau_id' => $niveau->id,
            'annee_academique' => date('Y'),
            'moyenne_s1' => $moyennesSemestres[0] ?? 0,
            'moyenne_s2' => $moyennesSemestres[1] ?? 0,
            'moyenne_annuelle' => $moyenne,
            'decision' => $decision
        ]);

        $statutProgression = $this->progressionService->determinerStatutFromDecision($decision);
        $codeNiveau = strtoupper(str_replace(' ', '', $niveau->code ?? $niveau->libelle ?? 'L1'));
        $diplome = (($codeNiveau === 'L3' || $codeNiveau === 'LICENCE3') && $decision === 'diplome') ? 'diplome' : null;
        $statutFinal = !empty($diplome) ? $diplome : $statutProgression;

        ProgressionEtudiant::updateOrCreate(
            ['etudiant_id' => $etudiant->id, 'annee_academique' => date('Y')],
            ['niveau_id' => $niveau->id, 'statut' => $statutFinal]
        );
    }
}
