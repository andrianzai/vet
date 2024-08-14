<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Penjualan;

class PenjualanController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('content.penjualan.index', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|exists:barangs,kode_barang',
            'jumlah' => 'required|integer|min:1',
        ]);

        $barang = Barang::where('kode_barang', $request->kode_barang)->first();

        if ($barang->stok_barang < $request->jumlah) {
            return back()->withErrors('Stok tidak mencukupi!');
        }

        $totalHarga = $barang->harga_barang * $request->jumlah;

        Penjualan::create([
            'kode_barang' => $request->kode_barang,
            'jumlah' => $request->jumlah,
            'total_harga' => $totalHarga,
        ]);

        // Kurangi stok barang
        $barang->kurangiStok($request->jumlah);

        return redirect()->back()->with('success', 'Penjualan berhasil!');
    }
}
