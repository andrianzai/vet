<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;
    protected $fillable = ['kode_pet','nama_pet','kategori_pet','jenis_pet','keterangan','tanggal_daftar','nic'];
}
