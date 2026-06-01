<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('emploi_du_temps', 'niveau_id')) {
            Schema::table('emploi_du_temps', function (Blueprint $table) {
                $table->foreignId('niveau_id')
                    ->nullable()
                    ->constrained('niveaux')
                    ->onDelete('cascade')
                    ->after('semestre_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('emploi_du_temps', 'niveau_id')) {
            Schema::table('emploi_du_temps', function (Blueprint $table) {
                $table->dropConstrainedForeignId('niveau_id');
            });
        }
    }
};
