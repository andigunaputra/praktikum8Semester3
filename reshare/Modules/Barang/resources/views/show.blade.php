@extends('layouts.app')
@section('title', 'Detail Barang Donasi - Admin')

@section('content')
<div class="container px-4 py-4">
    <div class="mb-4">
        <a href="{{ route('admin.barang.index') }}" class="text-decoration-none text-dark fw-semibold d-inline-flex align-items-center">
            <i class="bi bi-chevron-left me-2 fw-bold text-teal"></i>Kembali ke Daftar Barang
        </a>
        <h4 class="fw-bold text-dark mt-2 mb-1">Detail Barang Donasi #{{ $barang->id }}</h4>
        <p class="text-muted small mb-0">Informasi lengkap data barang donasi di platform ReShare.</p>
    </div>

    @php
        $fotoUrl = null;
        if ($barang->foto_barang) {
            if (\Illuminate\Support\Str::startsWith($barang->foto_barang, 'donasi/')) {
                $fotoUrl = asset('storage/' . $barang->foto_barang);
            } else {
                $fotoUrl = asset('reshare/uploads/barang/' . $barang->foto_barang);
            }
        }
    @endphp

    <div class="card border-0 shadow-sm mx-auto" style="border-radius: 14px; max-width: 900px; overflow: hidden;">
        <div class="row g-0">
            <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-4 border-end">
                @if($fotoUrl)
                    <div class="text-center">
                        <img src="{{ $fotoUrl }}" alt="{{ $barang->nama_barang }}" class="img-fluid rounded shadow-sm mb-3" style="max-height: 280px; object-fit: contain;">
                        <div>
                            <a href="{{ $fotoUrl }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-box-arrow-up-right me-1"></i>Buka Gambar Asli
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-image fs-1 mb-2 d-block"></i>
                        <p class="small mb-0">Tidak ada foto barang</p>
                    </div>
                @endif
            </div>

            <div class="col-md-7 p-4 p-md-5 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-1">
                            {{ $barang->kategori->nama_kategori ?? 'Tanpa Kategori' }}
                        </span>
                        @if($barang->status === 'Tersedia')
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1">Tersedia</span>
                        @elseif($barang->status === 'Diproses')
                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-1">Diproses</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1">Disalurkan</span>
                        @endif
                    </div>

                    <h3 class="fw-bold text-dark mb-3">{{ $barang->nama_barang }}</h3>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Kondisi Barang</small>
                            <span class="fw-semibold text-dark">{{ $barang->kondisi_barang }}</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Lokasi Barang</small>
                            <span class="fw-semibold text-dark"><i class="bi bi-geo-alt me-1 text-danger"></i>{{ $barang->lokasi }}</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Donatur / Pemilik</small>
                            <span class="fw-semibold text-dark"><i class="bi bi-person me-1 text-secondary"></i>{{ $barang->user->nama ?? $barang->user->name ?? '-' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <small class="text-muted d-block">Waktu Ditambahkan</small>
                            <span class="fw-semibold text-dark">{{ $barang->created_at ? $barang->created_at->format('d M Y, H:i') : '-' }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2">Deskripsi Barang:</h6>
                        <div class="p-3 bg-light rounded text-secondary" style="white-space: pre-line;">
                            {{ $barang->deskripsi }}
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 pt-3 border-top">
                    <a href="{{ route('admin.barang.edit', $barang->id) }}" class="btn btn-teal px-4">
                        <i class="bi bi-pencil-square me-1"></i>Edit Data
                    </a>
                    <form method="POST" action="{{ route('admin.barang.destroy', $barang->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger px-3">
                            <i class="bi bi-trash me-1"></i>Hapus
                        </button>
                    </form>
                    <a href="{{ route('admin.barang.index') }}" class="btn btn-light border px-3 ms-auto">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
