<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Stencu Coffee</title>
    <!-- Bootstrap 5 CSS & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            padding: 20px;
            margin: 0;
            color: #1e293b;
        }
        .login-card {
            max-width: 420px;
            width: 100%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 32px 28px;
        }
        .brand-logo {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Logo & Judul -->
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo-badge.svg') }}" alt="Logo Stencu" class="brand-logo mb-2">
            <h4 class="fw-bold mb-1 text-dark">Stencu Coffee</h4>
            <p class="text-muted small mb-0">Sistem Kasir &amp; Inventaris</p>
        </div>

        <!-- Alert Error / Notifikasi -->
        @if(session('success'))
            <div class="alert alert-success py-2 px-3 small rounded-3 mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Login -->
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <input type="hidden" name="station" id="stationInput" value="kasir">

            <!-- Pilih Role / Posisi Tugas -->
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary mb-1">Posisi / Role</label>
                <select id="roleSelect" class="form-select form-select-sm" onchange="onRoleChange(this.value)">
                    <option value="kasir" selected>Kasir POS</option>
                    <option value="dapur">Dapur &amp; Barista</option>
                    <option value="owner">Owner (Admin)</option>
                </select>
            </div>

            <!-- Email atau Username -->
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary mb-1">Email / Username</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                    <input type="text" id="loginInput" name="login" class="form-control" placeholder="Email atau username" value="staff@stencu.com" required autocomplete="username">
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary mb-1">Password</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                    <input type="password" id="passInput" name="password" class="form-control" placeholder="Password" value="password" required autocomplete="current-password">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()" title="Lihat password">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Tombol Login -->
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mb-3" id="btnLogin">
                Masuk
            </button>
        </form>

        <div class="position-relative text-center my-3">
            <hr class="text-muted opacity-25">
            <span class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small" style="font-size: 0.75rem;">
                atau
            </span>
        </div>

        <!-- Tombol Menu Pelanggan -->
        <a href="{{ route('public.menu') }}" class="btn btn-outline-success w-100 py-2 small fw-semibold">
            <i class="bi bi-cup-hot me-1"></i> Buku Menu Pelanggan (Meja)
        </a>

        <!-- Akses Cepat Ringkas (Untuk Evaluasi Dosen) -->
        <div class="text-center text-muted mt-3" style="font-size: 0.75rem;">
            Akses cepat: 
            <a href="{{ route('quick.login', 'kasir') }}" class="text-decoration-none">Kasir</a> &bull; 
            <a href="{{ route('quick.login', 'dapur') }}" class="text-decoration-none">Dapur</a> &bull; 
            <a href="{{ route('quick.login', 'owner') }}" class="text-decoration-none">Owner</a>
        </div>

        <div class="text-center text-muted mt-3 pt-2 border-top" style="font-size: 0.72rem;">
            &copy; {{ date('Y') }} Stencu Coffee &bull; Lhokseumawe
        </div>
    </div>

    <script>
        function onRoleChange(role) {
            const loginInput = document.getElementById('loginInput');
            const stationInput = document.getElementById('stationInput');
            const btnLogin = document.getElementById('btnLogin');

            if (role === 'kasir') {
                loginInput.value = 'staff@stencu.com';
                stationInput.value = 'kasir';
                btnLogin.innerText = 'Masuk sebagai Kasir';
            } else if (role === 'dapur') {
                loginInput.value = 'staff@stencu.com';
                stationInput.value = 'dapur';
                btnLogin.innerText = 'Masuk sebagai Dapur';
            } else if (role === 'owner') {
                loginInput.value = 'admin@stencu.com';
                stationInput.value = '';
                btnLogin.innerText = 'Masuk sebagai Owner';
            }
        }

        function togglePassword() {
            const pass = document.getElementById('passInput');
            const icon = document.getElementById('eyeIcon');
            if (pass.type === 'password') {
                pass.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                pass.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>
