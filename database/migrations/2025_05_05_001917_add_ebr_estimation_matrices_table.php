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
        Schema::create('ebr_estimation_matrices', function (Blueprint $table) {
            $table->id();
            $table->string('matrix_type');
            $table->string('risk_level_control_type')->nullable();
            $table->integer('percentage_control_level')->nullable();
            $table->string('probability')->nullable();
            $table->string('periodicity')->nullable();
            $table->string('periodicity_level')->nullable();
            $table->string('effectiveness')->nullable();
            $table->string('final_value');
            $table->string('basis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_estimation_matrices');
    }
};
