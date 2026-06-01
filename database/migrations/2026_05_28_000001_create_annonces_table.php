<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('contenu');
            $table->enum('type', ['info', 'important', 'urgent'])->default('info');
            $table->boolean('actif')->default(true);
            $table->date('date_publication')->nullable();
            $table->date('date_expiration')->nullable();
            $table->foreignId('filiere_id')->nullable()->constrained('filieres')->nullOnDelete();
            $table->foreignId('niveau_id')->nullable()->constrained('niveaux')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
