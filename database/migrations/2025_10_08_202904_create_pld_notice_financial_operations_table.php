<?php

use App\Models\PLDNoticeNotice;
use App\Models\PLDNoticePerson;
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
        Schema::create('pld_notice_financial_operations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignIdFor(PLDNoticeNotice::class, 'pld_notice_notice_id')->constrained();
            $table->string('monetary_instrument')->nullable();
            $table->string('currency')->nullable();
            $table->string('amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pld_notice_financial_operations');
    }
};
