<?php

namespace App\Http\Controllers;

use App\Models\Filiere;

use Illuminate\Http\Request;

class FiliereController extends Controller
{
    /**
     * Liste des filières
     */
    public function index()
    {
        $filieres = Filiere::latest()->paginate(10);

        return view('admin.filieres.index', compact('filieres'));
    }

    /**
     * Formulaire création
     */
    public function create()
    {
        return view('admin.filieres.create');
    }

    /**
     * Enregistrement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'code' => 'required|string|max:20|unique:filieres,code',

            'libelle' => 'required|string|max:255',

            'description' => 'nullable|string',
        ]);

        Filiere::create($validated);

        return redirect()
            ->route('admin.filieres.index')
            ->with('success', 'Filière créée avec succès.');
    }

    /**
     * Formulaire modification
     */
    public function edit(Filiere $filiere)
    {
        return view('admin.filieres.edit', compact('filiere'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, Filiere $filiere)
    {
        $validated = $request->validate([

            'code' => 'required|string|max:20|unique:filieres,code,' . $filiere->id,

            'libelle' => 'required|string|max:255',

            'description' => 'nullable|string',
        ]);

        $filiere->update($validated);

        return redirect()
            ->route('admin.filieres.index')
            ->with('success', 'Filière modifiée avec succès.');
    }

    /**
     * Suppression
     */
    public function destroy(Filiere $filiere)
    {
        $filiere->delete();

        return redirect()
            ->route('admin.filieres.index')
            ->with('success', 'Filière supprimée avec succès.');
    }
}
