@extends('layouts.app')

@section('title', 'Riwayat Pembelian Stok')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Riwayat Pembelian Stok (Stock-In)</h3>
        <p class="text-muted mb-0">Catatan pasokan biji kopi, packaging, dan bahan baku dari supplier mitra.</p>
    </div>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary fw-bold">
        <i class="bi bi-cart-plus me-1"></i> Catat Pembelian Baru
    </a>
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
