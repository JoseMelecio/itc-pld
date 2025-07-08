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
        Schema::create('ebr_matrix_qualitative_mitigants', function (Blueprint $table) {
            $table->id();
            $table->string('control_type');
            $table->string('control_level');
            $table->string('periodicity');
            $table->string('periodicity_level');
            $table->string('effectiveness');
            $table->string('final_level');
            $table->string('basis');
            $table->string('color');
            $table->integer('order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_matrix_qualitative_mitigants');
    }
};
