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
    </style>
    @yield('styles')
</head>
<body>
    <!-- Top Evaluator / Switcher Bar (Mode Uji Coba Multi-Peran) -->
    <div class="py-1 px-3 text-white d-flex justify-content-between align-items-center flex-wrap gap-2" style="background-color: #061224; border-bottom: 1px solid rgba(56, 189, 248, 0.2); font-size: 0.8rem;">
        <div class="d-flex align-items-center gap-2">
            <span class="badge px-2 py-0.5 rounded-pill" style="background-color: #2563eb; font-size: 0.72rem;">
                <i class="bi bi-shield-check me-1"></i> Mode Evaluasi UTS
            </span>
            <span class="text-light opacity-75 d-none d-sm-inline">
                Login Sebagai: <strong class="text-white">{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</strong>
            </span>
        </div>
        <div class="d-flex align-items-center gap-1">
            <span class="text-light opacity-75 small me-1 d-none d-md-inline" style="font-size: 0.75rem;">Ganti Peran:</span>
            <a href="{{ route('quick.login', 'owner') }}" class="btn btn-sm py-0 px-2 fw-semibold {{ Auth::user()->isOwner() ? 'btn-danger text-white' : 'btn-outline-light text-light' }}" style="font-size: 0.72rem; border-radius: 6px;">
                <i class="bi bi-person-fill-gear"></i> Owner
            </a>
            <a href="{{ route('quick.login', 'kasir') }}" class="btn btn-sm py-0 px-2 fw-semibold {{ (Auth::user()->isStaff() && Auth::user()->email === 'kasir@stencu.com') ? 'btn-primary text-white' : 'btn-outline-light text-light' }}" style="font-size: 0.72rem; border-radius: 6px; {{ (Auth::user()->isStaff() && Auth::user()->email === 'kasir@stencu.com') ? 'background-color: #2563eb; border-color: #2563eb;' : '' }}">
                <i class="bi bi-calculator"></i> Kasir
            </a>
            <a href="{{ route('quick.login', 'dapur') }}" class="btn btn-sm py-0 px-2 fw-semibold {{ (Auth::user()->isStaff() && Auth::user()->email === 'dapur@stencu.com') ? 'btn-warning text-dark' : 'btn-outline-light text-light' }}" style="font-size: 0.72rem; border-radius: 6px; {{ (Auth::user()->isStaff() && Auth::user()->email === 'dapur@stencu.com') ? 'background-color: #f59e0b; border-color: #f59e0b;' : '' }}">
                <i class="bi bi-fire"></i> Dapur
            </a>
            <a href="{{ route('quick.login', 'pelanggan') }}" class="btn btn-sm py-0 px-2 fw-semibold {{ Auth::user()->role === 'customer' ? 'btn-success text-white' : 'btn-outline-light text-light' }}" style="font-size: 0.72rem; border-radius: 6px;">
                <i class="bi bi-cup-hot"></i> Pelanggan
            </a>
            <a href="{{ route('home') }}" class="btn btn-sm py-0 px-2 btn-outline-info" style="font-size: 0.72rem; border-radius: 6px;" title="Kembali ke Beranda Utama">
                <i class="bi bi-house"></i> Portal Depan
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline ms-1">
                @csrf
                <button type="submit" class="btn btn-sm py-0 px-2 btn-outline-danger" style="font-size: 0.72rem; border-radius: 6px;" title="Logout">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- Top Navbar (Deep Navy Blue) -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-stencu sticky-top py-2 shadow-sm">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo-white.svg') }}" alt="Stencu Coffee" height="38">
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto align-items-center gap-2">
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
                            @elseif(Auth::user()->isStaff())
                                <span class="badge bg-primary badge-role" style="background-color: #2563eb !important;">Staff</span>
                            @else
                                <span class="badge bg-secondary badge-role">Customer</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2">
                            <li><span class="dropdown-item-text text-muted small">{{ Auth::user()->email }}</span></li>
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
                    <div class="small fw-bold text-uppercase px-2 mb-2" style="font-size: 0.72rem; color: #1e40af; letter-spacing: 0.8px;">
                        Menu Utama
                    </div>
                    <ul class="nav flex-column mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active-menu' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sales.create') ? 'active-menu' : '' }}" href="{{ route('sales.create') }}">
                                <i class="bi bi-cart-plus me-2 text-primary"></i> Kasir POS (Jual)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('sales.index*') && !request()->routeIs('sales.create') ? 'active-menu' : '' }}" href="{{ route('sales.index') }}">
                                <i class="bi bi-receipt me-2"></i> Riwayat Penjualan
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
                        Master Inventaris
                    </div>
                    <ul class="nav flex-column mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active-menu' : '' }}" href="{{ route('products.index') }}">
                                <i class="bi bi-box-seam me-2"></i> Katalog Produk
                            </a>
                        </li>
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

                    <div class="px-2 mt-4 pt-3 border-top small text-muted">
                        <div class="fw-bold text-dark">Stencu Coffee v2.0</div>
                        <span style="font-size: 0.75rem;">Batuphat Barat, Lhokseumawe</span>
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

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
