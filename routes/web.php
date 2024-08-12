<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;

Route::get('/', function () {
    return view('content.dashboard');
});

Route::get('/transaksi', function () {
    return view('content.transaksi');
});

Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');

Route::put('/barang/{product_code}', [BarangController::class, 'update'])->name('barang.update');
Route::delete('/barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
