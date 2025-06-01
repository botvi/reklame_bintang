<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = ['nama_supplier', 'alamat_supplier', 'no_hp_supplier'];

    public function barang_masuk()
    {
        return $this->hasMany(BarangMasuk::class, 'supplier_id');
    }
}
