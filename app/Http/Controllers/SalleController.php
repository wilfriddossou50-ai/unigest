<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function index()
    {
        $salles = Salle::orderBy('nom_salle')->paginate(15);
        return view('admin.salles.index', compact('salles'));
    }

    public function create()
    {
        return view('admin.salles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_salle' => 'required|string|max:50|unique:salles,nom_salle',
            'statut' => 'required|in:Actif,Inactif,Maintenance',
        ]);

        Salle::create($request->only(['nom_salle', 'statut']));

        return redirect()->route('admin.salles.index')
            ->with('success', 'Salle créée avec succès.');
    }

    public function edit(Salle $salle)
    {
        return view('admin.salles.edit', compact('salle'));
    }

    public function update(Request $request, Salle $salle)
    {
        $request->validate([
            'nom_salle' => 'required|string|max:50|unique:salles,nom_salle,' . $salle->id,
            'statut' => 'required|in:Actif,Inactif,Maintenance',
        ]);

        $salle->update($request->only(['nom_salle', 'statut']));

        return redirect()->route('admin.salles.index')
            ->with('success', 'Salle modifiée avec succès.');
    }

    public function destroy(Salle $salle)
    {
        $salle->update(['statut' => 'Inactif']);

        return redirect()->route('admin.salles.index')
            ->with('success', 'Salle désactivée.');
    }
}
