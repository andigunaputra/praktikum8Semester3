
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Donasi - ReShare</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f7f1;
            padding: 30px 15px;
            color: #26382c;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 28px;
            border-radius: 16px;
        }

        h1 {
            color: #25834b;
        }

        .item {
            padding: 12px;
            margin: 10px 0;
            background: #f4f8f4;
            border-radius: 8px;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            color: #25834b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Donasi Berhasil Diproses!</h1>
        <p>Berikut ringkasan data yang kamu masukkan.</p>

        <div class="item">
            <strong>Nama barang:</strong>
            {{ $data['nama_barang'] }}
        </div>

        <div class="item">
            <strong>Kategori:</strong>
            {{ $data['kategori'] }}
        </div>

        <div class="item">
            <strong>Kondisi:</strong>
            {{ $data['kondisi'] }}
        </div>

        <div class="item">
            <strong>Lokasi:</strong>
            {{ $data['lokasi'] }}
        </div>

        <div class="item">
            <strong>Deskripsi:</strong>
            {{ $data['deskripsi'] }}
        </div>

        <p>
            Catatan: data ini baru diproses sementara
            dan belum disimpan ke database.
        </p>

        <a href="{{ route('donasi.create') }}">Input donasi lagi</a>
        <br>
        <a href="{{ route('home') }}">Kembali ke beranda</a>
    </div>
</body>
</html>
