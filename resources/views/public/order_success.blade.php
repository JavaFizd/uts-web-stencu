@extends('layouts.public')

@section('title', 'Pesanan Berhasil - ' . $sale->sale_number)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Success Notification Card -->
            <div class="text-center mb-4">
                <div class="d-inline-flex p-3 bg-success-subtle text-success rounded-circle mb-3 shadow-sm">
                    <i class="bi bi-check2-circle fs-1"></i>
                </div>
                <h3 class="fw-bold mb-1">Pesanan Anda Berhasil Dikirim!</h3>
                <p class="text-muted">Barista Stencu Coffee telah menerima pesanan Anda dan segera meraciknya.</p>
            </div>

            <!-- Digital Receipt Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="text-center mb-3 pb-3 border-bottom">
                    <img src="{{ asset('images/logo.svg') }}" alt="Stencu Coffee" height="42">
                </div>
                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                    <div>
                        <span class="small text-muted d-block">Nomor Nota Pesanan:</span>
                        <h5 class="fw-bold text-primary font-monospace mb-0">{{ $sale->sale_number }}</h5>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold">
                            <i class="bi bi-hourglass-split me-1"></i> Sedang Diracik Barista
                        </span>
                    </div>
                </div>

                <div class="row g-2 small mb-4">
                    <div class="col-6">
                        <span class="text-muted d-block">Pemesan / Lokasi:</span>
                        <span class="fw-bold text-dark">{{ $sale->customer_name }}</span>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-muted d-block">Waktu Pesan:</span>
                        <span class="fw-semibold text-dark">{{ $sale->sold_at->format('d/m/Y H:i') }} WIB</span>
                    </div>
                    <div class="col-6 mt-2">
                        <span class="text-muted d-block">Metode Pembayaran:</span>
                        <span class="badge bg-light text-dark border text-uppercase">{{ $sale->payment_method }}</span>
                    </div>
                    <div class="col-6 mt-2 text-end">
                        <span class="text-muted d-block">Status Pembayaran:</span>
                        <span class="text-success fw-bold">Menunggu Konfirmasi Kasir</span>
                    </div>
                </div>

                <!-- Daftar Menu yang Dipesan -->
                <div class="table-responsive mb-3">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th>Menu Pesanan</th>
                                <th class="text-center" style="width: 80px;">Qty</th>
                                <th class="text-end" style="width: 120px;">Harga Satuan</th>
                                <th class="text-end" style="width: 130px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark small">{{ $item->product->name ?? 'Menu' }}</div>
                                        <span class="text-muted" style="font-size: 0.75rem;">{{ $item->product->category->name ?? '' }}</span>
                                    </td>
                                    <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                    <td class="text-end text-muted small">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold fs-6">TOTAL TAGIHAN:</td>
                                <td class="text-end fw-bold fs-5 text-success">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($sale->notes)
                    <div class="p-3 bg-light rounded-3 small text-muted mb-4 border">
                        <strong>Catatan Khusus:</strong> {{ $sale->notes }}
                    </div>
                @endif

                <!-- Petunjuk Pembayaran -->
                <div class="alert alert-warning border-warning small mb-0 rounded-3">
                    <div class="fw-bold mb-1"><i class="bi bi-info-circle-fill me-1"></i> Informasi Pembayaran:</div>
                    @if($sale->payment_method === 'cash')
                        Silakan lakukan pembayaran tunai sebesar <strong>Rp {{ number_format($sale->total, 0, ',', '.') }}</strong> langsung ke meja kasir barista dengan menyebutkan nomor nota <strong>{{ $sale->sale_number }}</strong>.
                    @elseif($sale->payment_method === 'qris')
                        Silakan scan kode QRIS Stencu Coffee di meja kasir sebesar <strong>Rp {{ number_format($sale->total, 0, ',', '.') }}</strong> saat mengambil pesanan atau saat barista mengantar ke meja.
                    @else
                        Silakan transfer ke Rekening BSI Stencu Coffee: <strong>7123-4567-89</strong> a.n. Teuku Stencu Coffee, lalu tunjukkan bukti bayar ke barista.
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('public.menu') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-1"></i> Pesan Menu Lainnya
                </a>
                <button type="button" class="btn btn-dark rounded-pill px-4" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Cetak Bukti Nota
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
