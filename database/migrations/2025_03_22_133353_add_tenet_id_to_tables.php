<?php

use App\Models\Tenant;
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
        $adminTenant = Tenant::where('name', 'admin')->first();
        Schema::table('permissions', function (Blueprint $table) use ($adminTenant) {
            $table->foreignIdFor(\App\Models\Tenant::class)->after('id')->default($adminTenant->id)->constrained();
        });

        Schema::table('pld_notice', function (Blueprint $table) use ($adminTenant) {
            $table->foreignIdFor(\App\Models\Tenant::class)->after('id')->default($adminTenant->id)->constrained();
        });

        Schema::table('system_logs', function (Blueprint $table) use ($adminTenant) {
            $table->foreignIdFor(\App\Models\Tenant::class)->after('id')->default($adminTenant->id)->constrained();
        });

        Schema::table('users', function (Blueprint $table) use ($adminTenant) {
            $table->foreignIdFor(\App\Models\Tenant::class)->after('id')->default($adminTenant->id)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('pld_notice', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('system_logs', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};
