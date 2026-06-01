<?php

namespace App\Http\Controllers;

use App\Models\Professeur;

use Illuminate\Http\Request;

class ProfesseurController extends Controller
{
    /**
     * Liste des professeurs
     */
    public function index()
    {
        $professeurs = Professeur::latest()->paginate(10);

        return view('admin.professeurs.index', compact('professeurs'));
    }

    /**
     * Formulaire création
     */
    public function create()
    {
        return view('admin.professeurs.create');
    }

    /**
     * Enregistrement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'code' => 'required|string|unique:professeurs,code',

            'nom' => 'required|string|max:255',

            'prenom' => 'required|string|max:255',

            'sexe' => 'required|in:M,F',

            'date_naissance' => 'nullable|date',

            'email' => 'required|email|unique:professeurs,email',

            'telephone' => 'nullable|string|max:30',

            'grade' => 'required|in:assistant,ingenieur,docteur',

            'specialite' => 'nullable|string|max:255',

            'statut' => 'required|in:actif,inactif',
        ]);

        Professeur::create($validated);

        return redirect()
            ->route('admin.professeurs.index')
            ->with('success', 'Professeur ajouté avec succès.');
    }

    /**
     * Formulaire modification
     */
    public function edit(Professeur $professeur)
    {
        return view('admin.professeurs.edit', compact('professeur'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, Professeur $professeur)
    {
        $validated = $request->validate([

            'code' => 'required|string|unique:professeurs,code,' . $professeur->id,

            'nom' => 'required|string|max:255',

            'prenom' => 'required|string|max:255',

            'sexe' => 'required|in:M,F',

            'date_naissance' => 'nullable|date',

            'email' => 'required|email|unique:professeurs,email,' . $professeur->id,

            'telephone' => 'nullable|string|max:30',

            'grade' => 'required|in:assistant,ingenieur,docteur',

            'specialite' => 'nullable|string|max:255',

            'statut' => 'required|in:actif,inactif',
        ]);

        $professeur->update($validated);

        return redirect()
            ->route('admin.professeurs.index')
            ->with('success', 'Professeur modifié avec succès.');
    }

    /**
     * Suppression
     */
    public function destroy(Professeur $professeur)
    {
        $professeur->delete();

        return redirect()
            ->route('admin.professeurs.index')
            ->with('success', 'Professeur supprimé avec succès.');
    }
}
