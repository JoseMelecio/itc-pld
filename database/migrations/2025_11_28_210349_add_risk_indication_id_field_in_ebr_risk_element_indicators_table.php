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
            $table->dropColumn('risk_indicator');
            $table->foreignIdFor(EBRRiskElement::class, 'risk_element_id')->after('name')->constrained();
            $table->string('type')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebr_risk_element_indicators', function (Blueprint $table) {
            $table->string('risk_indicator')->after('name');
            $table->dropForeign(['risk_element_id']);
            $table->dropColumn('risk_element_id');
            $table->dropColumn('type');
        });
    }
};
