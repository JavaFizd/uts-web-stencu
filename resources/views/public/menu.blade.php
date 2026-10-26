@extends('layouts.public')

@section('title', 'Pesan Menu Kopi & Makanan')

@section('content')
<div class="container py-4">
    <!-- Header Title -->
    <div class="row align-items-center mb-4">
        <div class="col-md-7">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill fw-bold small mb-2">
                <i class="bi bi-cart-check me-1"></i> Pemesanan Langsung di Web
            </span>
            <h2 class="fw-bold mb-1" style="color: #0f2b5c;">Daftar Menu Stencu Coffee</h2>
            <p class="text-muted mb-0">Pilih menu favorit Anda, masukkan ke keranjang, dan pesan langsung dari meja tanpa perlu antre!</p>
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

    <!-- Category Pills & Search Form (Clean White Card with Blue accents) -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <!-- Search Keyword -->
        <form action="{{ route('public.menu') }}" method="GET" class="mb-3">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-muted ps-3"><i class="bi bi-search text-primary"></i></span>
                <input type="text" name="search" class="form-control border-start-0 py-2" placeholder="Cari sanger, nutrisari, mie aceh, indomie..." value="{{ request('search') }}">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button type="submit" class="btn btn-primary fw-bold px-4" style="background-color: #2563eb; border: none;">Cari</button>
            </div>
        </form>

        <!-- Category Scrollable Pills -->
        <div class="d-flex gap-2 overflow-auto pb-1" style="white-space: nowrap;">
            <a href="{{ route('public.menu', ['search' => request('search')]) }}" 
               class="btn btn-sm rounded-pill px-3 py-2 {{ !request('category') ? 'btn-primary text-white fw-bold shadow-sm' : 'btn-outline-primary bg-white text-primary border-primary-subtle' }}"
               style="{{ !request('category') ? 'background-color: #2563eb; border: none;' : '' }}">
                Semua Kategori
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('public.menu', ['category' => $cat->slug, 'search' => request('search')]) }}" 
                   class="btn btn-sm rounded-pill px-3 py-2 {{ request('category') == $cat->slug ? 'btn-primary text-white fw-bold shadow-sm' : 'btn-outline-primary bg-white text-primary border-primary-subtle' }}"
                   style="{{ request('category') == $cat->slug ? 'background-color: #2563eb; border: none;' : '' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Product Grid -->
    <div class="row g-3 g-md-4 mb-5">
        @forelse($products as $product)
            <div class="col-sm-6 col-lg-4">
                <div class="card card-product h-100 p-3 d-flex flex-column">
                    <!-- Foto Menu Real JPG -->
                    <div class="position-relative overflow-hidden rounded-3 mb-3" style="height: 180px; background-color: #f1f5f9;">
                        @php
                            $imgSrc = $product->image ? (Str::startsWith($product->image, 'http') ? $product->image : asset($product->image)) : asset('images/products/sanger_dingin.jpg');
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
                            <button type="button" class="btn btn-primary btn-sm fw-bold px-3 py-2 text-white rounded-pill shadow-sm"
                                    style="background-color: #2563eb; border: none;"
                                    onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->selling_price }}, {{ $product->stock }})">
                                <i class="bi bi-plus-lg me-1"></i> Tambah
                            </button>
                        @else
                            <button class="btn btn-secondary btn-sm rounded-pill px-3 py-2 disabled" disabled>
                                Habis
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                <h5 class="fw-bold text-dark">Menu Tidak Ditemukan</h5>
                <p class="text-muted small">Coba cari dengan kata kunci lain atau pilih kategori lain.</p>
                <a href="{{ route('public.menu') }}" class="btn btn-primary btn-sm rounded-pill fw-bold text-white px-3" style="background-color: #2563eb; border: none;">Tampilkan Semua Menu</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mb-5">
        {{ $products->links() }}
    </div>
</div>

<!-- Floating Bottom Cart Bar (Deep Navy & Blue) -->
<div class="floating-cart-bar p-3 d-none align-items-center justify-content-between" id="floatingCart">
    <div class="d-flex align-items-center gap-3">
        <div class="p-2 bg-primary text-white rounded-circle fs-5 d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; background-color: #2563eb !important;">
            <i class="bi bi-bag-check-fill"></i>
        </div>
        <div>
            <div class="fw-bold fs-6 text-white" id="floatTotalAmount">Rp 0</div>
            <div class="small text-light opacity-75" id="floatItemCount">0 Menu Dipilih</div>
        </div>
    </div>
    <button type="button" class="btn btn-primary fw-bold text-white px-4 py-2 rounded-pill shadow-sm" style="background-color: #2563eb; border: 1px solid #60a5fa;" onclick="openCartModal()">
        Pesan Sekarang <i class="bi bi-arrow-right ms-1"></i>
    </button>
</div>

<!-- Modal Checkout Pesanan Online (Clean White & Navy) -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header text-white p-3 px-4" style="background-color: #0f2b5c;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-receipt text-info fs-4"></i>
                    <h5 class="modal-title fw-bold text-white mb-0" id="cartModalLabel">Keranjang &amp; Form Pemesanan</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('public.order.store') }}" method="POST" id="customerOrderForm">
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

                        <!-- Kolom Kanan: Identitas Pemesan & Meja -->
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-1 text-primary"></i> Data Pemesan</h6>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Nama Pemesan <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control" placeholder="Contoh: Cut Nabila / Bang Fajar" required value="{{ Auth::check() ? Auth::user()->name : old('customer_name') }}">
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
                                <label class="form-label small fw-bold">Nomor Meja Anda <span class="text-danger">*</span></label>
                                <input type="text" name="table_number" id="tableNumber" class="form-control" placeholder="Contoh: 04 (Lihat stiker di meja Anda)" value="{{ old('table_number') }}">
                                <div class="form-text small text-muted" style="font-size: 0.75rem;">Barista akan mengantar pesanan langsung ke meja ini.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="cash">Bayar Tunai di Kasir</option>
                                    <option value="qris">QRIS (BSI / Mandiri / GoPay)</option>
                                    <option value="transfer">Transfer Bank (BSI)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Catatan Khusus (Opsional)</label>
                                <input type="text" name="notes" class="form-control form-control-sm" placeholder="Contoh: Sanger manis sedang, Mie Aceh pedas">
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

@section('scripts')
<script>
    let cart = {};

    function addToCart(id, name, price, maxStock) {
        if (!cart[id]) {
            cart[id] = { id: id, name: name, price: price, qty: 0, maxStock: maxStock };
        }

        if (cart[id].qty < maxStock) {
            cart[id].qty++;
            renderCartUI();
        } else {
            alert('Jumlah pesanan mencapai batas stok yang tersedia (' + maxStock + ')');
        }
    }

    function changeQty(id, delta) {
        if (!cart[id]) return;
        
        let newQty = cart[id].qty + delta;
        if (newQty <= 0) {
            delete cart[id];
        } else if (newQty > cart[id].maxStock) {
            alert('Stok hanya tersisa ' + cart[id].maxStock);
        } else {
            cart[id].qty = newQty;
        }
        renderCartUI();
    }

    function removeItem(id) {
        delete cart[id];
        renderCartUI();
    }

    function renderCartUI() {
        let totalItems = 0;
        let grandTotal = 0;
        let listHtml = '';
        let hiddenInputsHtml = '';

        const keys = Object.keys(cart);

        if (keys.length === 0) {
            listHtml = '<div class="text-center py-4 text-muted small"><i class="bi bi-cart3 fs-3 d-block mb-1 text-muted"></i>Keranjang Anda masih kosong.</div>';
            document.getElementById('floatingCart').classList.add('d-none');
            document.getElementById('floatingCart').classList.remove('d-flex');
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
                            <button type="button" class="btn btn-link text-danger btn-sm p-0 ms-1" onclick="removeItem(${item.id})"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                `;

                hiddenInputsHtml += `
                    <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.qty}">
                `;
            });

            document.getElementById('floatingCart').classList.remove('d-none');
            document.getElementById('floatingCart').classList.add('d-flex');
            document.getElementById('btnSubmitCustomerOrder').disabled = false;
        }

        document.getElementById('cartModalList').innerHTML = listHtml + hiddenInputsHtml;
        document.getElementById('modalItemsCount').innerText = totalItems + ' Item';
        document.getElementById('modalGrandTotal').innerText = 'Rp ' + formatRupiah(grandTotal);
        document.getElementById('navCartCount').innerText = totalItems;
        document.getElementById('floatItemCount').innerText = totalItems + ' Menu Dipilih';
        document.getElementById('floatTotalAmount').innerText = 'Rp ' + formatRupiah(grandTotal);
    }

    function openCartModal() {
        let myModal = new bootstrap.Modal(document.getElementById('cartModal'));
        myModal.show();
    }

    function toggleTableInput(isDineIn) {
        let group = document.getElementById('tableInputGroup');
        let input = document.getElementById('tableNumber');
        if (isDineIn) {
            group.style.display = 'block';
            input.required = true;
        } else {
            group.style.display = 'none';
            input.required = false;
            input.value = '';
        }
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderCartUI();
    });
</script>
@endsection
