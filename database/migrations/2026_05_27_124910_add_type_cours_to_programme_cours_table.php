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
        Schema::table('programme_cours', function (Blueprint $table) {
            $table->enum('type_cours', ['Cours', 'Examen', 'Rattrapage', 'Reprise'])->default('Cours')->after('duree_heures');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programme_cours', function (Blueprint $table) {
            $table->dropColumn('type_cours');
        });
    }
};
