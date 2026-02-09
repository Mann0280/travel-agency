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
            if (!Schema::hasColumn('packages', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            if (!Schema::hasColumn('packages', 'travel_date')) {
                $table->date('travel_date')->nullable()->after('agency_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'image')) {
                $table->dropColumn('image');
            }
            if (Schema::hasColumn('packages', 'travel_date')) {
                $table->dropColumn('travel_date');
            }
        });
    }
};
