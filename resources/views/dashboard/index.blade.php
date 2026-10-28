@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Header & Quick Actions -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0f2b5c;">Dashboard Stencu Coffee</h3>
        <p class="text-muted mb-0 small">Ringkasan penjualan, pergerakan stok, dan estimasi laba warkop.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap no-print">
        <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold rounded-pill px-3 shadow-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan
        </button>
        <a href="{{ route('sales.create') }}" class="btn btn-success btn-sm fw-semibold rounded-pill px-3 shadow-sm">
            <i class="bi bi-cart-plus me-1"></i> Kasir POS
        </a>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary btn-sm fw-semibold rounded-pill px-3 shadow-sm" style="background-color: #2563eb; border: none;">
            <i class="bi bi-box-arrow-in-down me-1"></i> Beli Stok
        </a>
        @if(Auth::user()->isOwner())
            <a href="{{ route('users.index') }}" class="btn btn-outline-dark btn-sm fw-semibold rounded-pill px-3 shadow-sm">
                <i class="bi bi-people me-1"></i> Kelola Staf
            </a>
        @endif
    </div>
</div>

<!-- Alert Peringatan Stok Menipis (Critical Stock Indicator) -->
@if($lowStockProducts->count() > 0)
    <div class="alert alert-warning border-warning d-flex align-items-center gap-3 p-3 rounded-4 mb-4 shadow-sm" role="alert">
        <div class="p-2 bg-warning-subtle text-warning rounded-circle">
            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
        </div>
        <div class="flex-grow-1">
            <div class="fw-bold text-dark mb-1">Peringatan: {{ $lowStockProducts->count() }} Bahan / Menu Mendekati Batas Minimum</div>
            <div class="d-flex flex-wrap gap-1">
                @foreach($lowStockProducts as $low)
                    <span class="badge bg-danger text-white rounded-pill px-2.5 py-1" style="font-size: 0.75rem;">
                        {{ $low->name }} (Sisa: {{ $low->stock }} {{ $low->unit }})
                    </span>
                @endforeach
            </div>
        </div>
        <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-danger fw-semibold rounded-pill px-3">
            <i class="bi bi-box-arrow-in-down me-1"></i> Restok Sekarang
        </a>
    </div>
@endif

<!-- Ringkasan 4 Kartu Utama (Simpel & Mudah Dijelaskan ke Dosen) -->
<div class="row g-3 mb-4">
    <!-- 1. Penjualan Hari Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 bg-white h-100 border-0 shadow-sm" style="border-left: 4px solid #10b981 !important;">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-bold">PENJUALAN HARI INI</span>
                <span class="badge bg-success-subtle text-success small">{{ $salesTodayCount }} Nota</span>
            </div>
            <h4 class="fw-bold text-success mt-1 mb-1">Rp {{ number_format($salesTodayTotal, 0, ',', '.') }}</h4>
            <div class="small text-muted" style="font-size: 0.75rem;">
                Tanggal: {{ date('d M Y') }}
            </div>
        </div>
    </div>

    <!-- 2. Penjualan Bulan Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 bg-white h-100 border-0 shadow-sm" style="border-left: 4px solid #2563eb !important;">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-bold">PENJUALAN BULAN INI</span>
                <span class="badge bg-primary-subtle text-primary small">{{ date('M Y') }}</span>
            </div>
            <h4 class="fw-bold text-primary mt-1 mb-1">Rp {{ number_format($salesMonthTotal, 0, ',', '.') }}</h4>
            <div class="small text-muted" style="font-size: 0.75rem;">
                Total 1 Tahun: <strong>Rp {{ number_format($salesYearTotal, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <!-- 3. Belanja Bahan & Laba -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 bg-white h-100 border-0 shadow-sm" style="border-left: 4px solid #8b5cf6 !important;">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-bold">ESTIMASI LABA KOTOR</span>
                <span class="badge bg-purple-subtle text-purple small" style="background-color: #ede9fe; color: #6d28d9;">Bulan Ini</span>
            </div>
            <h4 class="fw-bold mt-1 mb-1" style="color: #6d28d9;">Rp {{ number_format($profitMonth, 0, ',', '.') }}</h4>
            <div class="small text-muted" style="font-size: 0.75rem;">
                Belanja Bahan: <strong class="text-danger">Rp {{ number_format($purchasesMonthTotal, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <!-- 4. Inventaris & Stok -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 bg-white h-100 border-0 shadow-sm" style="border-left: 4px solid #0f2b5c !important;">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-muted small fw-bold">INVENTARIS STOK</span>
                @if($lowStockProducts->count() > 0)
                    <span class="badge bg-danger-subtle text-danger small">{{ $lowStockProducts->count() }} Menipis</span>
                @else
                    <span class="badge bg-success-subtle text-success small">Stok Aman</span>
                @endif
            </div>
            <h4 class="fw-bold mt-1 mb-1" style="color: #0f2b5c;">{{ $totalProducts }} <span class="fs-6 fw-normal text-muted">Menu Aktif</span></h4>
            <div class="small text-muted" style="font-size: 0.75rem;">
                {{ $totalSuppliers }} Supplier &bull; {{ $totalStaff }} Staf Kasir
            </div>
        </div>
    </div>
</div>

<!-- Baris 3: Grafik Keuangan Visual & Menu Terlaris (Slide 18) -->
<div class="row g-4 mb-4">
    <!-- Kolom Kiri: Grafik Tren Penjualan & Pembelian (Chart.js) -->
    <div class="col-lg-7">
        <div class="card card-custom p-4 bg-white h-100 shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-bar-chart-line-fill text-primary me-1"></i> Grafik Arus Kas: Omset vs Belanja (7 Hari Terakhir)
                    </h6>
                    <span class="text-muted" style="font-size: 0.75rem;">Visualisasi harian pendapatan penjualan dan pengeluaran pasokan warkop</span>
                </div>
            </div>
            <div style="height: 280px; position: relative;">
                <canvas id="cashflowChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: 5 Menu Paling Laris (Best Sellers) -->
    <div class="col-lg-5">
        <div class="card card-custom p-4 bg-white h-100 shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-fire text-danger me-1"></i> 5 Menu Paling Laris (Best Seller)
                </h6>
                <span class="badge bg-danger-subtle text-danger small">Favorit</span>
            </div>

            <div class="d-flex flex-column gap-2">
                @forelse($topProducts as $rank => $item)
                    <div class="p-2.5 rounded-3 bg-light d-flex align-items-center justify-content-between border">
                        <div class="d-flex align-items-center gap-2.5">
                            <span class="badge rounded-circle {{ $rank === 0 ? 'bg-warning text-dark' : ($rank === 1 ? 'bg-secondary text-white' : 'bg-dark text-white') }}" style="width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                                #{{ $rank + 1 }}
                            </span>
                            <div>
                                <div class="fw-bold text-dark small">{{ $item->product->name ?? 'Menu' }}</div>
                                <span class="text-muted" style="font-size: 0.72rem;">{{ $item->product->category->name ?? 'Kategori' }}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary text-white fw-semibold" style="background-color: #2563eb !important;">
                                {{ $item->total_qty }} porsi terjual
                            </span>
                            <div class="text-muted mt-0.5" style="font-size: 0.72rem;">
                                Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted small">
                        Belum ada data penjualan tercatat.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Baris 4: Tabel 5 Transaksi Terakhir -->
<div class="row g-4">
    <!-- Tabel Penjualan Terakhir -->
    <div class="col-lg-7">
        <div class="card card-custom p-3 bg-white h-100 shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history me-1 text-primary"></i> 5 Penjualan Kasir Terakhir</h6>
                <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua Nota &rarr;</a>
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
                                    <a href="{{ route('sales.show', $sale) }}" class="fw-bold text-decoration-none text-primary">
                                        {{ $sale->sale_number }}
                                    </a>
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">{{ $sale->customer_name }}</div>
                                    @if($sale->notes)
                                        <div class="text-muted fst-italic" style="font-size: 0.72rem;">"{{ $sale->notes }}"</div>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $sale->user->name ?? 'Kasir' }}</td>
                                <td class="fw-semibold small">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($sale->status === 'completed')
                                        <span class="badge bg-success-subtle text-success small">Sukses</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger small">Batal</span>
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
        <div class="card card-custom p-3 bg-white h-100 shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam me-1 text-success"></i> 5 Pembelian Stok Terakhir</h6>
                <a href="{{ route('purchases.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">Lihat Semua PO &rarr;</a>
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
                                <td class="fw-semibold text-end small">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
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

@section('scripts')
<!-- Chart.js CDN untuk Visualisasi Arus Kas -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('cashflowChart').getContext('2d');
        
        const labels = {!! json_encode($chartLabels) !!};
        const salesData = {!! json_encode($chartSalesData) !!};
        const purchasesData = {!! json_encode($chartPurchasesData) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pemasukan Penjualan (Omset)',
                        data: salesData,
                        backgroundColor: 'rgba(37, 99, 235, 0.85)', // Royal Blue
                        borderColor: '#1d4ed8',
                        borderWidth: 1.5,
                        borderRadius: 6,
                    },
                    {
                        label: 'Pengeluaran Belanja Bahan (Restok)',
                        data: purchasesData,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)', // Red
                        borderColor: '#dc2626',
                        borderWidth: 1.5,
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 600 },
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000) + 'k';
                            },
                            font: { size: 11 }
                        },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: 600 } }
                    }
                }
            }
        });
    });
</script>
@endsection
