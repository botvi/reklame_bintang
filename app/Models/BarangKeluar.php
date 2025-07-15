<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    use HasFactory;
    protected $table = 'barang_keluars';
    protected $fillable = ['user_id', 'barang_masuk_id', 'harga_jual', 'satuan_id', 'jumlah_beli', 'total_harga'];

    public function user()
    {
        return $this->belongsTo(User::class);
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
