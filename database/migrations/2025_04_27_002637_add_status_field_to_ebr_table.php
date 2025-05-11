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
        Schema::table('ebrs', function (Blueprint $table) {
            $table->enum('status', ['processing', 'done'])->default('processing')->after('file_name_operations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebrs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
