<?php

namespace App\Http\Controllers;

use App\Models\Niveau;
use App\Models\Semestre;

use Illuminate\Http\Request;

class SemestreController extends Controller
{
    /**
     * Liste des semestres
     */
    public function index()
    {
        $semestres = Semestre::with(['niveau', 'modules'])->latest()->paginate(10);

        return view('admin.semestres.index', compact('semestres'));
    }

    /**
     * Formulaire création
     */
    public function create()
    {
        $niveaux = Niveau::orderBy('libelle')->get();

        return view('admin.semestres.create', compact('niveaux'));
    }

    /**
     * Enregistrement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'niveau_id' => 'required|exists:niveaux,id',

            'code' => 'required|string|max:10|unique:semestres,code',

            'libelle' => 'required|string|max:255',
        ]);

        Semestre::create($validated);

        return redirect()
            ->route('admin.semestres.index')
            ->with('success', 'Semestre créé avec succès.');
    }

    /**
     * Formulaire modification
     */
    public function edit(Semestre $semestre)
    {
        $niveaux = Niveau::orderBy('libelle')->get();

        return view('admin.semestres.edit', compact('semestre', 'niveaux'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, Semestre $semestre)
    {
        $validated = $request->validate([
            'niveau_id' => 'required|exists:niveaux,id',

            'code' => 'required|string|max:10|unique:semestres,code,' . $semestre->id,

            'libelle' => 'required|string|max:255',
        ]);

        $semestre->update($validated);

        return redirect()
            ->route('admin.semestres.index')
            ->with('success', 'Semestre modifié avec succès.');
    }

    /**
     * Suppression
     */
    public function destroy(Semestre $semestre)
    {
        $semestre->delete();

        return redirect()
            ->route('admin.semestres.index')
            ->with('success', 'Semestre supprimé avec succès.');
    }
}
