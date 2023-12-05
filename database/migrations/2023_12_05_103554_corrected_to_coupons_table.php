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
        Schema::table('coupons', function (Blueprint $table) {
            // $table->dropForeign('warehouse_product_id');
            // $table->dropForeign('category_id');

            $table->dropColumn('category_id');
            $table->dropColumn('warehouse_product_id');
            $table->dropColumn('product_id');


            $table->double('min_price', 15, 8)->nullable();
            $table->integer('order_count')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->foreign('company_id')->references('id')->on('companies');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            //
        });
    }
};
