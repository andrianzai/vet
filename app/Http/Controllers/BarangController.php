<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Barang; 

class BarangController extends Controller
{
    public function index ()
    {
        return view('content.stokBarang');
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'  => 'required|unique:barangs',
            'nama_barang'  => 'required',
            'stok_barang'  => 'required|integer',
            'harga_barang' => 'required|numeric',
        ]);

        Barang::create($request->all());

        return redirect()->route('content.stokBarang')
                         ->with('success','Barang sudah di tambahkan');
    }
}
