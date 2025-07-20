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
        Schema::create('ebr_matrix_qualitative_risks', function (Blueprint $table) {
            $table->id();
            $table->string('risk_level');
            $table->string('percentage')->default('N/A');
            $table->string('probability')->default('N/A');
            $table->string('final_value')->default('N/A');
            $table->string('basis')->default('N/A');
            $table->string('color')->default('FFFFFF');
            $table->integer('order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_matrix_qualitative_risks');
    }
};
