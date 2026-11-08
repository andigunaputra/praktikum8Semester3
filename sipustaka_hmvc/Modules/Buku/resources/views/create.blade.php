
@extends('layouts.app')

@section('title', 'Tambah Buku')
@section('header', 'Tambah Buku')

@section('content')

<style>
    .form-container {
        max-width: 650px;
    }

    .form-container p {
        color: #777;
        font-size: 14px;
        margin-bottom: 22px;
    }

    .form-group {
        margin-bottom: 17px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        color: #34445a;
        font-size: 14px;
        font-weight: 600;
    }

    .form-group input {
        width: 100%;
        padding: 11px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 14px;
    }

    .form-group input:focus {
        outline: none;
        border-color: #244b80;
    }

    .error {
        color: #c0392b;
        font-size: 13px;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 22px;
    }

    .btn-simpan {
        background: #244b80;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-kembali {
        background: #eee;
        color: #333;
        padding: 10px 18px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
    }
</style>

<div class="form-container">
    <h3 style="color:#243b5a; margin-bottom:5px;">Tambah Data Buku</h3>
    <p>Isi informasi buku yang akan ditambahkan ke perpustakaan.</p>

    <form action="{{ route('buku.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Masukkan judul buku">
            @error('judul')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Penulis</label>
            <input type="text" name="penulis" value="{{ old('penulis') }}" placeholder="Masukkan nama penulis">
            @error('penulis')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Penerbit</label>
            <input type="text" name="penerbit" value="{{ old('penerbit') }}" placeholder="Masukkan nama penerbit">
            @error('penerbit')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Tahun Terbit</label>
            <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}" placeholder="Contoh: 2024">
            @error('tahun_terbit')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Stok Buku</label>
            <input type="number" name="stok" value="{{ old('stok') }}" placeholder="Masukkan jumlah stok">
            @error('stok')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-simpan">Simpan Buku</button>
            <a href="{{ route('buku.index') }}" class="btn-kembali">Kembali</a>
        </div>
    </form>
</div>

@endsection
