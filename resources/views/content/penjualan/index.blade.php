@extends('layout.template')
@section('title','Penjualan')

@section('content')
    <h2>Kasir Penjualan</h2>
        
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
    @endif

    <form action="{{ route('penjualan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="kode_barang" class="form-label">Pilih Barang</label>
            <select class="form-select" name="kode_barang" id="kode_barang" required>
                <option value="">Pilih barang...</option>
                @foreach($barangs as $barang)
                <option value="{{ $barang->kode_barang }}">
                    {{ $barang->nama_barang }} (Stok: {{ $barang->stok_barang }}, Harga: {{ $barang->harga_barang }})
                </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" class="form-control" id="jumlah" name="jumlah" required min="1">
        </div>
        <button type="submit" class="btn btn-primary">Proses Penjualan</button>
    </form>
@endsection