<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'filieres' => Filiere::orderBy('libelle')->get(),
            'niveaux' => Niveau::orderBy('libelle')->get(),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'sexe' => ['required', 'in:M,F'],
            'date_naissance' => ['required', 'date'],
            'filiere_id' => ['required', 'exists:filieres,id'],
            'niveau_id' => ['required', 'exists:niveaux,id'],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'etudiant',
        ]);

        $user->inscriptions()->create([
            'filiere_id' => $request->filiere_id,
            'niveau_id' => $request->niveau_id,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'sexe' => $request->sexe,
            'date_naissance' => $request->date_naissance,
            'statut' => 'en_attente',
        ]);

        Auth::login($user);

        return redirect()->route('attente')->with('success', 'Votre inscription a été enregistrée et est en attente de validation par l’administration.');
    }
}
