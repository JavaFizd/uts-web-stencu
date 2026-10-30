@extends('layouts.app')

@section('title', 'Master Data Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0f2b5c;">Manajemen Produk &amp; Stok</h3>
        <p class="text-muted mb-0 small">Kelola katalog makanan, kopi, biji sangrai, foto menu, dan batas minimum stok.</p>
    </div>
    @if(Auth::user()->isOwner())
        <div class="d-flex gap-2">
            <a href="{{ route('products.create') }}" class="btn btn-warning fw-bold text-dark rounded-pill px-3 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Menu / Produk Baru
            </a>
        </div>
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
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary" title="Detail Produk">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(Auth::user()->isOwner())
                                    <button type="button" class="btn btn-outline-warning text-dark" data-bs-toggle="modal" data-bs-target="#modalPhoto{{ $product->id }}" title="Ubah / Unggah Foto Makanan">
                                        <i class="bi bi-camera-fill"></i>
                                    </button>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary" title="Edit Produk">
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

                            <!-- Modal Cepat Ganti Foto Produk -->
                            <div class="modal fade text-start" id="modalPhoto{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header bg-light border-bottom py-2.5 px-3">
                                            <h6 class="modal-title fw-bold text-dark mb-0">
                                                <i class="bi bi-camera me-1 text-primary"></i> Ubah Foto: {{ $product->name }}
                                            </h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('products.update-image', $product) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body p-3.5">
                                                <!-- Foto Saat Ini -->
                                                <div class="d-flex align-items-center gap-3 p-2.5 mb-3 bg-light rounded-3 border">
                                                    <img id="modalPreview{{ $product->id }}" src="{{ $imgTable }}" alt="{{ $product->name }}" class="rounded-3 object-fit-cover border" style="width: 72px; height: 72px;">
                                                    <div>
                                                        <div class="fw-bold small text-dark">{{ $product->name }}</div>
                                                        <div class="text-muted" style="font-size: 0.72rem;">SKU: {{ $product->sku }} &bull; {{ $product->category->name ?? '-' }}</div>
                                                        <div class="form-check mt-1">
                                                            <input class="form-check-input" type="checkbox" name="remove_image" id="removeCheck{{ $product->id }}" value="1">
                                                            <label class="form-check-label text-danger small" for="removeCheck{{ $product->id }}" style="font-size: 0.74rem;">
                                                                Hapus foto (gunakan default)
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- 1. Upload File Gambar -->
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-dark mb-1">
                                                        <i class="bi bi-upload me-1 text-primary"></i> 1. Unggah File Baru dari Komputer / HP
                                                    </label>
                                                    <input type="file" name="image_file" class="form-control form-control-sm" accept="image/*" onchange="previewModalFile(this, 'modalPreview{{ $product->id }}')">
                                                    <div class="form-text" style="font-size: 0.7rem;">Maks 5 MB &bull; Format: JPG, PNG, WEBP, GIF</div>
                                                </div>

                                                <!-- 2. Masukkan Link URL -->
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold text-dark mb-1">
                                                        <i class="bi bi-link-45deg me-1 text-primary"></i> 2. Atau Masukkan Link URL Gambar Web
                                                    </label>
                                                    <input type="url" name="image_url" id="modalUrlInput{{ $product->id }}" class="form-control form-control-sm" placeholder="https://..." oninput="previewModalUrl(this.value, 'modalPreview{{ $product->id }}')">
                                                </div>

                                                <!-- 3. Pilihan Galeri Preset Cepat -->
                                                <div>
                                                    <label class="form-label small fw-bold text-dark mb-1">
                                                        <i class="bi bi-grid me-1 text-primary"></i> 3. Atau Pilih dari Koleksi Menu Ikonik Stencu
                                                    </label>
                                                    <input type="hidden" name="image_preset" id="modalPresetInput{{ $product->id }}">
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickModalPreset('images/products/sanger_dingin.jpg', 'modalPreview{{ $product->id }}', 'modalPresetInput{{ $product->id }}')" style="font-size: 0.68rem;">☕ Sanger Dingin</button>
                                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickModalPreset('images/products/sanger_panas.jpg', 'modalPreview{{ $product->id }}', 'modalPresetInput{{ $product->id }}')" style="font-size: 0.68rem;">☕ Sanger Panas</button>
                                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickModalPreset('images/products/kopi_hitam.jpg', 'modalPreview{{ $product->id }}', 'modalPresetInput{{ $product->id }}')" style="font-size: 0.68rem;">☕ Kopi Hitam</button>
                                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickModalPreset('images/products/teh_tarik.jpg', 'modalPreview{{ $product->id }}', 'modalPresetInput{{ $product->id }}')" style="font-size: 0.68rem;">🍵 Teh Tarik</button>
                                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickModalPreset('images/products/milo.jpg', 'modalPreview{{ $product->id }}', 'modalPresetInput{{ $product->id }}')" style="font-size: 0.68rem;">🍫 Milo</button>
                                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickModalPreset('images/products/mie_aceh_goreng_telur.jpg', 'modalPreview{{ $product->id }}', 'modalPresetInput{{ $product->id }}')" style="font-size: 0.68rem;">🍜 Mie Telur</button>
                                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickModalPreset('images/products/mie_aceh_kosong.jpg', 'modalPreview{{ $product->id }}', 'modalPresetInput{{ $product->id }}')" style="font-size: 0.68rem;">🍜 Mie Polos</button>
                                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickModalPreset('images/products/bakwan_tahu_goreng.jpg', 'modalPreview{{ $product->id }}', 'modalPresetInput{{ $product->id }}')" style="font-size: 0.68rem;">🥟 Bakwan Tahu</button>
                                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickModalPreset('images/products/pisang_goreng.jpg', 'modalPreview{{ $product->id }}', 'modalPresetInput{{ $product->id }}')" style="font-size: 0.68rem;">🍌 Pisang Crispy</button>
                                                        <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickModalPreset('images/products/roti_bakar.jpg', 'modalPreview{{ $product->id }}', 'modalPresetInput{{ $product->id }}')" style="font-size: 0.68rem;">🍞 Roti Bakar</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light py-2 px-3">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                                                    <i class="bi bi-check-lg me-1"></i> Simpan Foto
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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

@push('scripts')
<script>
    function previewModalFile(input, targetImgId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(targetImgId).src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewModalUrl(url, targetImgId) {
        if (url.trim() !== '') {
            document.getElementById(targetImgId).src = url;
        }
    }

    function pickModalPreset(path, targetImgId, hiddenInputId) {
        const fullUrl = "{{ asset('') }}" + path;
        document.getElementById(targetImgId).src = fullUrl;
        document.getElementById(hiddenInputId).value = path;
    }
</script>
@endpush
@endsection
