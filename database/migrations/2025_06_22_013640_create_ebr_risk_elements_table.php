<?php

use App\Models\EBRType;
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
        Schema::create('ebr_risk_elements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(EBRType::class, 'ebr_type_id')->constrained();
            $table->integer('order');
            $table->string('risk_element');
            $table->string('lateral_header');
            $table->string('sub_header');
            $table->string('entity_grouper');
            $table->string('variable_grouper');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_risk_elements');
    }
};
