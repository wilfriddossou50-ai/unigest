<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    public function index(Request $request)
    {
        $query = Annonce::with(['filiere', 'niveau'])->orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('actif')) {
            $query->where('actif', $request->actif === 'true');
        }

        $annonces = $query->paginate(15)->withQueryString();

        return view('admin.annonces.index', compact('annonces'));
    }

    public function create()
    {
        $filieres = \App\Models\Filiere::orderBy('libelle')->get();
        $niveaux = \App\Models\Niveau::orderBy('libelle')->get();

        return view('admin.annonces.create', compact('filieres', 'niveaux'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'type' => 'required|in:info,important,urgent',
            'actif' => 'boolean',
            'date_publication' => 'nullable|date',
            'date_expiration' => 'nullable|date|after:date_publication',
            'filiere_id' => 'nullable|exists:filieres,id',
            'niveau_id' => 'nullable|exists:niveaux,id',
        ]);

        Annonce::create([
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'type' => $request->type,
            'actif' => $request->has('actif'),
            'date_publication' => $request->date_publication ?? now(),
            'date_expiration' => $request->date_expiration,
            'filiere_id' => $request->filiere_id,
            'niveau_id' => $request->niveau_id,
        ]);

        return redirect()->route('admin.annonces.index')
            ->with('success', 'Annonce créée avec succès.');
    }

    public function edit(Annonce $annonce)
    {
        $filieres = \App\Models\Filiere::orderBy('libelle')->get();
        $niveaux = \App\Models\Niveau::orderBy('libelle')->get();

        return view('admin.annonces.edit', compact('annonce', 'filieres', 'niveaux'));
    }

    public function update(Request $request, Annonce $annonce)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'contenu' => 'required|string',
            'type' => 'required|in:info,important,urgent',
            'actif' => 'boolean',
            'date_publication' => 'nullable|date',
            'date_expiration' => 'nullable|date|after:date_publication',
            'filiere_id' => 'nullable|exists:filieres,id',
            'niveau_id' => 'nullable|exists:niveaux,id',
        ]);

        $annonce->update([
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'type' => $request->type,
            'actif' => $request->has('actif'),
            'date_publication' => $request->date_publication,
            'date_expiration' => $request->date_expiration,
            'filiere_id' => $request->filiere_id,
            'niveau_id' => $request->niveau_id,
        ]);

        return redirect()->route('admin.annonces.index')
            ->with('success', 'Annonce modifiée avec succès.');
    }

    public function destroy(Annonce $annonce)
    {
        $annonce->delete();

        return back()->with('success', 'Annonce supprimée.');
    }
}
