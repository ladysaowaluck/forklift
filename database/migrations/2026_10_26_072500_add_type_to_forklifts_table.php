<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('forklifts') && !Schema::hasColumn('forklifts', 'type')) {
            Schema::table('forklifts', function (Blueprint $table) {
                $table->string('type')->default('Standard')->after('plate_number');
            });
        }
    }

    public function down(): void
    {
        Schema::table('forklifts', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
