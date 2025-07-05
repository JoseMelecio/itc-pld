<?php

use App\Models\EBR;
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
        Schema::create('ebr_risk_element_related', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EBR::class, 'ebr_id')->constrained();
            $table->foreignIdFor(EBRRiskElement::class, 'ebr_risk_element_id')->constrained();
            $table->string('element');
            $table->decimal('amount_mxn', 15,2);
            $table->integer('total_clients');
            $table->integer('total_operations');
            $table->decimal('weight_range_impact', 8,2);
            $table->decimal('frequency_range_impact', 8,2);
            $table->decimal('risk_inherent_concentration', 8,2);
            $table->decimal('risk_level_features', 8,2);
            $table->decimal('risk_level_integrated', 8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_risk_element_related');
    }
};
