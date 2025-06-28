<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;
    protected $table = 'barang_masuks';
    protected $fillable = ['user_id', 'kode_barang', 'supplier_id', 'nama_barang', 'gambar', 'tanggal_kadaluarsa', 'stok_awal', 'satuan_id', 'harga_persatuan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class);
    }
}
