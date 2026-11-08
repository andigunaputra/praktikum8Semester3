
@extends('layouts.app')

@section('title', 'Dashboard')

@section('header', 'Dashboard')

@section('content')

<style>
    .welcome {
        background: #eef4ff;
        padding: 22px;
        border-radius: 8px;
        margin-bottom: 25px;
        border-left: 4px solid #244b80;
    }

    .welcome h2 {
        margin: 0 0 8px;
        color: #243b5a;
        font-size: 22px;
    }

    .welcome p {
        margin: 0;
        color: #666;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-box {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 18px;
    }

    .stat-box p {
        color: #777;
        margin: 0 0 10px;
        font-size: 14px;
    }

    .stat-box h3 {
        margin: 0;
        font-size: 25px;
        color: #243b5a;
    }

    .menu-dashboard {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .menu-dashboard a {
        display: inline-block;
        padding: 11px 18px;
        background: #244b80;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
    }

    .menu-dashboard a:hover {
        background: #19385f;
    }

    @media(max-width: 700px) {
        .stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

@php
    $totalBuku = \Modules\Buku\Models\Buku::count();
    $totalPeminjaman = \Modules\Peminjaman\Models\Peminjaman::count();
    $totalPengguna = \App\Models\User::count();
    $peminjamanAktif = \Modules\Peminjaman\Models\Peminjaman::where('status', 'Dipinjam')->count();
@endphp

<div class="welcome">
    <h2>Selamat Datang di SiPustaka</h2>
    <p>Halo, {{ auth()->user()->name }}. Selamat menggunakan sistem informasi perpustakaan.</p>
</div>

<div class="stats">
    <div class="stat-box">
        <p>Total Buku</p>
        <h3>{{ $totalBuku }}</h3>
    </div>

    <div class="stat-box">
        <p>Total Peminjaman</p>
        <h3>{{ $totalPeminjaman }}</h3>
    </div>

    <div class="stat-box">
        <p>Peminjaman Aktif</p>
        <h3>{{ $peminjamanAktif }}</h3>
    </div>

    <div class="stat-box">
        <p>Total Pengguna</p>
        <h3>{{ $totalPengguna }}</h3>
    </div>
</div>

<h3 style="color:#243b5a; margin-bottom:15px;">Menu Utama</h3>

<div class="menu-dashboard">
    <a href="{{ url('/buku') }}">Data Buku</a>
    <a href="{{ url('/peminjaman') }}">Data Peminjaman</a>

    @if(auth()->user()->role === 'admin')
        <a href="{{ url('/kelola-pengguna') }}">Kelola Pengguna</a>
    @endif
</div>

@endsection
