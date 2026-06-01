<?php

namespace App\Http\Controllers;

use App\Models\CreneauHoraire;
use Illuminate\Http\Request;

class CreneauHoraireController extends Controller
{
    public function index()
    {
        $creneaux = CreneauHoraire::orderBy('heure_debut')->paginate(15);
        return view('admin.creneaux.index', compact('creneaux'));
    }

    public function create()
    {
        return view('admin.creneaux.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'est_actif' => 'boolean',
        ]);

        CreneauHoraire::create([
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'est_actif' => $request->boolean('est_actif', true),
        ]);

        return redirect()->route('admin.creneaux.index')
            ->with('success', 'Créneau horaire créé avec succès.');
    }

    public function edit(CreneauHoraire $creneau)
    {
        return view('admin.creneaux.edit', compact('creneau'));
    }

    public function update(Request $request, CreneauHoraire $creneau)
    {
        $request->validate([
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'est_actif' => 'boolean',
        ]);

        $creneau->update([
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'est_actif' => $request->boolean('est_actif', true),
        ]);

        return redirect()->route('admin.creneaux.index')
            ->with('success', 'Créneau horaire modifié avec succès.');
    }

    public function destroy(CreneauHoraire $creneau)
    {
        $creneau->delete();

        return redirect()->route('admin.creneaux.index')
            ->with('success', 'Créneau horaire supprimé.');
    }
}
