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

<div class="card card-custom bg-white p-4" style="max-width: 850px;">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row g-3">
            <!-- 1. SEKSI FOTO PRODUK / MAKANAN (BEBAS UNGGAH / PILIH / URL) -->
            <div class="col-12">
                <div class="p-3.5 rounded-3 border bg-light">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label fw-bold small text-dark mb-0">
                            <i class="bi bi-image text-primary me-1"></i> Foto Menu Makanan / Minuman (Bebas Masukkan Gambar)
                        </label>
                        <span class="badge bg-primary bg-opacity-10 text-primary small" style="font-size: 0.7rem;">Maks 5 MB &bull; JPG, PNG, WEBP</span>
                    </div>

                    <div class="row g-3 align-items-center">
                        <!-- Kotak Pratinjau Gambar Real-Time -->
                        <div class="col-sm-4 text-center">
                            <div class="position-relative mx-auto rounded-3 border overflow-hidden shadow-xs bg-white" style="width: 140px; height: 140px;">
                                <img id="previewImg" src="{{ asset('images/products/sanger_dingin.jpg') }}" alt="Pratinjau Foto" class="w-100 h-100 object-fit-cover">
                                <span class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white py-0.5 text-center" style="font-size: 0.65rem;">
                                    Pratinjau Foto
                                </span>
                            </div>
                            <button type="button" class="btn btn-xs btn-outline-danger mt-1.5 py-0.5 px-2 rounded-pill" onclick="resetImagePreview()" style="font-size: 0.7rem;">
                                <i class="bi bi-x me-1"></i>Reset Foto
                            </button>
                        </div>

                        <!-- Kontrol Pilihan Gambar (Upload File / URL / Preset) -->
                        <div class="col-sm-8">
                            <!-- Opsi A: Upload File Langsung dari Komputer / HP -->
                            <div class="mb-2.5">
                                <label class="small text-muted fw-bold d-block mb-1" style="font-size: 0.72rem;">1. UNGGAH FILE DARI PERANGKAT (KOMPUTER / HP):</label>
                                <input type="file" name="image_file" id="imageFileInput" class="form-control form-control-sm @error('image_file') is-invalid @enderror" accept="image/*" onchange="previewUploadFile(this)">
                                @error('image_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <!-- Opsi B: Input Link URL Gambar -->
                            <div class="mb-2.5">
                                <label class="small text-muted fw-bold d-block mb-1" style="font-size: 0.72rem;">2. ATAU INPUT LINK GAMBAR (URL WEB / GOOGLE):</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                    <input type="url" name="image_url" id="imageUrlInput" class="form-control" placeholder="https://contoh.com/foto-makanan.jpg" value="{{ old('image_url') }}" oninput="previewUrlInput(this.value)">
                                </div>
                            </div>

                            <!-- Opsi C: Pilihan Galeri Preset Cepat -->
                            <div>
                                <label class="small text-muted fw-bold d-block mb-1" style="font-size: 0.72rem;">3. ATAU PILIH DARI FOTO GALERI KHAS STENCU:</label>
                                <input type="hidden" name="image_preset" id="imagePresetInput" value="{{ old('image_preset') }}">
                                <div class="d-flex flex-wrap gap-1">
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickPreset('images/products/sanger_dingin.jpg', 'Sanger Dingin')" style="font-size: 0.7rem;">☕ Sanger Dingin</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickPreset('images/products/sanger_panas.jpg', 'Sanger Panas')" style="font-size: 0.7rem;">☕ Sanger Panas</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickPreset('images/products/kopi_hitam.jpg', 'Kopi Hitam')" style="font-size: 0.7rem;">☕ Kopi Hitam</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickPreset('images/products/teh_tarik.jpg', 'Teh Tarik')" style="font-size: 0.7rem;">🍵 Teh Tarik</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickPreset('images/products/milo.jpg', 'Milo Dingin')" style="font-size: 0.7rem;">🍫 Milo Dingin</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickPreset('images/products/mie_aceh_goreng_telur.jpg', 'Mie Aceh Telur')" style="font-size: 0.7rem;">🍜 Mie Telur</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickPreset('images/products/mie_aceh_kosong.jpg', 'Mie Aceh Polos')" style="font-size: 0.7rem;">🍜 Mie Polos</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickPreset('images/products/bakwan_tahu_goreng.jpg', 'Bakwan Tahu')" style="font-size: 0.7rem;">🥟 Bakwan Tahu</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickPreset('images/products/pisang_goreng.jpg', 'Pisang Goreng')" style="font-size: 0.7rem;">🍌 Pisang Crispy</button>
                                    <button type="button" class="btn btn-xs btn-outline-secondary py-0.5 px-2 rounded-pill" onclick="pickPreset('images/products/roti_bakar.jpg', 'Roti Bakar')" style="font-size: 0.7rem;">🍞 Roti Bakar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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

@push('scripts')
<script>
    const defaultPlaceholder = "{{ asset('images/products/sanger_dingin.jpg') }}";

    function previewUploadFile(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
            document.getElementById('imageUrlInput').value = '';
            document.getElementById('imagePresetInput').value = '';
        }
    }

    function previewUrlInput(url) {
        if (url.trim() !== '') {
            document.getElementById('previewImg').src = url;
            document.getElementById('imageFileInput').value = '';
            document.getElementById('imagePresetInput').value = '';
        }
    }

    function pickPreset(path, label) {
        const fullUrl = "{{ asset('') }}" + path;
        document.getElementById('previewImg').src = fullUrl;
        document.getElementById('imagePresetInput').value = path;
        document.getElementById('imageFileInput').value = '';
        document.getElementById('imageUrlInput').value = '';
    }

    function resetImagePreview() {
        document.getElementById('previewImg').src = defaultPlaceholder;
        document.getElementById('imageFileInput').value = '';
        document.getElementById('imageUrlInput').value = '';
        document.getElementById('imagePresetInput').value = '';
    }
</script>
@endpush
@endsection
