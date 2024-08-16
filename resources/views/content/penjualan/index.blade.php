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

<!-- Modal Konfirmasi Pembayaran -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="confirmList">
                        <!-- Daftar barang akan diisi di sini oleh JavaScript -->
                    </tbody>
                </table>
                <h4 class="text-end">Total Bayar: <span id="totalBayar"></span></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmPayment">Lanjutkan Pembayaran & Cetak Resi</button>
            </div>
        </div>
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

    // Simpan data barang yang akan diproses
    var listBarang = []; 

    // Function untuk menambah barang ke dalam list
    function addToCart(kode_barang, nama_barang, jumlah, harga) {
        var found = false;

        // Cek jika barang sudah ada di list, maka update jumlahnya
        listBarang.forEach(function(item) {
            if (item.kode_barang === kode_barang) {
                item.jumlah += jumlah;
                found = true;
            }
        });

        // Jika barang belum ada di list, tambahkan barang baru
        if (!found) {
            listBarang.push({
                kode_barang: kode_barang,
                nama_barang: nama_barang,
                jumlah: jumlah,
                harga: harga,
                total: jumlah * harga
            });
        }

        updateCart();
    }

    // Function untuk memperbarui daftar barang di dalam modal
    function updateCart() {
        var confirmList = $("#confirmList");
        confirmList.empty(); // Kosongkan tabel

        var totalBayar = 0;

        // Tambahkan semua barang ke dalam tabel
        listBarang.forEach(function(item) {
            confirmList.append(
                "<tr>" +
                    "<td>" + item.kode_barang + "</td>" +
                    "<td>" + item.nama_barang + "</td>" +
                    "<td>" + item.jumlah + "</td>" +
                    "<td>" + item.harga + "</td>" +
                    "<td>" + item.total + "</td>" +
                "</tr>"
            );

            totalBayar += item.total;
        });

        // Tampilkan total bayar
        $("#totalBayar").text("Rp " + totalBayar.toLocaleString('id-ID'));

        // Tampilkan modal
        $('#confirmModal').modal('show');
    }

    // Event handler untuk tombol "Lanjutkan Pembayaran & Cetak Resi"
    $("#confirmPayment").click(function() {
        processPayment(listBarang);
    });

    // Function untuk memproses pembayaran dan mencetak resi
    function processPayment(listBarang) {
        // Lakukan request AJAX atau kirim form ke server untuk memproses pembayaran

        $.ajax({
            url: '/penjualan/checkout',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                items: listBarang
            },
            success: function(response) {
                alert('Pembayaran berhasil. Resi sedang dicetak.');
                window.location.reload(); // Reload untuk reset form
            },
            error: function(xhr) {
                console.error('Error:', xhr.responseText);
                alert('Terjadi kesalahan saat memproses pembayaran.');
            }
        });
    }
</script>
@endsection