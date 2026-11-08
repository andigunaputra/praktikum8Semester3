<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ReShare') | Berbagi Barang, Menebar Harapan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/reshare-asli.css') }}">
</head>
<body class="d-flex flex-column min-vh-100">
    @auth
        @include('components.navbar')
    @endauth
    @if (session('success'))
        <div class="container-fluid px-4 px-lg-5 pt-3"><div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius:10px">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>
    @endif
    @if ($errors->any())
        <div class="container-fluid px-4 px-lg-5 pt-3"><div class="alert alert-danger border-0 shadow-sm" style="border-radius:10px"><strong>Periksa kembali input kamu:</strong><ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>
    @endif
    <main class="flex-grow-1">
        @yield('content')
    </main>
    <footer class="reshare-footer">© {{ date('Y') }} ReShare — Berbagi Barang, Menebar Harapan.</footer>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
