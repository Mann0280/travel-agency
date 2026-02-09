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
        Schema::table('users', function (Blueprint $table) {
            // Note: Enum modifications in MySQL/Laravel require a fresh enum definition
            // We ensure 'user', 'admin', 'agency', and 'agent' are all supported to prevent mismatches
            $table->enum('role', ['user', 'admin', 'agency', 'agent'])->default('user')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'agent', 'admin'])->default('user')->change();
        });
    }
};
