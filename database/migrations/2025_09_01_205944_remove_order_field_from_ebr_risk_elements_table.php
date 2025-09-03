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
        Schema::table('ebr_risk_elements', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->dropColumn('entity_grouper');
            $table->dropColumn('variable_grouper');
            $table->string('description')->after('sub_header');
            $table->dropForeign(['ebr_type_id']);
            $table->dropColumn('ebr_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebr_risk_elements', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->string('order')->after('sub_header');
            $table->string('entity_grouper')->after('order');
            $table->string('variable_grouper')->after('entity_grouper');
        });
    }
};
