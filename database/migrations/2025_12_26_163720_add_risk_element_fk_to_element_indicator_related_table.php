<?php

use App\Models\EBRRiskElement;
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
        Schema::table('ebr_risk_element_indicator_related', function (Blueprint $table) {
            //$table->dropForeign('ebr_risk_element_indicator_id_fk');
            //$table->dropColumn('ebr_risk_element_indicator_id');

            $table->foreignIdFor(EBRRiskElement::class, 'ebr_risk_element_id')->constrained()->after('ebr_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebr_risk_element_indicator_related', function (Blueprint $table) {
            $table->dropForeign(['ebr_risk_element_id_fk']);
            $table->dropColumn('ebr_risk_element_id');

            $table->foreignIdFor(EBRRiskElementIndicator::class, 'ebr_risk_element_indicator_id_fk')->constrained()->after('ebr_id');
        });
    }
};
