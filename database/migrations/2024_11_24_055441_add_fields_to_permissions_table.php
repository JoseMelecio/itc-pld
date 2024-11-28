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
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('to')->after('guard_name')->nullable(true);
            $table->string('icon')->after('to')->nullable(true);
            $table->boolean('heading')->after('icon')->default(false);
            $table->string('menu_label')->after('heading')->nullable(true);
            $table->foreignIdFor(\Spatie\Permission\Models\Permission::class)->nullable(true)->constrained();
            $table->integer('order_to_show')->after('menu_label')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('to');
            $table->dropColumn('icon');
            $table->dropColumn('heading');
            $table->dropColumn('menu_label');
            $table->dropForeign('permissions_permission_id_foreign');
            $table->dropColumn('permission_id');
        });
    }
};
