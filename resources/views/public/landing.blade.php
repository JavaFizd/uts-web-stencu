@extends('layouts.public')

@section('title', 'Selamat Datang di Stencu Coffee')

@section('content')
<div class="py-5" style="background: linear-gradient(150deg, #061224 0%, #0b1f3f 50%, #173877 100%); min-height: 82vh; display: flex; align-items: center;">
    <div class="container py-3">
        <!-- Logo & Header -->
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo-badge.svg') }}" alt="Stencu Coffee Logo" height="90" class="mb-3">
            <h2 class="fw-bold text-white mb-2" style="letter-spacing: 0.5px;">Stencu Coffee Batuphat</h2>
            <p class="text-light opacity-75 mx-auto mb-0" style="max-width: 500px; font-size: 0.95rem;">
                Warung kopi santai khas Aceh. Silakan pilih menu di bawah ini untuk memesan atau mengelola warkop:
            </p>
        </div>

        <!-- 3 Pilihan Masuk (Simpel, Jelas, & Elegan) -->
        <div class="row g-3 justify-content-center" style="max-width: 900px; margin: 0 auto;">
            <!-- 1. Pelanggan (Paling Utama) -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4 p-4 bg-white text-center d-flex flex-column" style="border-top: 4px solid #059669 !important;">
                    <div class="mb-3">
                        <div class="p-3 rounded-circle bg-success-subtle text-success mx-auto" style="width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-cup-hot-fill fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Pelanggan Warkop</h5>
                    <p class="text-muted small mb-4 flex-grow-1">
                        Pesan kopi, mie aceh, dan cemilan langsung dari meja Anda tanpa perlu login/password.
                    </p>
                    <a href="{{ route('public.menu') }}" class="btn btn-success fw-bold py-2 rounded-pill shadow-sm" style="background-color: #059669; border: none;">
                        <i class="bi bi-cup-straw me-1"></i> Buka Menu &amp; Pesan
                    </a>
                </div>
            </div>

            <!-- 2. Staff Kasir -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4 p-4 bg-white text-center d-flex flex-column" style="border-top: 4px solid #2563eb !important;">
                    <div class="mb-3">
                        <div class="p-3 rounded-circle bg-primary-subtle text-primary mx-auto" style="width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-calculator fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Staff Kasir</h5>
                    <p class="text-muted small mb-4 flex-grow-1">
                        Input pesanan transaksi kasir POS, cetak struk bon, dan catat belanja stok bahan.
                    </p>
                    <a href="{{ route('quick.login', 'kasir') }}" class="btn btn-primary fw-bold py-2 rounded-pill shadow-sm" style="background-color: #2563eb; border: none;">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Kasir (POS)
                    </a>
                </div>
            </div>

            <!-- 3. Owner -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-lg rounded-4 p-4 bg-white text-center d-flex flex-column" style="border-top: 4px solid #0f2b5c !important;">
                    <div class="mb-3">
                        <div class="p-3 rounded-circle bg-danger-subtle text-danger mx-auto" style="width: 56px; height: 56px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-shield-lock-fill fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Owner (Pemilik)</h5>
                    <p class="text-muted small mb-4 flex-grow-1">
                        Pusat kendali laporan omset, laba warkop, grafik keuangan, dan kelola akun staf.
                    </p>
                    <a href="{{ route('quick.login', 'owner') }}" class="btn btn-dark fw-bold py-2 rounded-pill shadow-sm" style="background-color: #0f2b5c; border: none;">
                        <i class="bi bi-speedometer2 me-1"></i> Masuk Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Ringkas Fasilitas -->
        <div class="d-flex justify-content-center gap-4 mt-5 flex-wrap small text-light opacity-75">
            <div><i class="bi bi-geo-alt-fill text-info me-1"></i> Batuphat Barat, Lhokseumawe</div>
            <div><i class="bi bi-clock text-info me-1"></i> Buka 07.30 - 00.30 WIB</div>
            <div><i class="bi bi-wifi text-info me-1"></i> Free Wi-Fi</div>
            <div><i class="bi bi-qr-code text-info me-1"></i> Tunai &bull; QRIS</div>
        </div>
    </div>
</div>
@endsection