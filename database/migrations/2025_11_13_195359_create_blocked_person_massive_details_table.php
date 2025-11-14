<?php

use App\Models\BlockedPersonMassive;
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
        Schema::create('blocked_person_massive_details', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('blocked_person_massive_id');
            $table->string('name')->nullable();
            $table->string('alias')->nullable();
            $table->string('date')->nullable();
            $table->string('coincidence')->nullable();
            $table->timestamps();

            $table->foreign('blocked_person_massive_id')->references('id')->on('blocked_person_massive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_person_massive_details');
    }
};
