<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications_custom', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('titre');
            $table->text('message');

            $table->enum('type', [
                'cours_programme',
                'changement_salle',
                'annulation_cours',
                'examen_programme',
                'resultats_publies',
                'rattrapage_propose',
                'decision_reprise',
                'progression_niveau',
                'annonce',
                'general'
            ])->default('general');

            $table->boolean('lu')->default(false);
            $table->timestamp('lu_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'lu']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_custom');
    }
};
