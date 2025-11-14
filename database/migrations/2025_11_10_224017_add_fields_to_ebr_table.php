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
        Schema::table('ebrs', function (Blueprint $table) {
            $table->decimal('concentration', 10, 2)->default(0.00)->change();
            $table->decimal('present_features', 10, 2)->default(0.00)->change();
            $table->decimal('inherent_entity_risk', 10, 2)->default(0.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebrs', function (Blueprint $table) {
            $table->integer('concentration')->default(0)->change();
            $table->integer('present_features')->default(0)->change();
            $table->integer('inherent_entity_risk')->default(0)->change();
        });
    }
};
