<?php

use App\Models\EBRType;
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
            $table->dropForeign(['ebr_type_id']);
            $table->dropColumn('ebr_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ebrs', function (Blueprint $table) {
            $table->foreignIdFor(EbrType::class, 'ebr_type_id')->after('user_id')->constrained();
        });
    }
};
