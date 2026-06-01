<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\User;
use App\Models\Filiere;
use App\Models\Niveau;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EtudiantController extends Controller
{
    public function index()
    {
        $etudiants = Etudiant::with(['user', 'filiere', 'niveau'])
            ->latest()
            ->paginate(10);

        return view('admin.etudiants.index', compact('etudiants'));
    }

    public function create()
    {
        $filieres = Filiere::all();
        $niveaux = Niveau::all();

        return view('admin.etudiants.create', compact('filieres', 'niveaux'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'etudiant',
            ]);

            Etudiant::create([
                'user_id' => $user->id,
                'filiere_id' => $request->filiere_id,
                'niveau_id' => $request->niveau_id,
                'numero_etudiant' => 'ETU-' . Str::upper(Str::random(6)),
                'created_by_admin' => true,
                'statut' => 'actif',
            ]);
        });

        return redirect()->route('admin.etudiants.index')
            ->with('success', 'Étudiant créé avec succès');
    }

    public function edit(Etudiant $etudiant)
    {
        $filieres = Filiere::all();
        $niveaux = Niveau::all();

        return view('admin.etudiants.edit', compact('etudiant', 'filieres', 'niveaux'));
    }

    public function update(Request $request, Etudiant $etudiant)
    {
        $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'statut' => 'required|in:actif,suspendu,diplome',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($request, $etudiant) {
            $etudiant->update($request->only([
                'filiere_id',
                'niveau_id',
                'statut',
            ]));

            if ($request->filled('password') && $etudiant->user) {
                $etudiant->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }
        });

        return redirect()->route('admin.etudiants.index')
            ->with('success', 'Étudiant mis à jour');
    }

    public function destroy(Etudiant $etudiant)
    {
        DB::transaction(function () use ($etudiant) {
            $user = $etudiant->user;

            if ($user) {
                $user->delete();
                return;
            }

            $etudiant->delete();
        });

        return redirect()->route('admin.etudiants.index')
            ->with('success', 'Étudiant et compte utilisateur supprimés');
    }
}
