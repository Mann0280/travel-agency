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
        Schema::table('stories', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('stories', 'destination')) {
                $table->string('destination')->nullable();
            }
            if (!Schema::hasColumn('stories', 'date')) {
                $table->date('date')->nullable();
            }
            if (!Schema::hasColumn('stories', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('stories', 'order')) {
                $table->integer('order')->default(0);
            }
            
            // Make original columns nullable since they aren't in the form
            $table->string('title')->nullable()->change();
            $table->text('content')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->dropColumn(['destination', 'date', 'is_active', 'order']);
            $table->string('title')->nullable(false)->change();
            $table->text('content')->nullable(false)->change();
        });
    }
};
