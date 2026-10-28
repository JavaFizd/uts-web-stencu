@extends('layouts.public')

@section('title', 'Pesan Menu Kopi & Makanan')

@section('content')
<div class="container py-4">
    <!-- Header Title & Cart Button -->
    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill fw-bold small mb-2">
                <i class="bi bi-cart-check me-1"></i> Pemesanan Langsung di Web
            </span>
            <h2 class="fw-bold mb-1" style="color: #0f2b5c;">Daftar Menu Stencu Coffee</h2>
            <p class="text-muted mb-0">Pilih menu favorit Anda, tambahkan ke keranjang, dan pesan langsung dari meja tanpa perlu antre!</p>
        </div>
        <div class="col-md-5 mt-3 mt-md-0 text-md-end">
            <button type="button" class="btn btn-primary rounded-pill px-4 py-2 position-relative shadow-sm" onclick="openCartModal()" style="background-color: #2563eb; border: none;">
                <i class="bi bi-cart3 fs-5 me-1 text-white"></i>
                <span class="fw-bold">Keranjang Saya</span>
                <span class="badge bg-white text-primary rounded-pill ms-1 fw-bold" id="navCartCount">0</span>
            </button>
        </div>
    </div>

    <!-- Flash Error / Success Notifications -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-x-circle-fill me-1"></i> Pesanan belum dapat diproses:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- 4 Kategori Tab Filter Instan (Tanpa Reload Halaman, Keranjang Aman 100%) -->
    <div class="row g-2 g-md-3 mb-4">
        <!-- Tab 1: Semua Menu -->
        <div class="col-6 col-md-3">
            <button type="button" class="card category-tab-btn active-tab w-100 p-3 rounded-4 border shadow-sm text-center" onclick="filterCategory('all', this)" id="tab-all">
                <div class="fs-3 mb-1 tab-icon"><i class="bi bi-grid-fill"></i></div>
                <div class="fw-bold fs-6 tab-title">Semua Menu</div>
                <span class="small tab-sub" style="font-size: 0.76rem;">12 Menu Warkop</span>
            </button>
        </div>
        <!-- Tab 2: Minuman -->
        <div class="col-6 col-md-3">
            <button type="button" class="card category-tab-btn w-100 p-3 rounded-4 border shadow-sm text-center bg-white text-dark" onclick="filterCategory('minuman', this)" id="tab-minuman">
                <div class="fs-3 mb-1 text-primary tab-icon"><i class="bi bi-cup-hot-fill"></i></div>
                <div class="fw-bold fs-6 tab-title">Minuman</div>
                <span class="small text-muted tab-sub" style="font-size: 0.76rem;">Sanger, Teh Tarik &amp; Milo</span>
            </button>
        </div>
        <!-- Tab 3: Makanan -->
        <div class="col-6 col-md-3">
            <button type="button" class="card category-tab-btn w-100 p-3 rounded-4 border shadow-sm text-center bg-white text-dark" onclick="filterCategory('makanan', this)" id="tab-makanan">
                <div class="fs-3 mb-1 text-danger tab-icon"><i class="bi bi-fire"></i></div>
                <div class="fw-bold fs-6 tab-title">Makanan</div>
                <span class="small text-muted tab-sub" style="font-size: 0.76rem;">Mie Aceh, Indomie &amp; Nasi</span>
            </button>
        </div>
        <!-- Tab 4: Cemilan -->
        <div class="col-6 col-md-3">
            <button type="button" class="card category-tab-btn w-100 p-3 rounded-4 border shadow-sm text-center bg-white text-dark" onclick="filterCategory('cemilan', this)" id="tab-cemilan">
                <div class="fs-3 mb-1 text-success tab-icon"><i class="bi bi-basket2-fill"></i></div>
                <div class="fw-bold fs-6 tab-title">Cemilan</div>
                <span class="small text-muted tab-sub" style="font-size: 0.76rem;">Pisang Keju, Roti &amp; Bakwan</span>
            </button>
        </div>
    </div>

    <!-- Search Form Bar (Live Search Instan) -->
    <div class="mb-4">
        <div class="input-group bg-white rounded-pill shadow-sm p-1 border">
            <span class="input-group-text bg-transparent border-0 text-primary ps-3"><i class="bi bi-search"></i></span>
            <input type="text" id="liveSearchInput" class="form-control border-0 bg-transparent py-2" placeholder="Cari kopi sanger, mie aceh, pisang goreng, roti bakar..." oninput="filterLiveSearch(this.value)">
            <button type="button" class="btn btn-link text-muted pe-3 text-decoration-none d-none" id="clearSearchBtn" onclick="clearLiveSearch()"><i class="bi bi-x-circle-fill"></i></button>
        </div>
    </div>

    <!-- Product Grid (12 Menu Warkop) -->
    <div class="row g-3 g-md-4 mb-5" id="productGrid">
        @foreach($products as $product)
            <div class="col-sm-6 col-lg-4 product-card-col" 
                 data-category="{{ $product->category->slug }}" 
                 data-name="{{ strtolower($product->name) }}"
                 data-id="{{ $product->id }}">
                <div class="card card-product h-100 p-3 d-flex flex-column" id="card-product-{{ $product->id }}" style="transition: all 0.2s ease-in-out;">
                    <!-- Foto Menu Real JPG -->
                    <div class="position-relative overflow-hidden rounded-3 mb-3" style="height: 180px; background-color: #f1f5f9;">
                        @php
                            $imgSrc = $product->image ? (Str::startsWith($product->image, 'http') ? $product->image : asset($product->image) . '?v=3') : asset('images/products/sanger_dingin.jpg?v=3');
                        @endphp
                        <img src="{{ $imgSrc }}" 
                             alt="{{ $product->name }}" 
                             class="w-100 h-100 object-fit-cover" 
                             onerror="this.onerror=null; this.src='{{ asset('images/products/sanger_dingin.jpg') }}';"
                             loading="lazy">
                        <span class="badge badge-category position-absolute top-0 start-0 m-2 px-2 py-1 small rounded-pill shadow-sm">
                            {{ $product->category->name }}
                        </span>
                    </div>

                    <!-- Status Stok -->
                    <div class="mb-2">
                        @if($product->stock > $product->minimum_stock)
                            <span class="badge badge-available px-2 py-1"><i class="bi bi-check-circle me-1"></i> Tersedia ({{ $product->stock }})</span>
                        @elseif($product->stock > 0)
                            <span class="badge badge-low px-2 py-1"><i class="bi bi-exclamation-circle me-1"></i> Sisa {{ $product->stock }} {{ $product->unit }}</span>
                        @else
                            <span class="badge badge-out px-2 py-1"><i class="bi bi-x-circle me-1"></i> Habis</span>
                        @endif
                    </div>

                    <h5 class="fw-bold mb-1 text-dark">{{ $product->name }}</h5>
                    <p class="text-muted small mb-3 flex-grow-1" style="line-height: 1.5;">
                        {{ $product->description ?: 'Sajian segar khas racikan Stencu Coffee.' }}
                    </p>

                    <!-- Price & Action Button -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                        <div>
                            <span class="small text-muted d-block" style="font-size: 0.75rem;">Harga / {{ $product->unit }}</span>
                            <span class="fs-5 fw-bold" style="color: #0f2b5c;">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                        </div>

                        @if($product->stock > 0)
                            <div id="btn-container-{{ $product->id }}">
                                <button type="button" class="btn btn-primary btn-sm fw-bold px-3 py-2 text-white rounded-pill shadow-sm"
                                        style="background-color: #2563eb; border: none;"
                                        onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->selling_price }}, {{ $product->stock }})">
                                    <i class="bi bi-plus-lg me-1"></i> Pesan
                                </button>
                            </div>
                        @else
                            <button class="btn btn-secondary btn-sm rounded-pill px-3 py-2 disabled" disabled>
                                Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Info Tentang Stencu Coffee di Bagian Bawah (Bersih & Rapi, Tanpa Keranjang Melayang) -->
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white mt-4 mb-4">
        <div class="row g-4 align-items-center">
            <div class="col-md-5 border-end">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="{{ asset('images/logo.svg') }}" alt="Stencu Coffee" height="38">
                </div>
                <p class="text-muted small mb-2">
                    Warung kopi legendaris khas Aceh di Batuphat Barat, menyajikan seduhan kopi saring, Sanger dingin pekat, Mie Aceh rempah asli, dan aneka cemilan hangat.
                </p>
                <div class="text-muted small">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                    Jl. Medan - Banda Aceh Km. 260, Batuphat Barat, Kec. Muara Satu, Kota Lhokseumawe, Aceh
                </div>
            </div>
            <div class="col-md-7">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="d-flex gap-2 align-items-start">
                            <i class="bi bi-clock-history text-primary fs-5 mt-1"></i>
                            <div>
                                <strong class="small d-block text-dark">Jam Operasional</strong>
                                <span class="text-muted small">Buka Setiap Hari: 07.30 - 00.30 WIB</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex gap-2 align-items-start">
                            <i class="bi bi-wifi text-primary fs-5 mt-1"></i>
                            <div>
                                <strong class="small d-block text-dark">Fasilitas Warkop</strong>
                                <span class="text-muted small">Free Wi-Fi Kencang &amp; Colokan Tiap Meja</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex gap-2 align-items-start">
                            <i class="bi bi-qr-code-scan text-success fs-5 mt-1"></i>
                            <div>
                                <strong class="small d-block text-dark">Pembayaran Praktis</strong>
                                <span class="text-muted small">Menerima Tunai &amp; QRIS Semua Bank / E-Wallet</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex gap-2 align-items-start">
                            <i class="bi bi-telephone-inbound text-primary fs-5 mt-1"></i>
                            <div>
                                <strong class="small d-block text-dark">Kontak &amp; Reservasi</strong>
                                <span class="text-muted small">WhatsApp: 0812-6900-1122</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Checkout Pesanan Online -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header text-white p-3 px-4" style="background-color: #0f2b5c;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-receipt text-info fs-4"></i>
                    <h5 class="modal-title fw-bold text-white mb-0" id="cartModalLabel">Keranjang &amp; Konfirmasi Pesanan</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('public.order.store') }}" method="POST" id="customerOrderForm" onsubmit="handleOrderSubmit()">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Kolom Kiri: Rincian Keranjang -->
                        <div class="col-md-6 border-end">
                            <h6 class="fw-bold mb-3 d-flex align-items-center justify-content-between">
                                <span><i class="bi bi-cart3 me-1 text-primary"></i> Daftar Menu Anda</span>
                                <span class="badge bg-light text-primary border small" id="modalItemsCount">0 Item</span>
                            </h6>

                            <div class="overflow-auto pe-2" style="max-height: 280px;" id="cartModalList">
                                <!-- Diisi otomatis oleh JavaScript -->
                            </div>

                            <div class="p-3 bg-light rounded-3 mt-3 border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary fw-semibold">Total Pembayaran:</span>
                                    <h4 class="fw-bold text-primary mb-0" id="modalGrandTotal" style="color: #1d4ed8 !important;">Rp 0</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Identitas Pemesan, Meja, & Metode Bayar -->
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-1 text-primary"></i> Data Pemesan</h6>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nama Anda (Pemesan) <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control fw-semibold" placeholder="Tulis nama Anda (Contoh: Cut Nabila / Fajar)" required value="{{ Auth::check() ? Auth::user()->name : old('customer_name') }}">
                                <div class="form-text text-muted" style="font-size: 0.74rem;">
                                    Nama ini akan tercetak di nota &amp; bisa dipakai untuk cek status pesanan Anda.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Tipe Pesanan <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="order_type" id="typeDineIn" value="dine_in" checked onchange="toggleTableInput(true)">
                                        <label class="form-check-label small fw-semibold" for="typeDineIn">
                                            Makan di Tempat (Dine In)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="order_type" id="typeTakeaway" value="takeaway" onchange="toggleTableInput(false)">
                                        <label class="form-check-label small fw-semibold" for="typeTakeaway">
                                            Bungkus (Takeaway)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Input Nomor Meja (Jika Dine-In) -->
                            <div class="mb-3" id="tableInputGroup">
                                <label class="form-label small fw-bold">Pilih Nomor Meja <span class="text-danger">*</span></label>
                                <select name="table_number" id="tableNumber" class="form-select fw-semibold text-dark">
                                    <option value="01">Meja 01</option>
                                    <option value="02">Meja 02</option>
                                    <option value="03" selected>Meja 03</option>
                                    <option value="04">Meja 04</option>
                                    <option value="05">Meja 05</option>
                                    <option value="06">Meja 06</option>
                                    <option value="07">Meja 07</option>
                                    <option value="08">Meja 08</option>
                                    <option value="09">Meja 09</option>
                                    <option value="10">Meja 10</option>
                                </select>
                                <div class="form-text small text-muted" style="font-size: 0.75rem;">Pesanan akan diantar barista langsung ke nomor meja ini.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select name="payment_method" id="paymentMethodSelect" class="form-select" onchange="checkPaymentMethod(this.value)" required>
                                    <option value="cash">Bayar Tunai di Kasir</option>
                                    <option value="qris" selected>QRIS (Scan Barcode &amp; Bayar Langsung)</option>
                                </select>
                            </div>

                            <!-- QRIS Info Notice Box -->
                            <div class="alert alert-info py-2 px-3 small rounded-3 mb-3" id="qrisNoticeBox">
                                <i class="bi bi-qr-code text-danger me-1"></i>
                                <strong>Info QRIS:</strong> Barcode QRIS resmi Stencu Coffee akan langsung muncul di layar setelah Anda kirim pesanan, sehingga Anda bisa langsung scan via m-Banking atau e-Wallet dan tinggal menunjukkan bukti transfer ke kasir.
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Catatan Racikan Khusus (Opsional)</label>
                                <textarea name="notes" rows="2" class="form-control form-control-sm" placeholder="Ketik catatan di sini (misal: sanger jangan terlalu manis, mie jangan pedes ya bang)"></textarea>
                                <div class="form-text text-muted" style="font-size: 0.74rem;">Catatan ini akan langsung dibaca oleh kasir dan barista saat meracik pesanan Anda.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light p-3 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Kembali Pilih Menu</button>
                    <button type="submit" class="btn btn-primary fw-bold text-white rounded-pill px-4 shadow-sm" id="btnSubmitCustomerOrder" style="background-color: #2563eb; border: none;">
                        <i class="bi bi-send-check me-1"></i> Konfirmasi &amp; Kirim Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .category-tab-btn {
        background-color: #ffffff;
        color: #0f172a;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .category-tab-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15, 43, 92, 0.08) !important;
    }
    .category-tab-btn.active-tab {
        background-color: #0f2b5c !important;
        border-color: #0f2b5c !important;
        color: #ffffff !important;
    }
    .category-tab-btn.active-tab .tab-icon {
        color: #38bdf8 !important;
    }
    .category-tab-btn.active-tab .tab-title {
        color: #ffffff !important;
    }
    .category-tab-btn.active-tab .tab-sub {
        color: rgba(255, 255, 255, 0.75) !important;
    }
</style>
@endsection

@section('scripts')
<script>
    // Master data produk untuk sinkronisasi tombol
    const productsData = {
        @foreach($products as $prod)
            {{ $prod->id }}: {
                id: {{ $prod->id }},
                name: "{{ addslashes($prod->name) }}",
                price: {{ (int)$prod->selling_price }},
                stock: {{ (int)$prod->stock }}
            },
        @endforeach
    };

    // Keranjang tersimpan di LocalStorage agar TIDAK HILANG saat ganti kategori makanan/minuman!
    let cart = {};

    function loadCartFromStorage() {
        try {
            const saved = localStorage.getItem('stencu_cart');
            if (saved) {
                cart = JSON.parse(saved);
            }
        } catch (e) {
            cart = {};
        }
    }

    function saveCartToStorage() {
        try {
            localStorage.setItem('stencu_cart', JSON.stringify(cart));
        } catch (e) {}
    }

    function addToCart(id, name, price, maxStock) {
        if (!cart[id]) {
            cart[id] = { id: id, name: name, price: price, qty: 0, maxStock: maxStock };
        }

        if (cart[id].qty < maxStock) {
            cart[id].qty++;
            saveCartToStorage();
            renderCartUI();
        } else {
            alert('Jumlah pesanan mencapai batas stok yang tersedia (' + maxStock + ')');
        }
    }

    function changeQty(id, delta) {
        if (!cart[id]) {
            if (delta > 0 && productsData[id]) {
                addToCart(id, productsData[id].name, productsData[id].price, productsData[id].stock);
            }
            return;
        }
        
        let newQty = cart[id].qty + delta;
        if (newQty <= 0) {
            delete cart[id];
        } else if (newQty > cart[id].maxStock) {
            alert('Stok hanya tersisa ' + cart[id].maxStock);
        } else {
            cart[id].qty = newQty;
        }
        saveCartToStorage();
        renderCartUI();
    }

    function removeItem(id) {
        delete cart[id];
        saveCartToStorage();
        renderCartUI();
    }

    function renderCartUI() {
        let totalItems = 0;
        let grandTotal = 0;
        let listHtml = '';
        let hiddenInputsHtml = '';

        const keys = Object.keys(cart);

        if (keys.length === 0) {
            listHtml = '<div class="text-center py-4 text-muted small"><i class="bi bi-cart3 fs-3 d-block mb-1 text-muted"></i>Keranjang Anda masih kosong. Silakan pilih menu kopi atau makanan.</div>';
            document.getElementById('btnSubmitCustomerOrder').disabled = true;
        } else {
            keys.forEach((id, index) => {
                let item = cart[id];
                let subtotal = item.price * item.qty;
                totalItems += item.qty;
                grandTotal += subtotal;

                listHtml += `
                    <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-white rounded-3 border">
                        <div style="max-width: 55%;">
                            <div class="fw-bold small text-dark">${item.name}</div>
                            <span class="text-muted" style="font-size: 0.75rem;">Rp ${formatRupiah(item.price)} &times; ${item.qty}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-outline-secondary btn-sm p-0 px-2 rounded-circle" onclick="changeQty(${item.id}, -1)">&minus;</button>
                            <span class="fw-bold px-1 small">${item.qty}</span>
                            <button type="button" class="btn btn-outline-primary btn-sm p-0 px-2 rounded-circle" onclick="changeQty(${item.id}, 1)">&plus;</button>
                            <button type="button" class="btn btn-link text-danger btn-sm p-0 ms-1" onclick="removeItem(${item.id})" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                `;

                hiddenInputsHtml += `
                    <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.qty}">
                `;
            });

            document.getElementById('btnSubmitCustomerOrder').disabled = false;
        }

        document.getElementById('cartModalList').innerHTML = listHtml + hiddenInputsHtml;
        document.getElementById('modalItemsCount').innerText = keys.length + ' Menu (' + totalItems + ' Porsi)';
        document.getElementById('modalGrandTotal').innerText = 'Rp ' + formatRupiah(grandTotal);
        document.getElementById('navCartCount').innerText = totalItems;

        updateProductCardButtons();
    }

    function updateProductCardButtons() {
        document.querySelectorAll('[id^="btn-container-"]').forEach(container => {
            const id = container.id.replace('btn-container-', '');
            const card = document.getElementById('card-product-' + id);
            const item = cart[id];

            if (item && item.qty > 0) {
                container.innerHTML = `
                    <div class="d-flex align-items-center bg-primary bg-opacity-10 border border-primary border-opacity-50 rounded-pill p-1 shadow-sm">
                        <button type="button" class="btn btn-primary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background-color: #2563eb; border: none;" onclick="changeQty(${id}, -1)">
                            <i class="bi bi-dash fw-bold text-white"></i>
                        </button>
                        <span class="fw-bold px-2 text-primary small" style="min-width: 24px; text-align: center;">${item.qty}</span>
                        <button type="button" class="btn btn-primary btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background-color: #2563eb; border: none;" onclick="changeQty(${id}, 1)">
                            <i class="bi bi-plus fw-bold text-white"></i>
                        </button>
                    </div>
                `;
                if (card) {
                    card.style.borderColor = '#2563eb';
                    card.style.backgroundColor = '#f8faff';
                    card.style.boxShadow = '0 4px 14px rgba(37, 99, 235, 0.15)';
                }
            } else {
                if (productsData[id]) {
                    const p = productsData[id];
                    container.innerHTML = `
                        <button type="button" class="btn btn-primary btn-sm fw-bold px-3 py-2 text-white rounded-pill shadow-sm"
                                style="background-color: #2563eb; border: none;"
                                onclick="addToCart(${p.id}, '${escapeJs(p.name)}', ${p.price}, ${p.stock})">
                            <i class="bi bi-plus-lg me-1"></i> Pesan
                        </button>
                    `;
                }
                if (card) {
                    card.style.borderColor = '';
                    card.style.backgroundColor = '#ffffff';
                    card.style.boxShadow = '';
                }
            }
        });
    }

    // Filter Kategori Instan di Browser (Tanpa reload, item keranjang tetap utuh!)
    let activeCategory = 'all';

    function filterCategory(category, btnElement) {
        activeCategory = category;

        // Update styling tombol tab
        document.querySelectorAll('.category-tab-btn').forEach(btn => {
            btn.classList.remove('active-tab');
            btn.classList.add('bg-white', 'text-dark');
        });
        if (btnElement) {
            btnElement.classList.add('active-tab');
            btnElement.classList.remove('bg-white', 'text-dark');
        }

        applyFilters();
    }

    function filterLiveSearch(query) {
        const clearBtn = document.getElementById('clearSearchBtn');
        if (query.trim().length > 0) {
            clearBtn.classList.remove('d-none');
        } else {
            clearBtn.classList.add('d-none');
        }
        applyFilters();
    }

    function clearLiveSearch() {
        document.getElementById('liveSearchInput').value = '';
        document.getElementById('clearSearchBtn').classList.add('d-none');
        applyFilters();
    }

    function applyFilters() {
        const query = (document.getElementById('liveSearchInput').value || '').toLowerCase().trim();

        document.querySelectorAll('.product-card-col').forEach(col => {
            const cat = col.getAttribute('data-category');
            const name = col.getAttribute('data-name') || '';

            const matchCat = (activeCategory === 'all' || cat === activeCategory);
            const matchSearch = (query === '' || name.includes(query));

            if (matchCat && matchSearch) {
                col.style.display = 'block';
            } else {
                col.style.display = 'none';
            }
        });
    }

    function openCartModal() {
        let myModal = new bootstrap.Modal(document.getElementById('cartModal'));
        myModal.show();
    }

    function toggleTableInput(isDineIn) {
        let group = document.getElementById('tableInputGroup');
        let select = document.getElementById('tableNumber');
        if (isDineIn) {
            group.style.display = 'block';
            select.disabled = false;
        } else {
            group.style.display = 'none';
            select.disabled = true;
        }
    }

    function checkPaymentMethod(val) {
        const qrisBox = document.getElementById('qrisNoticeBox');
        if (val === 'qris') {
            qrisBox.style.display = 'block';
        } else {
            qrisBox.style.display = 'none';
        }
    }

    function handleOrderSubmit() {
        // Hapus cart dari localStorage saat pesanan berhasil dikirim
        try {
            localStorage.removeItem('stencu_cart');
        } catch (e) {}
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function escapeJs(str) {
        return str.replace(/'/g, "\\'");
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadCartFromStorage();
        renderCartUI();
    });
</script>
@endsection
