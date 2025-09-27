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
            $table->float('diskon_terpakai', 20)->nullable()->after('total_harga');
            $table->float('total_harga_setelah_diskon', 20)->nullable()->after('diskon_terpakai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_keluars', function (Blueprint $table) {
            $table->dropColumn(['diskon_terpakai', 'total_harga_setelah_diskon']);
        });
    }
};
