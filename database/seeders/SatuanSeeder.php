<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('satuans')->insert([
            // UNIT (satuan hitung benda)
            ['nama_satuan' => 'pcs',        'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'lembar',     'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'buah',       'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'butir',      'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'pasang',     'konversi_ke_dasar' => 2,      'jenis' => 'unit'],
            ['nama_satuan' => 'lusin',      'konversi_ke_dasar' => 12,     'jenis' => 'unit'],
            ['nama_satuan' => 'gross',      'konversi_ke_dasar' => 144,    'jenis' => 'unit'],
            ['nama_satuan' => 'kodi',       'konversi_ke_dasar' => 20,     'jenis' => 'unit'],
            ['nama_satuan' => 'rim',        'konversi_ke_dasar' => 500,    'jenis' => 'unit'],
            ['nama_satuan' => 'pak',        'konversi_ke_dasar' => 20,     'jenis' => 'unit'],
            ['nama_satuan' => 'box',        'konversi_ke_dasar' => 10,     'jenis' => 'unit'],
            ['nama_satuan' => 'set',        'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'ikat',       'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'karung',     'konversi_ke_dasar' => 1,      'jenis' => 'unit'],

            // BERAT (satuan berat Indonesia)
            ['nama_satuan' => 'mg',         'konversi_ke_dasar' => 0.001,  'jenis' => 'berat'],
            ['nama_satuan' => 'gram',       'konversi_ke_dasar' => 1,      'jenis' => 'berat'],
            ['nama_satuan' => 'ons',        'konversi_ke_dasar' => 100,    'jenis' => 'berat'],
            ['nama_satuan' => 'pon',        'konversi_ke_dasar' => 500,    'jenis' => 'berat'],
            ['nama_satuan' => 'kg',         'konversi_ke_dasar' => 1000,   'jenis' => 'berat'],
            ['nama_satuan' => 'kwintal',    'konversi_ke_dasar' => 100000, 'jenis' => 'berat'],
            ['nama_satuan' => 'ton',        'konversi_ke_dasar' => 1000000,'jenis' => 'berat'],

            // VOLUME (satuan volume Indonesia)
            ['nama_satuan' => 'ml',         'konversi_ke_dasar' => 1,      'jenis' => 'volume'],
            ['nama_satuan' => 'cc',         'konversi_ke_dasar' => 1,      'jenis' => 'volume'],
            ['nama_satuan' => 'liter',      'konversi_ke_dasar' => 1000,   'jenis' => 'volume'],
            ['nama_satuan' => 'gelas',      'konversi_ke_dasar' => 200,    'jenis' => 'volume'], // rata-rata 200 ml
            ['nama_satuan' => 'botol',      'konversi_ke_dasar' => 600,    'jenis' => 'volume'], // rata-rata 600 ml
            ['nama_satuan' => 'kaleng',     'konversi_ke_dasar' => 330,    'jenis' => 'volume'], // rata-rata 330 ml
            ['nama_satuan' => 'galon',      'konversi_ke_dasar' => 19000,  'jenis' => 'volume'], // 1 galon = 19 liter (Indonesia)
            ['nama_satuan' => 'drum',       'konversi_ke_dasar' => 200000, 'jenis' => 'volume'], // rata-rata 200 liter
            ['nama_satuan' => 'kubik',      'konversi_ke_dasar' => 1000000,'jenis' => 'volume'], // 1 m³ = 1000 liter

            // PANJANG (satuan panjang Indonesia)
            ['nama_satuan' => 'mm',         'konversi_ke_dasar' => 0.1,    'jenis' => 'panjang'],
            ['nama_satuan' => 'cm',         'konversi_ke_dasar' => 1,      'jenis' => 'panjang'],
            ['nama_satuan' => 'm',          'konversi_ke_dasar' => 100,    'jenis' => 'panjang'],
            ['nama_satuan' => 'km',         'konversi_ke_dasar' => 100000, 'jenis' => 'panjang'],
            ['nama_satuan' => 'inci',       'konversi_ke_dasar' => 2.54,   'jenis' => 'panjang'],
            ['nama_satuan' => 'yard',       'konversi_ke_dasar' => 91.44,  'jenis' => 'panjang'],
            ['nama_satuan' => 'mil',        'konversi_ke_dasar' => 160934, 'jenis' => 'panjang'],

            // LUAS (satuan luas Indonesia)
            ['nama_satuan' => 'm2',         'konversi_ke_dasar' => 1,      'jenis' => 'luas'],
            ['nama_satuan' => 'are',        'konversi_ke_dasar' => 100,    'jenis' => 'luas'],
            ['nama_satuan' => 'hektar',     'konversi_ke_dasar' => 10000,  'jenis' => 'luas'],

            // LAIN-LAIN
            ['nama_satuan' => 'sachet',     'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'ampul',      'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'tube',       'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'pouch',      'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
            ['nama_satuan' => 'roll',       'konversi_ke_dasar' => 1,      'jenis' => 'unit'],
        ]);
    }
}
