<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;
    protected $table = 'barang_keluars';
    protected $fillable = ['user_id', 'barang_masuk_id', 'harga_jual', 'satuan_id', 'jumlah_beli', 'total_harga', 'diskon_terpakai', 'total_harga_setelah_diskon', 'pelanggan_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
    public function barang_masuk()
    {
        return $this->belongsTo(BarangMasuk::class);
    }
}
