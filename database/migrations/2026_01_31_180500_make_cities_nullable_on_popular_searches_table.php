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
        Schema::table('popular_searches', function (Blueprint $table) {
            $table->string('from_city')->nullable()->change();
            $table->string('to_city')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('popular_searches', function (Blueprint $table) {
            $table->string('from_city')->nullable(false)->change();
            $table->string('to_city')->nullable(false)->change();
        });
    }
};
