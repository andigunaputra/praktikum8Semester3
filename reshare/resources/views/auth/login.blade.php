@extends('layouts.auth')
@section('page-title', 'ReShare - Welcome back!')
@section('login-color', '#14B8A6')
@section('auth-content')
    <div class="text-start mb-4">
        <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
            <i class="bi bi-arrow-repeat text-secondary fs-4"></i>
            <span class="fw-bold fs-5" style="color:#14B8A6">ReShare</span>
        </div>
        <h4 class="fw-bold mb-1">Welcome back!</h4>
        <p class="text-muted small">Please login to continue</p>
    </div>

    @if (session('success'))<div class="alert alert-success small py-2">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-danger small py-2">{{ session('error') }}</div>@endif

    <form action="{{ route('login.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label small fw-semibold text-dark mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter your email..." autocomplete="email" required>
            @error('email')<div class="auth-error">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2">
            <label for="password" class="form-label small fw-semibold text-dark mb-1">Password</label>
            <input id="password" type="password" name="password" class="form-control" placeholder="Enter your password..." autocomplete="current-password" required>
            @error('password')<div class="auth-error">{{ $message }}</div>@enderror
        </div>
        <div class="text-end mb-4"><a href="#" class="text-decoration-none small" style="color:#3B82F6;font-size:.85rem" onclick="alert('Fitur lupa password belum dipasang di Laravel.');return false;">Forgot password?</a></div>
        <button type="submit" class="btn btn-teal w-100 mb-4">Login</button>
        <div class="divider mb-4">or continue with</div>
        <div class="row g-3 mb-5">
            <div class="col-6"><button type="button" onclick="showSocialNotice('Google')" class="btn btn-social w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-google text-danger"></i> Google</button></div>
            <div class="col-6"><button type="button" onclick="showSocialNotice('Apple')" class="btn btn-social w-100 d-flex align-items-center justify-content-center gap-2"><i class="bi bi-apple text-dark"></i> Apple</button></div>
        </div>
        <div class="text-center"><p class="small text-muted mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color:#3B82F6">Sign up</a></p></div>
    </form>
@endsection
@push('scripts')
<script>function showSocialNotice(provider){alert('Login '+provider+' belum dihubungkan ke layanan autentikasi. Gunakan email dan password untuk login.');}</script>
@endpush
