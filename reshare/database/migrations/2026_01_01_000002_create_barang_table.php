<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori')->cascadeOnDelete();
            $table->string('nama_barang', 150);
            $table->enum('kondisi_barang', ['Sangat Baik', 'Baik', 'Cukup'])->default('Baik');
            $table->text('deskripsi');
            $table->enum('status', ['Tersedia', 'Diproses', 'Disalurkan'])->default('Tersedia');
            $table->string('lokasi', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
