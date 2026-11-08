@extends('layouts.app')
@section('title', 'Edit Barang Donasi - Admin')

@section('content')
<div class="container px-4 py-4">
    <div class="mb-4">
        <a href="{{ route('admin.barang.index') }}" class="text-decoration-none text-dark fw-semibold d-inline-flex align-items-center">
            <i class="bi bi-chevron-left me-2 fw-bold text-teal"></i>Kembali ke Daftar Barang
        </a>
        <h4 class="fw-bold text-dark mt-2 mb-1">Edit Data Barang Donasi</h4>
        <p class="text-muted small mb-0">Ubah informasi barang donasi secara synchronous (Modul Barang).</p>
    </div>

    <div class="card border-0 shadow-sm mx-auto" style="border-radius: 14px; max-width: 860px;">
        <div class="card-body p-4 p-md-5">
            <form method="POST" action="{{ route('admin.barang.update', $barang->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <!-- Kolom Kiri: Foto Barang & Ganti Foto -->
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Foto Barang Saat Ini</label>
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
                        <div class="border rounded-3 p-3 text-center d-flex flex-column align-items-center justify-content-center bg-light position-relative" style="min-height: 260px; border-style: dashed !important; border-color: #25834b !important;">
                            @if($fotoUrl)
                                <img id="previewImage" src="{{ $fotoUrl }}" alt="{{ $barang->nama_barang }}" class="rounded mb-2 img-fluid" style="max-height: 180px; object-fit: contain;">
                                <div id="placeholderUpload" style="display: none;">
                                    <i class="bi bi-cloud-arrow-up text-teal" style="font-size: 3rem;"></i>
                                </div>
                                <div class="mt-1">
                                    <a href="{{ $fotoUrl }}" target="_blank" class="small text-teal text-decoration-none fw-semibold">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>Buka Foto Ukuran Penuh
                                    </a>
                                </div>
                            @else
                                <img id="previewImage" src="" alt="Preview Foto" class="rounded mb-2 img-fluid" style="display: none; max-height: 180px; object-fit: contain;">
                                <div id="placeholderUpload">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    <p class="small text-muted mt-2 mb-0">Belum ada foto</p>
                                </div>
                            @endif
                            <input type="file" name="foto_barang" id="foto_barang" class="form-control mt-3 @error('foto_barang') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg" onchange="previewFile(this)">
                        </div>
                        <small class="text-muted d-block text-center mt-2">Biarkan kosong jika tidak ingin mengganti foto (Maks 2 MB).</small>
                        @error('foto_barang')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kolom Kanan: Data Form -->
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" id="nama_barang" name="nama_barang" class="form-control @error('nama_barang') is-invalid @enderror" value="{{ old('nama_barang', $barang->nama_barang) }}" required maxlength="150">
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-sm-6">
                                <label for="kategori_id" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                <select id="kategori_id" name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" @selected(old('kategori_id', $barang->kategori_id) == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="kondisi_barang" class="form-label fw-semibold">Kondisi <span class="text-danger">*</span></label>
                                <select id="kondisi_barang" name="kondisi_barang" class="form-select @error('kondisi_barang') is-invalid @enderror" required>
                                    @foreach(['Sangat Baik', 'Baik', 'Cukup'] as $kondisi)
                                        <option value="{{ $kondisi }}" @selected(old('kondisi_barang', $barang->kondisi_barang) === $kondisi)>{{ $kondisi }}</option>
                                    @endforeach
                                </select>
                                @error('kondisi_barang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-sm-6">
                                <label for="lokasi" class="form-label fw-semibold">Lokasi <span class="text-danger">*</span></label>
                                <input type="text" id="lokasi" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror" value="{{ old('lokasi', $barang->lokasi) }}" required maxlength="255">
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-sm-6">
                                <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    @foreach(['Tersedia', 'Diproses', 'Disalurkan'] as $st)
                                        <option value="{{ $st }}" @selected(old('status', $barang->status) === $st)>{{ $st }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi Barang <span class="text-danger">*</span></label>
                            <textarea id="deskripsi" name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror" required maxlength="1000">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary px-4 py-2">Batal</a>
                            <button type="submit" class="btn btn-teal px-4 py-2">
                                <i class="bi bi-arrow-repeat me-1"></i>Perbarui Data Barang
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewFile(input) {
    const file = input.files[0];
    const preview = document.getElementById('previewImage');
    const placeholder = document.getElementById('placeholderUpload');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection
