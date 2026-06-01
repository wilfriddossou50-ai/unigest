<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programme_cours', function (Blueprint $table) {
            $table->id();

            $table->foreignId('matiere_id')
                ->constrained('matieres')
                ->onDelete('cascade');

            $table->foreignId('professeur_id')
                ->constrained('professeurs')
                ->onDelete('cascade');

            $table->foreignId('salle_id')
                ->nullable()
                ->constrained('salles')
                ->onDelete('set null');

            $table->foreignId('creneau_id')
                ->constrained('creneaux_horaires')
                ->onDelete('cascade');

            $table->enum('jour_semaine', ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']);
            $table->date('date_programme');

            $table->foreignId('semestre_id')
                ->nullable()
                ->constrained('semestres')
                ->onDelete('set null');

            $table->foreignId('niveau_id')
                ->nullable()
                ->constrained('niveaux')
                ->onDelete('set null');

            $table->foreignId('filiere_id')
                ->nullable()
                ->constrained('filieres')
                ->onDelete('set null');

            $table->integer('duree_heures')->default(2);

            $table->enum('statut', ['Programmé', 'Modifié', 'Annulé'])->default('Programmé');
            $table->text('notes')->nullable();

            $table->timestamps();

            // Indexes pour performance et détection de conflits
            $table->index(['salle_id', 'date_programme', 'creneau_id']);
            $table->index(['professeur_id', 'date_programme', 'creneau_id']);
            $table->index(['filiere_id', 'niveau_id', 'semestre_id', 'date_programme', 'creneau_id'], 'programme_cours_groupe_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programme_cours');
    }
};
