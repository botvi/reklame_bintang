<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'pelanggans';
    protected $fillable = ['kode_pelanggan', 'nama_pelanggan', 'no_hp_pelanggan', 'alamat_pelanggan'];
}
