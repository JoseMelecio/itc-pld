<?php

use App\Models\EBRRiskElement;
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
        Schema::create('ebr_risk_element_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EBRRiskElement::class, 'ebr_risk_element_id')->constrained();
            $table->string('characteristic')->nullable();
            $table->string('key')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('risk_indicator')->nullable();
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_risk_element_indicators');
    }
};
