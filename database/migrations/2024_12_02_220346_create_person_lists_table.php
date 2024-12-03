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
        Schema::create('person_lists', function (Blueprint $table) {
            $table->id();
            $table->string('origin');
            $table->string('record_type');
            $table->string('un_list_type');
            $table->text('first_name')->nullable();
            $table->text('second_name')->nullable();
            $table->text('third_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_lists');
    }
};
