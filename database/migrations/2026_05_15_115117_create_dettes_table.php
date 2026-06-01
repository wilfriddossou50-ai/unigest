<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dettes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('etudiant_id')
                ->constrained('etudiants')
                ->onDelete('cascade');

            $table->foreignId('matiere_id')
                ->constrained('matieres')
                ->onDelete('cascade');

            $table->foreignId('module_id')
                ->constrained('modules')
                ->onDelete('cascade');

            $table->foreignId('semestre_id')
                ->constrained('semestres')
                ->onDelete('cascade');

            $table->enum('statut', [
                'en_cours',
                'levee'
            ])->default('en_cours');

            $table->unique(['etudiant_id', 'matiere_id']);

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dettes');
    }
};
