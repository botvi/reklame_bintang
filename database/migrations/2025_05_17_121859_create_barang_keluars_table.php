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
            $table->foreignId('barang_masuk_id')->constrained('barang_masuks')->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('jumlah_beli'); // dalam satuan yang digunakan saat keluar
            $table->float('harga_persatuan');
            $table->float('total_harga');
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
