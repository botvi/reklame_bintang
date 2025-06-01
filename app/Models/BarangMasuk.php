<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory; 
    protected $table = 'barang_masuks';
    protected $fillable = ['user_id', 'kode_barang', 'satuan_id', 'supplier_id', 'nama_barang', 'harga_satuan', 'stok_barang', 'total_harga', 'tanggal_kadaluarsa', 'gambar'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
