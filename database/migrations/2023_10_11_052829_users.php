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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->nullable();
            $table->string('token')->unique()->nullable();
            $table->string('phone_number')->nullable();
            $table->string('password')->nullable();
            $table->string('language')->nullable();
            $table->string('verify_code')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('role_id')->nullable();
            $table->integer('personal_info_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
