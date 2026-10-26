@extends('layouts.app')

@section('title', 'Catat Pembelian Stok Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('purchases.index') }}" class="text-decoration-none small text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Pembelian
    </a>
    <h3 class="fw-bold mt-2">Catat Pembelian Bahan Baku & Biji Kopi (Stock-In)</h3>
    <p class="text-muted">Masukkan nota pembelian dari supplier. Stok produk di sistem akan bertambah secara otomatis.</p>
</div>

<form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
    @csrf

    <div class="card card-custom p-4 bg-white mb-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-text me-1 text-primary"></i> Data Nota Pembelian</h5>
        
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label small fw-bold">Pilih Mitra Pemasok (Supplier) <span class="text-danger">*</span></label>
                <select name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                            {{ $sup->name }} ({{ $sup->contact_person ?? 'Tanpa PIC' }})
                        </option>
                    @endforeach
                </select>
                @error('supplier_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-bold">Tanggal Pembelian <span class="text-danger">*</span></label>
                <input type="date" name="purchased_at" class="form-control @error('purchased_at') is-invalid @enderror" value="{{ old('purchased_at', date('Y-m-d')) }}" required>
                @error('purchased_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
                <label class="form-label small fw-bold">Catatan Pembelian (Opsional)</label>
                <input type="text" name="notes" class="form-control" placeholder="Contoh: Biji kopi Gayo panen baru, packaging 500 pcs..." value="{{ old('notes') }}">
            </div>
        </div>
    </div>

    <!-- Multi-Item Produk yang Dibeli -->
    <div class="card card-custom p-4 bg-white mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-box-seam me-1 text-success"></i> Rincian Produk yang Masuk</h5>
            <button type="button" class="btn btn-outline-primary btn-sm fw-bold" onclick="addPurchaseRow()">
                <i class="bi bi-plus-circle me-1"></i> Tambah Baris Produk
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0" id="purchaseTable">
                <thead class="table-light small">
                    <tr>
                        <th style="min-width: 250px;">Produk Kopi / Bahan <span class="text-danger">*</span></th>
                        <th style="width: 140px;">Kuantitas Masuk <span class="text-danger">*</span></th>
                        <th style="width: 180px;">Harga Beli Satuan (Rp) <span class="text-danger">*</span></th>
                        <th style="width: 180px;" class="text-end">Subtotal</th>
                        <th style="width: 50px;"></th>
                    </tr>
                </thead>
                <tbody id="purchaseTableBody">
                    <!-- Baris pertama default -->
                    <tr class="purchase-row">
                        <td>
                            <select name="items[0][product_id]" class="form-select product-select" required onchange="onProductSelect(this)">
                                <option value="">-- Pilih Produk --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" data-cost="{{ $p->purchase_price }}">
                                        {{ $p->name }} (SKU: {{ $p->sku }}, Stok Saat Ini: {{ $p->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[0][quantity]" class="form-control qty-input" min="1" value="1" required oninput="calculateSubtotal(this)">
                        </td>
                        <td>
                            <input type="number" name="items[0][unit_cost]" class="form-control cost-input" min="0" step="0.01" value="0" required oninput="calculateSubtotal(this)">
                        </td>
                        <td class="text-end fw-bold subtotal-display">Rp 0</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removePurchaseRow(this)" disabled>
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold fs-6">TOTAL PEMBELIAN:</td>
                        <td class="text-end fw-bold fs-5 text-primary" id="grandPurchaseTotal">Rp 0</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
        <button type="submit" class="btn btn-primary fw-bold px-4">
            <i class="bi bi-save me-1"></i> Simpan Pembelian & Tambah Stok
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
    let rowIndex = 1;
    const productsData = @json($products);

    function addPurchaseRow() {
        const tbody = document.getElementById('purchaseTableBody');
        const tr = document.createElement('tr');
        tr.className = 'purchase-row';

        let options = '<option value="">-- Pilih Produk --</option>';
        productsData.forEach(p => {
            options += `<option value="${p.id}" data-cost="${p.purchase_price}">${p.name} (SKU: ${p.sku}, Stok: ${p.stock})</option>`;
        });

        tr.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="form-select product-select" required onchange="onProductSelect(this)">
                    ${options}
                </select>
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][quantity]" class="form-control qty-input" min="1" value="1" required oninput="calculateSubtotal(this)">
            </td>
            <td>
                <input type="number" name="items[${rowIndex}][unit_cost]" class="form-control cost-input" min="0" step="0.01" value="0" required oninput="calculateSubtotal(this)">
            </td>
            <td class="text-end fw-bold subtotal-display">Rp 0</td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removePurchaseRow(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        rowIndex++;
        updateRowDeleteButtons();
    }

    function removePurchaseRow(btn) {
        const row = btn.closest('tr');
        row.remove();
        updateRowDeleteButtons();
        updateGrandTotal();
    }

    function updateRowDeleteButtons() {
        const rows = document.querySelectorAll('.purchase-row');
        rows.forEach(r => {
            const btn = r.querySelector('.btn-outline-danger');
            btn.disabled = (rows.length === 1);
        });
    }

    function onProductSelect(select) {
        const selectedOption = select.options[select.selectedIndex];
        const cost = selectedOption.getAttribute('data-cost') || 0;
        const row = select.closest('tr');
        const costInput = row.querySelector('.cost-input');
        costInput.value = cost;
        calculateSubtotal(costInput);
    }

    function calculateSubtotal(input) {
        const row = input.closest('tr');
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
        const subtotal = qty * cost;
        row.querySelector('.subtotal-display').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.purchase-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const cost = parseFloat(row.querySelector('.cost-input').value) || 0;
            total += (qty * cost);
        });
        document.getElementById('grandPurchaseTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
</script>
@endsection
