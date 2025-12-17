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
        Schema::table('ebr_risk_element_indicators', function (Blueprint $table) {
            $table->string('sql')->nullable()->after('report_config');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebr_risk_element_indicators', function (Blueprint $table) {
            $table->dropColumn('sql');
        });
    }
};
