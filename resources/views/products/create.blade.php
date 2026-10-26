@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('products.index') }}" class="text-decoration-none small text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Produk
    </a>
    <h3 class="fw-bold mt-2">Tambah Produk Baru</h3>
    <p class="text-muted">Daftarkan menu kopi, bahan baku, atau biji kopi sangrai baru ke inventaris.</p>
</div>

<div class="card card-custom bg-white p-4" style="max-width: 800px;">
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        
        <div class="row g-3">
            <!-- SKU & Kategori -->
            <div class="col-md-6">
                <label class="form-label fw-bold small">Kode SKU (Harus Unik) <span class="text-danger">*</span></label>
                <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" placeholder="Contoh: STN-ESP-02" value="{{ old('sku') }}" required>
                @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small">Kategori Produk <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Nama & Satuan -->
            <div class="col-md-8">
                <label class="form-label fw-bold small">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Cold Brew Latte" value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold small">Satuan Unit <span class="text-danger">*</span></label>
                <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" placeholder="cup, pack 250g, kg, bottle" value="{{ old('unit', 'cup') }}" required>
                @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Harga Modal & Harga Jual -->
            <div class="col-md-6">
                <label class="form-label fw-bold small">Harga Modal / Beli (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="purchase_price" step="0.01" min="0" class="form-control @error('purchase_price') is-invalid @enderror" placeholder="8000" value="{{ old('purchase_price') }}" required>
                @error('purchase_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small">Harga Jual (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="selling_price" step="0.01" min="0" class="form-control @error('selling_price') is-invalid @enderror" placeholder="18000" value="{{ old('selling_price') }}" required>
                @error('selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Stok Awal & Minimum Stok -->
            <div class="col-md-6">
                <label class="form-label fw-bold small">Stok Awal <span class="text-danger">*</span></label>
                <input type="number" name="stock" min="0" class="form-control @error('stock') is-invalid @enderror" placeholder="0" value="{{ old('stock', 0) }}" required>
                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold small">Batas Minimum Stok (Alert) <span class="text-danger">*</span></label>
                <input type="number" name="minimum_stock" min="0" class="form-control @error('minimum_stock') is-invalid @enderror" placeholder="5" value="{{ old('minimum_stock', 5) }}" required>
                <div class="form-text small">Sistem akan memberi peringatan jika stok berada di bawah angka ini.</div>
                @error('minimum_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <!-- Deskripsi -->
            <div class="col-12">
                <label class="form-label fw-bold small">Deskripsi Produk (Opsional)</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Karakter rasa, komposisi, atau catatan penyajian...">{{ old('description') }}</textarea>
            </div>

            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label small" for="isActive">Aktifkan produk untuk ditampilkan di katalog kasir & publik</label>
                </div>
            </div>

            <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-warning fw-bold text-dark px-4">
                    <i class="bi bi-save me-1"></i> Simpan Produk
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
