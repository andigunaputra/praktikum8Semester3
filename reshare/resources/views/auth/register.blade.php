@extends('layouts.auth')
@section('page-title', 'ReShare - Create your account')
@section('register-color', '#14B8A6')
@section('auth-content')
    <div class="text-start mb-4">
        <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
            <i class="bi bi-arrow-repeat text-secondary fs-4"></i>
            <span class="fw-bold fs-5" style="color:#14B8A6">ReShare</span>
        </div>
        <h4 class="fw-bold mb-1">Create your account</h4>
        <p class="text-muted small">Join reshare and start sharing</p>
    </div>

    @if (session('error'))<div class="alert alert-danger small py-2">{{ session('error') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger small py-2">Periksa kembali data yang kamu masukkan.</div>@endif

    <form action="{{ route('register.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label small fw-semibold text-dark mb-1">Fullname</label>
            <input id="name" type="text" name="nama" value="{{ old('name') }}" class="form-control" placeholder="Your fullname..." autocomplete="name" required maxlength="100">
            @error('name')<div class="auth-error">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="register-email" class="form-label small fw-semibold text-dark mb-1">Email</label>
            <input id="register-email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter your email..." autocomplete="email" required>
            @error('email')<div class="auth-error">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label for="register-password" class="form-label small fw-semibold text-dark mb-1">Password</label>
            <input id="register-password" type="password" name="password" class="form-control" placeholder="Enter your password..." autocomplete="new-password" required>
            @error('password')<div class="auth-error">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-teal w-100 mb-4">Create account</button>
        <div class="divider mb-4">or continue with</div>
        <div class="row g-3 mb-5">
            <div class="col-6"><button type="button" onclick="showSocialNotice('Google')" class="btn btn-social w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-google text-danger"></i> Google</button></div>
            <div class="col-6"><button type="button" onclick="showSocialNotice('Apple')" class="btn btn-social w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-apple text-dark"></i> Apple</button></div>
        </div>
        <div class="text-center"><p class="small text-muted mb-0">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color:#3B82F6">Log In</a></p></div>
    </form>
@endsection
@push('scripts')
<script>function showSocialNotice(provider){alert('Login '+provider+' belum dihubungkan ke layanan autentikasi. Silakan daftar menggunakan email.');}</script>
@endpush
