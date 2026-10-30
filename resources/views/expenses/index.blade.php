@extends('layouts.app')

@section('title', 'Pengeluaran Kas & Biaya Operasional')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0f2b5c;">
            <i class="bi bi-wallet2 text-danger me-1"></i> Pengeluaran Kas &amp; Biaya Operasional
        </h3>
        <p class="text-muted mb-0 small">Catat dan pantau seluruh uang kas keluar untuk operasional warkop (es batu, gas LPG, listrik, dll).</p>
    </div>
    <div class="d-flex gap-2 flex-wrap no-print">
        <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold rounded-pill px-3 shadow-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Laporan
        </button>
        <a href="{{ route('purchases.create') }}" class="btn btn-outline-primary btn-sm fw-semibold rounded-pill px-3 shadow-sm">
            <i class="bi bi-box-arrow-in-down me-1"></i> Belanja Stok Bahan
        </a>
        <a href="{{ route('expenses.create') }}" class="btn btn-danger btn-sm fw-bold rounded-pill px-3 shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> + Catat Pengeluaran Kas
        </a>
    </div>
</div>

<!-- 3 Kartu Ringkasan Pengeluaran -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card card-custom p-3 bg-white h-100 border-0 shadow-sm" style="border-left: 4px solid #ef4444 !important;">
            <span class="text-muted small fw-bold">PENGELUARAN HARI INI</span>
            <h4 class="fw-bold text-danger mt-1 mb-1">Rp {{ number_format($totalToday, 0, ',', '.') }}</h4>
            <div class="small text-muted" style="font-size: 0.75rem;">
                Tanggal: {{ date('d M Y') }}
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card card-custom p-3 bg-white h-100 border-0 shadow-sm" style="border-left: 4px solid #f97316 !important;">
            <span class="text-muted small fw-bold">PENGELUARAN BULAN INI</span>
            <h4 class="fw-bold mt-1 mb-1" style="color: #ea580c;">Rp {{ number_format($totalThisMonth, 0, ',', '.') }}</h4>
            <div class="small text-muted" style="font-size: 0.75rem;">
                Periode: {{ date('F Y') }}
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-lg-4">
        <div class="card card-custom p-3 bg-white h-100 border-0 shadow-sm" style="border-left: 4px solid #64748b !important;">
            <span class="text-muted small fw-bold">TOTAL PENGELUARAN KAS</span>
            <h4 class="fw-bold text-dark mt-1 mb-1">Rp {{ number_format($totalAllTime, 0, ',', '.') }}</h4>
            <div class="small text-muted" style="font-size: 0.75rem;">
                Total Seluruh Catatan Operasional
            </div>
        </div>
    </div>
</div>

<!-- Filter Sederhana -->
<div class="card card-custom p-3 bg-white mb-4 shadow-sm border no-print">
    <form action="{{ route('expenses.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama pengeluaran / nota..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select form-select-sm">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" title="Mulai Tanggal">
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-sm btn-primary w-100 rounded-pill fw-semibold">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if(request()->hasAny(['search', 'category', 'date_from', 'date_to']))
                <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill" title="Reset Filter">
                    <i class="bi bi-x-circle"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Tabel Riwayat Pengeluaran -->
<div class="card card-custom bg-white shadow-sm border overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light small">
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Nomor Nota</th>
                    <th>Tanggal</th>
                    <th>Keperluan / Nama Biaya</th>
                    <th>Kategori</th>
                    <th class="text-end">Nominal (Rp)</th>
                    <th>Dicatat Oleh</th>
                    <th>Catatan</th>
                    @if(Auth::user()->isOwner())
                        <th class="text-center no-print" style="width: 70px;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $index => $item)
                    <tr>
                        <td class="text-center text-muted small">{{ $expenses->firstItem() + $index }}</td>
                        <td>
                            <span class="badge bg-light text-primary border font-monospace fw-bold">{{ $item->expense_number }}</span>
                        </td>
                        <td class="small">{{ $item->expense_date->format('d/m/Y') }}</td>
                        <td class="fw-bold text-dark">{{ $item->title }}</td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary small rounded-pill px-2.5 py-1">
                                {{ $item->category }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-danger">
                            Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </td>
                        <td class="small text-muted">{{ $item->user->name ?? 'Kasir / Staf' }}</td>
                        <td class="small text-muted">{{ $item->notes ?? '-' }}</td>
                        @if(Auth::user()->isOwner())
                            <td class="text-center no-print">
                                <form action="{{ route('expenses.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus catatan pengeluaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2 rounded-pill" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ Auth::user()->isOwner() ? '9' : '8' }}" class="text-center py-5 text-muted">
                            <i class="bi bi-wallet2 fs-1 d-block mb-2 text-muted opacity-50"></i>
                            <h6 class="fw-bold text-dark">Belum Ada Catatan Pengeluaran Kas</h6>
                            <p class="small mb-3">Klik tombol di bawah untuk mencatat pengeluaran kas pertama kamu.</p>
                            <a href="{{ route('expenses.create') }}" class="btn btn-sm btn-danger rounded-pill px-3 fw-semibold">
                                + Catat Pengeluaran Baru
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-3">
    <div class="small text-muted">Menampilkan {{ $expenses->count() }} dari {{ $expenses->total() }} catatan pengeluaran</div>
    {{ $expenses->links() }}
</div>
@endsection
