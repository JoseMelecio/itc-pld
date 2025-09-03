<?php

use App\Models\EBR;
use App\Models\EBRRiskElementIndicator;
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
        Schema::create('ebr_risk_element_indicator_related', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EBR::class, 'ebr_id')->constrained();
            $table->foreignIdFor(EBRRiskElementIndicator::class, 'ebr_risk_element_indicator_id');
            $table->string('characteristic')->nullable();
            $table->string('key')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('risk_indicator')->nullable();
            $table->integer('order')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->integer('related_clients')->nullable();
            $table->integer('related_operations')->nullable();
            $table->decimal('weight_range_impact', 8,2);
            $table->decimal('frequency_range_impact', 8,2);
            $table->decimal('characteristic_concentration', 8,2);
            $table->timestamps();

            $table->foreign('ebr_risk_element_indicator_id', 'ebr_risk_element_indicator_id_fk')->references('id')->on('ebr_risk_element_indicators');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_risk_element_indicator_related');
    }
};
