<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi Barang - ReShare</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0f7f1;
            margin: 0;
            padding: 30px 15px;
            color: #26382c;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 5px 20px #00000010;
        }

        h1 {
            color: #25834b;
        }

        label {
            display: block;
            margin-top: 16px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border: 1px solid #ccd8ce;
            border-radius: 8px;
            font: inherit;
        }

        button {
            margin-top: 22px;
            padding: 13px;
            width: 100%;
            border: none;
            border-radius: 8px;
            background: #25834b;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #176537;
        }

        .error {
            color: #c62828;
            font-size: 14px;
        }

        a {
            color: #25834b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>ReShare</h1>
        <p>Berbagi Barang, Menebar Harapan.</p>
        <h2>Form Donasi Barang</h2>

        @if ($errors->any())
            <div class="error">
                <p>Periksa kembali data yang kamu masukkan:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('donasi.proses') }}" method="POST">
            @csrf

            <label>Nama Barang</label>
            <input
                type="text"
                name="nama_barang"
                value="{{ old('nama_barang') }}"
                placeholder="Contoh: Baju layak pakai"
                required
            >

            <label>Kategori</label>
            <select name="kategori" required>
                <option value="">Pilih kategori</option>
                <option value="Pakaian">Pakaian</option>
                <option value="Buku & Alat Tulis">Buku & Alat Tulis</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Peralatan Rumah">Peralatan Rumah Tangga</option>
                <option value="Mainan Anak">Mainan Anak</option>
            </select>

            <label>Kondisi Barang</label>
            <select name="kondisi" required>
                <option value="">Pilih kondisi</option>
                <option value="Sangat Baik">Sangat Baik</option>
                <option value="Baik">Baik</option>
                <option value="Cukup">Cukup</option>
            </select>

            <label>Lokasi Barang</label>
            <input
                type="text"
                name="lokasi"
                value="{{ old('lokasi') }}"
                placeholder="Contoh: Buah Batu, Bandung"
                required
            >

            <label>Deskripsi</label>
            <textarea
                name="deskripsi"
                rows="4"
                placeholder="Jelaskan kondisi dan manfaat barang"
                required
            >{{ old('deskripsi') }}</textarea>

            <button type="submit">Proses Donasi</button>
        </form>

        <p><a href="{{ route('home') }}">Kembali ke beranda</a></p>
    </div>
</body>
</html>
