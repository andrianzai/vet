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

<div class="row">
    <div class="col-md-4">
        <h4>Pilih Barang</h4>
        <form id="addItemForm">
            <div class="mb-3">
                <label for="kode_barang" class="form-label">Pilih Barang</label>
                <select class="form-select" id="kode_barang" required>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->kode_barang }}" data-harga="{{ $barang->harga_barang }}" data-stok="{{ $barang->stok_barang }}">
                            {{ $barang->nama_barang }} (Stok: {{ $barang->stok_barang }}, Harga: {{ $barang->harga_barang }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" required min="1">
            </div>
            <button type="button" id="addItem" class="btn btn-primary">Tambahkan</button>
        </form>
    </div>

    <div class="col-md-8">
        <h4>Barang yang Dipilih</h4>
        <form action="{{ route('penjualan.store') }}" method="POST">
            @csrf
            <table class="table table-bordered" id="itemsTable">
                <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Items will be dynamically added here -->
                </tbody>
            </table>
            <div class="text-end">
                <h4>Total Keseluruhan: <span id="totalKeseluruhan">0</span></h4>
            </div>
            <button type="submit" class="btn btn-success">Proses Penjualan</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('addItem').addEventListener('click', function() {
    const kodeBarang = document.getElementById('kode_barang');
    const selectedValue = kodeBarang.value; // This should be the kode_barang
    const jumlah = parseInt(document.getElementById('jumlah').value);

    console.log('Selected Kode Barang:', selectedValue); // Debugging line

    if (kodeBarang.value && jumlah > 0) {
        const namaBarang = kodeBarang.options[kodeBarang.selectedIndex].text.split('(')[0].trim();
        const hargaBarang = parseFloat(kodeBarang.options[kodeBarang.selectedIndex].dataset.harga);

        // Check if the item already exists in the table
        let exists = false;
        const rows = document.querySelectorAll('#itemsTable tbody tr');
        rows.forEach(row => {
            const existingKodeBarang = row.cells[0].innerText;
            if (existingKodeBarang === kodeBarang.value) {
                // Update the existing row's quantity and total
                const existingJumlah = parseInt(row.cells[2].innerText);
                const newJumlah = existingJumlah + jumlah;
                row.cells[2].innerHTML = `<input type="hidden" name="jumlah[]" value="${newJumlah}">${newJumlah}`;
                const newTotal = newJumlah * hargaBarang;
                row.cells[4].innerText = newTotal.toFixed(2);
                exists = true;
            }
        });

        // If the item does not exist, add a new row
        if (!exists) {
            const table = document.getElementById('itemsTable').getElementsByTagName('tbody')[0];
            const row = table.insertRow();

            const total = jumlah * hargaBarang;

            row.innerHTML = `
                <td><input type="hidden" name="kode_barang[]" value="${kodeBarang.value}">${kodeBarang.value}</td>
                <td>${namaBarang}</td>
                <td><input type="hidden" name="jumlah[]" value="${jumlah}">${jumlah}</td>
                <td>${hargaBarang}</td>
                <td>${total.toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-item">Hapus</button></td>
            `;
        }

        // Update total keseluruhan
        updateTotalKeseluruhan();

        // Reset form
        document.getElementById('addItemForm').reset();
    }
    });

    document.getElementById('itemsTable').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('tr').remove();
        // Update total keseluruhan
        updateTotalKeseluruhan();
    }
    });

    function updateTotalKeseluruhan() {
    let totalKeseluruhan = 0;
    const rows = document.querySelectorAll('#itemsTable tbody tr');
    rows.forEach(row => {
        const total = parseFloat(row.cells[4].innerText);
        totalKeseluruhan += total;
    });
    document.getElementById('totalKeseluruhan').innerText = totalKeseluruhan.toFixed(2);
    }
</script>
@endsection