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
        Schema::create('pld_notice_addresses', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignIdFor(PLDNoticeNotice::class, 'pld_notice_notice_id')->constrained();
            $table->enum('type', ['national', 'foreign']);
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('settlement')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('street')->nullable();
            $table->string('external_number')->nullable();
            $table->string('internal_number')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pld_notice_addresses');
    }
};
