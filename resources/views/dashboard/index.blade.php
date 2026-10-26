@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Dashboard Operasional Stencu Coffee</h3>
        <p class="text-muted mb-0">Ringkasan transaksi dan pergerakan stok UMKM Batuphat Barat.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('sales.create') }}" class="btn btn-success fw-bold">
            <i class="bi bi-cart-plus me-1"></i> Buka Kasir POS
        </a>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary fw-bold">
            <i class="bi bi-box-arrow-in-down me-1"></i> Input Beli Stok
        </a>
    </div>
</div>

<!-- Alert Produk Stok Menipis (Slide 18) -->
@if($lowStockProducts->count() > 0)
    <div class="alert alert-warning border-warning d-flex align-items-center gap-3 p-3 rounded-3 mb-4 shadow-sm" role="alert">
        <i class="bi bi-exclamation-octagon-fill fs-3 text-warning"></i>
        <div class="flex-grow-1">
            <h6 class="fw-bold mb-1 text-dark">Peringatan: Terdapat {{ $lowStockProducts->count() }} Produk yang Mencapai Batas Stok Minimum!</h6>
            <div class="small text-muted">
                Produk berikut perlu segera dipesan ke supplier:
                @foreach($lowStockProducts as $low)
                    <span class="badge bg-danger me-1">{{ $low->name }} (Sisa: {{ $low->stock }} {{ $low->unit }})</span>
                @endforeach
            </div>
        </div>
        <a href="{{ route('products.index', ['filter' => 'low_stock']) }}" class="btn btn-sm btn-outline-dark fw-semibold">
            Lihat Detail Stok
        </a>
    </div>
@endif

<!-- Metric Cards (Agregat dari Database - Slide 18) -->
<div class="row g-3 mb-4">
    <!-- Card 1: Penjualan Hari Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="text-muted small fw-semibold">PENJUALAN HARI INI</span>
                    <h4 class="fw-bold text-success mt-2 mb-1">Rp {{ number_format($salesTodayTotal, 0, ',', '.') }}</h4>
                    <span class="badge bg-success-subtle text-success small">{{ $salesTodayCount }} Transaksi</span>
                </div>
                <div class="p-2 bg-success-subtle rounded-3 text-success fs-4">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Penjualan Bulan Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="text-muted small fw-semibold">PENJUALAN BULAN INI</span>
                    <h4 class="fw-bold text-primary mt-2 mb-1">Rp {{ number_format($salesMonthTotal, 0, ',', '.') }}</h4>
                    <span class="badge bg-primary-subtle text-primary small">Bulan {{ date('F Y') }}</span>
                </div>
                <div class="p-2 bg-primary-subtle rounded-3 text-primary fs-4">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Transaksi Penjualan -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="text-muted small fw-semibold">TOTAL TRANSAKSI</span>
                    <h4 class="fw-bold text-dark mt-2 mb-1">{{ number_format($totalSalesCount) }}</h4>
                    <span class="badge bg-secondary-subtle text-secondary small">Status Selesai</span>
                </div>
                <div class="p-2 bg-secondary-subtle rounded-3 text-secondary fs-4">
                    <i class="bi bi-receipt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Total Master Produk -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="text-muted small fw-semibold">MASTER PRODUK</span>
                    <h4 class="fw-bold text-warning-emphasis mt-2 mb-1">{{ $totalProducts }} Item</h4>
                    <span class="badge bg-warning-subtle text-warning-emphasis small">{{ $totalSuppliers }} Mitra Supplier</span>
                </div>
                <div class="p-2 bg-warning-subtle rounded-3 text-warning-emphasis fs-4">
                    <i class="bi bi-cup-hot"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions Tables -->
<div class="row g-4">
    <!-- Tabel Penjualan Terakhir -->
    <div class="col-lg-7">
        <div class="card card-custom p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-1 text-primary"></i> 5 Penjualan Kasir Terakhir</h6>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>No. Nota</th>
                            <th>Pelanggan</th>
                            <th>Kasir</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSales as $sale)
                            <tr>
                                <td>
                                    <a href="{{ route('sales.show', $sale) }}" class="fw-bold text-decoration-none text-dark">
                                        {{ $sale->sale_number }}
                                    </a>
                                </td>
                                <td>{{ $sale->customer_name }}</td>
                                <td class="small text-muted">{{ $sale->user->name ?? 'Kasir' }}</td>
                                <td class="fw-semibold">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($sale->status === 'completed')
                                        <span class="badge bg-success-subtle text-success">Sukses</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">Batal</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted">Belum ada transaksi penjualan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Pembelian Terakhir -->
    <div class="col-lg-5">
        <div class="card card-custom p-3 bg-white h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-box-seam me-1 text-success"></i> 5 Pembelian Stok Terakhir</h6>
                <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>No. PO</th>
                            <th>Pemasok</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPurchases as $purchase)
                            <tr>
                                <td>
                                    <a href="{{ route('purchases.show', $purchase) }}" class="fw-bold text-decoration-none text-dark">
                                        {{ $purchase->purchase_number }}
                                    </a>
                                </td>
                                <td class="small">{{ $purchase->supplier->name ?? '-' }}</td>
                                <td class="fw-semibold text-end">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">Belum ada data pembelian stok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
