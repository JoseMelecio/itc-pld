<?php

use App\Models\PLDNotice;
use App\Models\PLDNoticeMassive;
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
        Schema::create('pld_notice_notices', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignIdFor(PldNotice::class, 'pld_notice_id')->constrained();
            $table->foreignIdFor(PLDNoticeMassive::class, 'pld_notice_massive_id')->constrained();
            $table->string('hash')->nullable();
            $table->string('modification_folio')->nullable();
            $table->string('modification_description')->nullable();
            $table->string('priority')->nullable();
            $table->string('alert_type')->nullable();
            $table->string('alert_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pld_notice_notices');
    }
};
