
@extends('layouts.app')

@section('title', 'Data Peminjaman')
@section('header', 'Data Peminjaman')

@section('content')

<style>
    .pinjaman-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pinjaman-header h3 {
        color: #243b5a;
        margin: 0 0 5px;
    }

    .pinjaman-header p {
        color: #777;
        font-size: 14px;
        margin: 0;
    }

    .btn-tambah, .btn-simpan {
        background: #244b80;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
    }

    .form-area {
        display: none;
        max-width: 650px;
        background: #fafbfc;
        border: 1px solid #e5e7eb;
        padding: 20px;
        border-radius: 7px;
        margin-bottom: 25px;
    }

    .form-area h3 {
        color: #243b5a;
        margin-top: 0;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
        font-weight: 600;
        color: #34445a;
    }

    .form-group input, .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
        background: white;
    }

    .form-group input:focus, .form-group select:focus {
        outline: none;
        border-color: #244b80;
    }

    .error {
        display: block;
        color: #c0392b;
        font-size: 12px;
        margin-top: 5px;
    }

    .btn-batal {
        background: #e9edf2;
        color: #333;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .table-peminjaman {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .table-peminjaman th {
        background: #f1f4f8;
        color: #34445a;
        padding: 13px 10px;
        text-align: left;
        white-space: nowrap;
    }

    .table-peminjaman td {
        padding: 12px 10px;
        border-bottom: 1px solid #eee;
    }

    .table-peminjaman tr:hover {
        background: #fafbfc;
    }

    .btn-edit, .btn-hapus {
        border: none;
        padding: 6px 9px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        margin: 2px;
    }

    .btn-edit {
        background: #e8f0ff;
        color: #244b80;
    }

    .btn-hapus {
        background: #fdecec;
        color: #c0392b;
    }

    .link-file {
        color: #244b80;
        text-decoration: none;
    }

    @media(max-width: 600px) {
        .form-area {
            padding: 15px;
        }
    }
</style>

<div class="pinjaman-header">
    <div>
        <h3>Daftar Peminjaman</h3>
        <p>Kelola data peminjaman buku perpustakaan.</p>
    </div>

    <button type="button" class="btn-tambah" onclick="tampilkanForm()">
        + Tambah Peminjaman
    </button>
</div>

<div id="formArea" class="form-area">
    <h3 id="judulForm">Tambah Peminjaman</h3>

    <form id="formPeminjaman" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="id">

        <div class="form-group">
            <label>Nama Peminjam</label>
            <input type="text" name="nama_peminjam" placeholder="Masukkan nama peminjam">
            <small class="error" id="error-nama_peminjam"></small>
        </div>

        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" name="judul_buku" placeholder="Masukkan judul buku">
            <small class="error" id="error-judul_buku"></small>
        </div>

        <div class="form-group">
            <label>Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam">
            <small class="error" id="error-tanggal_pinjam"></small>
        </div>

        <div class="form-group">
            <label>Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali">
            <small class="error" id="error-tanggal_kembali"></small>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Dipinjam">Dipinjam</option>
                <option value="Dikembalikan">Dikembalikan</option>
            </select>
            <small class="error" id="error-status"></small>
        </div>

        <div class="form-group">
            <label>File Bukti (JPG, PNG, PDF)</label>
            <input type="file" name="file_bukti" accept=".jpg,.jpeg,.png,.pdf">
            <small class="error" id="error-file_bukti"></small>
        </div>

        <button type="submit" class="btn-simpan">Simpan</button>
        <button type="button" class="btn-batal" onclick="sembunyikanForm()">Batal</button>
    </form>
</div>

<h3 style="color:#243b5a; margin-bottom:15px;">Riwayat Peminjaman</h3>

<div class="table-wrapper">
    <table class="table-peminjaman">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Bukti</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="tabelPeminjaman">
            <tr>
                <td colspan="8" style="text-align:center;">Memuat data...</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    const urlPeminjaman = "{{ url('/peminjaman') }}";
    const token = document.querySelector('meta[name="csrf-token"]').content;
    const form = document.getElementById('formPeminjaman');

    function tampilkanForm() {
        form.reset();
        document.getElementById('id').value = '';
        document.getElementById('judulForm').innerText = 'Tambah Peminjaman';
        hapusError();
        document.getElementById('formArea').style.display = 'block';
    }

    function sembunyikanForm() {
        document.getElementById('formArea').style.display = 'none';
    }

    function hapusError() {
        document.querySelectorAll('.error').forEach(el => el.innerText = '');
    }

    async function muatData() {
        const response = await fetch(urlPeminjaman, {
            headers: { 'Accept': 'application/json' }
        });

        const data = await response.json();
        const tbody = document.getElementById('tabelPeminjaman');

        tbody.innerHTML = '';

        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;">Belum ada data peminjaman.</td></tr>';
            return;
        }

        data.forEach((item, index) => {
            const bukti = item.file_bukti
                ? `<a class="link-file" href="/storage/${item.file_bukti}" target="_blank">Lihat File</a>`
                : '-';

            tbody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nama_peminjam}</td>
                    <td>${item.judul_buku}</td>
                    <td>${item.tanggal_pinjam}</td>
                    <td>${item.tanggal_kembali ?? '-'}</td>
                    <td>${item.status}</td>
                    <td>${bukti}</td>
                    <td>
                        <button class="btn-edit" onclick="editData(${item.id})">Edit</button>
                        <button class="btn-hapus" onclick="hapusData(${item.id})">Hapus</button>
                    </td>
                </tr>
            `;
        });
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        hapusError();

        const id = document.getElementById('id').value;
        const data = new FormData(form);

        let url = urlPeminjaman;

        if (id) {
            url += '/' + id;
            data.append('_method', 'PUT');
        }

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: data
        });

        const result = await response.json();

        if (response.status === 422) {
            Object.keys(result.errors).forEach(field => {
                const el = document.getElementById('error-' + field);
                if (el) el.innerText = result.errors[field][0];
            });
            return;
        }

        if (!response.ok) {
            alert(result.message || 'Terjadi kesalahan.');
            return;
        }

        alert(result.message);
        sembunyikanForm();
        muatData();
    });

    async function editData(id) {
        const response = await fetch(urlPeminjaman, {
            headers: { 'Accept': 'application/json' }
        });

        const data = await response.json();
        const item = data.find(row => row.id == id);

        if (!item) return;

        tampilkanForm();
        document.getElementById('judulForm').innerText = 'Edit Peminjaman';
        document.getElementById('id').value = item.id;

        form.elements['nama_peminjam'].value = item.nama_peminjam;
        form.elements['judul_buku'].value = item.judul_buku;
        form.elements['tanggal_pinjam'].value = item.tanggal_pinjam;
        form.elements['tanggal_kembali'].value = item.tanggal_kembali ?? '';
        form.elements['status'].value = item.status;
    }

    async function hapusData(id) {
        if (!confirm('Yakin ingin menghapus data ini?')) return;

        const response = await fetch(urlPeminjaman + '/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (!response.ok) {
            alert(result.message || 'Gagal menghapus data.');
            return;
        }

        alert(result.message);
        muatData();
    }

    muatData();
</script>

@endsection
