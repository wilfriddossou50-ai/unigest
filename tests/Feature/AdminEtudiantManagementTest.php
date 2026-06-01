<?php

use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\User;

test('admin can create a student account with a usable password', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $filiere = Filiere::create([
        'code' => 'INFO',
        'libelle' => 'Informatique',
    ]);
    $niveau = Niveau::create([
        'code' => 'L1',
        'libelle' => 'Licence 1',
    ]);

    $this->actingAs($admin)->post(route('admin.etudiants.store'), [
        'nom' => 'Kouadio',
        'prenom' => 'Marie',
        'email' => 'marie.kouadio@example.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'filiere_id' => $filiere->id,
        'niveau_id' => $niveau->id,
    ])->assertRedirect(route('admin.etudiants.index', absolute: false));

    $this->assertDatabaseHas('users', [
        'email' => 'marie.kouadio@example.test',
        'role' => 'etudiant',
    ]);
    $this->assertDatabaseHas('etudiants', [
        'filiere_id' => $filiere->id,
        'niveau_id' => $niveau->id,
        'created_by_admin' => true,
    ]);

    auth()->logout();
    $this->flushSession();

    $this->post('/login', [
        'email' => 'marie.kouadio@example.test',
        'password' => 'password123',
    ])->assertRedirect(route('etudiant.dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('deleting a student from admin also deletes the linked user account', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'etudiant']);
    $etudiant = Etudiant::create([
        'user_id' => $user->id,
        'numero_etudiant' => 'ETU-DELETE',
        'created_by_admin' => true,
        'statut' => 'actif',
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.etudiants.destroy', $etudiant))
        ->assertRedirect(route('admin.etudiants.index', absolute: false));

    $this->assertDatabaseMissing('etudiants', [
        'id' => $etudiant->id,
    ]);
    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});
