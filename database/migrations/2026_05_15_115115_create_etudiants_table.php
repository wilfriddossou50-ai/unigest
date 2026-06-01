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
        Schema::create('etudiants', function (Blueprint $table) {

            $table->id();

            // compte utilisateur lié
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // parcours académique
            $table->foreignId('filiere_id')
                ->nullable()
                ->constrained('filieres')
                ->onDelete('set null');

            $table->foreignId('niveau_id')
                ->nullable()
                ->constrained('niveaux')
                ->onDelete('set null');

            // identifiant étudiant
            $table->string('numero_etudiant')->unique();

            // création
            $table->boolean('created_by_admin')->default(false);

            // statut académique (IMPORTANT)
            $table->enum('statut', [
                'actif',
                'suspendu',
                'diplome'
            ])->default('actif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
