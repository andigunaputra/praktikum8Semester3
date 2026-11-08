@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
@php
    $kategoris = \App\Models\Kategori::orderBy('nama_kategori')->get();
    $jumlahDidonasikan = \App\Models\Barang::where('user_id', auth()->id())->count();
    $jumlahDiterima = \App\Models\Pengajuan::where('user_id', auth()->id())->where('status', 'Diterima')->count();
@endphp
<div class="container-fluid px-4 px-lg-5 py-4">
    <div class="row g-4 mb-5">
        <div class="col-lg-8"><div class="hero-banner d-flex flex-column justify-content-center h-100 shadow-sm"><div class="row align-items-center h-100"><div class="col-md-8 z-1 ps-md-4"><h2 class="fw-bold text-dark mb-2">Hai, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2><p class="text-secondary mb-4 fs-5">Mari berbagi barang berbagi manfaat</p><a href="{{ route('donasi.index') }}" class="btn btn-teal px-4 py-2"><i class="bi bi-heart me-2"></i>Donasi Sekarang</a></div><div class="col-md-4 d-none d-md-block text-end"><i class="bi bi-box2-heart hero-watermark"></i></div></div></div></div>
        <div class="col-lg-4"><div class="stat-card-container p-4 h-100"><h6 class="fw-bold text-dark mb-4">Ringkasan Aktivitas</h6><div class="stat-item"><div class="icon-box bg-info bg-opacity-10 text-info me-3"><i class="bi bi-box-seam"></i></div><div><h5 class="fw-bold mb-0 text-dark">{{ $jumlahDidonasikan }}</h5><p class="small text-muted mb-0">Barang Didonasikan</p></div></div><div class="stat-item"><div class="icon-box bg-success bg-opacity-10 text-success me-3"><i class="bi bi-people"></i></div><div><h5 class="fw-bold mb-0 text-dark">{{ $jumlahDiterima }}</h5><p class="small text-muted mb-0">Pengajuan Diterima</p></div></div><div class="stat-item"><div class="icon-box bg-danger bg-opacity-10 text-danger me-3"><i class="bi bi-heart"></i></div><div><h5 class="fw-bold mb-0 text-dark">{{ $jumlahPengajuan }}</h5><p class="small text-muted mb-0">Permintaan Saya</p></div></div></div></div>
    </div>
    <h5 class="fw-bold text-dark mb-3">Kategori</h5><div class="d-flex gap-3 overflow-x-auto pb-2 mb-5 hide-scrollbar"><a href="{{ route('barang.index') }}" class="p-3 cat-card d-flex align-items-center justify-content-center gap-2"><i class="bi bi-grid-fill cat-icon"></i>Semua</a>@foreach($kategoris as $kategori)<a href="{{ route('barang.index', ['kategori' => $kategori->id]) }}" class="p-3 cat-card d-flex align-items-center justify-content-center gap-2"><i class="bi {{ str_contains(strtolower($kategori->nama_kategori), 'pakaian') ? 'bi-bag' : (str_contains(strtolower($kategori->nama_kategori), 'buku') ? 'bi-book' : (str_contains(strtolower($kategori->nama_kategori), 'elektronik') ? 'bi-display' : 'bi-house')) }} cat-icon"></i>{{ $kategori->nama_kategori }}</a>@endforeach</div>
    <div class="d-flex justify-content-between align-items-center mb-3"><h5 class="fw-bold text-dark mb-0">Barang Terbaru</h5><a href="{{ route('barang.index') }}" class="text-decoration-none fw-semibold text-teal">Lihat semua</a></div>
    <div class="row g-4 mb-5">@forelse($barangs as $barang)
        @php
            $fotoUrl = null;
            if (!empty($barang->foto_barang)) {
                $fotoUrl = \Illuminate\Support\Str::startsWith($barang->foto_barang, 'donasi/')
                    ? asset('storage/' . $barang->foto_barang)
                    : asset('reshare/uploads/barang/' . $barang->foto_barang);
            }
        @endphp
        <div class="col-xl-3 col-lg-4 col-md-6"><a href="{{ route('barang.detail', $barang->id) }}" class="text-decoration-none h-100 d-block text-dark"><div class="item-card h-100 p-2 d-flex flex-column"><div class="img-placeholder mb-2">@if($fotoUrl)<img src="{{ $fotoUrl }}" alt="{{ $barang->nama_barang }}">@else<i class="bi bi-image fs-1"></i>@endif</div><div class="p-2 flex-grow-1 d-flex flex-column justify-content-between"><div><h6 class="fw-bold mb-1 text-truncate">{{ $barang->nama_barang }}</h6><p class="text-muted small mb-0 item-meta">{{ $barang->kategori->nama_kategori ?? 'Tanpa kategori' }} · {{ $barang->kondisi_barang }}</p></div><div class="mt-3 fw-medium item-location"><i class="bi bi-geo-alt me-1"></i>{{ $barang->lokasi }}</div></div></div></a></div>@empty<div class="col-12"><div class="empty-state"><i class="bi bi-box2 fs-1 mb-2"></i><p class="mb-0">Belum ada barang donasi yang tersedia saat ini.</p></div></div>@endforelse</div>
</div>
@endsection
