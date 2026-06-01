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
        Schema::create('notes', function (Blueprint $table) {

            $table->id();

            $table->foreignId('etudiant_id')
                ->constrained('etudiants')
                ->onDelete('cascade');

            $table->foreignId('matiere_id')
                ->constrained('matieres')
                ->onDelete('cascade');

            // NOTES DE BASE
            $table->decimal('cc', 5, 2)->nullable();
            $table->decimal('examen', 5, 2)->nullable();

            // RATTRAPAGE / REPRISE
            $table->decimal('rattrapage', 5, 2)->nullable();
            $table->decimal('reprise', 5, 2)->nullable();

            // CALCULS
            $table->decimal('note_calculee', 5, 2)->nullable();
            $table->decimal('note_finale', 5, 2)->nullable();

            // STATUT METIER
            $table->enum('statut', [
                'en_cours',
                'validee',
                'rattrapage',
                'rattrapage_valide',
                'reprise',
                'reprise_valide',
                'echec'
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
        Schema::dropIfExists('notes');
    }
};
