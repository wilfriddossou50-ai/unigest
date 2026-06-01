<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resultats_semestre', function (Blueprint $table) {

    $table->id();

    $table->foreignId('etudiant_id')
        ->constrained('etudiants')
        ->onDelete('cascade');

    $table->foreignId('semestre_id')
        ->constrained('semestres')
        ->onDelete('cascade');

    $table->decimal('moyenne', 4, 2)->default(0);

    $table->enum('decision', [
        'admis',
        'ajourne',
        'redoublant',
        'en_cours'
    ])->default('en_cours');

    $table->timestamps();

    $table->unique(['etudiant_id', 'semestre_id']);
});
    }

    public function down(): void
    {
        Schema::dropIfExists('resultats_semestre');
    }
};
