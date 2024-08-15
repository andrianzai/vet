<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PenjualanController;

Route::get('/', function () {
    return view('content.dashboard');
});

//barang
Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
Route::put('/barang/{kode_barang}', [BarangController::class, 'update'])->name('barang.update');
Route::delete('/barang/{kode_barang}', [BarangController::class, 'destroy'])->name('barang.destroy');

//penjualan
Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
Route::get('/penjualan/cetak-resi/{id}', [PenjualanController::class, 'cetakResi'])->name('penjualan.cetakResi');