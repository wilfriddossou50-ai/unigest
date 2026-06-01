<?php

namespace App\Http\Controllers;

use App\Models\EmploiDuTemps;
use App\Models\Matiere;
use App\Models\Niveau;
use App\Models\Professeur;
use App\Models\Semestre;

use Illuminate\Http\Request;

class EmploiDuTempsController extends Controller
{
    public function index(Request $request)
    {
        $query = EmploiDuTemps::with(['matiere', 'professeur', 'semestre', 'niveau'])
            ->orderBy('jour')
            ->orderBy('heure_debut');

        if ($request->filled('niveau_id')) {
            $query->where('niveau_id', $request->niveau_id);
        }

        if ($request->filled('semestre_id')) {
            $query->where('semestre_id', $request->semestre_id);
        }

        if ($request->filled('jour')) {
            $query->where('jour', $request->jour);
        }

        $seances = $query->paginate(10)->withQueryString();

        return view('admin.emplois.index', [
            'emplois' => $seances,
            'semestres' => Semestre::orderBy('libelle')->get(),
            'niveaux' => Niveau::orderBy('libelle')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.emplois.create', [
            'matieres' => Matiere::all(),
            'professeurs' => Professeur::all(),
            'semestres' => Semestre::all(),
            'niveaux' => Niveau::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'professeur_id' => 'required|exists:professeurs,id',
            'semestre_id' => 'required|exists:semestres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'salle' => 'required|string',
            'jour' => 'required|string',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
        ]);

        EmploiDuTemps::create($request->only([
            'matiere_id',
            'professeur_id',
            'semestre_id',
            'niveau_id',
            'salle',
            'jour',
            'heure_debut',
            'heure_fin',
            'code_seance',
        ]));

        return redirect()->route('admin.emplois.index')
            ->with('success', 'Séance ajoutée');
    }

    public function destroy(EmploiDuTemps $emploi)
    {
        try {
            $emploi->delete();
            return back()->with('success', 'Séance supprimée');
        } catch (\Exception $exception) {
            return back()->with('error', 'Impossible de supprimer la séance. Vérifiez qu\'elle n\'est pas utilisée ailleurs.');
        }
    }

    public function show(EmploiDuTemps $emploi)
    {
        return redirect()->route('admin.emplois.index');
    }

    public function edit(EmploiDuTemps $emploi)
    {
        return view('admin.emplois.edit', [
            'emploi' => $emploi,
            'matieres' => Matiere::all(),
            'professeurs' => Professeur::all(),
            'semestres' => Semestre::all(),
            'niveaux' => Niveau::all(),
        ]);
    }

    public function update(Request $request, EmploiDuTemps $emploi)
    {
        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'professeur_id' => 'required|exists:professeurs,id',
            'semestre_id' => 'required|exists:semestres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'salle' => 'required|string',
            'jour' => 'required|string',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
        ]);

        $emploi->update($request->only([
            'matiere_id',
            'professeur_id',
            'semestre_id',
            'niveau_id',
            'salle',
            'jour',
            'heure_debut',
            'heure_fin',
            'code_seance',
        ]));

        return redirect()->route('admin.emplois.index')->with('success', 'Séance mise à jour');
    }
}
