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
        try {
            // Validasi input
            $validated = $request->validate([
                'field_name' => 'required|string',
                'value' => 'required'
            ]);
    
            // Cari barang berdasarkan kode_barang
            $barang = Barang::findOrFail($kode_barang);
    
            // Update hanya field yang diterima
            $field_name = $validated['field_name'];
            $value = $validated['value'];
    
            // Pastikan bahwa field_name yang dikirim adalah salah satu dari field yang diperbolehkan
            if (in_array($field_name, ['nama_barang', 'stok_barang', 'harga_barang', 'keterangan'])) {
                // Update field dengan nilai baru
                $barang->$field_name = $value;
                $barang->save();
            } else {
                return response()->json(['error' => 'Field tidak valid'], 422);
            }
    
            return response()->json(['success' => 'Data barang berhasil diupdate']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat mengupdate data.'], 500);
        }
    }

    public function destroy($kode_barang)
    {
        $barang = Barang::findOrFail($kode_barang);
        $barang->delete();
        return response()->json(['success' => 'Barang berhasil dihapus']);
    }
}
