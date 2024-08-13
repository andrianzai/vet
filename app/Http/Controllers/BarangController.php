<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\Log;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('content.barang.index', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|string|unique:barangs',
            'nama_barang' => 'required|string',
            'stok_barang' => 'required|integer',
            'harga_barang' => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        Barang::create($request->all());
        return response()->json(['success' => 'Barang berhasil ditambahkan']);
    }

    public function update(Request $request, $kode_barang)
    {
        Log::info($request->all);
        // $request->validate([
        //     'nama_barang' => 'required|string',
        //     'stok_barang' => 'required|integer',
        //     'harga_barang' => 'required|numeric',
        //     'keterangan' => 'nullable|string',
        // ]);
        
        $barang = Barang::findOrFail($kode_barang);
        $barang->update([
            $request->input('column') => $request->input('value'),
        ]);
        return response()->json(['success' => 'Barang berhasil diupdate']);
    }

    public function destroy($kode_barang)
    {
        $barang = Barang::findOrFail($kode_barang);
        $barang->delete();
        return response()->json(['success' => 'Barang berhasil dihapus']);
    }
}
