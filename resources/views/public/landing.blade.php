@extends('layouts.public')

@section('title', 'Portal Sistem Inventaris & Penjualan')

@section('content')
<!-- Hero & Single Portal Gateway (Clean Deep Navy & White) -->
<div class="py-5" style="background: linear-gradient(150deg, #091a36 0%, #0f2b5c 50%, #1e40af 100%); min-height: 85vh; display: flex; align-items: center;">
    <div class="container py-3">
        <!-- Logo & Brand Header -->
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo-badge.svg') }}" alt="Stencu Coffee Logo" height="95" class="mb-3 shadow-sm">
            
            <div class="d-flex justify-content-center mb-2">
                <span class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-25 px-3 py-1.5 rounded-pill fw-semibold small">
                    <i class="bi bi-geo-alt-fill me-1"></i> Batuphat Barat, Muara Satu, Lhokseumawe
                </span>
            </div>

            <h1 class="h2 fw-bold text-white mb-2" style="letter-spacing: 0.5px;">Stencu Coffee Management System</h1>
            <p class="text-light opacity-75 mx-auto mb-0" style="max-width: 620px; font-size: 1rem; line-height: 1.5;">
                Sistem Terpadu Inventaris &amp; Penjualan Warkop Stencu Coffee. Silakan pilih peran di bawah ini untuk mengakses sistem sesuai kebutuhan Anda:
            </p>
        </div>

        <!-- 3 Role Access Cards (Clean, Simple, & Focused) -->
        <div class="row g-3 justify-content-center max-w-5xl mx-auto">
            <!-- 1. Owner Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4 p-4 bg-white d-flex flex-column" style="border-top: 4px solid #0f2b5c !important;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
                            <i class="bi bi-shield-lock-fill me-1"></i> Owner
                        </span>
                        <div class="p-2 rounded-circle bg-danger-subtle text-danger" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person-fill-gear fs-5"></i>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mb-1 text-dark">Pemilik Toko</h5>
                    <div class="text-muted small mb-3">Akun: <strong>Bang Teuku</strong></div>

                    <ul class="list-unstyled small text-secondary mb-4 flex-grow-1">
                        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Dashboard grafik omset &amp; laba</li>
                        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Laporan penjualan (Export PDF/Excel)</li>
                        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Kontrol stok bahan &amp; data supplier</li>
                        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Manajemen menu produk &amp; user staf</li>
                    </ul>

                    <a href="{{ route('quick.login', 'owner') }}" class="btn btn-primary w-100 fw-bold py-2.5 rounded-pill shadow-sm" style="background-color: #0f2b5c; border: none;">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk sebagai Owner
                    </a>
                </div>
            </div>

            <!-- 2. Staff Kasir Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4 p-4 bg-white d-flex flex-column" style="border-top: 4px solid #2563eb !important;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
                            <i class="bi bi-calculator-fill me-1"></i> Staff Kasir
                        </span>
                        <div class="p-2 rounded-circle bg-primary-subtle text-primary" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-shop fs-5"></i>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mb-1 text-dark">Kasir / Barista</h5>
                    <div class="text-muted small mb-3">Akun: <strong>Rian</strong></div>

                    <ul class="list-unstyled small text-secondary mb-4 flex-grow-1">
                        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Mesin kasir POS penjualan cepat</li>
                        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Input pesanan meja &amp; takeaway</li>
                        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Cetak struk nota pembayaran</li>
                        <li class="mb-2"><i class="bi bi-check2 text-primary me-2"></i>Cek catatan racikan dari pelanggan</li>
                    </ul>

                    <a href="{{ route('quick.login', 'kasir') }}" class="btn btn-primary w-100 fw-bold py-2.5 rounded-pill shadow-sm" style="background-color: #2563eb; border: none;">
                        <i class="bi bi-calculator me-1"></i> Masuk sebagai Kasir
                    </a>
                </div>
            </div>

            <!-- 3. Pelanggan Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4 p-4 bg-white d-flex flex-column" style="border-top: 4px solid #059669 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
                            <i class="bi bi-cup-hot-fill me-1"></i> Pelanggan
                        </span>
                        <div class="p-2 rounded-circle bg-success-subtle text-success" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-bag-check fs-5"></i>
                        </div>
                    </div>
                    
                    <h5 class="fw-bold mb-1 text-dark">Pelanggan Warkop</h5>
                    <div class="text-muted small mb-3">Akun: <strong>Cut Nabila / Tamu</strong></div>

                    <ul class="list-unstyled small text-secondary mb-4 flex-grow-1">
                        <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Buka katalog lengkap kopi &amp; makanan</li>
                        <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Pesan langsung ke kasir dari nomor meja</li>
                        <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Bisa ketik catatan racikan khusus</li>
                        <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Pantau status nota pesanan realtime</li>
                    </ul>

                    <a href="{{ route('quick.login', 'pelanggan') }}" class="btn btn-success w-100 fw-bold py-2.5 rounded-pill shadow-sm" style="background-color: #059669; border: none;">
                        <i class="bi bi-cup-straw me-1"></i> Masuk &amp; Pesan Menu
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Info Highlights -->
        <div class="d-flex justify-content-center gap-4 mt-5 flex-wrap small text-light opacity-75">
            <div><i class="bi bi-clock text-info me-1"></i> Buka 07.30 - 00.30 WIB</div>
            <div><i class="bi bi-wifi text-info me-1"></i> Free Wi-Fi Kencang</div>
            <div><i class="bi bi-plug text-info me-1"></i> Colokan Tiap Meja</div>
            <div><i class="bi bi-qr-code text-info me-1"></i> Bayar Tunai / QRIS</div>
        </div>
    </div>
</div>
@endsection
