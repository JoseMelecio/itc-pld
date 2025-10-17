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
        Schema::create('pld_notice_people', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignIdFor(PLDNoticeNotice::class, 'pld_notice_notice_id')->constrained();
            $table->enum('person_notice_type', ['object', 'beneficiary', 'representative']);
            $table->enum('person_type', ['individual', 'legal', 'trust']);
            $table->string('hash')->nullable();
            $table->string('name_or_company')->nullable();
            $table->string('paternal_last_name')->nullable();
            $table->string('maternal_last_name')->nullable();
            $table->string('birth_or_constitution_date')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('personal_id')->nullable();
            $table->string('nationality')->nullable();
            $table->string('business_activity')->nullable();
            $table->string('trust_identification')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pld_notice_people');
    }
};
