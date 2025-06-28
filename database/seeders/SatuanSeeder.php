<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('satuans')->insert([
            // UNIT (pcs, box, pack, lembar, lusin)
            ['nama_satuan' => 'pcs',     'konversi_ke_dasar' => 1,     'jenis' => 'unit'],
            ['nama_satuan' => 'lembar',  'konversi_ke_dasar' => 1,     'jenis' => 'unit'],
            ['nama_satuan' => 'lusin',   'konversi_ke_dasar' => 12,    'jenis' => 'unit'],
            ['nama_satuan' => 'box',     'konversi_ke_dasar' => 10,    'jenis' => 'unit'],
            ['nama_satuan' => 'pak',     'konversi_ke_dasar' => 20,    'jenis' => 'unit'],
            ['nama_satuan' => 'rim',     'konversi_ke_dasar' => 500,   'jenis' => 'unit'],

            // BERAT (gram, ons, kg, ton, kwintal)
            ['nama_satuan' => 'gram',    'konversi_ke_dasar' => 1,        'jenis' => 'berat'],
            ['nama_satuan' => 'ons',     'konversi_ke_dasar' => 100,      'jenis' => 'berat'],
            ['nama_satuan' => 'kg',      'konversi_ke_dasar' => 1000,     'jenis' => 'berat'],
            ['nama_satuan' => 'kwintal', 'konversi_ke_dasar' => 100000,   'jenis' => 'berat'],
            ['nama_satuan' => 'ton',     'konversi_ke_dasar' => 1000000,  'jenis' => 'berat'],

            // VOLUME (ml, liter, galon, drum, kubik)
            ['nama_satuan' => 'ml',      'konversi_ke_dasar' => 1,        'jenis' => 'volume'],
            ['nama_satuan' => 'liter',   'konversi_ke_dasar' => 1000,     'jenis' => 'volume'],
            ['nama_satuan' => 'galon',   'konversi_ke_dasar' => 19000,    'jenis' => 'volume'], // 1 galon = 19 liter (Indonesia)
            ['nama_satuan' => 'drum',    'konversi_ke_dasar' => 200000,   'jenis' => 'volume'], // rata-rata 200 liter
            ['nama_satuan' => 'kubik',   'konversi_ke_dasar' => 1000000,  'jenis' => 'volume'], // 1 m³ = 1000 liter
        ]);
    }
}
