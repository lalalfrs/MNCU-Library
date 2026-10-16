<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kata Sandi Baru Akun Perpustakaan MNC University</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #111827;
            margin: 0;
            padding: 24px;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #0f2a6b;
            padding: 32px 28px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: #dbeafe;
        }
        .content {
            padding: 32px 28px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #0f2a6b;
        }
        .text {
            font-size: 14px;
            line-height: 1.6;
            color: #374151;
            margin-bottom: 20px;
        }
        .password-box {
            background-color: #f0f4ff;
            border: 2px dashed #0f2a6b;
            border-radius: 12px;
            padding: 18px 24px;
            text-align: center;
            margin: 24px 0;
        }
        .password-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #4b5563;
            margin-bottom: 6px;
            font-weight: 600;
        }
        .password-value {
            font-family: 'Courier New', Courier, monospace;
            font-size: 22px;
            font-weight: 700;
            color: #0f2a6b;
            letter-spacing: 2px;
            user-select: all;
        }
        .btn-container {
            text-align: center;
            margin: 28px 0;
        }
        .btn {
            display: inline-block;
            background-color: #0f2a6b;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 32px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(15, 42, 107, 0.25);
        }
        .footer {
            background-color: #f8fafc;
            border-top: 1px solid #e5e7eb;
            padding: 20px 28px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            line-height: 1.5;
        }
        .alert-security {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 13px;
            color: #92400e;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Perpustakaan MNC University</h1>
            <p>Layanan Otentikasi & Keamanan Pemustaka</p>
        </div>

        <div class="content">
            <div class="greeting">Halo, {{ $pengguna->nama_lengkap }}!</div>
            
            <p class="text">
                Kami menerima permintaan reset kata sandi untuk akun perpustakaan Anda yang terdaftar dengan email <strong>{{ $pengguna->email }}</strong>. Sistem telah membuatkan kata sandi baru untuk Anda:
            </p>

            <div class="password-box">
                <div class="password-label">Kata Sandi Baru Anda</div>
                <div class="password-value">{{ $newPassword }}</div>
            </div>

            <p class="text">
                Silakan gunakan kata sandi di atas untuk masuk ke sistem perpustakaan.
            </p>

            <div class="btn-container">
                <a href="{{ route('login') }}" class="btn">Masuk ke Perpustakaan</a>
            </div>

            <div class="alert-security">
                <strong>Tips Keamanan:</strong> Demi menjaga keamanan data peminjaman dan riwayat baca Anda, sangat disarankan untuk segera mengubah kata sandi ini setelah Anda berhasil masuk.
            </div>
        </div>

        <div class="footer">
            © {{ date('Y') }} Perpustakaan MNC University.<br>
            Gedung Rektorat Lt. 2, Jl. Panjang No. 8C, Green Garden, Jakarta Barat.<br>
            Email: perpustakaan@mncuniversity.ac.id | Telp: (021) 5366-6555
        </div>
    </div>
</body>
</html>
