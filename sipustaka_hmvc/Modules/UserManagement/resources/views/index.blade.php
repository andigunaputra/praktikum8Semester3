
@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('header', 'Kelola Pengguna')

@section('content')

<style>
    .user-heading {
        margin-bottom: 20px;
    }

    .user-heading h3 {
        color: #243b5a;
        margin: 0 0 5px;
    }

    .user-heading p {
        color: #777;
        font-size: 14px;
        margin: 0;
    }

    .form-user {
        max-width: 550px;
        padding: 20px;
        background: #fafbfc;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        margin-bottom: 25px;
    }

    .form-group {
        margin-bottom: 14px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        color: #34445a;
        font-size: 14px;
        font-weight: 600;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box;
        background: white;
    }

    .btn-tambah {
        background: #244b80;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-simpan {
        background: #e8f0ff;
        color: #244b80;
        border: none;
        padding: 7px 10px;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 7px;
    }

    .btn-hapus {
        background: #fdecec;
        color: #c0392b;
        border: none;
        padding: 7px 10px;
        border-radius: 4px;
        cursor: pointer;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .table-user {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .table-user th {
        background: #f1f4f8;
        color: #34445a;
        text-align: left;
        padding: 13px 10px;
    }

    .table-user td {
        padding: 12px 10px;
        border-bottom: 1px solid #eee;
        vertical-align: top;
    }

    .table-user tr:hover {
        background: #fafbfc;
    }

    .edit-form {
        min-width: 180px;
    }

    .edit-form input,
    .edit-form select {
        width: 100%;
        box-sizing: border-box;
        padding: 8px;
        margin-bottom: 7px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .role-admin {
        color: #244b80;
        font-weight: 600;
    }

    .role-user {
        color: #555;
    }

    .pesan-sukses {
        background: #eaf7ee;
        color: #26733b;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .pesan-error {
        background: #fdecec;
        color: #c0392b;
        padding: 12px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
</style>

<div class="user-heading">
    <h3>Daftar Pengguna</h3>
    <p>Kelola akun admin dan pengguna SiPustaka.</p>
</div>

@if(session('success'))
    <div class="pesan-sukses">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="pesan-error">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<h3 style="color:#243b5a; margin-bottom:15px;">Tambah Pengguna</h3>

<div class="form-user">
    <form action="{{ route('usermanagement.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="btn-tambah">Tambah Pengguna</button>
    </form>
</div>

<h3 style="color:#243b5a; margin-bottom:15px;">Data Pengguna</h3>

<div class="table-wrapper">
    <table class="table-user">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Edit Pengguna</th>
                <th>Hapus</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>

                    <td>
                        @if($user->role === 'admin')
                            <span class="role-admin">Admin</span>
                        @else
                            <span class="role-user">User</span>
                        @endif
                    </td>

                    <td>
                        <form action="{{ route('usermanagement.update', $user->id) }}"
                              method="POST" class="edit-form">
                            @csrf
                            @method('PUT')

                            <input type="text" name="name"
                                   value="{{ $user->name }}" required>

                            <input type="email" name="email"
                                   value="{{ $user->email }}" required>

                            <select name="role">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>
                                    User
                                </option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>
                            </select>

                            <input type="password" name="password"
                                   placeholder="Password baru (opsional)">

                            <button type="submit" class="btn-simpan">
                                Simpan Perubahan
                            </button>
                        </form>
                    </td>

                    <td>
                        @if($user->id != auth()->id())
                            <form action="{{ route('usermanagement.destroy', $user->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-hapus">
                                    Hapus
                                </button>
                            </form>
                        @else
                            <span style="color:#777;">Akun Anda</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
