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
        Schema::create('satuans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan'); // Contoh: kg, ons, pcs
            $table->double('konversi_ke_dasar', 15, 4); // Misal: 1000.0000 gram untuk kg, bisa berkoma
            $table->string('jenis'); // berat = gram, volume = ml, unit = pcs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satuans');
    }
};
