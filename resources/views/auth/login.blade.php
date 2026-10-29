<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sistem - Stencu Coffee</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: radial-gradient(circle at 50% 15%, #173b75 0%, #0a1c38 50%, #030a17 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            padding: 24px 16px;
            margin: 0;
            color: #0f172a;
        }

        /* Bingkai Smartphone Modern Premium */
        .phone-frame {
            background: #ffffff;
            border-radius: 46px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.65), 0 0 0 10px #0b1f3d, 0 0 0 12px rgba(255, 255, 255, 0.08);
            max-width: 385px;
            width: 100%;
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.15);
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Speaker & Dynamic Island Notch */
        .phone-notch {
            width: 105px;
            height: 18px;
            background-color: #0b1f3d;
            border-bottom-left-radius: 14px;
            border-bottom-right-radius: 14px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .notch-camera {
            width: 7.5px;
            height: 7.5px;
            background-color: #1e3a66;
            border-radius: 50%;
        }

        .notch-speaker {
            width: 42px;
            height: 4px;
            background-color: #1e3a66;
            border-radius: 4px;
        }

        /* Tab Pilihan Peran Login (Kasir / Dapur / Owner) */
        .role-selector {
            background-color: #f1f5f9;
            border-radius: 14px;
            padding: 4px;
            display: flex;
            gap: 4px;
            margin-bottom: 14px;
            border: 1px solid #e2e8f0;
        }

        .role-btn {
            flex: 1;
            padding: 7px 4px;
            font-size: 0.76rem;
            font-weight: 700;
            border: none;
            background: transparent;
            color: #64748b;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            white-space: nowrap;
        }

        .role-btn:hover {
            color: #1e293b;
        }

        .role-btn.active-kasir {
            background-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 3px 8px rgba(37, 99, 235, 0.35);
        }

        .role-btn.active-dapur {
            background-color: #ea580c;
            color: #ffffff;
            box-shadow: 0 3px 8px rgba(234, 88, 12, 0.35);
        }

        .role-btn.active-owner {
            background-color: #0f2b5c;
            color: #ffffff;
            box-shadow: 0 3px 8px rgba(15, 43, 92, 0.35);
        }

        .role-info-pill {
            font-size: 0.72rem;
            padding: 5px 10px;
            border-radius: 8px;
            background-color: #eff6ff;
            color: #1d4ed8;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 12px;
            border: 1px dashed #bfdbfe;
            width: 100%;
            justify-content: center;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 4px;
        }

        .input-group-text {
            background-color: #f8fafc;
            border-right: none;
            border-color: #e2e8f0;
            color: #64748b;
            border-radius: 12px 0 0 12px;
            padding-left: 12px;
            padding-right: 8px;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 0.9rem;
            border: 1.5px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.2s ease-in-out;
            color: #0f172a;
        }

        .input-group > .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        .input-group > .form-control-pass {
            border-radius: 0;
            border-right: none;
        }

        .btn-toggle-pass {
            border: 1.5px solid #e2e8f0;
            border-left: none;
            background-color: #f8fafc;
            color: #64748b;
            border-radius: 0 12px 12px 0;
            padding: 0 12px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .btn-toggle-pass:hover {
            color: #0f2b5c;
        }

        .form-control:focus, .btn-toggle-pass:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: none;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
            border-radius: 12px;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control,
        .input-group:focus-within .btn-toggle-pass {
            border-color: #2563eb;
            background-color: #ffffff;
        }

        .btn-masuk {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            color: #ffffff;
            font-weight: 700;
            border-radius: 12px;
            padding: 10px;
            font-size: 0.92rem;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-masuk:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
        }

        .btn-pelanggan {
            background-color: #ecfdf5;
            color: #047857;
            border: 1.5px solid #6ee7b7;
            font-weight: 700;
            border-radius: 12px;
            padding: 9px;
            font-size: 0.86rem;
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
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }

        .home-bar {
            width: 110px;
            height: 4.5px;
            background-color: #cbd5e1;
            border-radius: 10px;
            margin: 12px auto 4px;
        }
    </style>
</head>
<body>
    <div class="phone-frame">
        <!-- Notch Speaker & Kamera Layar HP -->
        <div class="phone-notch">
            <div class="notch-speaker"></div>
            <div class="notch-camera"></div>
        </div>

        <!-- Header Branding Warkop Stencu -->
        <div class="pt-2 pb-2 text-center">
            <img src="{{ asset('images/logo-badge.svg') }}" alt="Stencu" height="62" class="mb-1.5 shadow-sm rounded-circle">
            <h5 class="fw-bold mb-0" style="color: #0f2b5c;">Stencu Coffee</h5>
            <div class="text-muted small" style="font-size: 0.76rem;">Sistem Kasir, Dapur & Inventaris</div>
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

            <!-- 1. Pilihan Staf & Peran (Kasir / Dapur / Owner) -->
            <div class="small fw-bold text-muted mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">PILIH AKSES MASUK:</div>
            <div class="role-selector">
                <button type="button" class="role-btn active-kasir" id="btnRoleKasir" onclick="selectRole('kasir')">
                    <i class="bi bi-calculator"></i> Kasir
                </button>
                <button type="button" class="role-btn" id="btnRoleDapur" onclick="selectRole('dapur')">
                    <i class="bi bi-fire"></i> Dapur
                </button>
                <button type="button" class="role-btn" id="btnRoleOwner" onclick="selectRole('owner')">
                    <i class="bi bi-shield-lock"></i> Owner
                </button>
            </div>

            <!-- Keterangan Tugas Role Aktif -->
            <div class="role-info-pill" id="roleInfoPill">
                <i class="bi bi-info-circle-fill"></i>
                <span id="roleInfoText">Layar Kasir POS (Cetak Nota & Transaksi)</span>
            </div>

            <!-- 2. Form Login Fleksibel -->
            <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                @csrf
                <div class="mb-2.5">
                    <label class="form-label" id="labelUsername">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text" id="loginInput" name="login" class="form-control" placeholder="kasir / dapur / admin" value="{{ old('login', 'kasir') }}" required autofocus autocomplete="username">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                        <input type="password" id="passwordInput" name="password" class="form-control form-control-pass" placeholder="••••••••" value="password" required autocomplete="current-password">
                        <button type="button" class="btn-toggle-pass" onclick="togglePasswordVisibility()" title="Lihat/Sembunyikan Password">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-masuk w-100 mb-2.5" id="btnSubmit">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                </button>
            </form>

            <div class="d-flex align-items-center my-2 text-muted">
                <hr class="flex-grow-1 my-0">
                <span class="px-2 small text-muted" style="font-size: 0.72rem;">atau</span>
                <hr class="flex-grow-1 my-0">
            </div>

            <!-- 3. Masuk Pelanggan Tanpa Password (Pesan Sendiri) -->
            <div class="mb-2.5">
                <a href="{{ route('public.menu') }}" class="btn-pelanggan shadow-sm">
                    <i class="bi bi-cup-straw me-1"></i> Masuk Pelanggan (Tanpa Password)
                </a>
            </div>

            <!-- 4. Tombol Demo Langsung 1-Klik -->
            <div class="d-flex justify-content-center gap-1.5 pt-1">
                <a href="{{ route('quick.login', 'kasir') }}" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-0.5 fw-semibold" style="font-size: 0.72rem;" title="1-Klik Masuk Kasir">
                    💼 Kasir
                </a>
                <a href="{{ route('quick.login', 'dapur') }}" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-2.5 py-0.5 fw-semibold" style="font-size: 0.72rem; border-color: #f59e0b;" title="1-Klik Masuk Dapur/Barista">
                    👨‍🍳 Dapur
                </a>
                <a href="{{ route('quick.login', 'owner') }}" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-0.5 fw-semibold" style="font-size: 0.72rem;" title="1-Klik Masuk Owner">
                    👑 Owner
                </a>
            </div>

            <div class="text-center mt-2.5 pt-2 border-top">
                <a href="{{ route('public.menu') }}" class="text-decoration-none small text-primary fw-semibold" style="font-size: 0.76rem;">
                    <i class="bi bi-shop me-1"></i> Buka Katalog Menu &rarr;
                </a>
            </div>

            <!-- Indikator Home Bar Bawah Smartphone -->
            <div class="home-bar"></div>
        </div>
    </div>

    <!-- Script Interaktif Pemilihan Staf (Kasir / Dapur / Owner) -->
    <script>
        function selectRole(role) {
            const btnKasir = document.getElementById('btnRoleKasir');
            const btnDapur = document.getElementById('btnRoleDapur');
            const btnOwner = document.getElementById('btnRoleOwner');
            const loginInput = document.getElementById('loginInput');
            const passwordInput = document.getElementById('passwordInput');
            const roleInfoPill = document.getElementById('roleInfoPill');
            const roleInfoText = document.getElementById('roleInfoText');
            const btnSubmit = document.getElementById('btnSubmit');

            // Reset class active
            btnKasir.className = 'role-btn';
            btnDapur.className = 'role-btn';
            btnOwner.className = 'role-btn';

            if (role === 'kasir') {
                btnKasir.className = 'role-btn active-kasir';
                loginInput.value = 'kasir';
                passwordInput.value = 'password';
                roleInfoPill.style.backgroundColor = '#eff6ff';
                roleInfoPill.style.color = '#1d4ed8';
                roleInfoPill.style.borderColor = '#bfdbfe';
                roleInfoText.innerText = 'Layar Kasir POS (Cetak Nota & Transaksi)';
                btnSubmit.style.background = 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)';
            } else if (role === 'dapur') {
                btnDapur.className = 'role-btn active-dapur';
                loginInput.value = 'dapur';
                passwordInput.value = 'password';
                roleInfoPill.style.backgroundColor = '#fff7ed';
                roleInfoPill.style.color = '#c2410c';
                roleInfoPill.style.borderColor = '#fed7aa';
                roleInfoText.innerText = 'Layar Dapur & Barista (Antrean Pesanan & Stok)';
                btnSubmit.style.background = 'linear-gradient(135deg, #ea580c 0%, #c2410c 100%)';
            } else if (role === 'owner') {
                btnOwner.className = 'role-btn active-owner';
                loginInput.value = 'admin';
                passwordInput.value = 'password';
                roleInfoPill.style.backgroundColor = '#f8fafc';
                roleInfoPill.style.color = '#0f2b5c';
                roleInfoPill.style.borderColor = '#cbd5e1';
                roleInfoText.innerText = 'Dashboard Pemilik (Laba, Omset & Kelola Staf)';
                btnSubmit.style.background = 'linear-gradient(135deg, #0f2b5c 0%, #06152b 100%)';
            }
        }

        function togglePasswordVisibility() {
            const passInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.className = 'bi bi-eye-slash';
            } else {
                passInput.type = 'password';
                eyeIcon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>
