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

    <!-- 3 Kategori Utama Pelanggan (Minuman, Makanan, Cemilan) -->
    <div class="row g-2 g-md-3 mb-4">
        <!-- Tab 1: Semua Menu -->
        <div class="col-6 col-md-3">
            <a href="{{ route('public.menu') }}" class="card text-decoration-none h-100 p-3 rounded-4 border shadow-sm text-center {{ !request('category') ? 'text-white' : 'bg-white text-dark' }}" style="{{ !request('category') ? 'background-color: #0f2b5c !important; border-color: #0f2b5c;' : '' }}">
                <div class="fs-3 mb-1 {{ !request('category') ? 'text-info' : 'text-primary' }}"><i class="bi bi-grid-fill"></i></div>
                <div class="fw-bold fs-6">Semua Menu</div>
                <span class="small {{ !request('category') ? 'text-light opacity-75' : 'text-muted' }}" style="font-size: 0.76rem;">12 Menu Warkop</span>
            </a>
        </div>
        <!-- Tab 2: Minuman -->
        <div class="col-6 col-md-3">
            <a href="{{ route('public.menu', ['category' => 'minuman']) }}" class="card text-decoration-none h-100 p-3 rounded-4 border shadow-sm text-center {{ request('category') == 'minuman' ? 'text-white' : 'bg-white text-dark' }}" style="{{ request('category') == 'minuman' ? 'background-color: #2563eb !important; border-color: #2563eb;' : '' }}">
                <div class="fs-3 mb-1 {{ request('category') == 'minuman' ? 'text-warning' : 'text-primary' }}"><i class="bi bi-cup-hot-fill"></i></div>
                <div class="fw-bold fs-6">Minuman</div>
                <span class="small {{ request('category') == 'minuman' ? 'text-light opacity-75' : 'text-muted' }}" style="font-size: 0.76rem;">Sanger, Teh Tarik &amp; Milo</span>
            </a>
        </div>
        <!-- Tab 3: Makanan -->
        <div class="col-6 col-md-3">
            <a href="{{ route('public.menu', ['category' => 'makanan']) }}" class="card text-decoration-none h-100 p-3 rounded-4 border shadow-sm text-center {{ request('category') == 'makanan' ? 'text-white' : 'bg-white text-dark' }}" style="{{ request('category') == 'makanan' ? 'background-color: #dc2626 !important; border-color: #dc2626;' : '' }}">
                <div class="fs-3 mb-1 {{ request('category') == 'makanan' ? 'text-warning' : 'text-danger' }}"><i class="bi bi-fire"></i></div>
                <div class="fw-bold fs-6">Makanan</div>
                <span class="small {{ request('category') == 'makanan' ? 'text-light opacity-75' : 'text-muted' }}" style="font-size: 0.76rem;">Mie Aceh, Indomie &amp; Nasi</span>
            </a>
        </div>
        <!-- Tab 4: Cemilan -->
        <div class="col-6 col-md-3">
            <a href="{{ route('public.menu', ['category' => 'cemilan']) }}" class="card text-decoration-none h-100 p-3 rounded-4 border shadow-sm text-center {{ request('category') == 'cemilan' ? 'text-white' : 'bg-white text-dark' }}" style="{{ request('category') == 'cemilan' ? 'background-color: #059669 !important; border-color: #059669;' : '' }}">
                <div class="fs-3 mb-1 {{ request('category') == 'cemilan' ? 'text-warning' : 'text-success' }}"><i class="bi bi-basket2-fill"></i></div>
                <div class="fw-bold fs-6">Cemilan</div>
                <span class="small {{ request('category') == 'cemilan' ? 'text-light opacity-75' : 'text-muted' }}" style="font-size: 0.76rem;">Pisang Keju, Roti &amp; Bakwan</span>
            </a>
        </div>
    </div>

    <!-- Search Form Bar -->
    <form action="{{ route('public.menu') }}" method="GET" class="mb-4">
        @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        <div class="input-group bg-white rounded-pill shadow-sm p-1 border">
            <span class="input-group-text bg-transparent border-0 text-primary ps-3"><i class="bi bi-search"></i></span>
            <input type="text" name="search" class="form-control border-0 bg-transparent py-2" placeholder="Cari kopi sanger, mie aceh, pisang goreng, indomie..." value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('public.menu', ['category' => request('category')]) }}" class="btn btn-link text-muted pe-2" title="Reset cari"><i class="bi bi-x-circle-fill"></i></a>
            @endif
            <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill" style="background-color: #2563eb; border: none;">Cari</button>
        </div>
    </form>

    <!-- Product Grid -->
    <div class="row g-3 g-md-4 mb-5">
        @forelse($products as $product)
            <div class="col-sm-6 col-lg-4">
                <div class="card card-product h-100 p-3 d-flex flex-column" id="card-product-{{ $product->id }}" style="transition: all 0.2s ease-in-out;">
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
                                <label class="form-label small fw-bold">Pilih Nomor Meja Anda <span class="text-danger">*</span></label>
                                <select name="table_number" id="tableNumber" class="form-select fw-semibold text-dark">
                                    <option value="01">Meja 01 (Area Utama)</option>
                                    <option value="02">Meja 02 (Area Utama)</option>
                                    <option value="03" selected>Meja 03 (Dekat Barista)</option>
                                    <option value="04">Meja 04 (Area Tengah)</option>
                                    <option value="05">Meja 05 (Samping Colokan)</option>
                                    <option value="06">Meja 06 (Lesehan / Santai)</option>
                                    <option value="07">Meja 07 (Area Luar / Smoking)</option>
                                    <option value="08">Meja 08 (Area Luar / Smoking)</option>
                                </select>
                                <div class="form-text small text-muted" style="font-size: 0.75rem;">Barista akan mengantar pesanan langsung ke meja yang Anda pilih.</div>
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
                                <label class="form-label small fw-bold">Catatan Tambahan Pesanan (Opsional)</label>
                                <textarea name="notes" rows="2" class="form-control form-control-sm" placeholder="Ketik catatan di sini (misal: kopinya jangan banyak gula, mienya jangan pedas ya bang)"></textarea>
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

@section('scripts')
<script>
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
            listHtml = '<div class="text-center py-4 text-muted small"><i class="bi bi-cart3 fs-3 d-block mb-1 text-muted"></i>Keranjang Anda masih kosong. Silakan pilih menu di atas.</div>';
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
                            <button type="button" class="btn btn-link text-danger btn-sm p-0 ms-1" onclick="removeItem(${item.id})" title="Hapus"><i class="bi bi-trash"></i></button>
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
        document.getElementById('modalItemsCount').innerText = keys.length + ' Menu (' + totalItems + ' Porsi)';
        document.getElementById('modalGrandTotal').innerText = 'Rp ' + formatRupiah(grandTotal);
        document.getElementById('navCartCount').innerText = totalItems;
        document.getElementById('floatItemCount').innerText = keys.length + ' Menu Dipilih (' + totalItems + ' Porsi)';
        document.getElementById('floatTotalAmount').innerText = 'Rp ' + formatRupiah(grandTotal);

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

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function escapeJs(str) {
        return str.replace(/'/g, "\\'");
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderCartUI();
    });
</script>
@endsection
