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
            // Address fields
            $table->string('address_line1')->nullable()->after('phone');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->string('city')->nullable()->after('address_line2');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->default('India')->after('state');
            $table->string('pincode')->nullable()->after('country');
            
            // Travel preferences
            $table->json('preferences')->nullable()->after('pincode');
            $table->string('budget_range')->nullable()->after('preferences');
            $table->string('preferred_duration')->nullable()->after('budget_range');
            
            // Emergency contact
            $table->string('emergency_name')->nullable()->after('preferred_duration');
            $table->string('emergency_phone')->nullable()->after('emergency_name');
            $table->string('emergency_relationship')->nullable()->after('emergency_phone');
            
            // Account info
            $table->timestamp('last_login')->nullable()->after('emergency_relationship');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active')->after('last_login');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'address_line1', 'address_line2', 'city', 'state', 'country', 'pincode',
                'preferences', 'budget_range', 'preferred_duration',
                'emergency_name', 'emergency_phone', 'emergency_relationship',
                'last_login', 'status'
            ]);
        });
    }
};
