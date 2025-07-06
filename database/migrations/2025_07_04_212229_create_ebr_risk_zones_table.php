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
        Schema::create('ebr_risk_zones', function (Blueprint $table) {
            $table->id();
            $table->string('risk_zone');
            $table->integer('incidence_of_crime');
            $table->decimal('percentage_1', 8, 2)->default(0);
            $table->decimal('percentage_2', 8, 2)->default(0);
            $table->integer('zone');
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_risk_zones');
    }
};

