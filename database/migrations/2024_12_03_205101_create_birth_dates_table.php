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
        Schema::create('birth_dates', function (Blueprint $table) {
            $table->id();
            $table->string('date_type');
            $table->integer('year')->nullable();
            $table->integer('final_year')->nullable();
            $table->integer('month')->nullable();
            $table->integer('day')->nullable();
            $table->foreignIdFor(\App\Models\PersonList::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birth_dates');
    }
};
