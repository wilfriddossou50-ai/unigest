<?php

namespace App\Http\Controllers;

use App\Models\Niveau;

use Illuminate\Http\Request;

class NiveauController extends Controller
{
    /**
     * Liste des niveaux
     */
    public function index()
    {
        $niveaux = Niveau::latest()->paginate(10);

        return view('admin.niveaux.index', compact('niveaux'));
    }

    /**
     * Formulaire création
     */
    public function create()
    {
        return view('admin.niveaux.create');
    }

    /**
     * Enregistrement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'code' => 'required|string|max:10|unique:niveaux,code',

            'libelle' => 'required|string|max:255',
        ]);

        Niveau::create($validated);

        return redirect()
            ->route('admin.niveaux.index')
            ->with('success', 'Niveau créé avec succès.');
    }

    /**
     * Formulaire modification
     */
    public function edit(Niveau $niveau)
    {
        return view('admin.niveaux.edit', compact('niveau'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, Niveau $niveau)
    {
        $validated = $request->validate([

            'code' => 'required|string|max:10|unique:niveaux,code,' . $niveau->id,

            'libelle' => 'required|string|max:255',
        ]);

        $niveau->update($validated);

        return redirect()
            ->route('admin.niveaux.index')
            ->with('success', 'Niveau modifié avec succès.');
    }

    /**
     * Suppression
     */
    public function destroy(Niveau $niveau)
    {
        $niveau->delete();

        return redirect()
            ->route('admin.niveaux.index')
            ->with('success', 'Niveau supprimé avec succès.');
    }
}
