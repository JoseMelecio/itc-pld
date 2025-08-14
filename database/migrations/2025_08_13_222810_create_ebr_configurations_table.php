<?php

use App\Models\Tenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ebr_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Tenant::class)->constrained();
            $table->foreignIdFor(User::class)->constrained();
            $table->json('template_clients_config')->nullable();
            $table->json('template_operations_config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_configurations');
    }
};
