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
        Schema::create('owner_pets', function (Blueprint $table) {
            $table->string('nic')->primary();
            $table->string('nama');
            $table->integer('phone');
            $table->string('alamat');
            $table->string('keterangan');
            $table->foreignId('kode_pet')->constrained('pet','kode_pet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_pets');
    }
};
