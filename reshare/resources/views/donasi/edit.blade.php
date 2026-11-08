@extends('layouts.app')
@section('title', 'Edit Donasi')
@section('content')
<div class="container px-4 py-4">
    <div class="mb-4"><a href="{{ route('barang.detail', $barang->id) }}" class="text-decoration-none text-dark fw-semibold d-inline-flex align-items-center back-link"><i class="bi bi-chevron-left me-2 fw-bold"></i>Edit Barang Donasi</a><p class="text-muted small ms-4 mb-0">Ubah data barang donasi Anda.</p></div>
    <div class="card donation-form-card p-4 mx-auto mb-4">
        <form method="POST" action="{{ route('donasi.update', $barang->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="upload-area-box p-4 text-center d-flex flex-column align-items-center justify-content-center h-100" id="uploadArea" style="cursor:pointer; border:2px dashed #ccd8ce; border-radius:12px; min-height:250px; position:relative; overflow:hidden;">
                        <img id="fotoPreview" src="{{ $barang->foto_barang ? asset('reshare/uploads/barang/'.$barang->foto_barang) : '' }}" alt="Preview" style="{{ $barang->foto_barang ? 'display:block' : 'display:none' }}; max-width:100%; max-height:100%; object-fit:cover; border-radius:8px;">
                        <div id="uploadPlaceholder" style="{{ $barang->foto_barang ? 'display:none' : '' }}">
                            <i class="bi bi-cloud-arrow-up upload-icon mb-2" style="font-size:2.5rem; color:#25834b;"></i>
                            <h6 class="fw-bold mb-1 text-teal">Upload Foto</h6>
                            <p class="text-muted small mb-3">Klik atau seret foto ke sini</p>
                            <span class="btn btn-teal btn-sm px-4">Pilih Foto</span>
                        </div>
                        <input type="file" name="foto_barang" id="fotoInput" accept="image/jpeg,image/png,image/jpg" style="position:absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer;">
                    </div>
                    <div class="text-center mt-2"><small class="text-muted upload-note">Format: JPG, JPEG, PNG (Maks 2 MB). Kosongkan jika tidak ingin mengganti foto.</small></div>
                </div>
                <div class="col-md-7">
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label fw-semibold">Nama Barang</label>
                        <input id="nama_barang" class="form-control form-control-reshare" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" placeholder="Contoh: Tas sekolah" required maxlength="150">
                    </div>
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label fw-semibold">Kategori</label>
                        <select id="kategori_id" class="form-select form-control-reshare" name="kategori_id" required>
                            <option value="">Pilih kategori barang</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" @selected(old('kategori_id', $barang->kategori_id) == $kategori->id)>{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kondisi_barang" class="form-label fw-semibold">Kondisi Barang</label>
                        <select id="kondisi_barang" class="form-select form-control-reshare" name="kondisi_barang" required>
                            @foreach(['Sangat Baik','Baik','Cukup'] as $kondisi)
                                <option value="{{ $kondisi }}" @selected(old('kondisi_barang', $barang->kondisi_barang) === $kondisi)>{{ $kondisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="lokasi" class="form-label fw-semibold">Lokasi</label>
                        <input id="lokasi" class="form-control form-control-reshare" name="lokasi" value="{{ old('lokasi', $barang->lokasi) }}" placeholder="Kota/kabupaten" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi Barang</label>
                        <textarea id="deskripsi" class="form-control form-control-reshare" name="deskripsi" rows="4" placeholder="Jelaskan kondisi dan detail barang" required maxlength="1000">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-teal px-4 py-2" type="submit"><i class="bi bi-pencil me-2"></i>Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const fotoInput = document.getElementById('fotoInput');
    const fotoPreview = document.getElementById('fotoPreview');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');

    fotoInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                fotoPreview.src = e.target.result;
                fotoPreview.style.display = 'block';
                uploadPlaceholder.style.display = 'none';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush
