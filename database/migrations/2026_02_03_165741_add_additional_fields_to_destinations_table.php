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
        Schema::table('destinations', function (Blueprint $table) {
            $table->string('category')->nullable()->after('location');
            $table->string('best_time')->nullable()->after('category');
            $table->json('tags')->nullable()->after('best_time');
            $table->boolean('status')->default(true)->after('tags');
            $table->string('meta_title')->nullable()->after('status');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('seo_keywords')->nullable()->after('meta_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn([
                'category',
                'best_time',
                'tags',
                'status',
                'meta_title',
                'meta_description',
                'seo_keywords'
            ]);
        });
    }
};
