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
       Schema::create('cart_items', function (Blueprint $table) {
            $table->string('id'); // Không auto-increment
            $table->uuid('rowId')->unique(); // Tương tự ShoppingCart
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); 
            $table->integer('qty');
            $table->string('name');
            $table->integer('price');
        
            $table->json('options'); // Lưu các thông tin bổ sung như image, attributes...
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
