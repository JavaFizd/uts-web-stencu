<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Stencu Coffee Management System</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #091a36 0%, #0f2b5c 50%, #1e40af 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 24px 48px rgba(9, 26, 54, 0.35);
            max-width: 440px;
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .login-header {
            background-color: #0f2b5c;
            color: #ffffff;
            padding: 30px 24px 24px 24px;
            text-align: center;
            border-bottom: 3px solid #2563eb;
        }
    </style>
</head>
<body>
    <div class="login-card mx-3 my-4">
        <div class="login-header">
            <div class="mb-2">
                <img src="{{ asset('images/logo-badge.svg') }}" alt="Stencu Coffee Logo" height="85">
            </div>
            <h4 class="fw-bold mb-1 mt-1 text-white" style="letter-spacing: 1px;">STENCU COFFEE</h4>
            <div class="text-light opacity-75 small">Sistem Inventaris &amp; Penjualan (UTS Web 2)</div>
        </div>

        <div class="p-4">
            <!-- Flash Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show small rounded-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show small rounded-3" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted"><i class="bi bi-envelope text-primary"></i></span>
                        <input type="email" id="emailInput" name="email" class="form-control" placeholder="nama@stencu.com" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted"><i class="bi bi-lock text-primary"></i></span>
                        <input type="password" id="passwordInput" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Ingat Saya di Perangkat Ini</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold text-white shadow-sm rounded-pill" style="background-color: #2563eb; border: none;">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk ke Sistem
                </button>
            </form>

            <hr class="my-4">

            <!-- Demo Account Fast Fill (Sangat Membantu Saat Dosen Menguji!) -->
            <div class="bg-light p-3 rounded-4 border">
                <div class="fw-bold small text-secondary mb-2 text-center">
                    <i class="bi bi-lightning-charge-fill text-primary"></i> Akun Demo Uji Dosen (Klik Langsung):
                </div>
                <div class="d-grid gap-1">
                    <button type="button" onclick="fillDemo('owner@stencu.com', 'password123')" class="btn btn-outline-danger btn-sm text-start rounded-pill">
                        <strong>Owner:</strong> owner@stencu.com <span class="badge bg-danger float-end">Superadmin</span>
                    </button>
                    <button type="button" onclick="fillDemo('kasir@stencu.com', 'password123')" class="btn btn-outline-primary btn-sm text-start rounded-pill">
                        <strong>Staff:</strong> kasir@stencu.com <span class="badge bg-primary float-end" style="background-color: #2563eb !important;">Kasir / Barista</span>
                    </button>
                    <button type="button" onclick="fillDemo('pelanggan@gmail.com', 'password123')" class="btn btn-outline-secondary btn-sm text-start rounded-pill">
                        <strong>Customer:</strong> pelanggan@gmail.com <span class="badge bg-secondary float-end">Pelanggan</span>
                    </button>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="text-decoration-none small text-muted">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Depan
                </a>
            </div>
        </div>
    </div>

    <script>
        function fillDemo(email, pass) {
            document.getElementById('emailInput').value = email;
            document.getElementById('passwordInput').value = pass;
        }
    </script>
</body>
</html>
