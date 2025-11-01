<?php

use App\Models\EBRConfiguration;
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
        Schema::create('ebr_configuration_risk_element', function (Blueprint $table) {
            $table->foreignIdFor(EBRConfiguration::class, 'ebr_configuration_id')->constrained('ebr_configurations');
            $table->foreignIdFor(EBRRiskElement::class, 'risk_element_id')->constrained('ebr_risk_elements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_configuration_risk_element');
    }
};
