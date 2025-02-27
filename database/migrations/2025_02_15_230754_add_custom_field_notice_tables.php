<?php

use App\Models\CustomField;
use App\Models\PLDNotice;
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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->string('validation');
            $table->json('validation_message')->nullable();
            $table->json('data_select')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pld_notice_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PLDNotice::class)->constrained('pld_notice');
            $table->foreignIdFor(CustomField::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('pld_notice_custom_fields');
        Schema::dropIfExists('custom_fields');
    }
};
