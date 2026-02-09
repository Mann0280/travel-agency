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
        Schema::table('account_contents', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
            $table->json('data')->nullable()->after('slug');
            
            // Make original columns nullable for now as we migrate to data column
            $table->enum('type', ['faq', 'about', 'policy'])->nullable()->change();
            $table->string('title')->nullable()->change();
            $table->text('content')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_contents', function (Blueprint $table) {
            $table->dropColumn(['slug', 'data']);
            
            $table->enum('type', ['faq', 'about', 'policy'])->nullable(false)->change();
            $table->string('title')->nullable(false)->change();
            $table->text('content')->nullable(false)->change();
        });
    }
};
