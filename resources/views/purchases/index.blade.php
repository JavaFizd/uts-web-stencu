@extends('layouts.app')

@section('title', 'Riwayat Pembelian Stok')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold mb-1">Riwayat Pembelian Stok (Stock-In)</h3>
        <p class="text-muted mb-0">Catatan pasokan biji kopi, packaging, dan bahan baku dari supplier mitra.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary fw-semibold rounded-pill px-3 shadow-sm no-print" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Rekap Belanja
        </button>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary fw-bold rounded-pill px-3 shadow-sm no-print" style="background-color: #2563eb; border: none;">
            <i class="bi bi-cart-plus me-1"></i> Catat Pembelian Baru
        </a>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="card card-custom p-3 bg-white mb-4">
    <form action="{{ route('purchases.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari No. Pembelian (PO)..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-3">
            <select name="supplier_id" class="form-select">
                <option value="">-- Semua Supplier --</option>
                @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>
                        {{ $sup->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" title="Dari Tanggal">
        </div>

        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-dark w-100 fw-semibold">Filter</button>
            @if(request()->hasAny(['search', 'supplier_id', 'date_from']))
                <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Ringkasan Belanja Hasil Filter (KPI Strip) -->
<div class="card card-custom p-3 bg-white mb-4 shadow-sm border" style="border-left: 4px solid #10b981 !important;">
    <div class="row align-items-center g-3">
        <div class="col-md-6">
            <span class="text-muted small fw-bold d-block" style="font-size: 0.72rem;">TOTAL PENGELUARAN BELANJA BAHAN (PO)</span>
            <h4 class="fw-bold text-success mb-0 mt-1">
                Rp {{ number_format($totalPurchasesAmount, 0, ',', '.') }}
            </h4>
            <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                Total belanja stok masuk dari seluruh supplier mitra
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-md-end gap-3 flex-wrap">
                <div class="px-3 py-2 bg-light rounded-3 text-center border">
                    <div class="h5 fw-bold text-dark mb-0">{{ number_format($totalPurchasesCount) }}</div>
                    <span class="text-muted" style="font-size: 0.72rem;">Nota PO Selesai</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Purchases Table -->
<div class="card card-custom bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No. PO / Nota</th>
                    <th>Tanggal Beli</th>
                    <th>Pemasok (Supplier)</th>
                    <th>Dicatat Oleh</th>
                    <th class="text-end">Total Pembelian</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $purchase)
                    <tr>
                        <td class="fw-bold font-monospace text-primary">
                            <a href="{{ route('purchases.show', $purchase) }}" class="text-decoration-none">
                                {{ $purchase->purchase_number }}
                            </a>
                        </td>
                        <td class="small text-muted">{{ $purchase->purchased_at->format('d M Y') }}</td>
                        <td class="fw-semibold">{{ $purchase->supplier->name ?? '-' }}</td>
                        <td class="small text-muted">{{ $purchase->user->name ?? '-' }}</td>
                        <td class="text-end fw-bold">
                            Rp {{ number_format($purchase->total, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($purchase->status === 'completed')
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
                            <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat pembelian stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex justify-content-between align-items-center">
        <div class="small text-muted">
            Menampilkan {{ $purchases->firstItem() ?? 0 }} - {{ $purchases->lastItem() ?? 0 }} dari {{ $purchases->total() }} data
        </div>
        <div>
            {{ $purchases->links() }}
        </div>
    </div>
</div>
@endsection
