<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('inscriptions', 'user_id')) {
            Schema::table('inscriptions', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('inscriptions', 'user_id')) {
            Schema::table('inscriptions', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }
    }
};
