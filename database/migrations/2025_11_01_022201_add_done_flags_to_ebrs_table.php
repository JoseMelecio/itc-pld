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
            $table->boolean('import_clients_done')->after('file_name_clients')->default(false);
            $table->boolean('import_operations_done')->after('file_name_operations')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebrs', function (Blueprint $table) {
            $table->dropColumn('import_clients_done');
            $table->dropColumn('import_operations_done');
        });
    }
};
