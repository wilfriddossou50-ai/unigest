<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('modules', 'filiere_id')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->foreignId('filiere_id')
                    ->after('id')
                    ->constrained('filieres')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('modules', 'filiere_id')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->dropConstrainedForeignId('filiere_id');
            });
        }
    }
};
