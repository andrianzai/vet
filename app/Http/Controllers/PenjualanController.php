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
            'kode_barang.*' => 'required|exists:barangs,kode_barang',
            'jumlah.*' => 'required|integer|min:1',
        ]);

        $items = $request->input('kode_barang');
        $jumlahs = $request->input('jumlah');

        foreach ($items as $index => $kodeBarang) {
            $barang = Barang::where('kode_barang', $kodeBarang)->first();

            if ($barang->stok_barang < $jumlahs[$index]) {
                return back()->withErrors("Stok tidak mencukupi untuk barang {$barang->nama_barang}!");
            }

            $totalHarga = $barang->harga_barang * $jumlahs[$index];

            Penjualan::create([
                'kode_barang' => $kodeBarang,
                'jumlah' => $jumlahs[$index],
                'total_harga' => $totalHarga,
            ]);

            // Kurangi stok barang
            $barang->kurangiStok($jumlahs[$index]);
        }

        return redirect()->back()->with('success', 'Penjualan berhasil diproses!');
    }
}
