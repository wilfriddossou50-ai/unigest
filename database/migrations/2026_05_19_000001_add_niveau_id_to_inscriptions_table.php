<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('inscriptions', 'niveau_id')) {
            Schema::table('inscriptions', function (Blueprint $table) {
                $table->foreignId('niveau_id')
                    ->nullable()
                    ->constrained('niveaux')
                    ->onDelete('cascade')
                    ->after('filiere_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('inscriptions', 'niveau_id')) {
            Schema::table('inscriptions', function (Blueprint $table) {
                $table->dropConstrainedForeignId('niveau_id');
            });
        }
    }
};
