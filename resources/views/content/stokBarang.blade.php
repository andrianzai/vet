@extends('layout.template')
@section('title', 'Stok Barang')

@section('content')
    <h1 class="mb-4">Daftar Barang</h1>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#inputBarangModal">
        Tambah Barang
    </button>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Barcode</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $barang)
                <tr>
                    <td>{{ $barang->product_name }}</td>
                    <td>{{ $barang->product_code }}</td>
                    <td>{{ $barang->price }}</td>
                    <td>{{ $barang->stock }}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBarangModal{{ $barang->product_code }}">
                            Edit
                        </button>
                        <form action="{{ route('barang.destroy', $barang->product_code) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit Barang -->
                <div class="modal fade" id="editBarangModal{{ $barang->product_code }}" tabindex="-1" aria-labelledby="editBarangModalLabel{{ $barang->product_code }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('barang.update', $barang->product_code) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editBarangModalLabel{{ $barang->product_code }}">Edit Barang</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="nama{{ $barang->product_code }}" class="form-label">Nama Barang</label>
                                        <input type="text" class="form-control" id="nama{{ $barang->product_code }}" name="nama" value="{{ $barang->nama }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="barcode{{ $barang->product_code }}" class="form-label">Barcode</label>
                                        <input type="text" class="form-control" id="barcode{{ $barang->product_code }}" name="barcode" value="{{ $barang->barcode }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jumlah{{ $barang->product_code }}" class="form-label">Jumlah</label>
                                        <input type="number" class="form-control" id="jumlah{{ $barang->product_code }}" name="jumlah" value="{{ $barang->jumlah }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="inputBarangModal" tabindex="-1" aria-labelledby="inputBarangModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="inputBarangModalLabel">Input Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input type="text" class="form-control" id="product_code" name="product_code" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="text" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection