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
            $table->string('code');
            $table->string('name');
            $table->integer('price')->length(6); // Harga produk, tipe data medium integer
            $table->string('currency', 5); // Mata uang produk, tidak boleh null
            $table->integer('discount')->length(6)->default(0); // Diskon produk, default 0, total 10 digit, 2 di belakang koma
            $table->string('dimension', 50)->nullable(); // Dimensi produk, maksimal 50 karakter, bisa kosong
            $table->string('unit', 5)->nullable(); // Satuan jual produk, maksimal 5 karakter, bisa kosong
            $table->timestamps();
            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
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
