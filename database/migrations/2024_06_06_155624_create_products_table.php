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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code', 18)->unique();
            $table->string('product_name', 30);
            $table->decimal('price', 16, 2);
            $table->string('currency', 5)->nullable();
            $table->decimal('discount', 8, 2)->nullable();
            $table->string('dimension', 50)->nullable();
            $table->string('unit', 5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
