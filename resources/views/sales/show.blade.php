@extends('layouts.app')

@section('title', 'Detail Struk Penjualan - #' . $sale->sale_number)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <a href="{{ route('sales.index') }}" class="text-decoration-none small text-muted">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Penjualan
        </a>
        <h3 class="fw-bold mt-2 mb-0">Struk Transaksi Penjualan</h3>
        <span class="text-muted small">ID Transaksi: #{{ $sale->id }} • Dibuat pada {{ $sale->sold_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Struk
        </button>

        <!-- Tombol Batalkan Transaksi (Hanya Role Owner - Slide 5 & 8) -->
        @if(Auth::user()->isOwner() && $sale->status === 'completed')
            <form action="{{ route('sales.cancel', $sale) }}" method="POST" onsubmit="return confirm('PERINGATAN: Membatalkan transaksi ini akan mengembalikan seluruh stok produk ke database secara otomatis. Lanjutkan?');">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Batalkan Transaksi & Pulihkan Stok
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4 bg-white shadow-sm border">
            <!-- Header Struk -->
            <div class="text-center pb-3 border-bottom mb-4">
                <i class="bi bi-cup-hot-fill fs-2 text-warning"></i>
                <h4 class="fw-bold mb-1">STENCU COFFEE</h4>
                <div class="small text-muted">Jl. Medan - Banda Aceh, Batuphat Barat, Lhokseumawe</div>
                <div class="small text-muted">Telp/WA: 0812-6900-1122</div>
            </div>

            <!-- Meta Data Struk -->
            <div class="row g-2 small mb-4">
                <div class="col-6">
                    <div class="text-muted">Nomor Nota:</div>
                    <div class="fw-bold font-monospace fs-6 text-primary">{{ $sale->sale_number }}</div>
                </div>
                <div class="col-6 text-end">
                    <div class="text-muted">Status Transaksi:</div>
                    <div>
                        @if($sale->status === 'completed')
                            <span class="badge bg-success-subtle text-success fs-6 fw-bold">SELESAI</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger fs-6 fw-bold">DIBATALKAN</span>
                        @endif
                    </div>
                </div>
                <div class="col-6 mt-2">
                    <div class="text-muted">Nama Pelanggan:</div>
                    <div class="fw-semibold">{{ $sale->customer_name }}</div>
                </div>
                <div class="col-6 mt-2 text-end">
                    <div class="text-muted">Metode Pembayaran:</div>
                    <div class="fw-semibold text-uppercase">{{ $sale->payment_method }}</div>
                </div>
                <div class="col-6 mt-2">
                    <div class="text-muted">Dilayani Oleh:</div>
                    <div>{{ $sale->user->name ?? 'Kasir Barista' }}</div>
                </div>
            </div>

            <!-- Tabel Detail Item Belanja -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>No</th>
                            <th>Menu / Produk</th>
                            <th class="text-center">Kuantitas</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $index => $item)
                            <tr>
                                <td class="text-center text-muted small" style="width: 40px;">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->product->name ?? 'Produk Dihapus' }}</div>
                                    <span class="small text-muted">SKU: {{ $item->product->sku ?? '-' }}</span>
                                </td>
                                <td class="text-center fw-bold">{{ $item->quantity }} {{ $item->product->unit ?? '' }}</td>
                                <td class="text-end text-muted">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-6">TOTAL PEMBAYARAN:</td>
                            <td class="text-end fw-bold fs-5 text-success">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($sale->notes)
                <div class="alert alert-light border small text-muted mb-4">
                    <strong>Catatan:</strong> {{ $sale->notes }}
                </div>
            @endif

            <div class="text-center pt-3 border-top text-muted small">
                Terima kasih atas kunjungan Anda ke Stencu Coffee Batuphat!<br>
                <em>~ Secangkir Kopi Gayo untuk Inspirasi Hari Anda ~</em>
            </div>
        </div>
    </div>
</div>
@endsection
