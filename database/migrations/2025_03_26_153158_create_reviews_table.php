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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); 
            $table->integer('parent_id')->default(0);
            $table->integer('lft')->default(0);
            $table->integer('rgt')->default(0);
            $table->integer('level')->default(0);
            $table->string('reviewable_type');
            $table->integer('reviewable_id');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade'); 
            $table->tinyInteger('score'); 
            $table->text('description')->nullable(); 
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Trạng thái duyệt
            $table->timestamps(); 
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
