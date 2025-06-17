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
            $table->enum('status', ['processing', 'done'])->default('processing');
            $table->decimal('total_operation_amount', 8, 2)->nullable();
            $table->bigInteger('total_clients')->nullable();;
            $table->bigInteger('total_operations')->nullable();;
            $table->integer('concentration')->nullable();;
            $table->integer('present_features')->nullable();;
            $table->integer('inherent_entity_risk')->nullable();;
            $table->integer('maximum_risk_level')->nullable();;
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
