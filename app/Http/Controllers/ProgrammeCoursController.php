<?php

namespace App\Http\Controllers;

use App\Models\ProgrammeCours;
use App\Models\Matiere;
use App\Models\Professeur;
use App\Models\Salle;
use App\Models\CreneauHoraire;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Semestre;
use App\Services\ConflitService;
use App\Models\Module;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProgrammeCoursController extends Controller
{
    protected ConflitService $conflitService;
    protected NotificationService $notificationService;

    public function __construct(ConflitService $conflitService, NotificationService $notificationService)
    {
        $this->conflitService = $conflitService;
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $query = ProgrammeCours::with(['matiere', 'professeur', 'salle', 'creneau', 'semestre', 'niveau', 'filiere'])
            ->orderBy('date_programme')
            ->orderBy('creneau_id');

        $query->when($request->filled('filiere_id'), fn($q) => $q->where('filiere_id', $request->filiere_id))
            ->when($request->filled('niveau_id'),  fn($q) => $q->where('niveau_id', $request->niveau_id))
            ->when($request->filled('semestre_id'), fn($q) => $q->where('semestre_id', $request->semestre_id))
            ->when($request->filled('jour'),        fn($q) => $q->where('jour_semaine', $request->jour));

        if ($request->filled('date_debut')) {
            $dateFin = Carbon::parse($request->date_debut)->endOfDay()->addDays(6);
            $query->whereBetween('date_programme', [$request->date_debut, $dateFin]);
        }

        $programmes = $query->paginate(20)->withQueryString();
        $lookups = $this->getFormLookups(['filieres', 'niveaux', 'semestres']);

        return view('admin.programme-cours.index', array_merge(['programmes' => $programmes], $lookups));
    }

    public function grille(Request $request)
    {
        $dateDebut = $request->filled('date_debut')
            ? Carbon::parse($request->date_debut)->startOfWeek()
            : Carbon::now()->startOfWeek();

        $dateFin = $dateDebut->copy()->endOfWeek();

        $creneaux = CreneauHoraire::actif()->orderBy('heure_debut')->get();
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

        $filieres = Filiere::orderBy('libelle')->get(['id', 'libelle']);
        $niveaux = Niveau::orderBy('libelle')->get(['id', 'libelle']);
        $semestres = Semestre::orderBy('libelle')->get(['id', 'libelle']);

        $query = ProgrammeCours::with(['matiere', 'professeur', 'salle', 'creneau', 'filiere', 'niveau', 'semestre'])
            ->whereBetween('date_programme', [$dateDebut, $dateFin])
            ->where('statut', '!=', 'Annulé');

        if ($request->filled('filiere_id')) {
            $query->where('filiere_id', $request->filiere_id);
        }
        if ($request->filled('niveau_id')) {
            $query->where('niveau_id', $request->niveau_id);
        }
        if ($request->filled('semestre_id')) {
            $query->where('semestre_id', $request->semestre_id);
        }

        $programmes = $query->get()->keyBy(function ($item) {
            return $item->jour_semaine . '-' . $item->creneau_id;
        });

        return view('admin.programme-cours.grille', compact(
            'dateDebut',
            'creneaux',
            'jours',
            'programmes',
            'filieres',
            'niveaux',
            'semestres'
        ));
    }

    public function edit(ProgrammeCours $programme)
    {
        $data = $this->getFormLookups([
            'filieres',
            'niveaux',
            'semestres',
            'modules',
            'matieres',
            'professeurs',
            'salles',
            'creneaux'
        ]);

        return view('admin.programme-cours.edit', array_merge(['programme' => $programme], $data));
    }

    public function update(Request $request, ProgrammeCours $programme)
    {
        $validated = $this->validateRequest($request);
        $validated['date_programme'] = Carbon::parse($validated['date_programme'])->format('Y-m-d');

        $programme->update($validated);

        return redirect()->route('admin.programme-cours.grille', ['date_debut' => $programme->date_programme->format('Y-m-d')])
            ->with('success', 'Le cours a bien été mis à jour.');
    }

    public function annuler(Request $request, ProgrammeCours $programme)
    {
        $programme->update(['statut' => 'Annulé']);

        return redirect()->route('admin.programme-cours.grille', ['date_debut' => $programme->date_programme->format('Y-m-d')])
            ->with('success', 'Le cours a été annulé.');
    }

    public function destroy(ProgrammeCours $programme)
    {
        $programme->delete();

        return redirect()->route('admin.programme-cours.index')
            ->with('success', 'Le cours a été supprimé.');
    }

    public function create()
    {
        // On récupère toutes les données nécessaires, incluant les modules
        $data = $this->getFormLookups([
            'filieres',
            'niveaux',
            'semestres',
            'modules',
            'matieres',
            'professeurs',
            'salles',
            'creneaux'
        ]);

        return view('admin.programme-cours.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        // Ajout de cette ligne pour garantir que le service reçoit une date propre (Y-m-d)
        $validated['date_programme'] = Carbon::parse($validated['date_programme'])->format('Y-m-d');

        $validated['duree_heures'] = $validated['duree_heures'] ?? 2;
        $validated['statut'] = 'Programmé';

        $conflits = $this->conflitService->detecterConflits($validated);

        if (!empty($conflits)) {
            return back()
                ->withInput()
                ->withErrors(['conflit' => collect($conflits)->pluck('message')->implode("\n")]);
        }

        $programme = ProgrammeCours::create($validated);
        $this->notificationService->notifierCoursProgramme($programme);

        return redirect()->route('admin.programme-cours.grille', ['date_debut' => $validated['date_programme']])
            ->with('success', 'Cours programmé avec succès.');
    }

    // ... (méthodes edit, update, annuler, destroy restent inchangées)

    protected function validateRequest(Request $request): array
    {
        return $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'professeur_id' => 'required|exists:professeurs,id',
            'salle_id' => 'nullable|exists:salles,id',
            'creneau_id' => 'required|exists:creneaux_horaires,id',
            'jour_semaine' => 'required|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi',
            'date_programme' => 'required|date',
            'semestre_id' => 'nullable|exists:semestres,id',
            'niveau_id' => 'nullable|exists:niveaux,id',
            'filiere_id' => 'nullable|exists:filieres,id',
            'module_id' => 'nullable|exists:modules,id', // Ajout de la validation du module
            'type_cours' => 'nullable|in:Cours,Examen,Rattrapage,Reprise',
            'duree_heures' => 'nullable|integer|min:1|max:6',
            'notes' => 'nullable|string',
        ]);
    }

    protected function getFormLookups(array $keys = []): array
    {
        $lookups = [
            'filieres' => fn() => Filiere::orderBy('libelle')->get(['id', 'libelle']),
            'niveaux' => fn() => Niveau::orderBy('libelle')->get(['id', 'libelle']),
            'semestres' => fn() => Semestre::orderBy('libelle')->get(['id', 'libelle']),
            'modules' => fn() => Module::orderBy('libelle')->get(['id', 'libelle', 'semestre_id']), // Correction ici
            'matieres' => fn() => Matiere::with('module:id,libelle')->orderBy('libelle')->get(['id', 'libelle', 'module_id']),
            'professeurs' => fn() => Professeur::orderBy('nom')->get(['id', 'nom', 'prenom']),
            'salles' => fn() => Salle::disponible()->orderBy('nom_salle')->get(['id', 'nom_salle']),
            'creneaux' => fn() => CreneauHoraire::actif()->orderBy('heure_debut')->get(['id', 'heure_debut', 'heure_fin']),
        ];

        $requested = empty($keys) ? array_keys($lookups) : $keys;
        $result = [];
        foreach ($requested as $key) {
            if (isset($lookups[$key])) {
                $result[$key] = $lookups[$key]();
            }
        }
        return $result;
    }
}
