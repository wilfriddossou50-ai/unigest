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
        Schema::create('professeurs', function (Blueprint $table) {

            $table->id();

            // identifiant métier
            $table->string('code')->unique();

            $table->string('nom');
            $table->string('prenom');

            $table->enum('sexe', ['M', 'F']);

            $table->date('date_naissance')->nullable();

            $table->string('email')->unique();
            $table->string('telephone')->nullable();

            $table->enum('grade', [
                'assistant',
                'ingenieur',
                'docteur',
            ])->default('assistant');

            $table->string('specialite')->nullable();

            $table->enum('statut', ['actif', 'inactif'])->default('actif');

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professeurs');
    }
};
