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
        Schema::table('warehouses', function (Blueprint $table) {
            $table->bigInteger('material_id')->nullable();
            $table->string('manufacturer_country')->nullable();
            $table->string('material_composition')->nullable();
            $table->json('images')->nullable();
            $table->text('description')->nullable()->default('text');

        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->foreign('material_id')->references('id')->on('materials');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('warehouses', function (Blueprint $table) {
            //
        });
    }
};
