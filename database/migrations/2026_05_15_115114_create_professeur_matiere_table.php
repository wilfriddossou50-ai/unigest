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
        Schema::create('professeur_matiere', function (Blueprint $table) {

            $table->id();

            $table->foreignId('professeur_id')
                ->constrained('professeurs')
                ->onDelete('cascade');

            $table->foreignId('matiere_id')
                ->constrained('matieres')
                ->onDelete('cascade');

            $table->unique(['professeur_id', 'matiere_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professeur_matiere');
    }
};
