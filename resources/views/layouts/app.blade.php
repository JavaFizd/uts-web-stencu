<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Inventaris & Penjualan') - Stencu Coffee</title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --stencu-navy-dark: #091a36;
            --stencu-navy: #0f2b5c;
            --stencu-blue-primary: #1d4ed8;
            --stencu-blue-royal: #2563eb;
            --stencu-blue-soft: #eff6ff;
            --stencu-blue-border: #bfdbfe;
            --stencu-bg: #f8fafc;
        }
        body {
            background-color: var(--stencu-bg);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            color: #0f172a;
        }
        .navbar-stencu {
            background-color: var(--stencu-navy);
            border-bottom: 2.5px solid var(--stencu-blue-royal);
        }
        .sidebar {
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            min-height: calc(100vh - 62px);
        }
        .nav-link.active-menu {
            background-color: var(--stencu-blue-soft);
            color: var(--stencu-blue-primary) !important;
            font-weight: 700;
            border-left: 4px solid var(--stencu-blue-royal);
            border-radius: 4px 8px 8px 4px;
        }
        .nav-link {
            color: #475569;
            padding: 10px 14px;
            margin-bottom: 4px;
            border-radius: 8px;
            transition: all 0.2s;
            font-weight: 500;
        }
        .nav-link:hover {
            background-color: #f1f5f9;
            color: #0f2b5c;
        }
        .badge-role {
            font-size: 0.75rem;
            padding: 4px 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(15, 43, 92, 0.04);
            background: #ffffff;
        }
        @media print {
            .navbar, .navbar-stencu, .sidebar, .btn, .no-print, [style*="border-bottom: 1px solid rgba(56, 189, 248"], .pagination, .alert {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
            }
            .card-custom, .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
                break-inside: avoid;
            }
            .col-md-9, .col-lg-10, main, .container-fluid {
                width: 100% !important;
                max-width: 100% !important;
                flex: 0 0 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Top Navbar (Deep Navy Blue) -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-stencu sticky-top py-2 shadow-sm">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center" href="{{ Auth::user()->isOwner() ? route('dashboard') : (Auth::user()->isDapurStation() ? route('sales.index') : route('sales.create')) }}">
                <img src="{{ asset('images/logo-white.svg') }}" alt="Stencu Coffee" height="38">
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
                    <!-- Notifikasi Pesanan Masuk Realtime (Untuk Kasir & Dapur) -->
                    <li class="nav-item dropdown me-1">
                        <a class="nav-link text-white position-relative px-2.5 py-1 rounded-pill bg-white bg-opacity-10 d-flex align-items-center gap-1.5 small fw-semibold" href="#" id="orderNotifDropdownToggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi Pesanan Masuk Live">
                            <i class="bi bi-bell-fill text-warning fs-6"></i>
                            <span class="d-none d-sm-inline">Pesanan Masuk</span>
                            <span id="orderNotifBadge" class="badge rounded-pill bg-danger border border-light" style="display: none; font-size: 0.65rem;">
                                0
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-0 overflow-hidden" style="width: 340px; max-width: 92vw;">
                            <li class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                                <span class="fw-bold small text-dark"><i class="bi bi-bell-fill text-primary me-1"></i> Pesanan Pelanggan Live</span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small" id="livePollingStatus" style="font-size: 0.68rem;">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Aktif (Live)
                                </span>
                            </li>
                            <div id="orderNotifList" style="max-height: 290px; overflow-y: auto;">
                                <li class="p-3 text-center text-muted small" id="orderNotifEmpty">
                                    <i class="bi bi-inbox text-muted fs-4 d-block mb-1 opacity-50"></i>
                                    Belum ada pesanan baru.
                                </li>
                            </div>
                            <li class="p-2 border-top bg-light text-center">
                                <a href="{{ route('sales.index') }}" class="small text-decoration-none fw-semibold text-primary">
                                    Buka Antrean Pesanan Dapur &rarr;
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-light opacity-90 px-3 py-1 rounded-pill bg-white bg-opacity-10 small fw-semibold" href="{{ route('public.menu') }}" target="_blank">
                            <i class="bi bi-shop me-1 text-info"></i> Buka Katalog Publik
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="p-1 rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: #2563eb !important;">
                                <i class="bi bi-person-fill fs-6"></i>
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            @if(Auth::user()->isOwner())
                                <span class="badge bg-danger badge-role">Owner</span>
                            @elseif(Auth::user()->isDapurStation())
                                <span class="badge bg-warning text-dark badge-role fw-bold"><i class="bi bi-fire me-1"></i>Dapur</span>
                            @elseif(Auth::user()->isStaff())
                                <span class="badge bg-primary badge-role" style="background-color: #2563eb !important;"><i class="bi bi-calculator me-1"></i>Kasir</span>
                            @else
                                <span class="badge bg-secondary badge-role">Customer</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2">
                            <li><span class="dropdown-item-text text-muted small">{{ Auth::user()->email }}</span></li>
                            @if(Auth::user()->isStaff())
                                <li><hr class="dropdown-divider"></li>
                                <li><span class="dropdown-header py-0 text-uppercase small" style="font-size: 0.68rem;">Ganti Stasiun Tugas</span></li>
                                <li>
                                    <a class="dropdown-item small {{ Auth::user()->isKasirStation() ? 'active fw-bold' : '' }}" href="{{ route('staff.switch-station', 'kasir') }}">
                                        <i class="bi bi-calculator me-2"></i> Stasiun Kasir POS
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item small {{ Auth::user()->isDapurStation() ? 'active fw-bold' : '' }}" href="{{ route('staff.switch-station', 'dapur') }}">
                                        <i class="bi bi-fire me-2"></i> Stasiun Dapur & Barista
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-semibold">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Wrapper with Responsive Sidebar -->
    <div class="container-fluid">
        <div class="row">
            <!-- Left Sidebar Navigation -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar py-3 px-3 collapse" id="navbarContent">
                <div class="position-sticky">
                    <!-- Chip Profil Akun Aktif (Membedakan Staf Kasir vs Dapur vs Owner) -->
                    <div class="p-2.5 mb-3 rounded-3 bg-light border text-center shadow-sm">
                        @if(Auth::user()->isOwner())
                            <span class="badge bg-danger mb-1" style="font-size: 0.7rem;"><i class="bi bi-shield-check me-1"></i>OWNER / ADMIN</span>
                            <div class="fw-bold small text-dark">{{ Auth::user()->name }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">admin@stencu.com</div>
                        @elseif(Auth::user()->isStaff())
                            <span class="badge bg-dark mb-1" style="font-size: 0.68rem;"><i class="bi bi-building me-1"></i>AKUN PERUSAHAAN STAF</span>
                            <div class="fw-bold small text-dark">{{ Auth::user()->name }}</div>
                            <div class="text-muted mb-2" style="font-size: 0.72rem;">{{ Auth::user()->email }}</div>

                            <!-- Indikator Tugas Stasiun & Tombol Cepat Pindah Stasiun -->
                            <div class="p-2 rounded-2 bg-white border">
                                <div class="text-muted mb-1" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">Stasiun Tugas Aktif:</div>
                                @if(Auth::user()->isDapurStation())
                                    <div class="badge bg-warning text-dark w-100 py-1.5 fw-bold mb-1.5" style="font-size: 0.74rem;">
                                        <i class="bi bi-fire me-1"></i>Dapur &amp; Barista
                                    </div>
                                    <a href="{{ route('staff.switch-station', 'kasir') }}" class="btn btn-xs btn-outline-primary w-100 py-1 rounded-pill fw-semibold" style="font-size: 0.68rem;" title="Beralih ke Stasiun Kasir POS">
                                        <i class="bi bi-arrow-repeat me-1"></i>Pindah ke Kasir
                                    </a>
                                @else
                                    <div class="badge bg-primary w-100 py-1.5 fw-bold mb-1.5" style="font-size: 0.74rem; background-color: #2563eb !important;">
                                        <i class="bi bi-calculator me-1"></i>Kasir POS (Jual)
                                    </div>
                                    <a href="{{ route('staff.switch-station', 'dapur') }}" class="btn btn-xs btn-outline-warning text-dark w-100 py-1 rounded-pill fw-semibold" style="font-size: 0.68rem; border-color: #f59e0b;" title="Beralih ke Stasiun Dapur & Barista">
                                        <i class="bi bi-arrow-repeat me-1"></i>Pindah ke Dapur
                                    </a>
                                @endif
                            </div>
                        @else
                            <span class="badge bg-secondary mb-1" style="font-size: 0.7rem;">PELANGGAN</span>
                            <div class="fw-bold small text-dark">{{ Auth::user()->name }}</div>
                            <div class="text-muted" style="font-size: 0.72rem;">{{ Auth::user()->email }}</div>
                        @endif
                    </div>

                    <div class="small fw-bold text-uppercase px-2 mb-2" style="font-size: 0.72rem; color: #1e40af; letter-spacing: 0.8px;">
                        Menu Utama
                    </div>
                    <ul class="nav flex-column mb-3">
                        @if(Auth::user()->isOwner())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active-menu' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard Owner
                                </a>
                            </li>
                        @endif
                        @if(!Auth::user()->isDapurStation())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('sales.create') ? 'active-menu' : '' }}" href="{{ route('sales.create') }}">
                                    <i class="bi bi-cart-plus me-2 text-primary"></i> Kasir POS (Jual)
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sales.index*') && !request()->routeIs('sales.create') ? 'active-menu' : '' }}" href="{{ route('sales.index') }}">
                                <i class="bi bi-receipt me-2 {{ Auth::user()->isDapurStation() ? 'text-warning' : '' }}"></i>
                                {{ Auth::user()->isDapurStation() ? 'Antrean Pesanan Dapur' : 'Riwayat Penjualan' }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('purchases.create') ? 'active-menu' : '' }}" href="{{ route('purchases.create') }}">
                                <i class="bi bi-box-arrow-in-down me-2 text-success"></i> Input Beli Stok
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('purchases.index*') && !request()->routeIs('purchases.create') ? 'active-menu' : '' }}" href="{{ route('purchases.index') }}">
                                <i class="bi bi-truck me-2"></i> Riwayat Pembelian
                            </a>
                        </li>
                    </ul>

                    <div class="small fw-bold text-uppercase px-2 mb-2" style="font-size: 0.72rem; color: #1e40af; letter-spacing: 0.8px;">
                        Inventaris
                    </div>
                    <ul class="nav flex-column mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active-menu' : '' }}" href="{{ route('products.index') }}">
                                <i class="bi bi-box-seam me-2"></i> Katalog Produk
                            </a>
                        </li>
                        @if(Auth::user()->isOwner())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active-menu' : '' }}" href="{{ route('categories.index') }}">
                                    <i class="bi bi-tags me-2"></i> Kategori Produk
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active-menu' : '' }}" href="{{ route('suppliers.index') }}">
                                    <i class="bi bi-people me-2"></i> Mitra Supplier
                                </a>
                            </li>
                        @endif
                    </ul>

                    @if(Auth::user()->isOwner())
                        <div class="small fw-bold text-uppercase px-2 mb-2" style="font-size: 0.72rem; color: #dc2626; letter-spacing: 0.8px;">
                            Superadmin (Owner)
                        </div>
                        <ul class="nav flex-column mb-4">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active-menu' : '' }}" href="{{ route('users.index') }}">
                                    <i class="bi bi-person-gear me-2"></i> Kelola Staf Kasir
                                </a>
                            </li>
                        </ul>
                    @endif

                    <div class="px-2 mt-4 pt-3 border-top">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-pill fw-semibold py-1.5 shadow-sm" style="font-size: 0.8rem;">
                                <i class="bi bi-box-arrow-right me-1"></i> Keluar / Logout
                            </button>
                        </form>
                        <div class="small text-muted mt-2 text-center" style="font-size: 0.72rem;">
                            Stencu Coffee &bull; Lhokseumawe
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Global Flash Notifications -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-danger"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Floating Toast Notifikasi Pesanan Baru (Melayang di Layar Kasir / Dapur) -->
    <div id="liveOrderToastContainer" class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1099; width: 480px; max-width: 95vw; pointer-events: none;"></div>

    <style>
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-25px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // =========================================================================
        // REALTIME ORDER NOTIFICATION SYSTEM (UNTUK KASIR & DAPUR BARISTA)
        // =========================================================================
        let lastKnownOrderId = 0;
        let isInitialCheck = true;
        let pendingNotifCount = 0;

        // Suara Notifikasi Cafe Chime (Menggunakan Web Audio API, 100% Offline & Ringan)
        function playCafeOrderChime() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();

                const now = ctx.currentTime;
                // Nada 1: E5 (659.25 Hz)
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(659.25, now);
                gain1.gain.setValueAtTime(0.25, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.35);

                // Nada 2: A5 (880 Hz)
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(880, now + 0.12);
                gain2.gain.setValueAtTime(0.35, now + 0.12);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.55);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(now + 0.12);
                osc2.stop(now + 0.55);

                // Nada 3: E6 (1318.51 Hz - Chime cerah manis)
                const osc3 = ctx.createOscillator();
                const gain3 = ctx.createGain();
                osc3.type = 'sine';
                osc3.frequency.setValueAtTime(1318.51, now + 0.25);
                gain3.gain.setValueAtTime(0.4, now + 0.25);
                gain3.gain.exponentialRampToValueAtTime(0.001, now + 0.85);
                osc3.connect(gain3);
                gain3.connect(ctx.destination);
                osc3.start(now + 0.25);
                osc3.stop(now + 0.85);
            } catch (e) {
                console.log('Audio chime info:', e);
            }
        }

        // Tampilkan Banner Toast Pesanan Baru Melayang
        function showOrderToast(order) {
            const container = document.getElementById('liveOrderToastContainer');
            if (!container) return;

            const toastId = 'toast-order-' + order.id;
            // Cegah duplikasi toast untuk order yang sama
            if (document.getElementById(toastId)) return;

            const isOnline = order.is_online;
            const badgeType = isOnline ? 'bg-warning text-dark' : 'bg-primary text-white';
            const badgeText = isOnline ? '<i class="bi bi-phone me-1"></i>PESANAN ONLINE MEJA' : '<i class="bi bi-calculator me-1"></i>PESANAN KASIR POS';

            const toastHtml = `
                <div id="${toastId}" class="card border-0 shadow-lg rounded-4 mb-2.5 overflow-hidden" style="pointer-events: auto; background: #ffffff; border-left: 6px solid #2563eb !important; animation: slideDown 0.35s ease;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-1.5">
                            <div>
                                <span class="badge ${badgeType} fw-bold px-2.5 py-1 rounded-pill" style="font-size: 0.68rem; letter-spacing: 0.5px;">
                                    ${badgeText}
                                </span>
                                <span class="text-muted ms-1.5 small fw-semibold" style="font-size: 0.72rem;">${order.time}</span>
                            </div>
                            <button type="button" class="btn-close" style="font-size: 0.75rem;" onclick="dismissOrderToast('${toastId}')"></button>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-1.5">
                            <h6 class="fw-bold text-dark mb-0 fs-6">${order.customer_name}</h6>
                            <span class="badge bg-light text-primary border small" style="font-size: 0.72rem;">#${order.sale_number}</span>
                        </div>

                        <div class="p-2 rounded-3 bg-light mb-2 text-dark small" style="font-size: 0.78rem;">
                            <div class="fw-semibold text-secondary mb-0.5"><i class="bi bi-cup-hot me-1 text-primary"></i> ${order.items_count} Item Menu:</div>
                            <div class="text-dark fw-bold text-truncate">${order.items_summary}</div>
                            ${order.notes ? `<div class="text-danger mt-1 small fst-italic"><i class="bi bi-chat-left-dots me-1"></i>"${order.notes}"</div>` : ''}
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-1 border-top">
                            <div>
                                <div class="text-muted" style="font-size: 0.68rem; text-transform: uppercase;">Total Pembayaran:</div>
                                <div class="fw-bold text-success fs-6">${order.total_formatted} <span class="badge bg-secondary-subtle text-secondary small" style="font-size: 0.65rem;">${order.payment_method}</span></div>
                            </div>
                            <div class="d-flex gap-1.5">
                                <a href="${order.show_url}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold" style="font-size: 0.75rem;">
                                    <i class="bi bi-receipt me-1"></i> Buka Nota
                                </a>
                                @if(Auth::user()->isDapurStation() || Auth::user()->isStaff())
                                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-xs" style="font-size: 0.75rem; background-color: #2563eb; border: none;">
                                    <i class="bi bi-fire me-1"></i> Ke Dapur
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', toastHtml);

            // Auto dismiss toast setelah 12 detik
            setTimeout(() => {
                dismissOrderToast(toastId);
            }, 12000);
        }

        function dismissOrderToast(id) {
            const el = document.getElementById(id);
            if (el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-20px)';
                el.style.transition = 'all 0.3s ease';
                setTimeout(() => el.remove(), 300);
            }
        }

        // Update Dropdown List Notifikasi di Navbar
        function prependOrderToDropdown(order) {
            const emptyEl = document.getElementById('orderNotifEmpty');
            if (emptyEl) emptyEl.style.display = 'none';

            const list = document.getElementById('orderNotifList');
            if (!list) return;

            const itemHtml = `
                <a href="${order.show_url}" class="dropdown-item p-2.5 border-bottom d-flex align-items-center gap-2.5 text-wrap">
                    <div class="p-2 rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; flex-shrink: 0;">
                        <i class="bi bi-bell-fill fs-6"></i>
                    </div>
                    <div style="flex-grow: 1; min-width: 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold small text-dark text-truncate">${order.customer_name}</span>
                            <span class="text-muted" style="font-size: 0.65rem;">${order.time}</span>
                        </div>
                        <div class="text-muted small text-truncate" style="font-size: 0.72rem;">${order.items_summary}</div>
                        <div class="fw-bold text-success" style="font-size: 0.74rem;">${order.total_formatted} &bull; <span class="badge bg-light text-secondary border py-0 px-1" style="font-size: 0.62rem;">${order.payment_method}</span></div>
                    </div>
                </a>
            `;

            list.insertAdjacentHTML('afterbegin', itemHtml);
        }

        function updateNotifBadge(newCount) {
            const badge = document.getElementById('orderNotifBadge');
            if (!badge) return;

            pendingNotifCount += newCount;
            if (pendingNotifCount > 0) {
                badge.textContent = pendingNotifCount > 9 ? '9+' : pendingNotifCount;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }

        // Pengecekan Polling Otomatis Setiap 4 Detik
        async function pollNewOrders() {
            try {
                const response = await fetch("{{ route('sales.check-new-orders') }}?last_id=" + lastKnownOrderId, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) return;
                const data = await response.json();

                if (isInitialCheck) {
                    // Set baseline ID saat pertama kali halaman dimuat
                    lastKnownOrderId = data.latest_id || 0;
                    isInitialCheck = false;

                    // Tampilkan pesanan terbaru di dropdown
                    if (data.recent_orders && data.recent_orders.length > 0) {
                        data.recent_orders.forEach(ord => prependOrderToDropdown(ord));
                    }
                    return;
                }

                // Jika ada pesanan baru masuk!
                if (data.new_count > 0 && data.orders && data.orders.length > 0) {
                    lastKnownOrderId = data.latest_id;
                    updateNotifBadge(data.new_count);

                    // Bunyikan Suara Chime Cafe
                    playCafeOrderChime();

                    // Tampilkan Toast untuk setiap order baru
                    data.orders.forEach(order => {
                        showOrderToast(order);
                        prependOrderToDropdown(order);
                    });

                    // Notifikasi visual jika sedang di halaman antrean dapur
                    const liveBanner = document.getElementById('liveKdsOrderBanner');
                    if (liveBanner) {
                        liveBanner.style.display = 'block';
                    }
                }
            } catch (err) {
                console.log('Polling check error:', err);
            }
        }

        // Reset badge saat dropdown dibuka
        document.getElementById('orderNotifDropdownToggle')?.addEventListener('click', function() {
            pendingNotifCount = 0;
            const badge = document.getElementById('orderNotifBadge');
            if (badge) badge.style.display = 'none';
        });

        // Jalankan polling saat halaman siap
        document.addEventListener('DOMContentLoaded', function() {
            pollNewOrders();
            setInterval(pollNewOrders, 4000); // Poll setiap 4 detik
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
