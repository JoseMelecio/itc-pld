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
        Schema::table('ebr_risk_element_indicators', function (Blueprint $table) {
            $table->dropForeign(['ebr_risk_element_id']);
            $table->dropColumn('ebr_risk_element_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebr_risk_element_indicators', function (Blueprint $table) {
            $table->foreignIdFor(EBRRiskElement::class, 'ebr_risk_element_id')->constrained();
        });
    }
};
