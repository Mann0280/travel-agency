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
        Schema::table('packages', function (Blueprint $table) {
            $table->string('location')->nullable();
            $table->string('category')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->json('itinerary')->nullable();
            $table->json('inclusions')->nullable();
            $table->json('exclusions')->nullable();
            $table->json('things_to_carry')->nullable();
            $table->json('terms_conditions')->nullable();
            $table->json('contact_info')->nullable();
            $table->json('available_months')->nullable();
            $table->integer('duration_days')->default(1);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'location', 'category', 'rating', 'reviews_count', 'status',
                'itinerary', 'inclusions', 'exclusions', 'things_to_carry',
                'terms_conditions', 'contact_info', 'available_months',
                'duration_days', 'start_date', 'end_date'
            ]);
        });
    }
};
