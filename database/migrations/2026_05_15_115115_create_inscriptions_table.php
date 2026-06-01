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
        Schema::create('inscriptions', function (Blueprint $table) {

            $table->id();

            // filière demandée
            $table->foreignId('filiere_id')
                ->constrained('filieres')
                ->onDelete('cascade');

            // infos personnelles (indépendantes du user)
            $table->string('nom');
            $table->string('prenom');
            $table->enum('sexe', ['M', 'F']);
            $table->date('date_naissance')->nullable();

            // statut traitement admin
            $table->enum('statut', [
                'en_attente',
                'approuvee',
                'refusee'
            ])->default('en_attente');

            $table->text('motif_refus')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
