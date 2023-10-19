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
        Schema::table('companies', function (Blueprint $table) {
            $table->foreign('address_id')->references('id')->on('addresses');
        });

        Schema::table('sizes', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('categories');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->foreign('size_id')->references('id')->on('sizes');
            $table->foreign('color_id')->references('id')->on('colors');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('uploads', function (Blueprint $table) {
            $table->foreign('order_detail_id')->references('id')->on('order_details');
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('personal_info_id')->references('id')->on('personal_infos');
            $table->foreign('address_id')->references('id')->on('addresses');

        });

        Schema::table('user_verifies', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
