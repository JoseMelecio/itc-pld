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
        Schema::table('ebr_configurations', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('ebrs', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

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

        Schema::table('system_logs', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::dropIfExists('tenants');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('ebr_configurations', function (Blueprint $table) {
            $table->foreignId('tenant_id')->constrained('tenants');
        });

        Schema::table('ebrs', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');
        });

        Schema::table('pld_notice', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');
        });

        Schema::table('system_logs', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained('tenants');
        });
    }
};
