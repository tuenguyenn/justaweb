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
        Schema::table('post_catalogue_language', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable(); // Soft delete column
            $table->timestamps(); // Adds 'created_at' and 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_catalogue_language', function (Blueprint $table) {
            $table->dropColumn(['deleted_at', 'created_at', 'updated_at']);
        });
    }
};
