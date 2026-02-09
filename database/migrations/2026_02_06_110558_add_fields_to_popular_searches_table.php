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
            $table->string('display_text', 50)->after('to_city');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('display_text');
            $table->integer('order')->default(0)->after('status');
            $table->integer('clicks')->default(0)->after('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('popular_searches', function (Blueprint $table) {
            $table->dropColumn(['display_text', 'status', 'order', 'clicks']);
        });
    }
};
