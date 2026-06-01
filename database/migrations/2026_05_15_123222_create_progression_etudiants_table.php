<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progression_etudiants', function (Blueprint $table) {

            $table->id();

            $table->foreignId('etudiant_id')
                ->constrained('etudiants')
                ->onDelete('cascade');

            $table->foreignId('niveau_id')
                ->constrained('niveaux')
                ->onDelete('cascade');

            $table->string('annee_academique');

            $table->enum('statut', [
                'en_cours',
                'passage',
                'redoublement',
                'diplome'
            ])->default('en_cours');

            $table->timestamps();

            $table->unique(['etudiant_id', 'annee_academique']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progression_etudiants');
    }
};
