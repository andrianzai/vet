<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Models\Barang;
use App\Models\Penjualan;

Route::post('/penjualan/checkout', [PenjualanController::class, 'checkout']);

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

    public function checkout(Request $request)
    {
        $items = $request->input('items');

        try {
            DB::beginTransaction();

            foreach ($items as $item) {
                $barang = Barang::where('kode_barang', $item['kode_barang'])->first();

                if ($barang) {
                    // Kurangi stok barang
                    $barang->stok_barang -= $item['jumlah'];
                    $barang->save();

                    // Hitung total harga
                    $total_harga = $item['jumlah'] * $barang->harga_barang;

                    // Simpan detail penjualan
                    Penjualan::create([
                        'kode_barang' => $item['kode_barang'],
                        'jumlah' => $item['jumlah'],
                        'harga_barang' => $barang->harga_barang,
                        'total_harga' => $total_harga,  // Pastikan total_harga diisi dengan benar
                    ]);
                }
            }

            DB::commit();

            return response()->json(['success' => 'Pembayaran berhasil. Resi sedang dicetak.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat checkout: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memproses pembayaran.'], 500);
        }
    }
}
