<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultats_annuels', function (Blueprint $table) {

    $table->id();

    $table->foreignId('etudiant_id')
        ->constrained('etudiants')
        ->onDelete('cascade');

    $table->foreignId('niveau_id')
        ->constrained('niveaux')
        ->onDelete('cascade');

    $table->string('annee_academique');

    $table->decimal('moyenne_s1', 4, 2)->default(0);
    $table->decimal('moyenne_s2', 4, 2)->default(0);

    $table->decimal('moyenne_annuelle', 4, 2)->default(0);

    $table->enum('decision', [
        'admis',
        'ajourne',
        'redoublant',
        'diplome',
        'en_cours'
    ])->default('en_cours');

    $table->timestamps();

    $table->unique(['etudiant_id', 'niveau_id', 'annee_academique']);
});
    }

    public function down(): void
    {
        Schema::dropIfExists('resultats_annuels');
    }
};
