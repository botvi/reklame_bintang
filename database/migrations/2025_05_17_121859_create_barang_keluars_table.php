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
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete()->cascadeOnUpdate()->nullable();
            $table->foreignId('barang_masuk_id')->constrained('barang_masuks')->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('jumlah_beli', 20); // dalam satuan yang digunakan saat keluar
            $table->float('harga_jual', 20);
            $table->float('total_harga', 20);
            $table->foreignId('satuan_id')->constrained('satuans')->cascadeOnDelete()->cascadeOnUpdate(); // satuan saat keluar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluars');
    }
};
