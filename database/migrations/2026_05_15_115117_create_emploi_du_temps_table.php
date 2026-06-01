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
        Schema::create('emploi_du_temps', function (Blueprint $table) {

            $table->id();

            $table->foreignId('matiere_id')
                ->constrained('matieres')
                ->onDelete('cascade');

            $table->foreignId('professeur_id')
                ->constrained('professeurs')
                ->onDelete('cascade');

            $table->foreignId('semestre_id')
                ->constrained('semestres')
                ->onDelete('cascade');

            $table->string('salle');

            $table->enum('jour', [
                'Lundi',
                'Mardi',
                'Mercredi',
                'Jeudi',
                'Vendredi',
                'Samedi'
            ]);

            $table->time('heure_debut');
            $table->time('heure_fin');

            // IMPORTANT: identifiant logique de cours (optionnel mais pro)
            $table->string('code_seance')->nullable();

            $table->timestamps();

            // éviter doublon exact
            $table->unique(['professeur_id', 'jour', 'heure_debut']);
            $table->unique(['salle', 'jour', 'heure_debut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emploi_du_temps');
    }
};
