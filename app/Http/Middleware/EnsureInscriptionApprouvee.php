<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureInscriptionApprouvee
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->isEtudiant()) {
            if (! $user->canAccessStudentSpace()) {
                return redirect()->route('attente');
            }
        }

        return $next($request);
    }
}
