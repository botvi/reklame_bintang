<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk membuat user super admin, kasir, dan pemilik toko.
     *
     * @return void
     */
    public function run()
    {
        // Cek dan buat user admin jika belum ada
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Admin',
                'no_wa' => '081234567890',
                'role' => 'admin',
                'profil' => '',
                'password' => Hash::make('password'), // Ganti 'password' dengan password yang lebih aman di production
            ]
        );

        // Cek dan buat user kasir jika belum ada
        User::updateOrCreate(
            ['username' => 'kasir'],
            [
                'nama' => 'Kasir',
                'no_wa' => '081234567890',
                'role' => 'kasir_toko',
                'profil' => '',
                'password' => Hash::make('password'), // Ganti 'password' dengan password yang lebih aman di production
            ]
        );

        // Cek dan buat user pemilik toko jika belum ada
        User::updateOrCreate(
            ['username' => 'pemilik'],
            [
                'nama' => 'Pemilik',
                'no_wa' => '081234567890',
                'role' => 'pemilik_toko',
                'profil' => '',
                'password' => Hash::make('password'), // Ganti 'password' dengan password yang lebih aman di production
            ]
        );
    }
}