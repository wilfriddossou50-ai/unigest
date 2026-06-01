<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'inscription.approuvee' => \App\Http\Middleware\EnsureInscriptionApprouvee::class,
        ]);

        $middleware->redirectGuestsTo(fn() => route('login'));

        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            $role = $request->user()?->role;

            return match ($role) {
                'admin'    => route('admin.dashboard'),
                'etudiant' => route('etudiant.dashboard'),
                default    => route('home'),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
