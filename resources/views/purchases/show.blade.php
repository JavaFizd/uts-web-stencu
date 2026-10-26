@extends('layouts.app')

@section('title', 'Detail Nota Pembelian - #' . $purchase->purchase_number)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <a href="{{ route('purchases.index') }}" class="text-decoration-none small text-muted">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Pembelian
        </a>
        <h3 class="fw-bold mt-2 mb-0">Nota Pembelian Stok (Stock-In)</h3>
        <span class="text-muted small">Nomor PO: #{{ $purchase->purchase_number }} • Tanggal: {{ $purchase->purchased_at->format('d/m/Y') }}</span>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Cetak Dokumen
        </button>

        @if(Auth::user()->isOwner() && $purchase->status === 'completed')
            <form action="{{ route('purchases.cancel', $purchase) }}" method="POST" onsubmit="return confirm('PERINGATAN: Membatalkan nota pembelian ini akan menarik kembali penambahan stok produk. Pastikan sisa stok mencukupi untuk ditarik. Lanjutkan?');">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Batalkan Pembelian (Kurangi Stok)
                </button>
            </form>
        @endif
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4 bg-white shadow-sm border">
            <!-- Info Supplier & Header -->
            <div class="d-flex justify-content-between align-items-start pb-3 border-bottom mb-4">
                <div>
                    <h5 class="fw-bold mb-1 text-primary">STENCU COFFEE - SURAT MASUK BARANG</h5>
                    <div class="small text-muted">Dicatat oleh staf: {{ $purchase->user->name ?? '-' }}</div>
                </div>
                <div class="text-end">
                    @if($purchase->status === 'completed')
                        <span class="badge bg-success-subtle text-success fs-6 fw-bold">SELESAI (STOK MASUK)</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger fs-6 fw-bold">DIBATALKAN</span>
                    @endif
                </div>
            </div>

            <!-- Detail Pemasok -->
            <div class="bg-light p-3 rounded-3 mb-4 border">
                <div class="row g-2 small">
                    <div class="col-md-6">
                        <div class="text-muted">Nama Pemasok:</div>
                        <div class="fw-bold fs-6">{{ $purchase->supplier->name ?? '-' }}</div>
                        <div class="text-muted">{{ $purchase->supplier->address ?? '-' }}</div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="text-muted">Kontak PIC:</div>
                        <div class="fw-semibold">{{ $purchase->supplier->contact_person ?? '-' }} ({{ $purchase->supplier->phone ?? '-' }})</div>
                        <div class="text-muted">{{ $purchase->supplier->email ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Tabel Item -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Kuantitas Masuk</th>
                            <th class="text-end">Harga Beli Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->items as $index => $item)
                            <tr>
                                <td class="text-center text-muted small" style="width: 40px;">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->product->name ?? 'Produk Dihapus' }}</div>
                                    <span class="small text-muted">SKU: {{ $item->product->sku ?? '-' }}</span>
                                </td>
                                <td class="text-center fw-bold">{{ $item->quantity }} {{ $item->product->unit ?? '' }}</td>
                                <td class="text-end text-muted">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-6">TOTAL PEMBELIAN:</td>
                            <td class="text-end fw-bold fs-5 text-primary">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($purchase->notes)
                <div class="alert alert-light border small text-muted mb-0">
                    <strong>Catatan Pembelian:</strong> {{ $purchase->notes }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
