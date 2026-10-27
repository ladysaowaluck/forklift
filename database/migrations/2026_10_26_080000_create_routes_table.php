<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('routes')) {
            Schema::create('routes', function (Blueprint $table) {
            $table->id('route_id');
            $table->unsignedBigInteger('origin_warehouse_id');
            $table->unsignedBigInteger('dest_warehouse_id');
            $table->decimal('distance', 8, 2)->comment('Distance in kilometers (KM)');
            $table->timestamps();

            $table->unique(['origin_warehouse_id', 'dest_warehouse_id'], 'uq_route_pair');
            $table->foreign('origin_warehouse_id')->references('warehouse_id')->on('warehouses')->onDelete('cascade');
            $table->foreign('dest_warehouse_id')->references('warehouse_id')->on('warehouses')->onDelete('cascade');
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
