<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * PAGE LOGIN
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * TRAITEMENT LOGIN
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            /**
             * 🔐 CAS ADMIN
             */
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            /**
             * 👨‍🎓 CAS ETUDIANT
             */
            if ($user->role === 'etudiant') {
                if ($user->hasRefusedInscription()) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => "Votre demande d'inscription a été refusée."
                    ]);
                }

                if (! $user->canAccessStudentSpace()) {
                    return redirect()->route('attente');
                }

                return redirect()->route('etudiant.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect'
        ]);
    }

    /**
     * LOGOUT
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * PAGE ATTENTE APPROBATION
     */
    public function attente(Request $request): View|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $inscription = $user->inscriptions()->with('filiere')->latest()->first();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'etudiant') {
            if ($user->canAccessStudentSpace()) {
                return redirect()->route('etudiant.dashboard');
            }

            if ($user->hasRefusedInscription()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => "Votre demande d'inscription a été refusée."]);
            }
        }

        return view('auth.attente', compact('user', 'inscription'));
    }
}
