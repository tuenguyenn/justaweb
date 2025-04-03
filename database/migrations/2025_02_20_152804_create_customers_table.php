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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_catalogue_id');

            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('province_id',10)->nullable();
            $table->string('district_id',10)->nullable();
            $table->string('ward_id',10)->nullable();
            $table->string('address')->nullable();
            $table->dateTime('birthday')->nullable();
            $table->string('image')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('ip')->nullable();
            $table->tinyInteger('publish')->default(1);
            $table->foreign('customer_catalogue_id')->references('id')->on('customer_catalogues');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamp('deleted_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
