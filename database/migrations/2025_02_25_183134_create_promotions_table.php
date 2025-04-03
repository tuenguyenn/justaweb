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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->string('code',20);
            $table->text('description')->nullable();
            $table->string('method');
            $table->json('discountInformation')->nullable();
            $table->tinyInteger('publish')->default(1);
            $table->tinyInteger('order')->default(0);
            $table->string('neverEndDate')->default(null);
            $table->timestamp('startDate');
            $table->timestamp('endDate');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
