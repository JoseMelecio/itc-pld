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
        Schema::dropIfExists('ebr_template_compositions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('ebr_template_compositions', function (Blueprint $table) {
            $table->id();
            $table->string('spreadsheet');
            $table->string('label');
            $table->string('var_name');
            $table->integer('order');
            $table->json('rules')->nullable();
            $table->string('type');
            $table->timestamps();
        });
    }
};
