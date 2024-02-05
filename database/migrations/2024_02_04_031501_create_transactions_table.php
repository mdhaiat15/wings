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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // ID dokumen, biasanya auto-increment
            $table->foreignId('user_id')->constrained();
            $table->string('doc_code', 3); // Kode dokumen, maksimal 3 karakter
            $table->string('doc_number', 10); // Nomor dokumen, maksimal 10 karakter
            $table->decimal('total', 10, 2); // Total dokumen, total 10 digit, 2 di belakang koma
            $table->date('transaksi_date'); // Tanggal dokumen
            $table->timestamps(); // Waktu pembuatan dan pembaruan rekaman
            $table->unsignedBigInteger('created_by_id')->default(0);
            $table->unsignedBigInteger('updated_by_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
