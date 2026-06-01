<?php

namespace App\Http\Controllers;

use App\Models\Professeur;
use App\Models\Matiere;
use App\Models\ProfesseurMatiere;

use Illuminate\Http\Request;

class ProfesseurMatiereController extends Controller
{
    public function index()
    {
        $assignations = ProfesseurMatiere::with(['professeur', 'matiere'])->get();

        return view('admin.professeur_matiere.index', compact('assignations'));
    }

    public function create()
    {
        $professeurs = Professeur::all();
        $matieres = Matiere::all();

        return view('admin.professeur_matiere.create', compact('professeurs', 'matieres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'professeur_id' => 'required|exists:professeurs,id',
            'matiere_id' => 'required|exists:matieres,id',
        ]);

        ProfesseurMatiere::firstOrCreate($request->only(['professeur_id', 'matiere_id']));

        return redirect()->route('admin.professeur-matiere.index')
            ->with('success', 'Affectation créée avec succès');
    }

    public function destroy($id)
    {
        ProfesseurMatiere::findOrFail($id)->delete();

        return back()->with('success', 'Affectation supprimée');
    }
}
