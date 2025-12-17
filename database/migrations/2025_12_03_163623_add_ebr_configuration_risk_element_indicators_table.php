<?php

use App\Models\EBRConfiguration;
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
        Schema::create('ebr_configuration_risk_element_indicators', function (Blueprint $table) {
            $table->unsignedBigInteger('ebr_configuration_id');
            $table->unsignedBigInteger('risk_indicator_id');

            $table->foreign('ebr_configuration_id', 'fk_config_id')
                ->references('id')->on('ebr_configurations');

            $table->foreign('risk_indicator_id', 'fk_risk_id')
                ->references('id')->on('ebr_risk_element_indicators');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_configuration_risk_element_indicators');
    }
};
