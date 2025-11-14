<?php

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
        Schema::create('blocked_person_massive', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignIdFor(User::class, 'user_id')->constrained();
            $table->string('file_uploaded')->nullable();
            $table->string('download_file_name')->nullable();
            $table->enum('status', ['pending', 'done', 'error'])->default('pending');
            $table->boolean('import_done')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_person_massive');
    }
};
