<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matieres', function (Blueprint $table) {

            $table->id();

            $table->foreignId('module_id')
                ->constrained('modules')
                ->onDelete('cascade');

            $table->string('code')->unique();   // ex: DEV101

            $table->string('libelle');          // ex: Algorithmique

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matieres');
    }
};
