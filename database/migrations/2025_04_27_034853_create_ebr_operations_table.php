<?php

use App\Models\EBR;
use App\Models\EBRClients;
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
            $table->id();
            $table->foreignIdFor(EBR::class, 'ebr_id')->constrained();
            $table->foreignIdFor(EBRClients::class, 'ebr_client_id')->constrained();
            $table->string('operation_folio')->nullable();
            $table->string('operation_date')->nullable();
            $table->string('operation_time')->nullable();
            $table->string('client_user_id_performed_operation')->nullable();
            $table->string('account_or_contract_number')->nullable();
            $table->string('operation_type')->nullable();
            $table->decimal('operation_amount', 15, 2)->default(0);
            $table->string('currency_type_used')->nullable();
            $table->string('amount_equivalent_mxn')->nullable();
            $table->string('exchange_rate')->nullable();
            $table->string('monetary_instrument')->nullable();
            $table->string('office_or_establishment')->nullable();
            $table->string('deposit_bank_account')->nullable();
            $table->string('resource_reception_operation')->nullable();
            $table->string('operation_status')->nullable();
            $table->string('payment_frequency')->nullable();
            $table->timestamps();
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

