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
        Schema::create('widgets', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('keyword')->unique();
                $table->text('description')->nullable();
                $table->longText('album')->nullable();
                $table->longText('model_id')->nullable();
                $table->string('model')->nullable();
                $table->string('short_code')->unique();
                $table->tinyInteger('publish')->default(1);
                $table->tinyInteger('order')->default(1);
                $table->timestamp('deleted_at')->nullable();
    
                $table->timestamps();
            });
      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widgets');
    }
};
