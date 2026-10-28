<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Stencu Coffee</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: radial-gradient(circle at 50% 20%, #102a54 0%, #061327 70%, #030a17 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            padding: 24px 16px;
            margin: 0;
        }

        /* Bingkai Smartphone Modern & Bersih */
        .phone-frame {
            background: #ffffff;
            border-radius: 46px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6), 0 0 0 10px #0b1f3d;
            max-width: 375px;
            width: 100%;
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* Speaker / Dynamic Island Notch */
        .phone-notch {
            width: 96px;
            height: 16px;
            background-color: #0b1f3d;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .notch-camera {
            width: 7px;
            height: 7px;
            background-color: #1e3a66;
            border-radius: 50%;
        }

        .notch-speaker {
            width: 38px;
            height: 3.5px;
            background-color: #1e3a66;
            border-radius: 4px;
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 0.9rem;
            border: 1.5px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .btn-masuk {
            background-color: #2563eb;
            border: none;
            color: #ffffff;
            font-weight: 700;
            border-radius: 12px;
            padding: 11px;
            font-size: 0.94rem;
            transition: all 0.2s;
        }

        .btn-masuk:hover {
            background-color: #1d4ed8;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-pelanggan {
            background-color: #ecfdf5;
            color: #059669;
            border: 1.5px solid #a7f3d0;
            font-weight: 700;
            border-radius: 12px;
            padding: 10px;
            font-size: 0.88rem;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: all 0.2s;
        }

        .btn-pelanggan:hover {
            background-color: #059669;
            color: #ffffff;
            border-color: #059669;
            transform: translateY(-1px);
        }

        .home-bar {
            width: 110px;
            height: 4px;
            background-color: #cbd5e1;
            border-radius: 10px;
            margin: 14px auto 8px;
        }
    </style>
</head>
<body>
    <div class="phone-frame">
        <!-- Notch Layar HP -->
        <div class="phone-notch">
            <div class="notch-speaker"></div>
            <div class="notch-camera"></div>
        </div>

        <!-- Header -->
        <div class="pt-2 pb-2 text-center">
            <img src="{{ asset('images/logo-badge.svg') }}" alt="Stencu" height="64" class="mb-2">
            <h5 class="fw-bold mb-0 text-dark" style="color: #0f2b5c !important;">Stencu Coffee</h5>
            <div class="text-muted small" style="font-size: 0.78rem;">Masuk Akun</div>
        </div>

        <div class="px-4 pb-3">
            <!-- Alert Notifikasi -->
            @if(session('success'))
                <div class="alert alert-success py-1.5 px-2.5 small rounded-3 mb-2 text-center" style="font-size: 0.76rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-1.5 px-2.5 small rounded-3 mb-2 text-center" style="font-size: 0.76rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form Login (Hanya Username 'admin' / 'kasir' Tanpa Email Panjang) -->
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-2.5">
                    <label class="form-label">Username</label>
                    <input type="text" id="loginInput" name="login" class="form-control" placeholder="admin / kasir" value="{{ old('login', old('email')) }}" required autofocus autocomplete="username">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" id="passwordInput" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn btn-masuk w-100 shadow-sm mb-2.5">
                    Masuk
                </button>
            </form>

            <div class="d-flex align-items-center my-2 text-muted">
                <hr class="flex-grow-1 my-0">
                <span class="px-2 small text-muted" style="font-size: 0.72rem;">atau</span>
                <hr class="flex-grow-1 my-0">
            </div>

            <!-- Masuk Pelanggan Tanpa Password -->
            <div class="mb-3">
                <a href="{{ route('public.menu') }}" class="btn-pelanggan shadow-sm">
                    <i class="bi bi-cup-straw me-1"></i> Masuk Pelanggan (Tanpa Password)
                </a>
            </div>

            <!-- Tombol Cepat Demo (Hanya Owner & Kasir) -->
            <div class="d-flex justify-content-center gap-2 pt-1">
                <a href="{{ route('quick.login', 'owner') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.74rem;">
                    👑 Demo Owner
                </a>
                <a href="{{ route('quick.login', 'kasir') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.74rem;">
                    💼 Demo Kasir
                </a>
            </div>

            <div class="text-center mt-3 pt-2 border-top">
                <a href="{{ route('home') }}" class="text-decoration-none small text-muted" style="font-size: 0.75rem;">
                    &larr; Halaman Utama
                </a>
            </div>

            <!-- Indikator Home Bar Bawah HP -->
            <div class="home-bar"></div>
        </div>
    </div>
</body>
</html>
