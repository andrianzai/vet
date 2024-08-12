<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = ['kode_transaksi','jumlah_barang','total','tanggal_transaksi','keterangan','kode_produk','kode_layanan'];
}
