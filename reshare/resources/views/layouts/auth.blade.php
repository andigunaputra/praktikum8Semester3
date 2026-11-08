<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title', 'ReShare - Welcome back!')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body{background-color:#EBF4F6;font-family:'Poppins',sans-serif}
        .auth-navbar{box-shadow:0 2px 4px rgba(0,0,0,.05)}
        .navbar-brand-custom{color:#0D9488!important;font-weight:700;font-size:1.5rem}
        .auth-card{background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.05);max-width:550px;width:100%;padding:3.5rem 3rem}
        .btn-teal{background-color:#14B8A6;color:#fff;font-weight:600;border:none;padding:.75rem;border-radius:8px;font-size:1rem}
        .btn-teal:hover{background-color:#0D9488;color:#fff}
        .form-control{border-radius:8px;padding:.75rem 1rem;border:1.5px solid #4B5563;font-size:.9rem;font-family:'Poppins',sans-serif}
        .form-control::placeholder{color:#9CA3AF}
        .form-control:focus{border-color:#0D9488;box-shadow:0 0 0 .2rem rgba(13,148,136,.25)}
        .divider{display:flex;align-items:center;text-align:center;color:#6B7280;font-size:.85rem}
        .divider::before,.divider::after{content:'';flex:1;border-bottom:1px solid #E5E7EB}
        .divider:not(:empty)::before{margin-right:1em}.divider:not(:empty)::after{margin-left:1em}
        .btn-social{border:1px solid #D1D5DB;border-radius:8px;padding:.6rem;font-weight:600;background:white;font-size:.9rem;color:#374151;font-family:'Poppins',sans-serif}
        .btn-social:hover{background-color:#F3F4F6}
        .footer-text{font-size:.75rem;color:#4B5563}.footer-link{text-decoration:none;color:#4B5563;margin:0 10px}.footer-link:hover{color:#111827}
        .auth-error{font-size:.8rem;margin-top:4px;color:#dc3545}
        @media(max-width:576px){.auth-card{padding:2rem 1.25rem}.auth-navbar .container-fluid{padding-left:1rem!important;padding-right:1rem!important}.auth-brand-icon{margin-left:0!important}.auth-nav-link{margin-right:.25rem!important}}
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-light bg-white py-3 auth-navbar">
        <div class="container-fluid px-4">
            <a class="navbar-brand navbar-brand-custom d-flex align-items-center gap-2" href="{{ route('home') }}">
                <i class="bi bi-arrow-repeat text-secondary fs-2 ms-5 auth-brand-icon"></i>
                <span>ReShare</span>
            </a>
            <div class="d-flex align-items-center gap-4">
                <a href="{{ route('register') }}" class="text-decoration-none fw-semibold auth-nav-link me-5" style="color:@yield('register-color', '#212529')">Register</a>
                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold auth-nav-link me-5" style="color:@yield('login-color', '#212529')">Login</a>
            </div>
        </div>
    </nav>

    <div class="container flex-grow-1 d-flex align-items-center justify-content-center py-5">
        <div class="auth-card mx-auto">
            @yield('auth-content')
        </div>
    </div>

    <footer class="py-4 mt-auto" style="background-color:transparent">
        <div class="container text-center">
            <p class="footer-text mb-0">2026 ReShare. All rights reserved.
                <span class="mx-2"></span><a href="#" class="footer-link">Terms of service</a>
                <span class="mx-2"></span><a href="#" class="footer-link">Privacy Policy</a>
                <span class="mx-2"></span><a href="#" class="footer-link">Help Center</a>
            </p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
