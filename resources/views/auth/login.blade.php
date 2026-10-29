<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Sistem Operasional - Stencu Coffee</title>
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
            background: radial-gradient(circle at 10% 20%, #173b75 0%, #0c1f3d 40%, #040914 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            padding: 30px 16px;
            margin: 0;
            color: #0f172a;
        }

        /* Container Kartu Login Modern */
        .login-card-wrapper {
            max-width: 1000px;
            width: 100%;
            background: #ffffff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1);
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Kolom Sisi Kiri (Brand Showcase) */
        .brand-panel {
            background: linear-gradient(145deg, #0f2b5c 0%, #071630 100%);
            color: #ffffff;
            padding: 44px 38px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(59, 130, 246, 0.25), transparent 70%),
                        radial-gradient(circle at bottom left, rgba(245, 158, 11, 0.15), transparent 70%);
            pointer-events: none;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 24px;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.09);
            transform: translateX(3px);
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* Kolom Sisi Kanan (Form Login) */
        .form-panel {
            padding: 44px 40px;
            background: #ffffff;
        }

        /* Segmented Role Tabs */
        .role-tabs {
            display: flex;
            background-color: #f1f5f9;
            padding: 5px;
            border-radius: 14px;
            gap: 6px;
            margin-bottom: 22px;
            border: 1px solid #e2e8f0;
        }

        .role-tab-btn {
            flex: 1;
            border: none;
            background: transparent;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .role-tab-btn:hover {
            color: #0f2b5c;
        }

        .role-tab-btn.active-staff {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .role-tab-btn.active-owner {
            background: #0f2b5c;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(15, 43, 92, 0.3);
        }

        /* Stasiun Kerja Picker (Kasir vs Dapur) */
        .station-card {
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #f8fafc;
            position: relative;
        }

        .station-card:hover {
            border-color: #93c5fd;
            background: #ffffff;
        }

        .station-card.selected-kasir {
            border-color: #2563eb;
            background: #eff6ff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .station-card.selected-dapur {
            border-color: #ea580c;
            background: #fff7ed;
            box-shadow: 0 4px 12px rgba(234, 88, 12, 0.15);
        }

        .form-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
        }

        .input-group-text {
            background-color: #f8fafc;
            border-right: none;
            border-color: #cbd5e1;
            color: #64748b;
            border-radius: 12px 0 0 12px;
            padding-left: 14px;
            padding-right: 10px;
        }

        .form-control {
            border-radius: 12px;
            padding: 11px 14px;
            font-size: 0.92rem;
            border: 1.5px solid #cbd5e1;
            background-color: #f8fafc;
            color: #0f172a;
            transition: all 0.2s;
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
            border: 1.5px solid #cbd5e1;
            border-left: none;
            background-color: #f8fafc;
            color: #64748b;
            border-radius: 0 12px 12px 0;
            padding: 0 14px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .btn-toggle-pass:hover {
            color: #0f2b5c;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
            border-radius: 12px;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control,
        .input-group:focus-within .btn-toggle-pass {
            border-color: #2563eb;
            background-color: #ffffff;
        }

        .btn-submit-main {
            border: none;
            color: #ffffff;
            font-weight: 700;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 0.95rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }

        .btn-submit-main:hover {
            transform: translateY(-1px);
        }

        .btn-pelanggan-alt {
            background-color: #ecfdf5;
            color: #047857;
            border: 1.5px solid #a7f3d0;
            font-weight: 700;
            border-radius: 12px;
            padding: 10px;
            font-size: 0.88rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s;
            width: 100%;
        }

        .btn-pelanggan-alt:hover {
            background-color: #059669;
            color: #ffffff;
            border-color: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
        }

        .demo-chip {
            font-size: 0.74rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 999px;
            text-decoration: none;
            transition: all 0.15s;
        }

        .demo-chip:hover {
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    <div class="login-card-wrapper">
        <div class="row g-0">
            <!-- Kolom Sisi Kiri: Brand & Deskripsi Sistem -->
            <div class="col-lg-5 brand-panel d-none d-lg-flex">
                <div style="position: relative; z-index: 2;">
                    <!-- Badge Lokasi & Status -->
                    <div class="brand-badge">
                        <span class="spinner-grow spinner-grow-sm text-success" style="width: 7px; height: 7px;" role="status"></span>
                        <span>Stencu Coffee &bull; Batuphat Barat</span>
                    </div>

                    <!-- Logo & Judul Brand -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ asset('images/logo-badge.svg') }}" alt="Stencu Logo" width="56" height="56" class="shadow-sm rounded-circle bg-white p-1">
                        <div>
                            <h4 class="fw-bold mb-0 text-white tracking-wide">STENCU COFFEE</h4>
                            <div class="text-white-50 small" style="font-size: 0.82rem;">Integrated POS &amp; Inventory</div>
                        </div>
                    </div>

                    <p class="text-white-50 small mb-4" style="line-height: 1.6;">
                        Sistem manajemen operasional warkop modern terpadu. Mendukung transaksi kasir cepat, tiket pesanan dapur real-time, dan kontrol inventaris akurat.
                    </p>

                    <!-- Fitur Utama -->
                    <div class="feature-item">
                        <div class="feature-icon bg-primary bg-opacity-25 text-info">
                            <i class="bi bi-calculator"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-white small">Kasir POS &amp; QRIS Realtime</div>
                            <div class="text-white-50" style="font-size: 0.74rem;">Pembayaran Tunai, QRIS padat, dan struk instan.</div>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon bg-warning bg-opacity-25 text-warning">
                            <i class="bi bi-fire"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-white small">Kitchen &amp; Barista Display</div>
                            <div class="text-white-50" style="font-size: 0.74rem;">Antrean racikan kopi Sanger &amp; masakan dapur tertib.</div>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon bg-success bg-opacity-25 text-success">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-white small">Smart Inventory &amp; HPP</div>
                            <div class="text-white-50" style="font-size: 0.74rem;">Otomatisasi potong stok dan kalkulasi laba bersih.</div>
                        </div>
                    </div>
                </div>

                <!-- Footer Kolom Kiri -->
                <div class="pt-4 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center" style="position: relative; z-index: 2;">
                    <span class="text-white-50 small" style="font-size: 0.74rem;">
                        <i class="bi bi-shield-check text-success me-1"></i> Sistem Terverifikasi UTS
                    </span>
                    <span class="badge bg-white bg-opacity-10 text-light fw-normal" style="font-size: 0.72rem;">v2.5 Pro</span>
                </div>
            </div>

            <!-- Kolom Sisi Kanan: Form Login Interaktif -->
            <div class="col-lg-7 form-panel">
                <!-- Header Mobile (Hanya tampil di layar kecil) -->
                <div class="d-lg-none text-center mb-4">
                    <img src="{{ asset('images/logo-badge.svg') }}" alt="Stencu" height="52" class="mb-2 shadow-sm rounded-circle">
                    <h5 class="fw-bold mb-0 text-dark">Stencu Coffee</h5>
                    <div class="text-muted small" style="font-size: 0.78rem;">Sistem Kasir, Dapur &amp; Inventaris</div>
                </div>

                <!-- Header Utama Form -->
                <div class="mb-3">
                    <h4 class="fw-bold text-dark mb-1">Masuk ke Sistem</h4>
                    <p class="text-muted small mb-0">Gunakan akun resmi operasional atau pilih stasiun tugas Anda.</p>
                </div>

                <!-- Alert Feedback -->
                @if(session('success'))
                    <div class="alert alert-success py-2 px-3 small rounded-3 mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                        <div>{{ $errors->first() }}</div>
                    </div>
                @endif

                <!-- Pilihan Peran: Akun Perusahaan Staf vs Owner -->
                <div class="role-tabs">
                    <button type="button" class="role-tab-btn active-staff" id="tabStaff" onclick="switchLoginType('staff')">
                        <i class="bi bi-building"></i> Akun Perusahaan Staf
                    </button>
                    <button type="button" class="role-tab-btn" id="tabOwner" onclick="switchLoginType('owner')">
                        <i class="bi bi-shield-lock"></i> Owner (Pemilik)
                    </button>
                </div>

                <!-- FORM UTAMA -->
                <form action="{{ route('login.post') }}" method="POST" id="mainLoginForm">
                    @csrf
                    <!-- Hidden Input untuk Stasiun Kerja Staf ('kasir' atau 'dapur') -->
                    <input type="hidden" name="station" id="stationInput" value="kasir">

                    <!-- SEKSI PILIH STASIUN TUGAS (Hanya Tampil untuk Staf) -->
                    <div id="stationSection" class="mb-3.5">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">PILIH STASIUN TUGAS HARI INI:</label>
                            <span class="badge bg-primary bg-opacity-10 text-primary small" style="font-size: 0.7rem;">Fleksibel per Shift</span>
                        </div>
                        <div class="row g-2">
                            <!-- Stasiun Kasir POS -->
                            <div class="col-6">
                                <div class="station-card selected-kasir" id="cardKasir" onclick="selectStation('kasir')">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div class="d-flex align-items-center gap-1.5 text-primary fw-bold small">
                                            <i class="bi bi-calculator fs-6"></i>
                                            <span>Kasir POS</span>
                                        </div>
                                        <i class="bi bi-check-circle-fill text-primary" id="checkKasir"></i>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.72rem; line-height: 1.35;">
                                        Melayani transaksi penjualan, scan QRIS, dan cetak struk kasir.
                                    </div>
                                </div>
                            </div>

                            <!-- Stasiun Dapur & Barista -->
                            <div class="col-6">
                                <div class="station-card" id="cardDapur" onclick="selectStation('dapur')">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div class="d-flex align-items-center gap-1.5 text-danger fw-bold small">
                                            <i class="bi bi-fire fs-6"></i>
                                            <span>Dapur &amp; Barista</span>
                                        </div>
                                        <i class="bi bi-circle text-muted" id="checkDapur"></i>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.72rem; line-height: 1.35;">
                                        Monitor antrean racikan kopi Sanger &amp; pesanan makanan siap saji.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Input Email Perusahaan / Username -->
                    <div class="mb-3">
                        <label class="form-label" id="labelLogin">Email Resmi Staf</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                            <input type="text" id="loginField" name="login" class="form-control" placeholder="staff@stencu.com" value="{{ old('login', 'staff@stencu.com') }}" required autofocus autocomplete="username">
                        </div>
                        <div class="form-text text-muted" id="helpEmail" style="font-size: 0.74rem;">
                            <i class="bi bi-info-circle me-1"></i>Gunakan satu akun resmi perusahaan untuk seluruh staf operasional.
                        </div>
                    </div>

                    <!-- Input Password -->
                    <div class="mb-3.5">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label mb-1">Password</label>
                            <span class="text-muted small" style="font-size: 0.74rem;">Default: <code>password</code></span>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" id="passField" name="password" class="form-control form-control-pass" placeholder="••••••••" value="password" required autocomplete="current-password">
                            <button type="button" class="btn-toggle-pass" onclick="togglePass()" title="Tampilkan Password">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Masuk Utama -->
                    <button type="submit" class="btn btn-submit-main shadow-sm mb-3" id="btnSubmitMain" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span id="btnSubmitText">Masuk ke Stasiun Kasir POS</span>
                    </button>
                </form>

                <div class="d-flex align-items-center my-3 text-muted">
                    <hr class="flex-grow-1 my-0">
                    <span class="px-3 small text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">atau akses publik</span>
                    <hr class="flex-grow-1 my-0">
                </div>

                <!-- Tombol Masuk Pelanggan (Tanpa Password) -->
                <div class="mb-3">
                    <a href="{{ route('public.menu') }}" class="btn-pelanggan-alt shadow-xs">
                        <i class="bi bi-cup-straw text-success fs-6"></i>
                        <span>Buku Menu &amp; Pemesanan Meja Pelanggan (Tanpa Password)</span>
                    </a>
                </div>

                <!-- Akses Cepat Demo (1-Klik untuk Evaluasi / Dosen) -->
                <div class="p-2.5 rounded-3 bg-light border text-center">
                    <div class="text-muted small fw-bold mb-1.5" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                        AKSES CEPAT DEMO (1-KLIK LANGSUNG):
                    </div>
                    <div class="d-flex flex-wrap justify-content-center gap-1.5">
                        <a href="{{ route('quick.login', 'kasir') }}" class="demo-chip bg-primary text-white" title="Masuk Kasir POS">
                            <i class="bi bi-calculator me-1"></i>Kasir
                        </a>
                        <a href="{{ route('quick.login', 'dapur') }}" class="demo-chip bg-warning text-dark" title="Masuk Dapur & Barista">
                            <i class="bi bi-fire me-1"></i>Dapur
                        </a>
                        <a href="{{ route('quick.login', 'owner') }}" class="demo-chip bg-dark text-white" title="Masuk Owner">
                            <i class="bi bi-shield-lock me-1"></i>Owner
                        </a>
                        <a href="{{ route('public.menu') }}" class="demo-chip bg-success text-white" title="Buka e-Menu Pelanggan">
                            <i class="bi bi-shop me-1"></i>Pelanggan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Interaktif Login & Pemilihan Stasiun Tugas -->
    <script>
        let currentType = 'staff'; // 'staff' atau 'owner'
        let currentStation = 'kasir'; // 'kasir' atau 'dapur'

        function switchLoginType(type) {
            currentType = type;
            const tabStaff = document.getElementById('tabStaff');
            const tabOwner = document.getElementById('tabOwner');
            const stationSection = document.getElementById('stationSection');
            const loginField = document.getElementById('loginField');
            const labelLogin = document.getElementById('labelLogin');
            const helpEmail = document.getElementById('helpEmail');
            const btnSubmitMain = document.getElementById('btnSubmitMain');
            const btnSubmitText = document.getElementById('btnSubmitText');

            if (type === 'staff') {
                tabStaff.className = 'role-tab-btn active-staff';
                tabOwner.className = 'role-tab-btn';
                stationSection.style.display = 'block';
                labelLogin.innerText = 'Email Resmi Staf';
                loginField.value = 'staff@stencu.com';
                loginField.placeholder = 'staff@stencu.com';
                helpEmail.innerHTML = '<i class="bi bi-info-circle me-1"></i>Gunakan satu akun resmi perusahaan untuk seluruh staf operasional.';
                updateSubmitButton();
            } else {
                tabStaff.className = 'role-tab-btn';
                tabOwner.className = 'role-tab-btn active-owner';
                stationSection.style.display = 'none';
                labelLogin.innerText = 'Email Pemilik (Owner)';
                loginField.value = 'admin@stencu.com';
                loginField.placeholder = 'admin@stencu.com';
                helpEmail.innerHTML = '<i class="bi bi-info-circle me-1"></i>Akses penuh ke dashboard analitik finansial, laba rugi, dan master data.';
                btnSubmitMain.style.background = 'linear-gradient(135deg, #0f2b5c 0%, #071630 100%)';
                btnSubmitText.innerText = 'Masuk ke Dashboard Owner';
            }
        }

        function selectStation(station) {
            currentStation = station;
            document.getElementById('stationInput').value = station;

            const cardKasir = document.getElementById('cardKasir');
            const cardDapur = document.getElementById('cardDapur');
            const checkKasir = document.getElementById('checkKasir');
            const checkDapur = document.getElementById('checkDapur');

            if (station === 'kasir') {
                cardKasir.className = 'station-card selected-kasir';
                cardDapur.className = 'station-card';
                checkKasir.className = 'bi bi-check-circle-fill text-primary';
                checkDapur.className = 'bi bi-circle text-muted';
            } else {
                cardKasir.className = 'station-card';
                cardDapur.className = 'station-card selected-dapur';
                checkKasir.className = 'bi bi-circle text-muted';
                checkDapur.className = 'bi bi-check-circle-fill text-danger';
            }

            updateSubmitButton();
        }

        function updateSubmitButton() {
            if (currentType !== 'staff') return;

            const btnSubmitMain = document.getElementById('btnSubmitMain');
            const btnSubmitText = document.getElementById('btnSubmitText');

            if (currentStation === 'kasir') {
                btnSubmitMain.style.background = 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)';
                btnSubmitText.innerText = 'Masuk ke Stasiun Kasir POS';
            } else {
                btnSubmitMain.style.background = 'linear-gradient(135deg, #ea580c 0%, #c2410c 100%)';
                btnSubmitText.innerText = 'Masuk ke Stasiun Dapur & Barista';
            }
        }

        function togglePass() {
            const passField = document.getElementById('passField');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passField.type === 'password') {
                passField.type = 'text';
                eyeIcon.className = 'bi bi-eye-slash';
            } else {
                passField.type = 'password';
                eyeIcon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>