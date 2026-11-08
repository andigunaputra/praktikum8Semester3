@extends('layouts.app')
@section('title', 'Barang Donasi')
@section('content')
@php($kategoris = \App\Models\Kategori::orderBy('nama_kategori')->get())
<div class="container px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4"><div><h4 class="fw-bold text-dark mb-1">Semua Barang Donasi</h4><p class="text-muted small mb-0">Temukan barang layak pakai yang kamu butuhkan.</p></div></div>
    <div class="row g-4"><div class="col-lg-3"><div class="card category-filter p-3"><h6 class="fw-bold text-dark mb-3">Filter Kategori</h6><div class="d-flex flex-column gap-2"><a href="{{ route('barang.index') }}" class="text-decoration-none small {{ !request('kategori') ? 'text-teal fw-bold' : 'text-muted' }}">Semua Kategori</a>@foreach($kategoris as $kategori)<a href="{{ route('barang.index', ['kategori' => $kategori->id]) }}" class="text-decoration-none small {{ request('kategori') == $kategori->id ? 'text-teal fw-bold' : 'text-muted' }}">{{ $kategori->nama_kategori }}</a>@endforeach</div></div></div>
    <div class="col-lg-9"><div class="row g-4">@forelse($barangs as $barang)
        @php
            $fotoUrl = null;
            if (!empty($barang->foto_barang)) {
                $fotoUrl = \Illuminate\Support\Str::startsWith($barang->foto_barang, 'donasi/')
                    ? asset('storage/' . $barang->foto_barang)
                    : asset('reshare/uploads/barang/' . $barang->foto_barang);
            }
        @endphp
        <div class="col-md-6 col-xl-4"><a href="{{ route('barang.detail', $barang->id) }}" class="text-decoration-none h-100 d-block"><div class="item-card h-100 d-flex flex-column p-2"><div class="img-placeholder">@if($fotoUrl)<img src="{{ $fotoUrl }}" alt="{{ $barang->nama_barang }}">@else<i class="bi bi-image fs-1"></i>@endif</div><div class="p-3 flex-grow-1 d-flex flex-column justify-content-between"><div><h6 class="fw-bold text-dark mb-1 text-truncate">{{ $barang->nama_barang }}</h6><p class="text-muted small mb-2 item-meta">{{ $barang->kategori->nama_kategori ?? 'Tanpa kategori' }} · {{ $barang->kondisi_barang }}</p><p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($barang->deskripsi, 75) }}</p></div><div class="d-flex justify-content-between align-items-center mt-2"><span class="small text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $barang->lokasi }}</span><span class="status-pill">{{ $barang->status }}</span></div></div></div></a></div>@empty<div class="col-12"><div class="empty-state"><i class="bi bi-box2 fs-1 mb-2"></i><p class="mb-0">Belum ada barang yang sesuai.</p></div></div>@endforelse</div></div></div>
</div>
@endsection
