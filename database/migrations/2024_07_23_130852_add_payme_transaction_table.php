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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('paycom_transaction_id');
            $table->string('paycom_time');
            $table->datetime('paycom_time_datetime');
            $table->datetime('create_time');
            $table->string('create_time_unix');
            $table->datetime('perform_time')->nullable();
            $table->string('perform_time_unix')->nullable();
            $table->datetime('cancel_time')->nullable();
            $table->string('cancel_time_unix')->nullable();
            $table->integer('amount');
            $table->smallInteger('state');
            $table->smallInteger('reason')->nullable();
            $table->string('receivers',500)->nullable();
            $table->integer('order_id');
            $table->timestamps();
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
