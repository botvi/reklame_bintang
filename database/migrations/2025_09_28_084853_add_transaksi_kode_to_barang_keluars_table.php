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
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->string('transaksi_kode', 50)->nullable()->index()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->dropIndex(['transaksi_kode']);
            $table->dropColumn('transaksi_kode');
        });
    }
};
