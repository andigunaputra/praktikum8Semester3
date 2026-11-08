
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SiPustaka</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f4f8;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px 15px;
        }

        .register-container {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 35px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo h2 {
            color: #244b80;
            margin-bottom: 7px;
        }

        .logo p {
            color: #777;
            font-size: 14px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            color: #34445a;
            font-size: 14px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #244b80;
        }

        button {
            width: 100%;
            padding: 11px;
            background: #244b80;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 15px;
            margin-top: 5px;
        }

        button:hover {
            background: #19385f;
        }

        .error {
            background: #fdecec;
            color: #c0392b;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .error p {
            margin: 5px 0;
        }

        .login {
            text-align: center;
            margin-top: 22px;
            color: #777;
            font-size: 14px;
        }

        .login a {
            color: #244b80;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="register-container">

        <div class="logo">
            <h2>SiPustaka</h2>
            <p>Buat akun perpustakaan kamu</p>
        </div>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="/register" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name"
                       value="{{ old('name') }}"
                       placeholder="Masukkan nama lengkap"
                       required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="Masukkan email"
                       required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password"
                       placeholder="Masukkan password"
                       required>
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       placeholder="Ulangi password"
                       required>
            </div>

            <button type="submit">Daftar Sekarang</button>
        </form>

        <div class="login">
            Sudah punya akun? <a href="/login">Login</a>
        </div>

    </div>

</body>
</html>
