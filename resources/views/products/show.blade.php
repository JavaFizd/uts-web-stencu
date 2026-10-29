@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <a href="{{ route('products.index') }}" class="text-decoration-none small text-muted">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Produk
        </a>
        <h3 class="fw-bold mt-2 mb-0">{{ $product->name }}</h3>
        <span class="badge bg-light text-dark border font-monospace">{{ $product->sku }}</span>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning fw-bold text-dark rounded-pill px-3 shadow-sm">
            <i class="bi bi-camera me-1"></i> Ubah Data &amp; Foto Menu
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Card Info Produk -->
    <div class="col-md-5 col-lg-4">
        <div class="card card-custom p-3 bg-white mb-4">
            <div class="mb-3 rounded-3 overflow-hidden border position-relative" style="height: 190px; background-color: #f8f9fa;">
                @php
                    $imgShow = $product->image ? (Str::startsWith($product->image, 'http') ? $product->image : asset($product->image) . '?v=3') : asset('images/products/sanger_dingin.jpg?v=3');
                @endphp
                <img src="{{ $imgShow }}" 
                     alt="{{ $product->name }}" 
                     class="w-100 h-100 object-fit-cover"
                     onerror="this.onerror=null; this.src='{{ asset('images/products/sanger_dingin.jpg') }}';">
                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-dark bg-opacity-75 position-absolute bottom-0 end-0 m-2 rounded-pill py-0.5 px-2.5 text-white" style="font-size: 0.72rem;">
                    <i class="bi bi-camera me-1"></i> Ganti Foto
                </a>
            </div>
            <h6 class="fw-bold text-muted small text-uppercase mb-3">Informasi Utama</h6>
            
            <div class="mb-3">
                <span class="small text-muted d-block">Kategori</span>
                <span class="fw-bold">{{ $product->category->name ?? '-' }}</span>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <span class="small text-muted d-block">Harga Beli / Modal</span>
                    <span class="fw-semibold text-secondary">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</span>
                </div>
                <div class="col-6">
                    <span class="small text-muted d-block">Harga Jual</span>
                    <span class="fw-bold text-success fs-5">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <span class="small text-muted d-block">Stok Fisik</span>
                    @if($product->isLowStock())
                        <span class="badge bg-warning text-dark fs-6">⚠️ {{ $product->stock }} {{ $product->unit }}</span>
                    @else
                        <span class="badge bg-success-subtle text-success fs-6">{{ $product->stock }} {{ $product->unit }}</span>
                    @endif
                </div>
                <div class="col-6">
                    <span class="small text-muted d-block">Batas Min. Stok</span>
                    <span class="fw-bold text-muted">{{ $product->minimum_stock }} {{ $product->unit }}</span>
                </div>
            </div>

            <div class="mb-2">
                <span class="small text-muted d-block">Deskripsi</span>
                <p class="small text-secondary mb-0">{{ $product->description ?: 'Tidak ada deskripsi tambahan.' }}</p>
            </div>
        </div>
    </div>

    <!-- Tab Riwayat Penjualan & Pembelian -->
    <div class="col-md-7 col-lg-8">
        <div class="card card-custom p-3 bg-white">
            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history me-1 text-primary"></i> Riwayat Penjualan Produk Ini (Terbaru)</h6>
            
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead class="table-light small">
                        <tr>
                            <th>No. Nota</th>
                            <th>Tanggal</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($product->saleItems->take(5) as $saleItem)
                            <tr>
                                <td>
                                    <a href="{{ route('sales.show', $saleItem->sale_id) }}" class="fw-bold text-decoration-none">
                                        {{ $saleItem->sale->sale_number ?? 'Nota #' . $saleItem->sale_id }}
                                    </a>
                                </td>
                                <td class="small text-muted">{{ $saleItem->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center fw-bold">{{ $saleItem->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($saleItem->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted small">Belum ada riwayat penjualan untuk produk ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
