@extends('layouts.app')
@section('title', 'Profil')
@section('content')
<div class="card">
    <h1>Profil Pengguna</h1>
    <p><strong>Nama:</strong> {{ auth()->user()->name }}</p>
    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
    <p><strong>Peran:</strong> {{ auth()->user()->role }}</p>
    <p class="muted">Halaman profil dasar untuk memenuhi navigasi aplikasi. Pengeditan profil dapat dikembangkan berikutnya.</p>
</div>
@endsection
