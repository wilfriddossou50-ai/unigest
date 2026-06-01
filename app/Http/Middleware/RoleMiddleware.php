<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles = null): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Pas connecté → page de login
        if (!$user) {
            return redirect()->route('login');
        }

        // Bloquer uniquement les étudiants auto-inscrits non encore validés.
        if ($user->role === 'etudiant') {
            if ($user->hasRefusedInscription()) {
                Auth::logout();

                return redirect()->route('login')
                    ->withErrors(['email' => "Votre demande d'inscription a été refusée."]);
            }

            if (! $user->canAccessStudentSpace()) {
                return redirect()->route('attente');
            }
        }

        // Vérification du rôle
        if ($roles) {
            $allowedRoles = explode(',', $roles);

            if (!in_array($user->role, $allowedRoles, true)) {
                // Mauvais rôle → rediriger vers son propre espace
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard')
                        ->with('warning', "Accès non autorisé.");
                }
                if ($user->role === 'etudiant') {
                    return redirect()->route('etudiant.dashboard')
                        ->with('warning', "Accès non autorisé.");
                }
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
