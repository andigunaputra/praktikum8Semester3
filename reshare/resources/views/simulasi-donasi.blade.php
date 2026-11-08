@extends('layouts.app')
@section('title', 'Simulasi Donasi')
@section('content')
<div class="card form">
    <h1>Simulasi Donasi Barang</h1>
    <p>Form ini khusus Modul 2: input diproses dan ditampilkan tanpa menyimpan ke database.</p>
    <form method="POST" action="{{ route('simulasi.proses') }}">
        @csrf
        <label for="nama_barang">Nama barang</label>
        <input id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}" required maxlength="150">
        <label for="kategori">Kategori</label>
        <select id="kategori" name="kategori" required>
            <option value="">-- Pilih kategori --</option>
            @foreach (['Pakaian','Buku & Alat Tulis','Peralatan Rumah','Elektronik','Mainan'] as $kategori)
                <option value="{{ $kategori }}" @selected(old('kategori') === $kategori)>{{ $kategori }}</option>
            @endforeach
        </select>
        <label for="kondisi">Kondisi barang</label>
        <select id="kondisi" name="kondisi" required>
            <option value="">-- Pilih kondisi --</option>
            @foreach (['Sangat Baik','Baik','Cukup'] as $kondisi)
                <option value="{{ $kondisi }}" @selected(old('kondisi') === $kondisi)>{{ $kondisi }}</option>
            @endforeach
        </select>
        <label for="lokasi">Lokasi</label>
        <input id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required maxlength="255">
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" required maxlength="1000">{{ old('deskripsi') }}</textarea>
        <p><button class="btn" type="submit">Proses Data</button></p>
    </form>
</div>
@endsection
