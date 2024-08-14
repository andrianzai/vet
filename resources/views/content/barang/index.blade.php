@extends('layout.template')
@section('title')

@section('content')

    <h2 class="mb-4">Data Barang</h2>
    <div class="container mt-3">
        <!-- Alert placeholder -->
        <div id="alertPlaceholder"></div>
    </div>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBarangModal">Tambah Barang</button>
    <table id="barangTable" class="table table-striped">
        <thead>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Stok Barang</th>
                <th>Harga Barang</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangs as $barang)
            <tr data-id="{{ $barang->kode_barang }}">
                <td>{{ $barang->kode_barang }}</td>
                <td contenteditable="true" class="editable" data-field="nama_barang" data-id="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</td>
                <td contenteditable="true" class="editable" data-field="stok_barang" data-id="{{ $barang->stok_barang }}">{{ $barang->stok_barang }}</td>
                <td contenteditable="true" class="editable" data-field="harga_barang" data-id="{{ $barang->harga_barang }}">{{ $barang->harga_barang }}</td>
                <td contenteditable="true" class="editable" data-field="keterangan" data-id="{{ $barang->keterangan }}">{{ $barang->keterangan }}</td>
                <td>
                    <button class="btn btn-danger btn-sm delete-barang">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Tambah Barang -->
    <div class="modal fade" id="addBarangModal" tabindex="-1" aria-labelledby="addBarangModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addBarangForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBarangModalLabel">Tambah Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kode_barang" class="form-label">Kode Barang</label>
                            <input type="text" class="form-control" id="kode_barang" name="kode_barang" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" required>
                        </div>
                        <div class="mb-3">
                            <label for="stok_barang" class="form-label">Stok Barang</label>
                            <input type="number" class="form-control" id="stok_barang" name="stok_barang" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_barang" class="form-label">Harga Barang</label>
                            <input type="number" class="form-control" id="harga_barang" name="harga_barang" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Confirm -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin melanjutkan?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmButton" class="btn btn-primary">Ya, Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery harus di-load sebelum JavaScript lainnya -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>

    function showAlert(message, type, reload = false) {
        var alertPlaceholder = document.getElementById('alertPlaceholder');
        var alertHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        alertPlaceholder.innerHTML = alertHTML;

        // Jika reload adalah true, lakukan reload halaman setelah 2 detik
        if (reload) {
            setTimeout(function() {
                location.reload();
            }, 1000); // Waktu tunggu sebelum reload (2 detik)
        } else {
            // Hapus alert secara otomatis setelah 5 detik jika tidak ada reload
            setTimeout(function() {
                alertPlaceholder.innerHTML = '';
            }, 5000);
        }
    }

    function showConfirm(callback) {
        var confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'), {
            keyboard: false
        });

        document.getElementById('confirmButton').onclick = function() {
            callback();
            confirmModal.hide();
        };

        confirmModal.show();
    }
    
    $(document).ready(function() {
        // Simpan data baru
        $('#addBarangForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "{{ route('barang.store') }}",
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Gagal menambah data');
                }
            });
        });

        // Edit inline
        $('.editable').on('blur', function() {
            var kode_barang = $(this).data('id');
            var field_name = $(this).data('field');
            var value = $(this).text();

            $.ajax({
                method: "PUT",
                url: "/barang/" + kode_barang,
                data: {
                    field_name: field_name,
                    value: value,
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    // alert('Data berhasil diperbarui');
                    showAlert('Data berhasil diupdate', 'success');
                },
                error: function(xhr) {
                    // alert('Gagal memperbarui data');
                    showAlert('Terjadi kesalahan saat mengupdate data.', 'danger');
                    console.error(xhr.responseText); // Untuk debugging lebih lanjut
                }
            });
        });

        // Hapus barang
        $('.delete-barang').on('click', function() {
            var kode_barang = $(this).closest('tr').data('id');

            showConfirm(function() {
                $.ajax({
                    type: "DELETE",
                    url: "/barang/" + kode_barang,
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // location.reload();
                        showAlert('Data berhasil dihapus', 'success',true);
                    },
                    error: function(xhr) {
                        // alert('Gagal menghapus data');
                        showAlert('Terjadi kesalahan saat menghapus data.', 'danger');
                        console.error(xhr.responseText); // Untuk debugging lebih lanjut
                    }
                });
            });
        });
    });
    </script>
    
@endsection