<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Katalog Menu & Pemesanan Online') - Stencu Coffee Batuphat</title>
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
            --stencu-blue-hover: #1e40af;
            --stencu-blue-light: #eff6ff;
            --stencu-blue-border: #bfdbfe;
            --stencu-cyan-accent: #38bdf8;
            --stencu-card: #ffffff;
            --stencu-bg: #f8fafc;
            --stencu-text: #0f172a;
            --stencu-muted: #64748b;
        }
        body {
            background-color: var(--stencu-bg);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: var(--stencu-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-stencu {
            background-color: var(--stencu-navy);
            border-bottom: 2px solid rgba(56, 189, 248, 0.25);
            backdrop-filter: blur(12px);
        }
        .hero-section {
            background: linear-gradient(145deg, #091a36 0%, #0f2b5c 55%, #1e40af 100%);
            color: #ffffff;
            padding: 70px 0 60px 0;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at 85% 20%, rgba(56, 189, 248, 0.2) 0%, transparent 60%);
            pointer-events: none;
        }
        .card-product {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: var(--stencu-card);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 2px 8px rgba(15, 43, 92, 0.04);
        }
        .card-product:hover {
            transform: translateY(-4px);
            border-color: #93c5fd;
            box-shadow: 0 14px 28px rgba(15, 43, 92, 0.08);
        }
        .badge-available {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            font-weight: 600;
        }
        .badge-low {
            background-color: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
            font-weight: 600;
        }
        .badge-out {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            font-weight: 600;
        }
        .badge-category {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            font-weight: 600;
        }
        .btn-stencu {
            background-color: var(--stencu-blue-royal);
            color: #ffffff;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-stencu:hover {
            background-color: var(--stencu-blue-hover);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }
        .floating-cart-bar {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1040;
            width: 92%;
            max-width: 640px;
            background: var(--stencu-navy);
            color: #ffffff;
            border-radius: 18px;
            box-shadow: 0 16px 36px rgba(9, 26, 54, 0.35);
            border: 1.5px solid rgba(56, 189, 248, 0.4);
            transition: all 0.3s ease;
        }
        .nav-link.active-blue {
            color: #ffffff !important;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 8px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-stencu sticky-top py-2 shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo-white.svg') }}" alt="Stencu Coffee Logo" height="42" class="d-inline-block">
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav ms-auto align-items-center gap-1 my-2 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('home') ? 'active-blue' : 'text-light' }}" href="{{ route('home') }}">
                            <i class="bi bi-house me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('public.menu') ? 'active-blue' : 'text-light' }}" href="{{ route('public.menu') }}">
                            <i class="bi bi-cup-straw me-1"></i> Menu &amp; Pesan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('public.order.track') ? 'active-blue' : 'text-light' }}" href="{{ route('public.order.track') }}">
                            <i class="bi bi-receipt me-1"></i> Cek Status Nota
                        </a>
                    </li>

                    @auth
                        @if(Auth::user()->isOwner())
                            <li class="nav-item ms-lg-2">
                                <a class="btn btn-danger btn-sm px-3 rounded-pill fw-semibold shadow-sm" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Panel Owner
                                </a>
                            </li>
                        @elseif(Auth::user()->isStaff())
                            <li class="nav-item ms-lg-2">
                                <a class="btn btn-primary btn-sm px-3 rounded-pill fw-semibold shadow-sm" href="{{ route('sales.create') }}" style="background-color: #2563eb; border: none;">
                                    <i class="bi bi-calculator me-1"></i> Panel Kasir
                                </a>
                            </li>
                        @endif
                        <li class="nav-item ms-lg-1">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-pill fw-semibold text-white border-danger" style="background-color: rgba(220, 38, 38, 0.2);">
                                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-light btn-sm px-3 rounded-pill fw-semibold" href="{{ route('home') }}">
                                <i class="bi bi-grid-fill me-1"></i> Portal Masuk
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer (Deep Navy & White) -->
    <footer class="bg-dark text-white-50 py-5 mt-5 border-top" style="background-color: #091a36 !important;">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="mb-3">
                        <img src="{{ asset('images/logo-white.svg') }}" alt="Stencu Coffee Logo" height="38">
                    </div>
                    <p class="small text-light opacity-75 mb-3 pe-md-4">
                        Warung kopi santai khas Aceh dengan racikan Kopi Sanger nikmat, kopi hitam saring pekat, aneka minuman sachet dingin, Mie Aceh bumbu rempah, hingga Indomie telur hangat.
                    </p>
                    <div class="small text-light opacity-90">
                        <i class="bi bi-geo-alt-fill text-info me-1"></i> Jl. Medan - Banda Aceh, Batuphat Barat, Kec. Muara Satu, Kota Lhokseumawe, Aceh
                    </div>
                </div>

                <div class="col-md-3">
                    <h6 class="text-white fw-bold mb-3">Jam Buka Warkop</h6>
                    <ul class="list-unstyled small text-light opacity-75">
                        <li class="mb-2"><i class="bi bi-clock me-2 text-info"></i> <strong>Setiap Hari:</strong> 07.30 - 00.30 WIB</li>
                        <li class="mb-2"><i class="bi bi-wifi me-2 text-info"></i> Free Wi-Fi Kencang</li>
                        <li class="mb-2"><i class="bi bi-plug me-2 text-info"></i> Colokan Tiap Meja</li>
                    </ul>
                </div>

                <div class="col-md-4">
                    <h6 class="text-white fw-bold mb-3">Pemesanan Mandiri di Tempat</h6>
                    <p class="small text-light opacity-75 mb-3">
                        Duduk di meja warkop, buka menu dari HP Anda, pilih sajian, dan pesanan akan langsung dibuat oleh barista kami!
                    </p>
                    <a href="{{ route('public.menu') }}" class="btn btn-primary btn-sm fw-bold px-3 py-2 text-white rounded-pill shadow-sm" style="background-color: #2563eb; border: none;">
                        <i class="bi bi-cart3 me-1"></i> Buka Menu Pemesanan
                    </a>
                </div>
            </div>

            <hr class="border-secondary opacity-25 my-4">
            <div class="text-center small text-light opacity-50">
                &copy; {{ date('Y') }} Stencu Coffee Batuphat Barat • Integrated Laravel Project UTS Web Programming 2
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
