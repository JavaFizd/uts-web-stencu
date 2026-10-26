<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Stencu Coffee</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: #0f172a;
        }
        .error-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 16px 36px rgba(15, 43, 92, 0.08);
            border: 1px solid #e2e8f0;
            max-width: 520px;
            width: 100%;
            overflow: hidden;
            text-align: center;
            padding: 40px 30px;
        }
    </style>
</head>
<body>
    <div class="error-card mx-3">
        <div class="mb-3">
            <div class="d-inline-flex p-3 rounded-circle bg-danger bg-opacity-10 text-danger fs-1">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
        </div>

        <h3 class="fw-bold mb-2" style="color: #0f2b5c;">Akses Ditolak (403)</h3>
        
        <p class="text-secondary small mb-4" style="line-height: 1.6;">
            {{ $exception->getMessage() ?: 'Maaf, akun Anda tidak memiliki hak akses (wewenang) untuk membuka halaman ini.' }}
        </p>

        @auth
            <div class="p-3 bg-light rounded-3 mb-4 text-start border small">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Nama Pengguna:</span>
                    <strong class="text-dark">{{ Auth::user()->name }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Peran / Role Akun:</span>
                    <span class="badge {{ Auth::user()->role === 'owner' ? 'bg-danger' : (Auth::user()->role === 'staff' ? 'bg-primary' : 'bg-secondary') }}">
                        {{ strtoupper(Auth::user()->role) }}
                    </span>
                </div>
            </div>

            <div class="d-grid gap-2">
                @if(Auth::user()->role === 'owner' || Auth::user()->role === 'staff')
                    <a href="{{ route('dashboard') }}" class="btn btn-primary rounded-pill py-2 fw-semibold" style="background-color: #2563eb; border: none;">
                        <i class="bi bi-speedometer2 me-1"></i> Kembali ke Dashboard
                    </a>
                @else
                    <a href="{{ route('public.menu') }}" class="btn btn-primary rounded-pill py-2 fw-semibold" style="background-color: #2563eb; border: none;">
                        <i class="bi bi-cup-straw me-1"></i> Buka Katalog Menu
                    </a>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 fw-semibold">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout &amp; Ganti Akun Lain
                    </button>
                </form>
            </div>
        @else
            <div class="d-grid gap-2">
                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill py-2 fw-semibold" style="background-color: #2563eb; border: none;">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Halaman Login
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill py-2">
                    Kembali ke Halaman Depan
                </a>
            </div>
        @endauth
    </div>
</body>
</html>
