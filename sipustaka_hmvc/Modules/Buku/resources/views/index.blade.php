
@extends('layouts.app')

@section('title', 'Data Buku')
@section('header', 'Data Buku')

@section('content')

<style>
    .buku-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .buku-header p {
        color: #777;
        font-size: 14px;
        margin: 5px 0 0;
    }

    .btn-tambah {
        background: #244b80;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
    }

    .search-form {
        display: flex;
        gap: 8px;
        margin-bottom: 18px;
    }

    .search-form input {
        max-width: 300px;
        padding: 9px 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .search-form button {
        background: #244b80;
        color: white;
        border: none;
        padding: 9px 15px;
        border-radius: 5px;
        cursor: pointer;
    }

    .table-buku {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .table-buku th {
        background: #f1f4f8;
        color: #34445a;
        text-align: left;
        padding: 13px 10px;
    }

    .table-buku td {
        padding: 12px 10px;
        border-bottom: 1px solid #eee;
    }

    .table-buku tr:hover {
        background: #fafbfc;
    }

    .aksi a, .aksi button {
        display: inline-block;
        border: none;
        padding: 6px 10px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 12px;
        cursor: pointer;
        margin-right: 3px;
    }

    .btn-edit {
        background: #e8f0ff;
        color: #244b80;
    }

    .btn-hapus {
        background: #fdecec;
        color: #c0392b;
    }

    .pagination {
        margin-top: 18px;
    }

    @media(max-width: 700px) {
        .table-buku {
            min-width: 650px;
        }

        .table-wrapper {
            overflow-x: auto;
        }
    }
</style>

<div class="buku-header">
    <div>
        <h3 style="margin:0; color:#243b5a;">Daftar Buku</h3>
        <p>Informasi koleksi buku perpustakaan SiPustaka.</p>
    </div>

    @if(auth()->user()->role === 'admin')
        <a href="{{ route('buku.create') }}" class="btn-tambah">
            + Tambah Buku
        </a>
    @endif
</div>

<form action="{{ route('buku.index') }}" method="GET" class="search-form">
    <input
        type="text"
        name="search"
        placeholder="Cari judul atau penulis..."
        value="{{ request('search') }}"
    >
    <button type="submit">Cari</button>
</form>

@if(session('success'))
    <div style="background:#eaf7ee; color:#26733b; padding:12px; border-radius:5px; margin-bottom:15px;">
        {{ session('success') }}
    </div>
@endif

<div class="table-wrapper">
    <table class="table-buku">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Stok</th>
                @if(auth()->user()->role === 'admin')
                    <th>Aksi</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @forelse($bukus as $index => $buku)
                <tr>
                    <td>{{ $bukus->firstItem() + $index }}</td>
                    <td>{{ $buku->judul }}</td>
                    <td>{{ $buku->penulis }}</td>
                    <td>{{ $buku->penerbit }}</td>
                    <td>{{ $buku->tahun_terbit }}</td>
                    <td>{{ $buku->stok }}</td>

                    @if(auth()->user()->role === 'admin')
                        <td class="aksi">
                            <a href="{{ route('buku.edit', $buku->id) }}" class="btn-edit">
                                Edit
                            </a>

                            <form action="{{ route('buku.destroy', $buku->id) }}"
                                  method="POST"
                                  style="display:inline;"
                                  onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-hapus">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:25px; color:#777;">
                        Data buku belum tersedia.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination">
    {{ $bukus->links() }}
</div>

@endsection
