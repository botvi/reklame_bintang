<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;
    protected $table = 'barang_keluars';
    protected $fillable = ['user_id', 'barang_masuk_id', 'jumlah_keluar', 'total_harga'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barang_masuk()
    {
        return $this->belongsTo(BarangMasuk::class);
    }
}
