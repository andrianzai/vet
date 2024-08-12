<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->string('kode_transaksi')->primary();
            $table->integer('jumlah_barang');
            $table->integer('total');
            $table->dateTime('tanggal_transaksi');
            $table->string('keterangan');
            $table->foreignId('kode_barang')->constrained('barang','kode_barang');
            $table->foreignId('kode_layanan')->constrained('layanan','kode_layanan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
