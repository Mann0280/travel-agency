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
        Schema::table('agencies', function (Blueprint $table) {
            $table->string('contact_person')->nullable()->after('name');
            $table->string('alternate_phone')->nullable()->after('phone');
            $table->string('address_line1')->nullable()->after('address');
            $table->string('address_line2')->nullable()->after('address_line1');
            $table->string('city')->nullable()->after('address_line2');
            $table->string('state')->nullable()->after('city');
            $table->string('country')->default('India')->after('state');
            $table->string('pincode')->nullable()->after('country');
            $table->integer('experience_years')->default(0)->after('pincode');
            $table->decimal('commission_percentage', 5, 2)->default(10.00)->after('experience_years');
            $table->decimal('rating', 3, 2)->default(5.00)->after('commission_percentage');
            $table->string('status')->default('pending')->after('rating');
            $table->string('bank_name')->nullable()->after('status');
            $table->string('account_holder')->nullable()->after('bank_name');
            $table->string('account_number')->nullable()->after('account_holder');
            $table->string('ifsc_code')->nullable()->after('account_number');
            $table->string('gst_number')->nullable()->after('ifsc_code');
            $table->string('pan_number')->nullable()->after('gst_number');
            $table->string('license_number')->nullable()->after('pan_number');
            $table->string('payment_terms')->nullable()->after('license_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agencies', function (Blueprint $table) {
            $table->dropColumn([
                'contact_person', 'alternate_phone', 'address_line1', 'address_line2',
                'city', 'state', 'country', 'pincode', 'experience_years',
                'commission_percentage', 'rating', 'status', 'bank_name',
                'account_holder', 'account_number', 'ifsc_code', 'gst_number',
                'pan_number', 'license_number', 'payment_terms'
            ]);
        });
    }
};
