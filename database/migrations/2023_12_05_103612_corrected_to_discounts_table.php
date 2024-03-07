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
        Schema::table('discounts', function (Blueprint $table) {
//             $table->dropForeign('company_id');
//             $table->dropForeign('category_id');
//
            $table->dropColumn('company_id');
            $table->dropColumn('category_id');

            $table->integer('warehouse_id')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            //
        });
    }
};
