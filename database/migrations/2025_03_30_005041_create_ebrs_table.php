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
        Schema::create('ebrs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Tenant::class)->constrained();
            $table->foreignIdFor(\App\Models\User::class)->constrained();
            $table->string('file_name_clients');
            $table->string('file_name_operations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebrs');
    }
};
