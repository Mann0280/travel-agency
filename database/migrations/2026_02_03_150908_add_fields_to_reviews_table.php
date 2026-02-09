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
        Schema::table('reviews', function (Blueprint $table) {
            //
            $table->string('title')->nullable();
            $table->string('category')->nullable();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->boolean('featured')->default(false);
            $table->integer('helpful_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['title', 'category', 'status', 'featured', 'helpful_count']);
        });
    }
};
