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
            background: linear-gradient(145deg, #061224 0%, #0b1f3f 50%, #173877 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            padding: 16px;
        }

        /* Bingkai Menyerupai Layar Smartphone */
        .phone-frame {
            background: #ffffff;
            border-radius: 36px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4), 0 0 0 8px #091a36;
            max-width: 360px;
            width: 100%;
            overflow: hidden;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
        }

        .phone-notch {
            width: 90px;
            height: 12px;
            background-color: #061224;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            margin: 0 auto;
        }

        .form-control {
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 0.88rem;
            border: 1.5px solid #e2e8f0;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .btn-masuk {
            background-color: #2563eb;
            border: none;
            color: #ffffff;
            font-weight: 700;
            border-radius: 10px;
            padding: 10px;
            font-size: 0.9rem;
        }

        .btn-masuk:hover {
            background-color: #1d4ed8;
            color: #ffffff;
        }

        .btn-pelanggan {
            background-color: #ecfdf5;
            color: #059669;
            border: 1.5px solid #a7f3d0;
            font-weight: 700;
            border-radius: 10px;
            padding: 9px;
            font-size: 0.88rem;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-pelanggan:hover {
            background-color: #059669;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="phone-frame">
        <div class="phone-notch"></div>

        <div class="p-4 text-center">
            <img src="{{ asset('images/logo-badge.svg') }}" alt="Stencu" height="60" class="mb-2">
            <h5 class="fw-bold mb-0 text-dark">Stencu Coffee</h5>
            <div class="text-muted small" style="font-size: 0.78rem;">Masuk Akun</div>
        </div>

        <div class="px-4 pb-4">
            <!-- Alert Notifikasi -->
            @if(session('success'))
                <div class="alert alert-success py-1.5 px-2.5 small rounded-3 mb-2" style="font-size: 0.75rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-1.5 px-2.5 small rounded-3 mb-2" style="font-size: 0.75rem;">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form Login Email & Password -->
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-2">
                    <label class="form-label small fw-semibold text-secondary mb-1" style="font-size: 0.78rem;">Email</label>
                    <input type="email" id="emailInput" name="email" class="form-control" placeholder="staff@stencu.com" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary mb-1" style="font-size: 0.78rem;">Password</label>
                    <input type="password" id="passwordInput" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-masuk w-100 shadow-sm mb-2.5">
                    Masuk
                </button>
            </form>

            <div class="d-flex align-items-center my-2 text-muted">
                <hr class="flex-grow-1 my-0">
                <span class="px-2 small text-muted" style="font-size: 0.7rem;">atau</span>
                <hr class="flex-grow-1 my-0">
            </div>

            <!-- Masuk Pelanggan Tanpa Password -->
            <div class="mb-3">
                <a href="{{ route('public.menu') }}" class="btn-pelanggan shadow-sm">
                    <i class="bi bi-cup-straw me-1"></i> Masuk Pelanggan (Tanpa Password)
                </a>
            </div>

            <!-- Tombol Cepat Demo (Owner & Staff) -->
            <div class="d-flex justify-content-center gap-1.5 pt-1">
                <a href="{{ route('quick.login', 'owner') }}" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-0.5 fw-semibold" style="font-size: 0.72rem;">
                    👑 Demo Owner
                </a>
                <a href="{{ route('quick.login', 'staff') }}" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-0.5 fw-semibold" style="font-size: 0.72rem;">
                    💼 Demo Staff
                </a>
            </div>

            <div class="text-center mt-3 pt-2 border-top">
                <a href="{{ route('home') }}" class="text-decoration-none small text-muted" style="font-size: 0.74rem;">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </div>
</body>
</html>