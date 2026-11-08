
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SiPustaka</title>

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
        }

        .login-container {
            width: 100%;
            max-width: 400px;
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
            margin-bottom: 18px;
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
        }

        button:hover {
            background: #19385f;
        }

        .error {
            background: #fdecec;
            color: #c0392b;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .daftar {
            text-align: center;
            margin-top: 22px;
            color: #777;
            font-size: 14px;
        }

        .daftar a {
            color: #244b80;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="login-container">

        <div class="logo">
            <h2>SiPustaka</h2>
            <p>Sistem Informasi Perpustakaan</p>
        </div>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf

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

            <button type="submit">Login</button>
        </form>

        <div class="daftar">
            Belum punya akun? <a href="/register">Daftar sekarang</a>
        </div>

    </div>

</body>
</html>
