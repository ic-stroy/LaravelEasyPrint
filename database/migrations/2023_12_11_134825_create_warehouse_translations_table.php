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
        Schema::create('warehouse_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('warehouse_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('lang', 100)->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_translations');
    }
};
