<?php

namespace App\Http\Controllers;

use App\Models\Dette;
use App\Models\Etudiant;
use App\Services\DetteService;
use Illuminate\Http\Request;

class DetteController extends Controller
{
    protected $service;

    public function __construct(DetteService $service)
    {
        $this->service = $service;
    }

    /**
     * Affiche la liste des dettes avec statistiques globales dynamiques
     */
    public function index(Request $request)
    {
        // 1. Statistiques globales (Calculées sur toute la table pour la précision)
        $stats = [
            'en_cours'  => Dette::where('statut', 'en_cours')->count(),
            'etudiants' => Dette::distinct('etudiant_id')->count('etudiant_id'),
            'levees'    => Dette::where('statut', 'levee')->count(),
        ];

        // 2. Préparation de la requête avec relations pour éviter le problème N+1
        $query = Dette::with(['etudiant.user', 'matiere', 'module', 'semestre']);

        // 3. Application du filtre si présent
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // 4. Pagination avec maintien des filtres dans les liens (withQueryString)
        $dettes = $query->latest()->paginate(10)->withQueryString();

        return view('admin.dettes.index', compact('dettes', 'stats'));
    }

    /**
     * Affiche les dettes spécifiques à un étudiant
     */
    public function etudiant(Etudiant $etudiant)
    {
        $dettes = Dette::with(['matiere', 'module', 'semestre'])
            ->where('etudiant_id', $etudiant->id)
            ->latest()
            ->get();

        return view('admin.dettes.etudiant', compact('etudiant', 'dettes'));
    }

    /**
     * Action pour lever une dette
     */
    public function lever(Dette $dette)
    {
        $this->service->leverDette($dette);

        return back()->with('success', 'La dette a été levée avec succès.');
    }
}
