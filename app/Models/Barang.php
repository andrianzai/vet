<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    // use HasFactory;

    protected $table = 'barangs'; // Nama tabel
    protected $primaryKey = 'kode_barang'; // Primary key yang digunakan
    public $incrementing = false; // Jika primary key bukan auto-increment
    protected $keyType = 'string'; // Jika tipe primary key adalah string
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'stok_barang',
        'harga_barang',
        'keterangan'
    ];

    public $timestamps = false;
}
