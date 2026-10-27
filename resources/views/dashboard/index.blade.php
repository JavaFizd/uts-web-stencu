@extends('layouts.app')

@section('title', 'Dashboard Eksekutif')

@section('content')
<!-- Header & Quick Actions -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0f2b5c;">Dashboard Eksekutif Stencu Coffee</h3>
        <p class="text-muted mb-0">Pusat kendali omset, arus kas pengeluaran, pergerakan stok, dan performa warkop.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('sales.create') }}" class="btn btn-success fw-bold rounded-pill px-3 shadow-sm">
            <i class="bi bi-cart-plus me-1"></i> Buka Kasir POS
        </a>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary fw-bold rounded-pill px-3 shadow-sm" style="background-color: #2563eb; border: none;">
            <i class="bi bi-box-arrow-in-down me-1"></i> Input Beli Stok
        </a>
        @if(Auth::user()->isOwner())
            <a href="{{ route('users.index') }}" class="btn btn-outline-dark fw-bold rounded-pill px-3 shadow-sm">
                <i class="bi bi-people-fill me-1"></i> Kelola Staf
            </a>
        @endif
    </div>
</div>

<!-- Alert Peringatan Stok Menipis (Critical Stock Indicator) -->
@if($lowStockProducts->count() > 0)
    <div class="alert alert-warning border-warning d-flex align-items-center gap-3 p-3 rounded-4 mb-4 shadow-sm" role="alert">
        <div class="p-2 bg-warning-subtle text-warning rounded-circle">
            <i class="bi bi-exclamation-octagon-fill fs-3"></i>
        </div>
        <div class="flex-grow-1">
            <h6 class="fw-bold mb-1 text-dark">Peringatan: Terdapat {{ $lowStockProducts->count() }} Bahan/Menu yang Mencapai Batas Minimum!</h6>
            <div class="small text-muted mb-1">
                Bahan berikut segera habis dan perlu di-restok dari supplier:
            </div>
            <div class="d-flex flex-wrap gap-1">
                @foreach($lowStockProducts as $low)
                    <span class="badge bg-danger text-white rounded-pill px-2.5 py-1">
                        {{ $low->name }} (Sisa: {{ $low->stock }} {{ $low->unit }})
                    </span>
                @endforeach
            </div>
        </div>
        <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-danger fw-bold rounded-pill px-3">
            <i class="bi bi-cart-plus me-1"></i> Belanja Stok Sekarang
        </a>
    </div>
@endif

<!-- Baris 1: Ringkasan Periode Pemasukan Penjualan -->
<div class="row g-3 mb-4">
    <!-- 1. Hari Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 bg-white h-100 border-0 shadow-sm" style="border-left: 4px solid #10b981 !important;">
            <span class="text-muted small fw-semibold">PEMASUKAN HARI INI</span>
            <h4 class="fw-bold text-success mt-2 mb-1">Rp {{ number_format($salesTodayTotal, 0, ',', '.') }}</h4>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <span class="badge bg-success-subtle text-success small">{{ $salesTodayCount }} Transaksi</span>
                <span class="small text-muted">{{ date('d M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- 2. Minggu Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 bg-white h-100 border-0 shadow-sm" style="border-left: 4px solid #3b82f6 !important;">
            <span class="text-muted small fw-semibold">PEMASUKAN MINGGU INI</span>
            <h4 class="fw-bold text-primary mt-2 mb-1">Rp {{ number_format($salesWeekTotal, 0, ',', '.') }}</h4>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <span class="badge bg-primary-subtle text-primary small">7 Hari Berjalan</span>
                <span class="small text-muted">Sen - Min</span>
            </div>
        </div>
    </div>

    <!-- 3. Bulan Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 bg-white h-100 border-0 shadow-sm" style="border-left: 4px solid #8b5cf6 !important;">
            <span class="text-muted small fw-semibold">PEMASUKAN BULAN INI</span>
            <h4 class="fw-bold mt-2 mb-1" style="color: #6d28d9;">Rp {{ number_format($salesMonthTotal, 0, ',', '.') }}</h4>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <span class="badge bg-purple-subtle text-purple small" style="background-color: #ede9fe; color: #6d28d9;">Bulan {{ date('F Y') }}</span>
                <span class="small text-muted">Akumulasi</span>
            </div>
        </div>
    </div>

    <!-- 4. Tahun Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-custom p-3 bg-white h-100 border-0 shadow-sm" style="border-left: 4px solid #0f2b5c !important;">
            <span class="text-muted small fw-semibold">PEMASUKAN TAHUN INI</span>
            <h4 class="fw-bold mt-2 mb-1" style="color: #0f2b5c;">Rp {{ number_format($salesYearTotal, 0, ',', '.') }}</h4>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <span class="badge bg-dark-subtle text-dark small">Tahun {{ date('Y') }}</span>
                <span class="small text-muted">Tahun Buku</span>
            </div>
        </div>
    </div>
</div>

<!-- Baris 2: Neraca Laba Kotor & Efisiensi Arus Kas -->
<div class="row g-3 mb-4">
    <!-- Card Laba Kotor -->
    <div class="col-md-4">
        <div class="card card-custom p-4 text-white h-100 shadow-sm" style="background: linear-gradient(135deg, #091a36 0%, #0f2b5c 60%, #1e40af 100%);">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-1 fw-bold">
                    <i class="bi bi-wallet2 me-1"></i> Neraca Laba Kotor
                </span>
                <i class="bi bi-graph-up-arrow fs-4 text-info"></i>
            </div>
            <span class="small text-light opacity-75">ESTIMASI LABA KOTOR BULAN INI:</span>
            <h3 class="fw-bold text-white mt-1 mb-2">Rp {{ number_format($profitMonth, 0, ',', '.') }}</h3>
            <p class="small text-light opacity-75 mb-0">
                Pemasukan (Rp {{ number_format($salesMonthTotal, 0, ',', '.') }}) dikurangi Pengeluaran Belanja Bahan (Rp {{ number_format($purchasesMonthTotal, 0, ',', '.') }}).
            </p>
        </div>
    </div>

    <!-- Card Total Pengeluaran Belanja Bahan -->
    <div class="col-md-4">
        <div class="card card-custom p-4 bg-white h-100 shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small fw-bold">TOTAL PENGELUARAN BELANJA BAHAN</span>
                <div class="p-2 bg-danger-subtle text-danger rounded-circle">
                    <i class="bi bi-cart-dash-fill fs-5"></i>
                </div>
            </div>
            <h3 class="fw-bold text-danger mb-1">Rp {{ number_format($purchasesMonthTotal, 0, ',', '.') }}</h3>
            <div class="small text-muted">Belanja stok ke Toko Grosir &amp; Penggilingan Kopi bulan ini.</div>
            <div class="mt-3 pt-2 border-top d-flex justify-content-between small">
                <span class="text-muted">Total Keseluruhan:</span>
                <strong class="text-dark">Rp {{ number_format($purchasesAllTimeTotal, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <!-- Card Tim & Operasional -->
    <div class="col-md-4">
        <div class="card card-custom p-4 bg-white h-100 shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small fw-bold">KARYAWAN &amp; MITRA USAHA</span>
                <div class="p-2 bg-info-subtle text-primary rounded-circle">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
            </div>
            <div class="row g-2 text-center mt-1">
                <div class="col-4">
                    <div class="p-2 bg-light rounded-3">
                        <div class="h5 fw-bold text-primary mb-0">{{ $totalStaff }}</div>
                        <span class="text-muted" style="font-size: 0.72rem;">Staf Aktif</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 bg-light rounded-3">
                        <div class="h5 fw-bold text-dark mb-0">{{ $totalSuppliers }}</div>
                        <span class="text-muted" style="font-size: 0.72rem;">Supplier</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 bg-light rounded-3">
                        <div class="h5 fw-bold text-success mb-0">{{ $totalProducts }}</div>
                        <span class="text-muted" style="font-size: 0.72rem;">Menu Aktif</span>
                    </div>
                </div>
            </div>
            <div class="mt-3 pt-2 border-top text-center">
                <a href="{{ route('users.index') }}" class="small fw-bold text-decoration-none text-primary">
                    Kelola Akun Karyawan &rarr;
                </a>
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
