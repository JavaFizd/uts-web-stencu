<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Stencu Coffee</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(145deg, #061224 0%, #0b1f3f 50%, #173877 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            padding: 20px;
        }

        /* Tampilan Bingkai Menyerupai Layar Smartphone */
        .phone-frame {
            background: #ffffff;
            border-radius: 36px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4), 0 0 0 10px #091a36;
            max-width: 380px;
            width: 100%;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.15);
            position: relative;
        }

        /* Speaker / Notch Hiasan HP */
        .phone-notch {
            width: 110px;
            height: 14px;
            background-color: #061224;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            margin: 0 auto;
        }

        .phone-header {
            padding: 24px 20px 16px 20px;
            text-align: center;
        }

        .phone-body {
            padding: 0 24px 28px 24px;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 0.9rem;
            border: 1.5px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .btn-stencu-login {
            background-color: #2563eb;
            border: none;
            color: #ffffff;
            font-weight: 700;
            border-radius: 12px;
            padding: 11px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .btn-stencu-login:hover {
            background-color: #1d4ed8;
            color: #ffffff;
        }

        .btn-customer-guest {
            background-color: #ecfdf5;
            color: #059669;
            border: 1.5px solid #a7f3d0;
            font-weight: 700;
            border-radius: 12px;
            padding: 11px;
            font-size: 0.92rem;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: all 0.2s;
        }

        .btn-customer-guest:hover {
            background-color: #059669;
            color: #ffffff;
            border-color: #059669;
        }
    </style>
</head>
<body>
    <div class="phone-frame">
        <!-- Hiasan Notch HP -->
        <div class="phone-notch"></div>

        <!-- Header Simpel -->
        <div class="phone-header">
            <img src="{{ asset('images/logo-badge.svg') }}" alt="Stencu Coffee" height="70" class="mb-2">
            <h5 class="fw-bold mb-0 text-dark">Stencu Coffee</h5>
            <div class="text-muted small" style="font-size: 0.78rem;">Masuk ke Akun Anda</div>
        </div>

        <div class="phone-body">
            <!-- Flash Message / Error -->
            @if(session('success'))
                <div class="alert alert-success py-2 px-3 small rounded-3 mb-3" style="font-size: 0.78rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3" style="font-size: 0.78rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form Login Staff & Owner -->
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-2.5">
                    <label class="form-label small fw-semibold text-secondary mb-1">Email</label>
                    <input type="email" id="emailInput" name="email" class="form-control" placeholder="nama@stencu.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary mb-1">Password</label>
                    <input type="password" id="passwordInput" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-stencu-login w-100 shadow-sm mb-3">
                    Masuk ke Sistem &rarr;
                </button>
            </form>

            <div class="d-flex align-items-center my-3 text-muted">
                <hr class="flex-grow-1 my-0">
                <span class="px-2 small text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Atau</span>
                <hr class="flex-grow-1 my-0">
            </div>

            <!-- Bagian Pelanggan (Tanpa Password - Masuk Langsung) -->
            <div class="mb-3">
                <a href="{{ route('public.menu') }}" class="btn-customer-guest shadow-sm">
                    <i class="bi bi-cup-straw me-1"></i> Masuk Sebagai Pelanggan <br>
                    <span class="small fw-normal" style="font-size: 0.75rem;">(Tanpa Password &bull; Pesan Mandiri)</span>
                </a>
            </div>

            <!-- Tombol Cepat Demo untuk Dosen / Pengujian (Rapi & Bersih) -->
            <div class="pt-2 text-center">
                <div class="text-muted small mb-2" style="font-size: 0.72rem;">Opsi Cepat Demo:</div>
                <div class="d-flex justify-content-center gap-1.5 flex-wrap">
                    <a href="{{ route('quick.login', 'owner') }}" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-0.5 fw-semibold" style="font-size: 0.72rem;">
                        <i class="bi bi-shield-lock-fill me-0.5"></i> Owner
                    </a>
                    <a href="{{ route('quick.login', 'kasir') }}" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-0.5 fw-semibold" style="font-size: 0.72rem;">
                        <i class="bi bi-calculator-fill me-0.5"></i> Staff Kasir
                    </a>
                </div>
            </div>

            <div class="text-center mt-3 pt-2 border-top">
                <a href="{{ route('home') }}" class="text-decoration-none small text-muted" style="font-size: 0.75rem;">
                    &larr; Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</body>
</html>