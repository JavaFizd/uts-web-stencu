@extends('layouts.app')

@section('title', 'Kasir POS - Penjualan Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('sales.index') }}" class="text-decoration-none small text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Penjualan
    </a>
    <h3 class="fw-bold mt-2">Point of Sale (POS Kasir Stencu Coffee)</h3>
    <p class="text-muted">Pilih menu pesanan pelanggan. Stok produk akan berkurang otomatis saat pesanan disimpan.</p>
</div>

<form action="{{ route('sales.store') }}" method="POST" id="posForm">
    @csrf

    <div class="row g-4">
        <!-- Kolom Kiri: Daftar Menu Kopi & Produk Stencu -->
        <div class="col-lg-7">
            <div class="card card-custom p-3 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-cup-hot-fill text-primary me-1"></i> Pilih Menu Pesanan</h5>
                    <input type="text" id="menuSearch" class="form-control form-control-sm w-50" placeholder="Ketik nama menu...">
                </div>

                <!-- Filter Kategori Cepat POS Kasir -->
                <div class="d-flex gap-1 mb-3 overflow-auto pb-1">
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 pos-cat-filter active fw-semibold" style="background-color: #0f2b5c; border-color: #0f2b5c;" onclick="filterPosCat('all', this)">Semua (12)</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 pos-cat-filter fw-semibold" onclick="filterPosCat('minuman', this)">☕ Minuman</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 pos-cat-filter fw-semibold" onclick="filterPosCat('makanan', this)">🍜 Makanan</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 pos-cat-filter fw-semibold" onclick="filterPosCat('cemilan', this)">🍞 Cemilan</button>
                </div>

                <div class="row g-3 overflow-auto" style="max-height: 520px;" id="menuContainer">
                    @forelse($products as $product)
                        <div class="col-sm-6 menuItem" data-name="{{ strtolower($product->name) }}" data-category="{{ strtolower($product->category->slug ?? '') }}">
                            <div class="card p-3 border rounded-3 h-100 {{ $product->stock <= 0 ? 'bg-light opacity-50' : '' }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-light text-dark border small">{{ $product->category->name }}</span>
                                    @if($product->stock <= 0)
                                        <span class="badge bg-danger small">Habis</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success small">Stok: {{ $product->stock }}</span>
                                    @endif
                                </div>
                                <div class="d-flex gap-2 align-items-center mb-2">
                                    @php
                                        $imgPos = $product->image ? (Str::startsWith($product->image, 'http') ? $product->image : asset($product->image)) : asset('images/products/sanger_dingin.jpg');
                                    @endphp
                                    <img src="{{ $imgPos }}" 
                                         alt="{{ $product->name }}" 
                                         class="rounded-3 object-fit-cover border" 
                                         style="width: 46px; height: 46px;"
                                         onerror="this.onerror=null; this.src='{{ asset('images/products/sanger_dingin.jpg') }}';"
                                         loading="lazy">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark small" style="line-height: 1.2;">{{ $product->name }}</h6>
                                        <span class="text-muted" style="font-size: 0.7rem;">{{ $product->sku }}</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="fw-bold text-dark">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                                    @if($product->stock > 0)
                                        <button type="button" class="btn btn-sm btn-primary fw-bold shadow-sm" style="background-color: #2563eb; border: none;" onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->selling_price }}, {{ $product->stock }})">
                                            + Tambah
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-secondary" disabled>Kosong</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4 text-muted">Belum ada data produk aktif.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Keranjang Kasir & Info Pelanggan -->
        <div class="col-lg-5">
            <div class="card card-custom p-3 bg-white">
                <h5 class="fw-bold mb-3"><i class="bi bi-receipt me-1 text-primary"></i> Struk Keranjang Kasir</h5>

                <!-- Info Pelanggan & Pembayaran -->
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Pelanggan <span class="text-danger">*</span></label>
                    <input type="text" name="customer_name" class="form-control" placeholder="Contoh: Rian / Meja 04" value="{{ old('customer_name', 'Pelanggan Walk-In') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="payment_method" class="form-select" required>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai / Cash</option>
                        <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS (BSI / Mandiri)</option>
                        <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Waktu / Tanggal Transaksi</label>
                    <input type="datetime-local" name="sold_at" class="form-control" value="{{ old('sold_at', date('Y-m-d\TH:i')) }}">
                    <div class="form-text small text-muted" style="font-size: 0.72rem;">Otomatis waktu saat ini, bisa diganti jika ingin mencatat tanggal/jam tertentu.</div>
                </div>

                <!-- Tabel Item Keranjang -->
                <div class="table-responsive mb-3" style="min-height: 180px;">
                    <table class="table table-sm align-middle mb-0" id="cartTable">
                        <thead class="table-light small">
                            <tr>
                                <th>Menu</th>
                                <th style="width: 80px;" class="text-center">Qty</th>
                                <th style="width: 90px;" class="text-end">Subtotal</th>
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                            <tr id="emptyCartRow">
                                <td colspan="4" class="text-center py-4 text-muted small">
                                    <i class="bi bi-cart3 fs-3 d-block mb-1 text-muted"></i>
                                    Keranjang masih kosong. Klik menu di sebelah kiri untuk menambahkan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-secondary">Estimasi Total:</span>
                        <h4 class="fw-bold text-success mb-0" id="grandTotalDisplay">Rp 0</h4>
                    </div>
                    <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                        *Total final dihitung dan divalidasi langsung oleh server database (Slide 8).
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Catatan Pesanan (Opsional)</label>
                    <input type="text" name="notes" class="form-control form-control-sm" placeholder="Less ice, no sugar, take away...">
                </div>

                <button type="submit" class="btn btn-success w-100 py-2 fw-bold fs-6 shadow-sm" id="btnSubmitSale" disabled>
                    <i class="bi bi-check2-circle me-1"></i> Simpan Transaksi & Potong Stok
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    let cart = {};

    function addToCart(productId, name, price, maxStock) {
        if (cart[productId]) {
            if (cart[productId].qty < maxStock) {
                cart[productId].qty++;
            } else {
                alert('Jumlah pesanan sudah mencapai batas maksimum stok tersedia (' + maxStock + ')!');
                return;
            }
        } else {
            cart[productId] = {
                id: productId,
                name: name,
                price: price,
                maxStock: maxStock,
                qty: 1
            };
        }
        renderCart();
    }

    function changeQty(productId, delta) {
        if (!cart[productId]) return;
        let newQty = cart[productId].qty + delta;
        if (newQty <= 0) {
            delete cart[productId];
        } else if (newQty > cart[productId].maxStock) {
            alert('Stok tidak mencukupi untuk jumlah ini (Maks: ' + cart[productId].maxStock + ')!');
            return;
        } else {
            cart[productId].qty = newQty;
        }
        renderCart();
    }

    function removeFromCart(productId) {
        delete cart[productId];
        renderCart();
    }

    function renderCart() {
        const tbody = document.getElementById('cartBody');
        const emptyRow = document.getElementById('emptyCartRow');
        const submitBtn = document.getElementById('btnSubmitSale');
        const totalDisplay = document.getElementById('grandTotalDisplay');

        const keys = Object.keys(cart);
        if (keys.length === 0) {
            tbody.innerHTML = `
                <tr id="emptyCartRow">
                    <td colspan="4" class="text-center py-4 text-muted small">
                        <i class="bi bi-cart3 fs-3 d-block mb-1 text-muted"></i>
                        Keranjang masih kosong. Klik menu di sebelah kiri untuk menambahkan.
                    </td>
                </tr>
            `;
            submitBtn.disabled = true;
            totalDisplay.innerText = 'Rp 0';
            return;
        }

        let html = '';
        let grandTotal = 0;
        let index = 0;

        keys.forEach(k => {
            const item = cart[k];
            const subtotal = item.price * item.qty;
            grandTotal += subtotal;

            html += `
                <tr>
                    <td>
                        <div class="fw-semibold small">${item.name}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Rp ${item.price.toLocaleString('id-ID')}</div>
                        <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                    </td>
                    <td class="text-center">
                        <div class="input-group input-group-sm justify-content-center">
                            <button type="button" class="btn btn-outline-secondary px-2 py-0" onclick="changeQty(${item.id}, -1)">-</button>
                            <input type="number" name="items[${index}][quantity]" class="form-control text-center px-1" value="${item.qty}" min="1" max="${item.maxStock}" style="max-width: 45px;" readonly>
                            <button type="button" class="btn btn-outline-secondary px-2 py-0" onclick="changeQty(${item.id}, 1)">+</button>
                        </div>
                    </td>
                    <td class="text-end fw-semibold small">
                        Rp ${subtotal.toLocaleString('id-ID')}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-link text-danger p-0" onclick="removeFromCart(${item.id})">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </td>
                </tr>
            `;
            index++;
        });

        tbody.innerHTML = html;
        submitBtn.disabled = false;
        totalDisplay.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    // Filter menu search
    document.getElementById('menuSearch').addEventListener('input', function(e) {
        const val = e.target.value.toLowerCase();
        document.querySelectorAll('.menuItem').forEach(el => {
            const name = el.getAttribute('data-name');
            el.style.display = name.includes(val) ? '' : 'none';
        });
    });

    // Filter kategori POS
    function filterPosCat(slug, btn) {
        document.querySelectorAll('.pos-cat-filter').forEach(b => {
            b.classList.remove('active', 'btn-primary');
            b.style.backgroundColor = '';
            b.style.borderColor = '';
            b.classList.add('btn-outline-secondary');
        });
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-primary', 'active');
        btn.style.backgroundColor = '#0f2b5c';
        btn.style.borderColor = '#0f2b5c';

        document.querySelectorAll('.menuItem').forEach(el => {
            const cat = el.getAttribute('data-category');
            if (slug === 'all' || cat === slug) {
                el.style.display = '';
            } else {
                el.style.display = 'none';
            }
        });
    }
</script>
@endsection
