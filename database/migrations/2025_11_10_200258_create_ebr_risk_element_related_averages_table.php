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
        Schema::create('ebr_risk_element_related_averages', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignIdFor(EBR::class, 'ebr_id')->constrained();
            $table->foreignIdFor(EBRRiskElement::class, 'ebr_risk_element_id')->constrained();
            $table->decimal('average_risk_inherent_concentration', 10, 2)->default(0.00);
            $table->decimal('average_risk_level_features', 10, 2)->default(0.00);
            $table->decimal('average_risk_level_integrated', 10, 2)->default(0.00);
            $table->decimal('weight_impact_range_header', 10, 2)->default(0.00);
            $table->decimal('frequency_range_header', 10, 2)->default(0.00);
            $table->decimal('risk_inherent_concentration_header', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_risk_element_related_averages');
    }
};
