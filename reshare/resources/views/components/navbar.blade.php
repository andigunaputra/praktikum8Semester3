@php($path = request()->path())
<nav class="navbar navbar-expand-lg navbar-custom py-3 sticky-top">
    <div class="container-fluid px-4 px-lg-5">
        <div class="d-flex align-items-center gap-4">
            <a class="navbar-brand brand-logo d-flex align-items-center gap-2 m-0" href="{{ route('dashboard') }}"><i class="bi bi-arrow-repeat text-secondary fs-4"></i><span>ReShare</span></a>
            <form action="{{ route('barang.index') }}" method="GET" class="d-none d-md-flex position-relative">
                <input type="text" name="search" class="form-control search-input pe-5" placeholder="Cari barang donasi" value="{{ request('search') }}">
                <button type="submit" class="btn position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent text-dark"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link nav-link-custom {{ in_array($path, ['dashboard']) ? 'active' : '' }}" href="{{ route('dashboard') }}">Beranda</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">Barang</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom {{ request()->routeIs('donasi.*') ? 'active' : '' }}" href="{{ route('donasi.index') }}">Donasi</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom {{ request()->routeIs('riwayat') ? 'active' : '' }}" href="{{ route('riwayat') }}">Riwayat</a></li>
                @if (auth()->user()->role === 'admin')
                    <li class="nav-item dropdown"><a class="nav-link nav-link-custom dropdown-toggle {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">Admin Panel</a>
                        <ul class="dropdown-menu shadow border-0 mt-2">
                            <li><a class="dropdown-item small" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><span class="dropdown-item small text-muted"><i class="bi bi-people me-2"></i>Kelola User</span></li>
                            <li><span class="dropdown-item small text-muted"><i class="bi bi-grid me-2"></i>Kelola Kategori</span></li>
                            <li><a class="dropdown-item small" href="{{ route('admin.barang.index') }}"><i class="bi bi-box-seam me-2"></i>Kelola Barang</a></li>
                            <li><span class="dropdown-item small text-muted"><i class="bi bi-clipboard-check me-2"></i>Kelola Pengajuan</span></li>
                        </ul>
                    </li>
                @endif
                <li class="nav-item dropdown ms-lg-2"><a class="nav-link nav-link-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}</a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2"><li><a class="dropdown-item small" href="{{ route('profil') }}"><i class="bi bi-person me-2"></i>Edit Profil</a></li><li><hr class="dropdown-divider"></li><li><form method="POST" action="{{ route('logout') }}">@csrf<button class="dropdown-item small text-danger" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button></form></li></ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
