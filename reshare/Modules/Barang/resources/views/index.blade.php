@extends('layouts.app')
@section('title', 'Kelola Barang Donasi - Admin')

@section('content')
<div class="container-fluid px-4 px-lg-5 py-4">
    <!-- Header Page -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-speedometer2 me-1"></i>Admin Dashboard
                </a>
                <span class="text-muted small">/</span>
                <span class="small fw-semibold text-teal">Kelola Barang</span>
            </div>
            <h4 class="fw-bold text-dark mb-1">Pengelolaan Barang Donasi</h4>
            <p class="text-muted small mb-0">Kelola katalog barang donasi ReShare menggunakan modul CRUD Synchronous.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.barang.create') }}" class="btn btn-teal px-3 py-2 shadow-sm">
                <i class="bi bi-plus-circle me-2"></i>Tambah Barang
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary px-3 py-2">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('admin.barang.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Cari Nama / Deskripsi / Lokasi</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Filter Kategori</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" @selected(request('kategori_id') == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Filter Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach(['Tersedia', 'Diproses', 'Disalurkan'] as $st)
                            <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-teal w-100">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    @if(request()->hasAny(['search', 'kategori_id', 'status']))
                        <a href="{{ route('admin.barang.index') }}" class="btn btn-light border text-muted" title="Reset filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Barang -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam me-2 text-teal"></i>Daftar Barang Donasi</h6>
            <span class="badge bg-light text-dark border px-3 py-2 fw-medium">Total: {{ $barangs->total() }} Data</span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th style="width: 80px;">Foto</th>
                        <th>Nama Barang & Lokasi</th>
                        <th>Kategori</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Donatur</th>
                        <th class="text-center pe-4" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $index => $item)
                        @php
                            $fotoUrl = null;
                            if ($item->foto_barang) {
                                if (\Illuminate\Support\Str::startsWith($item->foto_barang, 'donasi/')) {
                                    $fotoUrl = asset('storage/' . $item->foto_barang);
                                } else {
                                    $fotoUrl = asset('reshare/uploads/barang/' . $item->foto_barang);
                                }
                            }
                        @endphp
                        <tr>
                            <td class="ps-4 text-muted small">{{ $barangs->firstItem() + $index }}</td>
                            <td>
                                @if($fotoUrl)
                                    <a href="{{ $fotoUrl }}" target="_blank" title="Klik untuk perbesar">
                                        <img src="{{ $fotoUrl }}" alt="{{ $item->nama_barang }}" class="rounded shadow-sm" style="width: 52px; height: 52px; object-fit: cover;">
                                    </a>
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 52px; height: 52px;">
                                        <i class="bi bi-image fs-4"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->nama_barang }}</div>
                                <div class="text-muted small"><i class="bi bi-geo-alt me-1 text-danger"></i>{{ $item->lokasi }}</div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info px-2 py-1">
                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="small fw-medium">{{ $item->kondisi_barang }}</span>
                            </td>
                            <td>
                                @if($item->status === 'Tersedia')
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">Tersedia</span>
                                @elseif($item->status === 'Diproses')
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1">Diproses</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">Disalurkan</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $item->user->nama ?? $item->user->name ?? 'Anonim' }}
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.barang.show', $item->id) }}" class="btn btn-outline-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.barang.edit', $item->id) }}" class="btn btn-outline-warning" title="Edit Barang">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.barang.destroy', $item->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang \'{{ addslashes($item->nama_barang) }}\'? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus Barang">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <p class="mb-0">Tidak ada data barang yang sesuai dengan kriteria pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($barangs->hasPages())
            <div class="card-footer bg-white py-3 px-4 border-top">
                {{ $barangs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
