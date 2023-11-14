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
       Schema::table('addresses', function (Blueprint $table) {
            // $table->renameColumn('region', 'name')->nullable();
            $table->dropColumn('district');
            $table->integer('city_id')->nullable();
            $table->integer('postcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            //
        });
    }
};
