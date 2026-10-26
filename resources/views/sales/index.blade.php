@extends('layouts.app')

@section('title', 'Riwayat Transaksi Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Riwayat Transaksi Penjualan (POS)</h3>
        <p class="text-muted mb-0">Catatan kasir, pesanan pelanggan, dan mutasi stok keluar (Stock-out).</p>
    </div>
    <a href="{{ route('sales.create') }}" class="btn btn-success fw-bold">
        <i class="bi bi-cart-plus me-1"></i> Buka Kasir POS Baru
    </a>
</div>

<!-- Search & Filter Bar (Slide 18) -->
<div class="card card-custom p-3 bg-white mb-4">
    <form action="{{ route('sales.index') }}" method="GET" class="row g-2 align-items-center">
        <!-- Search Keyword -->
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari No. Nota / Pelanggan..." value="{{ request('search') }}">
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="col-md-3">
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" title="Dari Tanggal">
        </div>
        <div class="col-md-3">
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" title="Sampai Tanggal">
        </div>

        <!-- Status Filter -->
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Semua Status --</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <div class="col-md-1 d-flex gap-1">
            <button type="submit" class="btn btn-dark w-100 fw-semibold" title="Terapkan Filter">
                <i class="bi bi-funnel"></i>
            </button>
            @if(request()->hasAny(['search', 'date_from', 'date_to', 'status']))
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Sales Table -->
<div class="card card-custom bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. Nota</th>
                    <th>Waktu / Tanggal</th>
                    <th>Nama Pelanggan</th>
                    <th>Kasir</th>
                    <th>Metode Bayar</th>
                    <th class="text-end">Total Tagihan</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td class="fw-bold font-monospace text-primary">
                            <a href="{{ route('sales.show', $sale) }}" class="text-decoration-none">
                                {{ $sale->sale_number }}
                            </a>
                        </td>
                        <td class="small text-muted">{{ $sale->sold_at->format('d M Y, H:i') }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $sale->customer_name }}</div>
                            @if($sale->notes)
                                <div class="small text-muted fst-italic mt-0.5" style="font-size: 0.76rem;">
                                    <i class="bi bi-chat-left-dots text-primary me-1"></i> "{{ $sale->notes }}"
                                </div>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $sale->user->name ?? 'Kasir' }}</td>
                        <td>
                            <span class="badge bg-light text-dark border text-uppercase">{{ $sale->payment_method }}</span>
                        </td>
                        <td class="text-end fw-bold fs-6">
                            Rp {{ number_format($sale->total, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($sale->status === 'completed')
                                <span class="badge bg-success-subtle text-success fw-bold px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i> Selesai
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger fw-bold px-2 py-1">
                                    <i class="bi bi-x-circle me-1"></i> Dibatalkan
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-info" title="Lihat Struk">
                                <i class="bi bi-receipt"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-1"></i>
                            Tidak ada transaksi penjualan yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-3 border-top d-flex justify-content-between align-items-center">
        <div class="small text-muted">
            Menampilkan {{ $sales->firstItem() ?? 0 }} - {{ $sales->lastItem() ?? 0 }} dari {{ $sales->total() }} transaksi
        </div>
        <div>
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection
