<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;
    protected $table = 'satuans';
    protected $fillable = ['nama_satuan', 'konversi_ke_dasar', 'jenis'];
    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }
    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class);
    }
}
