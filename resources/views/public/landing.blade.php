@extends('layouts.public')

@section('title', 'Warung Kopi & Tempat Nongkrong Santai')

@section('content')
<!-- Hero Section (Deep Navy Blue & Clean White) -->
<header class="hero-section text-center">
    <div class="container py-4 position-relative">
        <!-- Logo Emblem Badge -->
        <div class="mb-4">
            <img src="{{ asset('images/logo-badge.svg') }}" alt="Stencu Coffee Emblem" height="110" class="shadow-sm">
        </div>

        <span class="badge bg-primary bg-opacity-25 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm">
            <i class="bi bi-geo-alt-fill me-1"></i> Batuphat Barat, Muara Satu, Lhokseumawe
        </span>
        <h1 class="display-5 fw-bold mb-3 text-white">Stencu Coffee Lhokseumawe</h1>
        <p class="lead text-light opacity-75 mx-auto mb-4" style="max-width: 680px; font-size: 1.15rem; line-height: 1.6;">
            Sistem Inventaris &amp; Penjualan Terintegrasi Warkop Stencu Coffee. Satu portal terpadu untuk mengecek seluruh fungsi sistem sebagai <strong>Owner</strong>, <strong>Kasir</strong>, atau <strong>Pelanggan</strong>.
        </p>

        <!-- PORTAL TERPADU: PILIH MASUK SEBAGAI APA -->
        <div class="row g-3 justify-content-center text-start mt-3 mb-4">
            <!-- 1. Role Owner -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow rounded-4 p-4 text-dark bg-white position-relative overflow-hidden" style="border-top: 4px solid #1e40af !important;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 0.75rem;">
                            <i class="bi bi-shield-lock-fill me-1"></i> Owner / Pemilik
                        </span>
                        <div class="p-2 rounded-circle bg-danger-subtle text-danger">
                            <i class="bi bi-person-fill-gear fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark">Pemilik Toko</h5>
                    <p class="text-muted small mb-3">Akses manajemen menyeluruh untuk monitoring omset dan kendali bisnis warkop.</p>
                    <ul class="list-unstyled small text-secondary mb-4 space-y-1">
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-primary me-1.5"></i> Dashboard eksekutif &amp; analitik</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-primary me-1.5"></i> Laporan omset &amp; export PDF/Excel</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-primary me-1.5"></i> Kendali stok &amp; data supplier</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-primary me-1.5"></i> Kelola menu produk &amp; akun staf</li>
                    </ul>
                    <a href="{{ route('quick.login', 'owner') }}" class="btn btn-primary w-100 fw-bold py-2.5 rounded-pill shadow-sm mt-auto" style="background-color: #0f2b5c; border: none;">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk sebagai Owner
                    </a>
                </div>
            </div>

            <!-- 2. Role Kasir / Staff -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow rounded-4 p-4 text-dark bg-white position-relative overflow-hidden" style="border-top: 4px solid #2563eb !important;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 0.75rem;">
                            <i class="bi bi-calculator-fill me-1"></i> Staff / Kasir
                        </span>
                        <div class="p-2 rounded-circle bg-primary-subtle text-primary">
                            <i class="bi bi-shop-window fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark">Kasir / Barista</h5>
                    <p class="text-muted small mb-3">Operasional harian kasir warkop, pemrosesan order meja, dan cetak struk.</p>
                    <ul class="list-unstyled small text-secondary mb-4 space-y-1">
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-primary me-1.5"></i> Mesin POS penjualan cepat</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-primary me-1.5"></i> Input nomor meja &amp; takeaway</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-primary me-1.5"></i> Cetak nota struk pembayaran</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-primary me-1.5"></i> Restok barang masuk (Stock-in)</li>
                    </ul>
                    <a href="{{ route('quick.login', 'kasir') }}" class="btn btn-primary w-100 fw-bold py-2.5 rounded-pill shadow-sm mt-auto" style="background-color: #2563eb; border: none;">
                        <i class="bi bi-calculator me-1"></i> Masuk sebagai Kasir
                    </a>
                </div>
            </div>

            <!-- 3. Role Pelanggan / Customer -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow rounded-4 p-4 text-dark bg-white position-relative overflow-hidden" style="border-top: 4px solid #059669 !important;">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 0.75rem;">
                            <i class="bi bi-cup-hot-fill me-1"></i> Pelanggan / Tamu
                        </span>
                        <div class="p-2 rounded-circle bg-success-subtle text-success">
                            <i class="bi bi-bag-check fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark">Pelanggan Warkop</h5>
                    <p class="text-muted small mb-3">Pesan kopi sanger &amp; makanan langsung dari meja tanpa harus antre di kasir.</p>
                    <ul class="list-unstyled small text-secondary mb-4 space-y-1">
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-primary me-1.5"></i> Katalog menu kopi &amp; mie Aceh</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-primary me-1.5"></i> Keranjang belanja &amp; order mandiri</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-primary me-1.5"></i> Cek status pesanan nota realtime</li>
                        <li class="mb-1.5"><i class="bi bi-check2-circle text-primary me-1.5"></i> Pilihan bayar tunai / QRIS</li>
                    </ul>
                    <a href="{{ route('quick.login', 'pelanggan') }}" class="btn btn-success w-100 fw-bold py-2.5 rounded-pill shadow-sm mt-auto" style="background-color: #059669; border: none;">
                        <i class="bi bi-cup-straw me-1"></i> Masuk sebagai Pelanggan
                    </a>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 flex-wrap mt-3">
            <a href="{{ route('public.menu') }}" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold small">
                <i class="bi bi-grid-fill me-1"></i> Buka Katalog Menu Tamu
            </a>
            <a href="{{ route('public.order.track') }}" class="btn btn-outline-info rounded-pill px-4 py-2 fw-semibold small">
                <i class="bi bi-receipt me-1"></i> Lacak Status Nota
            </a>
        </div>

        <!-- Quick Info Highlights -->
        <div class="d-flex justify-content-center gap-4 mt-5 flex-wrap small text-light opacity-75">
            <div><i class="bi bi-clock text-info me-1"></i> Buka 07.30 - 00.30 WIB</div>
            <div><i class="bi bi-wifi text-info me-1"></i> Free Wi-Fi Kencang</div>
            <div><i class="bi bi-plug text-info me-1"></i> Colokan Tiap Meja</div>
            <div><i class="bi bi-qr-code text-info me-1"></i> Bayar Tunai / QRIS</div>
        </div>
    </div>
</header>

<!-- Keunggulan Warkop Stencu -->
<section class="py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="card p-4 border-0 shadow-sm h-100 rounded-4 bg-white">
                    <div class="p-3 bg-primary-subtle text-primary rounded-circle mx-auto mb-3" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-cup-hot-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark">Kopi Sanger Asli Aceh</h5>
                    <p class="text-muted small mb-0">Racikan kopi saring pekat ditarik dipadu susu kental manis dengan takaran sanger yang pas dan berbusa harum.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 border-0 shadow-sm h-100 rounded-4 bg-white">
                    <div class="p-3 bg-danger-subtle text-danger rounded-circle mx-auto mb-3" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-fire fs-2"></i>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark">Mie Aceh &amp; Indomie Telur</h5>
                    <p class="text-muted small mb-0">Mie kuning basah racikan bumbu rempah pedas gurih serta aneka Indomie goreng ceplok dan kuah kari telur rebus.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-4 border-0 shadow-sm h-100 rounded-4 bg-white">
                    <div class="p-3 bg-info-subtle text-primary rounded-circle mx-auto mb-3" style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-phone-flip fs-2"></i>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark">Pesan Cepat via Web</h5>
                    <p class="text-muted small mb-0">Duduk santai di meja warkop, buka menu dari HP, dan kirim pesanan langsung ke kasir tanpa perlu berdiri antre.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Menu Populer Warkop Preview -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <span class="text-primary text-uppercase fw-bold small">Sajian Favorit Pengunjung</span>
                <h3 class="fw-bold mb-0 text-dark">Menu Paling Laris di Stencu</h3>
            </div>
            <a href="{{ route('public.menu') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 py-2 fw-semibold">
                Buka Semua Menu <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3 g-md-4">
            @forelse($featuredProducts as $item)
                <div class="col-sm-6 col-lg-4">
                    <div class="card card-product h-100 p-3 d-flex flex-column">
                        <!-- Foto Sajian Menu Real JPG -->
                        <div class="position-relative overflow-hidden rounded-3 mb-3" style="height: 180px; background-color: #f1f5f9;">
                            @php
                                $imgSrc = $item->image ? (Str::startsWith($item->image, 'http') ? $item->image : asset($item->image)) : asset('images/products/sanger_dingin.jpg');
                            @endphp
                            <img src="{{ $imgSrc }}" 
                                 alt="{{ $item->name }}" 
                                 class="w-100 h-100 object-fit-cover" 
                                 onerror="this.onerror=null; this.src='{{ asset('images/products/sanger_dingin.jpg') }}';"
                                 loading="lazy">
                            <span class="badge badge-category position-absolute top-0 start-0 m-2 px-2 py-1 small rounded-pill">
                                {{ $item->category->name }}
                            </span>
                        </div>

                        <!-- Status Stok -->
                        <div class="mb-2">
                            @if($item->stock > $item->minimum_stock)
                                <span class="badge badge-available px-2 py-1"><i class="bi bi-check-circle me-1"></i> Tersedia ({{ $item->stock }})</span>
                            @elseif($item->stock > 0)
                                <span class="badge badge-low px-2 py-1"><i class="bi bi-exclamation-circle me-1"></i> Sisa {{ $item->stock }} {{ $item->unit }}</span>
                            @else
                                <span class="badge badge-out px-2 py-1"><i class="bi bi-x-circle me-1"></i> Habis</span>
                            @endif
                        </div>

                        <h5 class="fw-bold mb-1 text-dark">{{ $item->name }}</h5>
                        <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($item->description, 80) }}</p>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                            <div>
                                <span class="small text-muted d-block" style="font-size: 0.75rem;">Harga / {{ $item->unit }}</span>
                                <span class="fs-5 fw-bold" style="color: #0f2b5c;">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('public.menu', ['search' => $item->name]) }}" class="btn btn-primary btn-sm rounded-pill fw-bold text-white px-3 py-2 shadow-sm" style="background-color: #2563eb; border: none;">
                                <i class="bi bi-cart-plus me-1"></i> Pesan Menu
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4 text-muted">Belum ada menu yang ditampilkan.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Lokasi & Info Warkop Stencu -->
<section class="py-5">
    <div class="container">
        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 text-white" style="background: linear-gradient(135deg, #091a36 0%, #0f2b5c 100%);">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="badge bg-primary bg-opacity-50 text-info px-3 py-1 rounded-pill fw-bold small mb-2 border border-info border-opacity-25">
                        <i class="bi bi-geo-alt-fill me-1"></i> Lokasi Strategis
                    </span>
                    <h3 class="fw-bold mb-3 text-white">Mampir &amp; Nongkrong di Stencu Coffee</h3>
                    <p class="text-light opacity-75 mb-4">
                        Berada persis di tepi jalur lintas Jl. Medan - Banda Aceh, kawasan Batuphat Barat, Lhokseumawe. Tempat pas untuk rehat melepas lelah, diskusi tugas kuliah, atau nongkrong santai bareng kawan.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('public.menu') }}" class="btn btn-primary fw-bold text-white px-4 py-2 rounded-pill shadow-sm" style="background-color: #2563eb; border: none;">
                            <i class="bi bi-cup-hot me-1"></i> Lihat Semua Menu
                        </a>
                        <a href="https://maps.google.com/?q=Batuphat+Barat+Lhokseumawe" target="_blank" class="btn btn-outline-light px-4 py-2 rounded-pill">
                            <i class="bi bi-map me-1"></i> Petunjuk Arah
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <div class="p-4 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-15">
                        <i class="bi bi-shop fs-1 text-info mb-2 d-block"></i>
                        <h5 class="fw-bold text-white mb-1">Stencu Coffee Batuphat</h5>
                        <p class="small text-light opacity-75 mb-0">Buka Setiap Hari: 07.30 - 00.30 WIB<br>Free Wi-Fi &bull; Colokan Lengkap</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
