<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semestres', function (Blueprint $table) {

            $table->id();

            $table->foreignId('niveau_id')
                ->constrained('niveaux')
                ->onDelete('cascade');

            $table->string('code')->unique();   // S1, S2, S3...
            $table->string('libelle');          // Semestre 1, Semestre 2...

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semestres');
    }
};
