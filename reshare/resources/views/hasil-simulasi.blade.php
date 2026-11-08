@extends('layouts.app')
@section('title', 'Hasil Simulasi')
@section('content')
<div class="card">
    <h1>Hasil Simulasi Donasi</h1>
    <p>Data berhasil divalidasi dan diproses. Sesuai latihan Modul 2, data ini tidak disimpan ke database.</p>
    <table>
        <tr><th>Nama barang</th><td>{{ $data['nama_barang'] }}</td></tr>
        <tr><th>Kategori</th><td>{{ $data['kategori'] }}</td></tr>
        <tr><th>Kondisi</th><td>{{ $data['kondisi'] }}</td></tr>
        <tr><th>Lokasi</th><td>{{ $data['lokasi'] }}</td></tr>
        <tr><th>Deskripsi</th><td>{{ $data['deskripsi'] }}</td></tr>
    </table>
    <p><a class="btn" href="{{ route('simulasi.create') }}">Input lagi</a></p>
</div>
@endsection
