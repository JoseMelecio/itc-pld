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
        Schema::table('pld_notice', function (Blueprint $table) {
            $table->boolean('allow_massive')->default(false)->after('template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pld_notice', function (Blueprint $table) {
            $table->dropColumn('allow_massive');
        });
    }
};
