@extends('layouts.app')
@section('title', 'Detail Barang')
@section('content')
@php
    $fotoUrl = null;
    if (!empty($barang->foto_barang)) {
        $fotoUrl = \Illuminate\Support\Str::startsWith($barang->foto_barang, 'donasi/')
            ? asset('storage/' . $barang->foto_barang)
            : asset('reshare/uploads/barang/' . $barang->foto_barang);
    }
@endphp
<div class="container px-4 py-4">
    <a href="{{ route('barang.index') }}" class="text-decoration-none text-dark fw-semibold back-link"><i class="bi bi-chevron-left me-2"></i>Kembali ke Barang</a>
    <div class="row g-4 mt-2">
        <div class="col-lg-5">
            <div class="detail-image">
                @if($fotoUrl)
                    <img src="{{ $fotoUrl }}" alt="{{ $barang->nama_barang }}">
                @else
                    <i class="bi bi-image fs-1"></i>
                @endif
            </div>
        </div>
        <div class="col-lg-7">
            <div class="detail-card">
                <span class="status-pill">{{ $barang->status }}</span>
                <h3 class="fw-bold mt-3">{{ $barang->nama_barang }}</h3>
                <p class="text-muted">{{ $barang->kategori->nama_kategori ?? 'Tanpa kategori' }} · {{ $barang->kondisi_barang }}</p>
                <hr>
                <h6 class="fw-bold">Deskripsi Barang</h6>
                <p class="text-secondary">{{ $barang->deskripsi }}</p>
                <p><i class="bi bi-geo-alt text-teal me-2"></i>{{ $barang->lokasi }}</p>
                <p><i class="bi bi-person text-teal me-2"></i>{{ $barang->user->nama ?? $barang->user->name ?? 'Donatur ReShare' }}</p>

                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-teal px-4 py-2"><i class="bi bi-heart me-2"></i>Ajukan Permintaan</a>

                    @auth
                        @if(auth()->id() === $barang->user_id || auth()->user()->role === 'admin')
                            <a href="{{ route('donasi.index') }}" class="btn btn-outline-secondary px-4 py-2"><i class="bi bi-box2-heart me-2"></i>Kelola di Donasi (AJAX)</a>
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.barang.edit', $barang->id) }}" class="btn btn-outline-warning px-4 py-2"><i class="bi bi-pencil-square me-2"></i>Edit (Admin)</a>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
