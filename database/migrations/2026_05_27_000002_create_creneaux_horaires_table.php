<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creneaux_horaires', function (Blueprint $table) {
            $table->id();
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->boolean('est_actif')->default(true);
            $table->timestamps();

            $table->unique(['heure_debut', 'heure_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creneaux_horaires');
    }
};
