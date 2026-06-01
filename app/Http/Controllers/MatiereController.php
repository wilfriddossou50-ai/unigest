<?php

namespace App\Http\Controllers;

use App\Models\Matiere;
use App\Models\Module;

use Illuminate\Http\Request;

class MatiereController extends Controller
{
    /**
     * Liste des matières
     */
    public function index()
    {
        $matieres = Matiere::with(['module.filiere', 'module.semestre', 'professeurs'])
            ->latest()
            ->paginate(10);

        return view('admin.matieres.index', compact('matieres'));
    }

    public function create()
    {
        $modules = Module::with(['filiere', 'semestre'])
            ->orderBy('nom')
            ->get();

        return view('admin.matieres.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'code' => 'required|string|max:20|unique:matieres,code',
            'libelle' => 'required|string|max:255',
        ]);

        Matiere::create($validated);

        return redirect()->route('admin.matieres.index')
            ->with('success', 'Matière créée avec succès.');
    }

    public function edit(Matiere $matiere)
    {
        $modules = Module::with(['filiere', 'semestre'])
            ->orderBy('nom')
            ->get();

        return view('admin.matieres.edit', compact('matiere', 'modules'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'code' => 'required|string|max:20|unique:matieres,code,' . $matiere->id,
            'libelle' => 'required|string|max:255',
        ]);

        $matiere->update($validated);

        return redirect()->route('admin.matieres.index')
            ->with('success', 'Matière modifiée avec succès.');
    }

    public function destroy(Matiere $matiere)
    {
        $matiere->delete();

        return redirect()->route('admin.matieres.index')
            ->with('success', 'Matière supprimée avec succès.');
    }
}
