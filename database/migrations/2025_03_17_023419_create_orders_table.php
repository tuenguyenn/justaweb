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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code',30);
            $table->string('fullname');
            $table->string('phone',20);
            $table->string('email',50);
            $table->string('province_id',10)->nullable();
            $table->string('district_id',10)->nullable();
            $table->string('ward_id',10)->nullable();
            $table->string('address');
            $table->text('description')->nullable();
            $table->json('promotion')->nullable();
            $table->json('cart')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable(); // Cho phép NULL
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            
            $table->string('guest_cookie');
            $table->string('method',20)->nullable();
            $table->string('confirm',20)->default('pending');
            $table->string('delivery',20)->default('pending');
            $table->string('payment',20);
            $table->float('shipping')->default(0);



            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
