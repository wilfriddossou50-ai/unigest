<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Inscription;
use App\Models\Note;
use App\Models\Matiere;
use App\Models\EmploiDuTemps;
use App\Models\Semestre;
use App\Models\Professeur; // Assurez-vous que ce modèle existe
use App\Models\Filiere;
use App\Models\ResultatAnnuel;
use App\Models\ResultatSemestre;
use App\Models\ProgressionEtudiant;
use App\Models\Dette;
use App\Services\ModuleService;
use App\Services\ResultatAnnuelService;
use App\Services\ResultatSemestreService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Espace Admin : Vue d'ensemble et statistiques
     */
    public function adminDashboard(): View
    {
        $totalEtudiants = Etudiant::count();
        $totalProfesseurs = Professeur::count();
        $totalFilieres = Filiere::count();
        $pending = Inscription::where('statut', 'en_attente')->count();

        $latestInscriptions = Inscription::with('filiere')->latest()->limit(5)->get();
        $latestNotes = Note::with(['etudiant.user', 'matiere'])->latest()->limit(5)->get();

        return view('dashboard.admin.index', compact(
            'totalEtudiants',
            'totalProfesseurs',
            'totalFilieres',
            'pending',
            'latestInscriptions',
            'latestNotes'
        ));
    }

    /**
     * Helper pour charger l'étudiant avec ses relations
     */
    /**
     * Helper pour charger l'étudiant avec ses relations
     */
    protected function getStudentEtudiant(): ?Etudiant
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // On vérifie si l'utilisateur est bien un étudiant avant de chercher la relation
        if (!$user || !$user->isEtudiant()) {
            return null;
        }

        return $user->etudiant()->with(['filiere', 'niveau'])->first();
    }

    protected function ensureStudentAccess(): ?RedirectResponse
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (! $user || ! $user->canAccessStudentSpace()) {
            return Redirect::route('attente');
        }

        return null;
    }

    public function etudiantDashboard(): RedirectResponse|View
    {
        if ($redirect = $this->ensureStudentAccess()) {
            return $redirect;
        }

        $user = Auth::user();
        $etudiant = $this->getStudentEtudiant();
        $latestNotes = $etudiant ? $etudiant->notes()->with('matiere.module.semestre')->latest()->limit(5)->get() : collect();
        $pendingDettes = $etudiant ? $etudiant->dettes()->where('statut', 'en_cours')->count() : 0;

        return view('dashboard.etudiant.index', compact('user', 'etudiant', 'latestNotes', 'pendingDettes'));
    }

    public function etudiantDettes(): RedirectResponse|View
    {
        if ($redirect = $this->ensureStudentAccess()) {
            return $redirect;
        }

        $etudiant = $this->getStudentEtudiant();
        if (! $etudiant) {
            return Redirect::route('attente');
        }

        $dettes = Dette::with(['matiere.module', 'semestre'])
            ->where('etudiant_id', $etudiant->id)
            ->latest()
            ->get();

        $pending = $dettes->where('statut', 'en_cours')->count();

        return view('dashboard.etudiant.dettes', compact('etudiant', 'dettes', 'pending'));
    }

    public function etudiantNotes(Request $request): RedirectResponse|View
    {
        if ($redirect = $this->ensureStudentAccess()) {
            return $redirect;
        }

        $etudiant = $this->getStudentEtudiant();
        if (! $etudiant) return Redirect::route('attente');

        // Récupération des semestres filtrés par niveau
        $semestresDisponibles = Semestre::where('niveau_id', $etudiant->niveau_id)->get();

        $selectedSemestreId = $request->query(
            'semestre_id',
            $semestresDisponibles->where('actif', true)->first()?->id ?? $semestresDisponibles->first()?->id
        );

        $notes = $etudiant->notes()
            ->whereHas('matiere.module.semestre', function ($query) use ($selectedSemestreId) {
                $query->where('semestres.id', $selectedSemestreId);
            })
            ->with('matiere.module')
            ->get();

        $average = $notes->whereNotNull('note_finale')->avg('note_finale');
        $average = $average !== null ? number_format($average, 2, ',', '.') : '—';
        $validatedCount = $notes->whereIn('statut', ['validee', 'rattrapage_valide', 'reprise_valide'])->count();
        $currentSemestre = $semestresDisponibles->find($selectedSemestreId)?->libelle ?? 'Non défini';

        return view('dashboard.etudiant.notes', compact(
            'etudiant',
            'notes',
            'average',
            'validatedCount',
            'semestresDisponibles',
            'selectedSemestreId',
            'currentSemestre'
        ));
    }

    public function etudiantModules(ModuleService $moduleService): RedirectResponse|View
    {
        if ($redirect = $this->ensureStudentAccess()) {
            return $redirect;
        }

        $etudiant = $this->getStudentEtudiant();
        if (! $etudiant) return Redirect::route('attente');

        $modules = $etudiant->filiere?->modules()->with(['semestre', 'matieres'])->get() ?? collect();

        $modules->each(function ($module) use ($etudiant, $moduleService) {
            $module->moyenne = $moduleService->calculerMoyenne($etudiant->id, $module->id);
            $module->est_valide = $moduleService->estValide($etudiant->id, $module->id);
        });

        return view('dashboard.etudiant.modules', compact('etudiant', 'modules'));
    }

    public function etudiantMatieres(): RedirectResponse|View
    {
        if ($redirect = $this->ensureStudentAccess()) {
            return $redirect;
        }

        $etudiant = $this->getStudentEtudiant();
        if (! $etudiant) return Redirect::route('attente');

        $moduleIds = $etudiant->filiere?->modules()->pluck('id') ?? collect();
        $matieres = $moduleIds->isNotEmpty()
            ? Matiere::with('module.semestre')->whereIn('module_id', $moduleIds)->get()
            : collect();

        return view('dashboard.etudiant.matieres', compact('etudiant', 'matieres'));
    }

    public function etudiantEmploi(): RedirectResponse|View
    {
        if ($redirect = $this->ensureStudentAccess()) {
            return $redirect;
        }

        $etudiant = $this->getStudentEtudiant();
        if (! $etudiant) {
            return Redirect::route('attente');
        }

        $semestresDisponibles = Semestre::where('niveau_id', $etudiant->niveau_id)
            ->orderBy('libelle')
            ->get();

        $selectedSemestreId = request()->query('semestre_id', $semestresDisponibles->first()?->id);

        $semesterIds = $etudiant->filiere?->modules()->pluck('semestre_id')->filter()->unique() ?? collect();
        $emplois = $semesterIds->isNotEmpty()
            ? EmploiDuTemps::with('matiere.module', 'professeur', 'semestre', 'niveau')
            ->whereIn('semestre_id', $semesterIds)
            ->where('niveau_id', $etudiant->niveau_id)
            ->when($selectedSemestreId, fn($query, $selectedSemestreId) => $query->where('semestre_id', $selectedSemestreId))
            ->orderBy('jour')
            ->orderBy('heure_debut')
            ->get()
            : collect();

        return view('dashboard.etudiant.emploi', compact('etudiant', 'emplois', 'semestresDisponibles', 'selectedSemestreId'));
    }

    public function etudiantBulletin(): RedirectResponse|View
    {
        if ($redirect = $this->ensureStudentAccess()) {
            return $redirect;
        }

        $etudiant = $this->getStudentEtudiant();
        if (! $etudiant) {
            return Redirect::route('attente');
        }

        $this->genererResultatsEtudiantsSurChargement($etudiant);

        $user = Auth::user();
        $resultatsSemestre = ResultatSemestre::with('semestre')
            ->where('etudiant_id', $etudiant->id)
            ->get();

        $resultatAnnuel = ResultatAnnuel::where('etudiant_id', $etudiant->id)
            ->where('niveau_id', $etudiant->niveau_id)
            ->where('annee_academique', date('Y'))
            ->latest('id')
            ->first();

        $progression = ProgressionEtudiant::where('etudiant_id', $etudiant->id)
            ->latest('annee_academique')
            ->first();

        $notes = $etudiant->notes()->with('matiere.module.semestre')->get();
        $bulletinReady = $resultatsSemestre->isNotEmpty() || $notes->isNotEmpty();

        return view('dashboard.etudiant.bulletin', compact(
            'user',
            'etudiant',
            'resultatAnnuel',
            'progression',
            'resultatsSemestre',
            'notes',
            'bulletinReady'
        ));
    }

    protected function genererResultatsEtudiantsSurChargement(Etudiant $etudiant)
    {
        $serviceSemestre = app(ResultatSemestreService::class);
        $serviceAnnuel = app(ResultatAnnuelService::class);

        $semestres = Semestre::where('niveau_id', $etudiant->niveau_id)
            ->orderBy('code')
            ->get();

        $resultats = collect();
        foreach ($semestres as $semestre) {
            $moyenne = $serviceSemestre->calculerMoyenne($etudiant->id, $semestre->id);
            $decision = $serviceSemestre->decision($etudiant->id, $semestre->id);

            $resultat = ResultatSemestre::updateOrCreate(
                [
                    'etudiant_id' => $etudiant->id,
                    'semestre_id' => $semestre->id,
                ],
                [
                    'moyenne' => $moyenne,
                    'decision' => $decision,
                ]
            );

            $resultat->semestre = $semestre;
            $resultats->push($resultat);
        }

        if ($resultats->isEmpty() || !$etudiant->niveau) {
            return;
        }

        $moyennesSemestres = $resultats
            ->filter(fn($resultat) => $resultat->decision !== 'en_cours')
            ->pluck('moyenne')
            ->filter(fn($moyenne) => is_numeric($moyenne))
            ->values()
            ->all();

        if (empty($moyennesSemestres)) {
            return;
        }

        $moyennesSemestres = array_pad($moyennesSemestres, 2, 0);

        $moyenneAnnuel = $serviceAnnuel->calculerMoyenne($moyennesSemestres);
        $decisionAnnuel = $serviceAnnuel->decision($moyenneAnnuel, $etudiant->niveau);

        ResultatAnnuel::updateOrCreate(
            [
                'etudiant_id' => $etudiant->id,
                'niveau_id' => $etudiant->niveau_id,
                'annee_academique' => date('Y'),
            ],
            [
                'moyenne_s1' => $moyennesSemestres[0] ?? 0,
                'moyenne_s2' => $moyennesSemestres[1] ?? 0,
                'moyenne_annuelle' => $moyenneAnnuel,
                'decision' => $decisionAnnuel,
            ]
        );
    }

    public function etudiantProfil(): RedirectResponse|View
    {
        $etudiant = $this->getStudentEtudiant();
        if (! $etudiant) return Redirect::route('attente');

        return view('dashboard.etudiant.profil', compact('etudiant'));
    }
}
