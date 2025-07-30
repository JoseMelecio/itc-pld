<?php

use App\Models\EBRClient;
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
        Schema::table('ebr_operations', function (Blueprint $table) {
            $table->dropForeign(['ebr_client_id']);

            $table->foreignIdFor(EBRClient::class, 'ebr_client_id')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebr_operations', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\EBRClient::class, 'ebr_client_id')
                ->nullable(false)
                ->change();

            $table->foreign('ebr_client_id')->references('id')->on('ebr_clients');
        });
    }
};
