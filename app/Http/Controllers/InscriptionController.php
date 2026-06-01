<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\User;
use App\Models\Etudiant;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class InscriptionController extends Controller
{
    public function index()
    {
        $inscriptions = Inscription::with(['user', 'filiere'])
            ->latest()
            ->paginate(10);

        return view('admin.inscriptions.index', compact('inscriptions'));
    }

    public function show(Inscription $inscription)
    {
        $inscription->load(['user', 'filiere']);

        return view('admin.inscriptions.show', compact('inscription'));
    }

    /*
    |--------------------------------------------------------------------------
    | APPROBATION ADMIN
    |--------------------------------------------------------------------------
    */

    public function approuver(Inscription $inscription)
    {
        $user = $inscription->user;

        if (! $user) {
            return redirect()->back()->withErrors([
                'inscription' => 'Aucun compte utilisateur associé à cette inscription.',
            ]);
        }

        $user->update([
            'role' => 'etudiant',
        ]);

        $inscription->update([
            'statut' => 'approuvee',
            'user_id' => $user->id,
        ]);

        if (! $user->etudiant) {
            Etudiant::create([
                'user_id' => $user->id,
                'filiere_id' => $inscription->filiere_id,
                'niveau_id' => $inscription->niveau_id,
                'numero_etudiant' => 'ETUD-' . (Etudiant::max('id') + 1),
                'created_by_admin' => false,
                'statut' => 'actif',
            ]);
        }

        return redirect()->back()->with('success', 'Inscription approuvée');
    }

    /*
    |--------------------------------------------------------------------------
    | REFUS
    |--------------------------------------------------------------------------
    */

    public function refuser(Request $request, Inscription $inscription)
    {
        $request->validate([
            'motif_refus' => 'required|string',
        ]);

        $inscription->update([
            'statut' => 'refusee',
            'motif_refus' => $request->motif_refus,
        ]);

        return redirect()->back()->with('success', 'Inscription refusée');
    }
}
