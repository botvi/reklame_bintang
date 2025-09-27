<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('satuans')->insert([
            // Satuan yang digunakan sesuai permintaan
            ['nama_satuan' => 'rim',      'konversi_ke_dasar' => 500,    'jenis' => 'unit'],
            ['nama_satuan' => 'lembar',   'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'roll',     'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'liter',    'konversi_ke_dasar' => 1000,   'jenis' => 'volume'],
            ['nama_satuan' => 'ml',       'konversi_ke_dasar' => 1,      'jenis' => 'volume'],
            ['nama_satuan' => 'kg',       'konversi_ke_dasar' => 1000,   'jenis' => 'berat'],
            ['nama_satuan' => 'botol',    'konversi_ke_dasar' => 600,    'jenis' => 'volume'], // rata-rata 600 ml
            ['nama_satuan' => 'pack',     'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'meter',    'konversi_ke_dasar' => 100,    'jenis' => 'panjang'],
            ['nama_satuan' => 'm²',       'konversi_ke_dasar' => 1,      'jenis' => 'luas'],
            ['nama_satuan' => 'box',      'konversi_ke_dasar' => 10,     'jenis' => 'unit'],
        ]);
    }
}
