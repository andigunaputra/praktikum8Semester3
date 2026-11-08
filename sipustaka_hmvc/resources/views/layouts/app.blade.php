
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SiPustaka') - Digital Library</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f7fb;
            color: #1e293b;
        }

        .sidebar {
            position: fixed;
            width: 250px;
            height: 100vh;
            background: #142d4e;
            color: white;
            padding: 28px 18px;
        }

        .brand {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 40px;
            padding-left: 12px;
        }

        .brand span {
            color: #60a5fa;
        }

        .menu-title {
            color: #94a9c4;
            font-size: 11px;
            font-weight: 700;
            margin: 25px 12px 12px;
            text-transform: uppercase;
        }

        .menu a {
            display: block;
            color: #d4dfed;
            text-decoration: none;
            padding: 13px 14px;
            margin-bottom: 7px;
            border-radius: 9px;
            font-size: 14px;
            transition: .2s;
        }

        .menu a:hover,
        .menu a.active {
            background: #244a76;
            color: white;
        }

        .main {
            margin-left: 250px;
            padding: 30px 38px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
        }

        .topbar h2 {
            font-size: 23px;
            color: #142d4e;
        }

        .user-info {
            background: white;
            padding: 12px 18px;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0,0,0,.04);
            font-size: 13px;
        }

        .content {
            background: white;
            padding: 28px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(15, 40, 70, .04);
        }

        .page-title {
            font-size: 22px;
            margin-bottom: 8px;
            color: #142d4e;
        }

        .page-description {
            color: #8290a3;
            font-size: 13px;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            border: none;
            background: #2563eb;
            color: white;
            padding: 11px 18px;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        .btn-danger {
            background: #dc2626;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 22px;
            font-size: 13px;
        }

        th {
            background: #f1f5f9;
            color: #475569;
            text-align: left;
            padding: 15px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #edf0f5;
        }

        input, select {
            width: 100%;
            padding: 11px;
            border: 1px solid #dce3ec;
            border-radius: 8px;
            margin-top: 7px;
            font-family: inherit;
        }

        .alert {
            padding: 13px;
            background: #dcfce7;
            color: #166534;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        @media(max-width: 800px) {
            .sidebar {
                width: 190px;
            }

            .main {
                margin-left: 190px;
                padding: 20px;
            }
        }

        @media(max-width: 600px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 15px;
            }

            .content {
                padding: 17px;
                overflow-x: auto;
            }

            .topbar {
                gap: 12px;
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="brand">Si<span>Pustaka</span></div>

        <div class="menu-title">Menu Utama</div>

        <nav class="menu">
            <a href="{{ url('/dashboard') }}">▦ Dashboard</a>
            <a href="{{ url('/buku') }}">▤ Data Buku</a>
            <a href="{{ url('/peminjaman') }}">⇄ Peminjaman</a>

            @if(auth()->user()->role === 'admin')
                <div class="menu-title">Administrasi</div>
                <a href="{{ url('/kelola-pengguna') }}">♙ Kelola Pengguna</a>
            @endif
        </nav>

        <div class="menu-title">Akun</div>

        <nav class="menu">
            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button class="btn btn-danger" type="submit" style="width:100%">
                    Keluar
                </button>
            </form>
        </nav>
    </aside>

    <main class="main">

        <header class="topbar">
            <h2>@yield('header', 'Dashboard')</h2>

            <div class="user-info">
                {{ auth()->user()->name }}
                ({{ ucfirst(auth()->user()->role) }})
            </div>
        </header>

        <section class="content">
            @if(session('success'))
                <div class="alert">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </section>

    </main>

    @stack('scripts')

</body>
</html>
