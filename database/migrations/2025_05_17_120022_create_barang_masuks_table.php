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
        Schema::create('barang_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('stok_awal'); // jumlah dalam satuan asli
            $table->float('harga_persatuan', 20);
            $table->float('harga_modal', 20);
            $table->float('harga_jual', 20);
            $table->float('max_pembelian_to_diskon', 20)->nullable();
            $table->float('diskon', 20)->nullable();
            $table->foreignId('satuan_id')->constrained('satuans')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuks');
    }
};
