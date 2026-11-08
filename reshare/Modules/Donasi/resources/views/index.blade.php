@extends('layouts.app')
@section('title', 'Donasi Barang (CRUD AJAX)')

@section('content')
<div class="container-fluid px-4 px-lg-5 py-4">
    <!-- Header Page -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-house me-1"></i>Beranda
                </a>
                <span class="text-muted small">/</span>
                <span class="small fw-semibold text-teal">Modul Donasi (AJAX)</span>
            </div>
            <h4 class="fw-bold text-dark mb-1">Pengelolaan Donasi Barang</h4>
            <p class="text-muted small mb-0">
                Kelola donasi barang secara real-time menggunakan <strong>CRUD AJAX</strong> (Modal Bootstrap, jQuery, & Upload Storage).
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if(auth()->user()->role === 'admin')
                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2">
                    <i class="bi bi-shield-check me-1"></i>Akses Admin (Semua Donasi)
                </span>
            @else
                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                    <i class="bi bi-person me-1"></i>Donatur: {{ auth()->user()->nama ?? auth()->user()->name }}
                </span>
            @endif
            <button type="button" class="btn btn-teal px-3 py-2 shadow-sm" id="btnTambahDonasi">
                <i class="bi bi-plus-circle me-2"></i>Donasi Barang Baru
            </button>
        </div>
    </div>

    <!-- Alert Container (Notifikasi Asinkron AJAX) -->
    <div id="ajaxAlertContainer"></div>

    <!-- Filter & Pencarian (Tanpa Reload) -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-3 p-md-4">
            <form id="filterForm" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold text-muted">Cari Donasi (Nama / Lokasi / Deskripsi)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="filterSearch" class="form-control border-start-0" placeholder="Ketik kata kunci pencarian...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Filter Kategori</label>
                    <select id="filterKategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-muted">Filter Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Diproses">Diproses</option>
                        <option value="Disalurkan">Disalurkan</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-teal w-100">
                        <i class="bi bi-funnel me-1"></i>Terapkan
                    </button>
                    <button type="button" id="btnResetFilter" class="btn btn-light border text-muted" title="Reset filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Donasi AJAX -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-box2-heart me-2 text-teal"></i>Daftar Donasi Barang
            </h6>
            <span class="badge bg-light text-dark border px-3 py-2 fw-medium" id="totalDonasiBadge">
                Memuat data...
            </span>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover" id="tabelDonasi">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 60px;">No</th>
                        <th style="width: 80px;">Foto</th>
                        <th>Barang & Lokasi</th>
                        <th>Kategori</th>
                        <th>Kondisi</th>
                        <th>Status</th>
                        <th>Donatur</th>
                        <th class="text-center pe-4" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="donasiTableBody">
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="spinner-border text-teal mb-2" role="status"></div>
                            <p class="mb-0">Memuat data donasi via AJAX...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL FORM TAMBAH & EDIT DONASI (AJAX BOOTSTRAP MODAL)   -->
<!-- ======================================================== -->
<div class="modal fade" id="modalDonasi" tabindex="-1" aria-labelledby="modalDonasiTitle" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 14px;">
            <div class="modal-header border-bottom py-3 px-4">
                <h5 class="modal-title fw-bold text-dark" id="modalDonasiTitle">
                    <i class="bi bi-heart me-2 text-teal"></i>Donasi Barang Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formDonasi" enctype="multipart/form-data">
                <input type="hidden" id="donasi_id" name="donasi_id" value="">
                <div class="modal-body p-4">
                    <!-- Error Alert Box di Modal -->
                    <div id="modalAlertError" class="alert alert-danger d-none border-0 shadow-sm" style="border-radius: 10px;">
                        <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Periksa kembali input Anda:</div>
                        <ul class="mb-0 ps-3" id="modalErrorList"></ul>
                    </div>

                    <div class="row g-4">
                        <!-- Kolom Foto & Preview -->
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Foto Barang Donasi</label>
                            <div class="border rounded-3 p-3 text-center d-flex flex-column align-items-center justify-content-center bg-light position-relative" style="min-height: 240px; border-style: dashed !important; border-color: #25834b !important;">
                                <img id="modalFotoPreview" src="" alt="Preview Foto" class="rounded mb-2 img-fluid" style="display: none; max-height: 160px; object-fit: contain;">
                                <div id="modalFotoPlaceholder">
                                    <i class="bi bi-cloud-arrow-up text-teal" style="font-size: 2.8rem;"></i>
                                    <p class="small text-muted mt-2 mb-0">Format: JPG, JPEG, PNG (Maks 2 MB)</p>
                                </div>
                                <div id="currentPhotoLinkContainer" class="mt-2" style="display: none;">
                                    <a href="#" target="_blank" id="currentPhotoLink" class="small text-teal text-decoration-none fw-semibold">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>Buka Foto Saat Ini
                                    </a>
                                </div>
                                <input type="file" name="foto_barang" id="inputFotoBarang" class="form-control mt-3" accept="image/jpeg,image/png,image/jpg">
                            </div>
                            <small class="text-muted d-block text-center mt-2" id="photoHelpText">Pilih foto barang donasi yang jelas dan layak.</small>
                            <div class="invalid-feedback d-block" id="error_foto_barang"></div>
                        </div>

                        <!-- Kolom Input Form -->
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="inputNamaBarang" class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inputNamaBarang" name="nama_barang" placeholder="Contoh: Baju Seragam SD" maxlength="150" required>
                                <div class="invalid-feedback" id="error_nama_barang"></div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-sm-6">
                                    <label for="inputKategori" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select" id="inputKategori" name="kategori_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="error_kategori_id"></div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="inputKondisi" class="form-label fw-semibold">Kondisi <span class="text-danger">*</span></label>
                                    <select class="form-select" id="inputKondisi" name="kondisi_barang" required>
                                        <option value="Sangat Baik">Sangat Baik</option>
                                        <option value="Baik" selected>Baik</option>
                                        <option value="Cukup">Cukup</option>
                                    </select>
                                    <div class="invalid-feedback" id="error_kondisi_barang"></div>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-sm-6">
                                    <label for="inputLokasi" class="form-label fw-semibold">Lokasi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="inputLokasi" name="lokasi" placeholder="Kota / Wilayah" maxlength="255" required>
                                    <div class="invalid-feedback" id="error_lokasi"></div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="inputStatus" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="inputStatus" name="status" required>
                                        <option value="Tersedia" selected>Tersedia</option>
                                        <option value="Diproses">Diproses</option>
                                        <option value="Disalurkan">Disalurkan</option>
                                    </select>
                                    <div class="invalid-feedback" id="error_status"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="inputDeskripsi" class="form-label fw-semibold">Deskripsi Barang <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="inputDeskripsi" name="deskripsi" rows="3" placeholder="Jelaskan detail ukuran, bahan, dan kelayakan barang donasi..." maxlength="1000" required></textarea>
                                <div class="invalid-feedback" id="error_deskripsi"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-teal px-4" id="btnSubmitForm">
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="submitSpinner"></span>
                        <i class="bi bi-cloud-arrow-up me-1" id="submitIcon"></i>Simpan Donasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL DETAIL DONASI (AJAX VIEW MODAL)                    -->
<!-- ======================================================== -->
<div class="modal fade" id="modalDetailDonasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header border-bottom py-3 px-4">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-info-circle me-2 text-teal"></i>Detail Donasi Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-5 bg-light rounded d-flex flex-column align-items-center justify-content-center p-3 text-center">
                        <img id="detailFoto" src="" alt="Foto Barang" class="img-fluid rounded shadow-sm mb-2" style="max-height: 220px; object-fit: contain;">
                        <div id="detailNoFoto" class="text-muted py-4 d-none">
                            <i class="bi bi-image fs-1 d-block mb-1"></i>
                            <span class="small">Tidak ada foto</span>
                        </div>
                        <a href="#" target="_blank" id="detailFotoLink" class="btn btn-sm btn-outline-secondary mt-2 d-none">
                            <i class="bi bi-box-arrow-up-right me-1"></i>Buka Foto Asli
                        </a>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-1" id="detailKategori">-</span>
                            <span class="badge px-3 py-1" id="detailStatus">-</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-3" id="detailNamaBarang">-</h4>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <small class="text-muted d-block">Kondisi Barang</small>
                                <span class="fw-semibold text-dark" id="detailKondisi">-</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Lokasi</small>
                                <span class="fw-semibold text-dark" id="detailLokasi">-</span>
                            </div>
                            <div class="col-6 mt-2">
                                <small class="text-muted d-block">Donatur</small>
                                <span class="fw-semibold text-dark" id="detailDonatur">-</span>
                            </div>
                            <div class="col-6 mt-2">
                                <small class="text-muted d-block">Tanggal Donasi</small>
                                <span class="fw-semibold text-dark" id="detailTanggal">-</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted d-block fw-semibold mb-1">Deskripsi:</small>
                            <div class="p-3 bg-light rounded text-secondary small" id="detailDeskripsi" style="white-space: pre-line;">-</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-2 px-4">
                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // 1. Setup Header AJAX Global: CSRF Token & Accept JSON
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    });

    const modalDonasi = new bootstrap.Modal(document.getElementById('modalDonasi'));
    const modalDetail = new bootstrap.Modal(document.getElementById('modalDetailDonasi'));

    // Helper XSS Escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Helper Tampilkan Notifikasi Alert
    function showAlert(type, message) {
        const icon = type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill';
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:10px">
                <i class="bi bi-${icon} me-2"></i>${escapeHtml(message)}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#ajaxAlertContainer').html(alertHtml);
        // Scroll ke atas dengan halus
        $('html, body').animate({ scrollTop: $('#ajaxAlertContainer').offset().top - 80 }, 200);
    }

    // Reset Form & Error Validasi Modal
    function resetModalForm() {
        $('#formDonasi')[0].reset();
        $('#donasi_id').val('');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#modalAlertError').addClass('d-none');
        $('#modalErrorList').empty();
        $('#modalFotoPreview').hide().attr('src', '');
        $('#modalFotoPlaceholder').show();
        $('#currentPhotoLinkContainer').hide();
        $('#photoHelpText').text('Pilih foto barang donasi yang jelas dan layak (Maks 2 MB).');
    }

    // Preview Foto Saat Dipilih
    $('#inputFotoBarang').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#modalFotoPreview').attr('src', e.target.result).show();
                $('#modalFotoPlaceholder').hide();
            };
            reader.readAsDataURL(file);
        }
    });

    // 2. READ: Muat Data Donasi Melalui AJAX (Tanpa Reload)
    function loadDonasi() {
        const search = $('#filterSearch').val();
        const kategori_id = $('#filterKategori').val();
        const status = $('#filterStatus').val();

        $('#donasiTableBody').html(`
            <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                    <div class="spinner-border spinner-border-sm text-teal me-2" role="status"></div>
                    <span>Memperbarui data tabel...</span>
                </td>
            </tr>
        `);

        $.ajax({
            url: "{{ route('donasi.index') }}",
            type: "GET",
            data: {
                search: search,
                kategori_id: kategori_id,
                status: status
            },
            success: function(res) {
                if (res.status === 'success') {
                    $('#totalDonasiBadge').text('Total: ' + res.total + ' Data');
                    const items = res.data;
                    if (items.length === 0) {
                        $('#donasiTableBody').html(`
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                    <p class="mb-0">Belum ada data donasi yang sesuai.</p>
                                </td>
                            </tr>
                        `);
                        return;
                    }

                    let rowsHtml = '';
                    items.forEach(function(item, index) {
                        let fotoCell = '';
                        if (item.foto_url) {
                            fotoCell = `
                                <a href="${escapeHtml(item.foto_url)}" target="_blank" title="Klik untuk membuka foto asli">
                                    <img src="${escapeHtml(item.foto_url)}" alt="${escapeHtml(item.nama_barang)}" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                </a>
                            `;
                        } else {
                            fotoCell = `
                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                    <i class="bi bi-image fs-4"></i>
                                </div>
                            `;
                        }

                        let statusBadge = '';
                        if (item.status === 'Tersedia') {
                            statusBadge = '<span class="badge bg-success bg-opacity-10 text-success px-2 py-1">Tersedia</span>';
                        } else if (item.status === 'Diproses') {
                            statusBadge = '<span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1">Diproses</span>';
                        } else {
                            statusBadge = '<span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">Disalurkan</span>';
                        }

                        let actionButtons = `
                            <button type="button" class="btn btn-outline-info btn-sm btn-detail" data-id="${item.id}" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </button>
                        `;

                        if (item.can_manage) {
                            actionButtons += `
                                <button type="button" class="btn btn-outline-warning btn-sm btn-edit" data-id="${item.id}" title="Edit Donasi">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm btn-delete" data-id="${item.id}" data-nama="${escapeHtml(item.nama_barang)}" title="Hapus Donasi">
                                    <i class="bi bi-trash"></i>
                                </button>
                            `;
                        }

                        rowsHtml += `
                            <tr>
                                <td class="ps-4 text-muted small">${index + 1}</td>
                                <td>${fotoCell}</td>
                                <td>
                                    <div class="fw-bold text-dark">${escapeHtml(item.nama_barang)}</div>
                                    <div class="text-muted small"><i class="bi bi-geo-alt me-1 text-danger"></i>${escapeHtml(item.lokasi)}</div>
                                </td>
                                <td><span class="badge bg-info bg-opacity-10 text-info px-2 py-1">${escapeHtml(item.kategori_nama)}</span></td>
                                <td><span class="small fw-medium">${escapeHtml(item.kondisi_barang)}</span></td>
                                <td>${statusBadge}</td>
                                <td class="small text-muted">${escapeHtml(item.donatur_nama)}</td>
                                <td class="text-center pe-4">
                                    <div class="btn-group btn-group-sm" role="group">
                                        ${actionButtons}
                                    </div>
                                </td>
                            </tr>
                        `;
                    });

                    $('#donasiTableBody').html(rowsHtml);
                }
            },
            error: function(xhr) {
                $('#donasiTableBody').html(`
                    <tr>
                        <td colspan="8" class="text-center py-5 text-danger">
                            <i class="bi bi-exclamation-triangle fs-2 d-block mb-2"></i>
                            <p class="mb-0">Gagal memuat data donasi. Silakan muat ulang halaman.</p>
                        </td>
                    </tr>
                `);
            }
        });
    }

    // Panggil pertama kali saat halaman siap
    loadDonasi();

    // Event Filter & Reset
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        loadDonasi();
    });

    $('#btnResetFilter').on('click', function() {
        $('#filterSearch').val('');
        $('#filterKategori').val('');
        $('#filterStatus').val('');
        loadDonasi();
    });

    // 3. CREATE: Buka Modal Tambah Donasi
    $('#btnTambahDonasi').on('click', function() {
        resetModalForm();
        $('#modalDonasiTitle').html('<i class="bi bi-heart me-2 text-teal"></i>Donasi Barang Baru');
        $('#btnSubmitForm').html('<i class="bi bi-cloud-arrow-up me-1"></i>Simpan Donasi');
        modalDonasi.show();
    });

    // 4. SUBMIT FORM: Tambah / Edit via AJAX FormData
    $('#formDonasi').on('submit', function(e) {
        e.preventDefault();

        const id = $('#donasi_id').val();
        const isEdit = id !== '';
        const url = isEdit ? "{{ url('donasi') }}/" + id : "{{ route('donasi.store') }}";

        // Buat FormData untuk mengirim file dan teks
        const formData = new FormData(this);
        if (isEdit) {
            // Spoofing method PUT untuk kompatibilitas file upload di PHP
            formData.append('_method', 'PUT');
        }

        // Tampilkan indikator loading tombol
        $('#btnSubmitForm').prop('disabled', true);
        $('#submitSpinner').removeClass('d-none');
        $('#submitIcon').addClass('d-none');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#modalAlertError').addClass('d-none');

        $.ajax({
            url: url,
            type: "POST", // Menggunakan POST (dengan method spoofing _method: PUT jika edit)
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                $('#btnSubmitForm').prop('disabled', false);
                $('#submitSpinner').addClass('d-none');
                $('#submitIcon').removeClass('d-none');

                if (res.status === 'success') {
                    modalDonasi.hide();
                    resetModalForm();
                    showAlert('success', res.message);
                    // Perbarui tabel langsung tanpa reload
                    loadDonasi();
                }
            },
            error: function(xhr) {
                $('#btnSubmitForm').prop('disabled', false);
                $('#submitSpinner').addClass('d-none');
                $('#submitIcon').removeClass('d-none');

                // HTTP 422: Validasi Gagal
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $('#modalErrorList').empty();
                    for (const field in errors) {
                        $(`#input${field.charAt(0).toUpperCase() + field.slice(1)}`).addClass('is-invalid');
                        $(`#error_${field}`).text(errors[field][0]);
                        $('#modalErrorList').append(`<li>${escapeHtml(errors[field][0])}</li>`);
                    }
                    $('#modalAlertError').removeClass('d-none');
                } else if (xhr.status === 403) {
                    $('#modalErrorList').html('<li>Akses ditolak: Anda tidak memiliki izin untuk tindakan ini.</li>');
                    $('#modalAlertError').removeClass('d-none');
                } else {
                    $('#modalErrorList').html('<li>Terjadi kesalahan pada server. Silakan coba kembali.</li>');
                    $('#modalAlertError').removeClass('d-none');
                }
            }
        });
    });

    // 5. EDIT: Ambil Data dan Tampilkan di Modal
    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        resetModalForm();

        $.ajax({
            url: "{{ url('donasi') }}/" + id,
            type: "GET",
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#donasi_id').val(d.id);
                    $('#inputNamaBarang').val(d.nama_barang);
                    $('#inputKategori').val(d.kategori_id);
                    $('#inputKondisi').val(d.kondisi_barang);
                    $('#inputLokasi').val(d.lokasi);
                    $('#inputStatus').val(d.status);
                    $('#inputDeskripsi').val(d.deskripsi);

                    if (d.foto_url) {
                        $('#modalFotoPreview').attr('src', d.foto_url).show();
                        $('#modalFotoPlaceholder').hide();
                        $('#currentPhotoLink').attr('href', d.foto_url);
                        $('#currentPhotoLinkContainer').show();
                        $('#photoHelpText').text('Pilih foto baru jika ingin mengganti foto lama.');
                    }

                    $('#modalDonasiTitle').html('<i class="bi bi-pencil-square me-2 text-teal"></i>Edit Data Donasi');
                    $('#btnSubmitForm').html('<i class="bi bi-save me-1"></i>Perbarui Donasi');
                    modalDonasi.show();
                }
            },
            error: function(xhr) {
                showAlert('danger', 'Gagal mengambil data donasi untuk diedit.');
            }
        });
    });

    // 6. SHOW DETAIL: Tampilkan Modal Detail
    $(document).on('click', '.btn-detail', function() {
        const id = $(this).data('id');

        $.ajax({
            url: "{{ url('donasi') }}/" + id,
            type: "GET",
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;
                    $('#detailNamaBarang').text(d.nama_barang);
                    $('#detailKategori').text(d.kategori_nama);
                    $('#detailKondisi').text(d.kondisi_barang);
                    $('#detailLokasi').text(d.lokasi);
                    $('#detailDeskripsi').text(d.deskripsi);
                    $('#detailDonatur').text(d.donatur_nama + ' (' + d.donatur_email + ')');
                    $('#detailTanggal').text(d.created_at);

                    if (d.status === 'Tersedia') {
                        $('#detailStatus').attr('class', 'badge bg-success bg-opacity-10 text-success px-3 py-1').text('Tersedia');
                    } else if (d.status === 'Diproses') {
                        $('#detailStatus').attr('class', 'badge bg-warning bg-opacity-10 text-warning px-3 py-1').text('Diproses');
                    } else {
                        $('#detailStatus').attr('class', 'badge bg-secondary bg-opacity-10 text-secondary px-3 py-1').text('Disalurkan');
                    }

                    if (d.foto_url) {
                        $('#detailFoto').attr('src', d.foto_url).show();
                        $('#detailNoFoto').addClass('d-none');
                        $('#detailFotoLink').attr('href', d.foto_url).removeClass('d-none');
                    } else {
                        $('#detailFoto').hide();
                        $('#detailNoFoto').removeClass('d-none');
                        $('#detailFotoLink').addClass('d-none');
                    }

                    modalDetail.show();
                }
            },
            error: function(xhr) {
                showAlert('danger', 'Gagal memuat detail donasi.');
            }
        });
    });

    // 7. DELETE: Hapus Data Melalui AJAX (Konfirmasi Tanpa Reload)
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');

        if (confirm(`Apakah Anda yakin ingin menghapus donasi "${nama}"?\n\nFoto terkait juga akan dihapus dari storage. Tindakan ini tidak dapat dibatalkan.`)) {
            $.ajax({
                url: "{{ url('donasi') }}/" + id,
                type: "DELETE",
                success: function(res) {
                    if (res.status === 'success') {
                        showAlert('success', res.message);
                        // Perbarui tabel langsung tanpa reload halaman
                        loadDonasi();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        showAlert('danger', 'Akses ditolak: Anda tidak memiliki izin untuk menghapus donasi ini.');
                    } else {
                        showAlert('danger', 'Gagal menghapus data donasi.');
                    }
                }
            });
        }
    });
});
</script>
@endpush
