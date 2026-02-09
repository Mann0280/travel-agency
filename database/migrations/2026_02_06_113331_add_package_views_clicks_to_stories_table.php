<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->foreignId('package_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            $table->integer('views')->default(0)->after('order');
            $table->integer('clicks')->default(0)->after('views');
        });
    }

    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn(['package_id', 'views', 'clicks']);
        });
    }
};
