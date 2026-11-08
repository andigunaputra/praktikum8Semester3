@extends('layouts.app')
@section('title', 'Pengajuan')
@section('content')
<h1>Barang yang Bisa Diajukan</h1>
<p>Halaman ini menampilkan barang berstatus tersedia. Form pengajuan lanjutan bisa dibuat sesuai instruksi tugas.</p>
<div class="grid">
    @forelse ($barangs as $barang)
        <div class="card">
            <h3>{{ $barang->nama_barang }}</h3>
            <p>{{ $barang->kategori->nama_kategori ?? '-' }} · {{ $barang->lokasi }}</p>
            <p>{{ $barang->kondisi_barang }}</p>
            <a href="{{ route('barang.detail', $barang->id) }}">Lihat detail</a>
        </div>
    @empty
        <div class="card">Tidak ada barang yang tersedia saat ini.</div>
    @endforelse
</div>
@endsection
