<?php

namespace App\Http\Controllers; // Corrigé : Alignement avec le dossier Admin

use App\Http\Controllers\Controller; // Ajouté : Nécessaire pour les sous-contrôleurs
use App\Models\Filiere;
use App\Models\Module;
use App\Models\Semestre;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Liste des modules
     */
    public function index()
    {
        // On récupère les modules avec leurs relations pour l'affichage
        $modules = Module::with(['filiere', 'semestre', 'matieres'])->latest()->paginate(10);

        return view('admin.modules.index', compact('modules'));
    }

    /**
     * Formulaire création
     */
    public function create()
    {
        $filieres = Filiere::orderBy('libelle')->get();
        $semestres = Semestre::all();

        return view('admin.modules.create', compact('filieres', 'semestres'));
    }

    /**
     * Enregistrement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'filiere_id'  => 'required|exists:filieres,id',
            'semestre_id' => 'required|exists:semestres,id',
            'code'        => 'required|string|unique:modules,code',
            'libelle'     => 'required|string|max:255',
        ]);

        Module::create($validated);

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Module créé avec succès.');
    }

    /**
     * Formulaire modification
     */
    public function edit(Module $module)
    {
        $filieres = Filiere::orderBy('libelle')->get();
        $semestres = Semestre::all();

        return view('admin.modules.edit', compact('module', 'filieres', 'semestres'));
    }

    /**
     * Mise à jour
     */
    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'filiere_id'  => 'required|exists:filieres,id',
            'semestre_id' => 'required|exists:semestres,id',
            'code'        => 'required|string|unique:modules,code,' . $module->id,
            'libelle'     => 'required|string|max:255',
        ]);

        $module->update($validated);

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Module modifié avec succès.');
    }

    /**
     * Suppression
     */
    public function destroy(Module $module)
    {
        $module->delete();

        return redirect()
            ->route('admin.modules.index')
            ->with('success', 'Module supprimé avec succès.');
    }
}
