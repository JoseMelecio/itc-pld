<?php

use App\Models\PLDNotice;
use App\Models\User;
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
        Schema::create('pld_notice_massives', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(PLDNotice::class, 'pld_notice_id')->constrained();
            $table->string('file_uploaded');
            $table->string('original_name');
            $table->string('xml_generated')->nullable();
            $table->json('form_data')->nullable();
            $table->enum('status', ['processing', 'done', 'error'])->default('processing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pld_notice_massives');
    }
};
