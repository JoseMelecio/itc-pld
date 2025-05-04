<?php

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
        Schema::create('ebr_operations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('ebr_id');
            $table->uuid('ebr_customer_id');;
            $table->string('operation_folio');
            $table->string('operation_date');
            $table->string('operation_time');
            $table->string('client_user_id');
            $table->string('account_or_contract_number');
            $table->string('operation_type');
            $table->string('operation_amount');
            $table->string('currency_type');
            $table->string('equivalent_amount_mxn');
            $table->string('exchange_rate_used');
            $table->string('monetary_instrument');
            $table->string('operation_location');
            $table->string('external_bank_account');
            $table->string('resource_reception_operation');
            $table->string('operation_status');
            $table->string('payment_frequency');
            $table->timestamps();

            $table->foreign('ebr_id')->references('id')->on('ebrs');
            $table->foreign('ebr_customer_id')->references('id')->on('ebr_customers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebr_operations');
    }
};

