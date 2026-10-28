@extends('layouts.public')

@section('title', 'Pesanan Berhasil - ' . $sale->sale_number)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Success Notification Card (Hidden on Print) -->
            <div class="text-center mb-4 no-print">
                <div class="d-inline-flex p-3 bg-success-subtle text-success rounded-circle mb-3 shadow-sm">
                    <i class="bi bi-check2-circle fs-1"></i>
                </div>
                <h3 class="fw-bold mb-1 text-dark">Pesanan Anda Berhasil Dikirim!</h3>
                <p class="text-muted small">Pesanan telah masuk ke sistem Stencu Coffee dan langsung diracik oleh barista.</p>
            </div>

            <!-- TAMPILAN KHUSUS PEMBAYARAN QRIS (Langsung Scan Barcode di Web) -->
            @if($sale->payment_method === 'qris')
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-center bg-white no-print" style="border: 2px dashed #b91c1c !important;">
                    <div class="d-flex justify-content-center mb-2">
                        <span class="badge bg-danger text-white rounded-pill px-3 py-1 fw-bold">
                            <i class="bi bi-qr-code me-1"></i> Pembayaran QRIS Langsung
                        </span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Scan Barcode QRIS untuk Bayar Sekarang</h5>
                    <p class="text-muted small mb-3">
                        Total yang harus ditransfer: <strong class="text-danger fs-5">Rp {{ number_format($sale->total, 0, ',', '.') }}</strong>
                    </p>

                    <!-- Gambar QRIS Resmi Stencu Coffee -->
                    <div class="d-flex justify-content-center mb-3">
                        <div class="p-2 bg-white rounded-4 shadow-sm border text-center" style="max-width: 260px;">
                            <img src="{{ asset('images/qris_stencu.svg') }}" alt="QRIS Stencu Coffee" class="img-fluid rounded" style="max-height: 310px;">
                        </div>
                    </div>

                    <!-- Petunjuk Pembayaran Singkat -->
                    <div class="alert alert-light border small text-muted text-start mb-0 rounded-3">
                        <div class="fw-bold text-dark mb-1"><i class="bi bi-phone-fill text-danger me-1"></i> Cara Bayar:</div>
                        <ol class="ps-3 mb-0" style="line-height: 1.6;">
                            <li>Buka aplikasi m-Banking (BSI, BCA, Mandiri) atau e-Wallet (GoPay, OVO, Dana).</li>
                            <li>Pilih menu <strong>Scan QR / Bayar</strong> &rarr; arahkan kamera ke barcode di atas.</li>
                            <li>Masukkan nominal <strong>Rp {{ number_format($sale->total, 0, ',', '.') }}</strong> lalu konfirmasi bayar.</li>
                            <li>Saat ke kasir atau pesanan tiba, tinggal tunjukkan bukti transaksi sukses kepada barista.</li>
                        </ol>
                    </div>
                </div>
            @endif

            <!-- STRUK NOTA FISIK / THERMAL SLIP (Ini yang dicetak saat Print) -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4" id="receiptPrintSlip">
                <!-- Struk Header -->
                <div class="text-center pb-3 border-bottom mb-3">
                    <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: 1px;">STENCU COFFEE</h4>
                    <div class="small text-muted" style="font-size: 0.76rem;">Batuphat Barat, Muara Satu, Kota Lhokseumawe</div>
                    <div class="small text-muted" style="font-size: 0.74rem;">Telp/WA: 0812-6900-1122</div>
                    <div class="text-muted font-monospace small mt-1">----------------------------------------</div>
                </div>

                <!-- Meta Transaksi -->
                <div class="d-flex justify-content-between align-items-center small mb-1">
                    <span class="text-muted">No. Nota :</span>
                    <div>
                        <strong class="font-monospace text-primary fs-6">{{ $sale->sale_number }}</strong>
                        <span class="badge bg-light text-muted border ms-1">#{{ $sale->id }}</span>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center small mb-1">
                    <span class="text-muted">Waktu :</span>
                    <span class="text-dark">{{ $sale->sold_at->format('d/m/Y H:i') }} WIB</span>
                </div>
                <div class="d-flex justify-content-between align-items-center small mb-1">
                    <span class="text-muted">Nama Pemesan :</span>
                    <strong class="text-dark fs-6">{{ $sale->customer_name }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center small mb-2">
                    <span class="text-muted">Metode Bayar :</span>
                    <span class="badge bg-light text-dark border text-uppercase">{{ $sale->payment_method }}</span>
                </div>

                <div class="text-muted font-monospace small text-center mb-2">----------------------------------------</div>

                <!-- Rincian Menu Pesanan -->
                <div class="mb-2">
                    @foreach($sale->items as $item)
                        <div class="d-flex justify-content-between align-items-start small mb-1.5">
                            <div style="max-width: 65%;">
                                <div class="fw-bold text-dark">{{ $item->product->name ?? 'Menu' }}</div>
                                <span class="text-muted" style="font-size: 0.74rem;">{{ $item->quantity }} &times; Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-end fw-bold text-dark">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-muted font-monospace small text-center my-2">----------------------------------------</div>

                <!-- Total -->
                <div class="d-flex justify-content-between align-items-center fw-bold fs-6 mb-1">
                    <span>TOTAL BAYAR :</span>
                    <span class="text-primary fs-5" style="color: #1d4ed8 !important;">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
                </div>

                @if($sale->notes)
                    <div class="p-2 bg-light rounded-2 small text-muted mt-2 border">
                        <strong class="text-dark">Catatan:</strong> {{ $sale->notes }}
                    </div>
                @endif

                <!-- Struk Footer -->
                <div class="text-center pt-3 mt-3 border-top text-muted small" style="font-size: 0.74rem;">
                    <div>Terima Kasih Atas Kunjungan Anda!</div>
                    <div class="fst-italic text-secondary">Racikan Kopi Saring &amp; Rempah Asli Aceh</div>
                </div>
            </div>

            <!-- Tombol Aksi Bawah (Bersih, Realistis, & Bebas Tulisan Fake) -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 no-print">
                <div class="d-flex gap-2">
                    <a href="{{ route('public.menu') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Pesan Menu Lain
                    </a>
                    @if(Auth::check() && (Auth::user()->isOwner() || Auth::user()->isStaff()))
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary rounded-pill px-3 py-2 fw-semibold">
                            <i class="bi bi-speedometer2 me-1"></i> Kembali ke Dashboard
                        </a>
                    @endif
                </div>

                <button type="button" class="btn btn-dark rounded-pill px-4 py-2 fw-bold shadow-sm" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Cetak Struk Bon
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Styling Cetakan Struk Bon Kompak & Ringkas (Thermal Slip Style) */
    @media print {
        body {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .no-print, header, nav, footer, .navbar, .alert {
            display: none !important;
        }
        .container {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        #receiptPrintSlip {
            width: 300px !important;
            margin: 0 auto !important;
            padding: 12px !important;
            border: 1px dashed #000000 !important;
            box-shadow: none !important;
            font-size: 11px !important;
            color: #000000 !important;
            page-break-inside: avoid !important;
        }
        #receiptPrintSlip h4 {
            font-size: 15px !important;
            color: #000000 !important;
        }
        #receiptPrintSlip .text-primary {
            color: #000000 !important;
        }
        #receiptPrintSlip .badge {
            border: 1px solid #000 !important;
            color: #000 !important;
            background: transparent !important;
        }
    }
</style>
@endsection
