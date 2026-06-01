<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Etudiant;
use App\Models\Matiere;
use App\Services\NoteService;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class NoteController extends Controller
{
    protected $service;

    public function __construct(NoteService $service)
    {
        $this->service = $service;
    }

    /**
     * AFFICHAGE DE LA LISTE DES NOTES
     */
    public function index(Request $request)
    {
        $notes = Note::with(['etudiant.user', 'etudiant.filiere', 'etudiant.niveau', 'matiere.module.filiere', 'matiere.module.semestre'])
            ->when($request->filled('matiere_id'), fn($query) => $query->where('matiere_id', $request->matiere_id))
            ->when($request->filled('etudiant_id'), fn($query) => $query->where('etudiant_id', $request->etudiant_id))
            ->when($request->filled('semestre_id'), function ($query) use ($request) {
                $query->whereHas('matiere.module', fn($moduleQuery) => $moduleQuery->where('semestre_id', $request->semestre_id));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $etudiants = Etudiant::with(['user', 'filiere', 'niveau'])->orderBy('numero_etudiant')->get();
        $matieres = Matiere::with('module.semestre')->orderBy('libelle')->get();
        $semestres = \App\Models\Semestre::orderBy('libelle')->get();

        return view('admin.notes.index', compact('notes', 'etudiants', 'matieres', 'semestres'));
    }

    /**
     * FORMULAIRE DE SAISIE (Initial ou Régularisation)
     */
    public function create()
    {
        $etudiants = Etudiant::with(['user', 'filiere', 'niveau'])->orderBy('numero_etudiant')->get();
        $matieres = Matiere::with(['module.filiere', 'module.semestre'])->orderBy('libelle')->get();

        return view('admin.notes.create', compact('etudiants', 'matieres'));
    }

    /**
     * ENREGISTREMENT ET TRAITEMENT UNIQUE DES NOTES
     */
    public function store(Request $request)
    {
        // 1. Validation globale des données entrantes
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'matiere_id' => 'required|exists:matieres,id',
            'type_note' => 'required|in:cc,examen,rattrapage,reprise',
            'cc' => 'nullable|numeric|min:0|max:20',
            'examen' => 'nullable|numeric|min:0|max:20',
            'note_rattrapage' => 'nullable|numeric|min:0|max:20',
            'note_reprise' => 'nullable|numeric|min:0|max:20',
        ]);

        $etudiant = Etudiant::findOrFail($request->etudiant_id);
        $matiere = Matiere::with('module')->findOrFail($request->matiere_id);

        // Vérification de la cohérence de la filière
        if ($etudiant->filiere_id !== $matiere->module?->filiere_id) {
            throw ValidationException::withMessages([
                'matiere_id' => "Cette matière n'appartient pas à la filière de l'étudiant sélectionné.",
            ]);
        }

        // Recherche ou création du dossier de note pour cet étudiant dans cette matière
        $noteRecord = Note::firstOrNew([
            'etudiant_id' => $request->etudiant_id,
            'matiere_id' => $request->matiere_id,
        ]);

        // A. CAS DU PARCOURS NORMAL (CC / EXAMEN)
        if (in_array($request->type_note, ['cc', 'examen'])) {
            if ($request->cc === null && $request->examen === null) {
                throw ValidationException::withMessages([
                    'cc' => "Veuillez saisir au moins la note de CC ou d'examen.",
                ]);
            }

            $cc = $request->filled('cc') ? (float)$request->cc : $noteRecord->cc;
            $examen = $request->filled('examen') ? (float)$request->examen : $noteRecord->examen;

            $noteCalcul = $this->service->calculer($cc, $examen);
            $statutCalcul = $this->service->statut($noteCalcul);

            $noteRecord->cc = $cc;
            $noteRecord->examen = $examen;
            $noteRecord->note_calculee = $noteCalcul;

            if (!in_array($noteRecord->statut, ['rattrapage_valide', 'reprise', 'reprise_valide', 'echec'])) {
                $noteRecord->note_finale = $noteCalcul;
            }

            $noteRecord->statut = $statutCalcul;
            $noteRecord->save();

            return redirect()->route('admin.notes.index')->with('success', 'Note initiale enregistrée');
        }

        // B. CAS DU RATTRAPAGE
        if ($request->type_note === 'rattrapage') {
            if (!$request->filled('note_rattrapage')) {
                throw ValidationException::withMessages(['note_rattrapage' => 'La note de rattrapage est requise.']);
            }

            $result = $this->service->appliquerRattrapage((float)$request->note_rattrapage);

            $noteRecord->rattrapage = (float)$request->note_rattrapage;
            $noteRecord->statut = $result['statut'];

            if (!is_null($result['note_finale'])) {
                $noteRecord->note_finale = $result['note_finale'];
            }

            $noteRecord->save();

            // Notification de l'étudiant
            if ($etudiant->user) {
                app(\App\Services\NotificationService::class)->notifierRattrapage($etudiant->user_id, $matiere->libelle);
            }

            return redirect()->route('admin.notes.index')->with('success', 'Rattrapage enregistré et traité');
        }

        // C. CAS DE LA REPRISE
        if ($request->type_note === 'reprise') {
            if (!$request->filled('note_reprise')) {
                throw ValidationException::withMessages(['note_reprise' => 'La note de reprise est requise.']);
            }

            $result = $this->service->appliquerReprise((float)$request->note_reprise);

            $noteRecord->reprise = (float)$request->note_reprise;
            $noteRecord->statut = $result['statut'];

            if (!is_null($result['note_finale'])) {
                $noteRecord->note_finale = $result['note_finale'];
            }

            $noteRecord->save();

            // Notification de l'étudiant
            if ($etudiant->user) {
                app(\App\Services\NotificationService::class)->notifierReprise($etudiant->user_id, $matiere->libelle);
            }

            return redirect()->route('admin.notes.index')->with('success', 'Reprise enregistrée et traitée');
        }
    }

    /**
     * Conservés uniquement pour la rétrocompatibilité des routes web.php 
     * (L'application utilise désormais la méthode store() ci-dessus via le formulaire unique)
     */
    public function rattrapage(Request $request, Note $note)
    {
        $request->validate(['note_rattrapage' => 'required|numeric|min:0|max:20']);
        $result = $this->service->appliquerRattrapage($request->note_rattrapage);
        $data = ['rattrapage' => $request->note_rattrapage, 'statut' => $result['statut']];
        if (!is_null($result['note_finale'])) $data['note_finale'] = $result['note_finale'];
        $note->update($data);
        if ($note->etudiant?->user) {
            app(\App\Services\NotificationService::class)->notifierRattrapage($note->etudiant->user_id, $note->matiere->libelle);
        }
        return back()->with('success', 'Rattrapage traité');
    }

    public function reprise(Request $request, Note $note)
    {
        $request->validate(['note_reprise' => 'required|numeric|min:0|max:20']);
        $result = $this->service->appliquerReprise($request->note_reprise);
        $data = ['reprise' => $request->note_reprise, 'statut' => $result['statut']];
        if (!is_null($result['note_finale'])) $data['note_finale'] = $result['note_finale'];
        $note->update($data);
        if ($note->etudiant?->user) {
            app(\App\Services\NotificationService::class)->notifierReprise($note->etudiant->user_id, $note->matiere->libelle);
        }
        return back()->with('success', 'Reprise traitée');
    }
}
