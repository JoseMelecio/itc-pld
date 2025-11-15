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
        Schema::table('blocked_person_massive', function (Blueprint $table) {
            $table->integer('total_rows')->default(0)->after('import_done');
            $table->integer('matches')->default(0)->after('import_done');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blocked_person_massive', function (Blueprint $table) {
            $table->dropColumn('total_rows');
            $table->dropColumn('matches');
        });
    }
};
