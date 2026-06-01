<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('semestres', 'niveau_id')) {
            Schema::table('semestres', function (Blueprint $table) {
                $table->foreignId('niveau_id')
                    ->after('id')
                    ->nullable()
                    ->constrained('niveaux')
                    ->nullOnDelete();
            });
        }

        $mapping = [
            'S1' => 'L1',
            'S2' => 'L1',
            'S3' => 'L2',
            'S4' => 'L2',
            'S5' => 'L3',
            'S6' => 'L3',
        ];

        foreach ($mapping as $codeSemestre => $codeNiveau) {
            $niveauId = DB::table('niveaux')->where('code', $codeNiveau)->value('id');

            if ($niveauId) {
                DB::table('semestres')
                    ->where('code', $codeSemestre)
                    ->update(['niveau_id' => $niveauId]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('semestres', 'niveau_id')) {
            Schema::table('semestres', function (Blueprint $table) {
                $table->dropConstrainedForeignId('niveau_id');
            });
        }
    }
};
