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
        Schema::create('pets', function (Blueprint $table) {
            $table->string('kode_pet')->primary();
            $table->string('nama_pet');
            $table->string('kategori_pet');
            $table->string('jenis_pet');
            $table->string('keterangan');
            $table->dateTime('tanggal_daftar');
            $table->foreignId('owner_pet')->constrained('owner_pet','nic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
