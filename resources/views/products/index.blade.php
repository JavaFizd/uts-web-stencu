@extends('layouts.app')

@section('title', 'Master Data Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Manajemen Produk & Stok</h3>
        <p class="text-muted mb-0">Kelola katalog kopi, biji sangrai, stok fisik, dan batas minimum alert.</p>
    </div>
    @if(Auth::user()->isOwner())
        <a href="{{ route('products.create') }}" class="btn btn-warning fw-bold text-dark">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
        </a>
    @endif
</div>

<!-- Search & Filter Bar (Slide 18) -->
<div class="card card-custom p-3 bg-white mb-4">
    <form action="{{ route('products.index') }}" method="GET" class="row g-2 align-items-center">
        <!-- Search Keyword -->
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-light text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama kopi atau SKU..." value="{{ request('search') }}">
            </div>
        </div>

        <!-- Filter Category -->
        <div class="col-md-3">
            <select name="category_id" class="form-select">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Filter Low Stock -->
        <div class="col-md-3">
            <select name="filter" class="form-select">
                <option value="">-- Semua Status Stok --</option>
                <option value="low_stock" {{ request('filter') == 'low_stock' ? 'selected' : '' }}>
                    ⚠️ Hanya Stok Menipis (<= Min)
                </option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-dark w-100 fw-semibold">Filter</button>
            @if(request()->hasAny(['search', 'category_id', 'filter']))
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Products Table -->
<div class="card card-custom bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 120px;">SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th class="text-end">Harga Modal</th>
                    <th class="text-end">Harga Jual</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Min. Stok</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td class="fw-bold font-monospace small text-primary">{{ $product->sku }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @php
                                    $imgTable = $product->image ? (Str::startsWith($product->image, 'http') ? $product->image : asset($product->image) . '?v=3') : asset('images/products/sanger_dingin.jpg?v=3');
                                @endphp
                                <img src="{{ $imgTable }}" 
                                     alt="{{ $product->name }}" 
                                     class="rounded-3 object-fit-cover border" 
                                     style="width: 42px; height: 42px;" 
                                     onerror="this.onerror=null; this.src='{{ asset('images/products/sanger_dingin.jpg') }}';"
                                     loading="lazy">
                                <div>
                                    <div class="fw-bold text-dark">{{ $product->name }}</div>
                                    <span class="small text-muted">{{ $product->unit }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $product->category->name ?? '-' }}</span>
                        </td>
                        <td class="text-end text-muted small">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold text-dark">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($product->stock <= 0)
                                <span class="badge bg-danger">Habis (0)</span>
                            @elseif($product->isLowStock())
                                <span class="badge bg-warning text-dark">⚠️ {{ $product->stock }}</span>
                            @else
                                <span class="badge bg-success-subtle text-success fw-bold">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td class="text-center text-muted small">{{ $product->minimum_stock }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(Auth::user()->isOwner())
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus/menonaktifkan produk {{ $product->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-1"></i>
                            Tidak ada produk yang sesuai dengan kriteria filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination (Slide 18) -->
    <div class="p-3 border-top d-flex justify-content-between align-items-center">
        <div class="small text-muted">
            Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
        </div>
        <div>
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
