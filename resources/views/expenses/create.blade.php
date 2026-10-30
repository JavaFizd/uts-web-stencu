@extends('layouts.app')

@section('title', 'Catat Pengeluaran Kas Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('expenses.index') }}" class="text-decoration-none small text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat Pengeluaran
    </a>
    <h3 class="fw-bold mt-2" style="color: #0f2b5c;">
        <i class="bi bi-wallet2 text-danger me-1"></i> Catat Pengeluaran Kas (Uang Keluar)
    </h3>
    <p class="text-muted small">Catat uang kas keluar untuk keperluan operasional warkop harian.</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4 bg-white shadow-sm border">
            <!-- Pilihan Cepat Kebutuhan Warkop (Quick Fill Chips) -->
            <div class="mb-4 p-3 bg-light rounded-3 border">
                <span class="small fw-bold text-muted d-block mb-2">
                    <i class="bi bi-lightning-charge-fill text-warning me-1"></i> Klik Contoh Pengeluaran Cepat (Opsional):
                </span>
                <div class="d-flex flex-wrap gap-1.5">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5 small" onclick="quickFill('Beli Es Batu Kristal (2 Karung)', 'Operasional Harian', 25000)">
                        🧊 Es Batu (Rp 25.000)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5 small" onclick="quickFill('Isi Ulang Tabung Gas LPG 3kg', 'Gas & Listrik', 22000)">
                        🔥 Gas LPG 3kg (Rp 22.000)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5 small" onclick="quickFill('Isi Ulang Galon Air Mineral (3 Galon)', 'Operasional Harian', 15000)">
                        💧 Air Galon (Rp 15.000)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5 small" onclick="quickFill('Beli Cup Plastik & Sedotan', 'Perlengkapan & Kemasan', 45000)">
                        🥤 Cup & Sedotan (Rp 45.000)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-2.5 small" onclick="quickFill('Token Listrik PLN Warkop', 'Gas & Listrik', 100000)">
                        ⚡ Token Listrik (Rp 100.000)
                    </button>
                </div>
            </div>

            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf

                <!-- Keperluan / Nama Biaya -->
                <div class="mb-3">
                    <label class="form-label small fw-bold">Keperluan / Nama Biaya <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="titleInput" class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Beli Es Batu Kristal 2 Karung" value="{{ old('title') }}" required autofocus>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <!-- Kategori Pengeluaran -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Kategori Pengeluaran <span class="text-danger">*</span></label>
                        <select name="category" id="categorySelect" class="form-select @error('category') is-invalid @enderror" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Pengeluaran -->
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Nominal Pengeluaran -->
                <div class="mb-3">
                    <label class="form-label small fw-bold">Nominal Uang Keluar (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text fw-bold">Rp</span>
                        <input type="number" name="amount" id="amountInput" min="1" step="500" class="form-control fs-5 fw-bold text-danger @error('amount') is-invalid @enderror" placeholder="0" value="{{ old('amount') }}" required>
                    </div>
                    @error('amount')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Catatan Tambahan (Opsional) -->
                <div class="mb-4">
                    <label class="form-label small fw-bold">Catatan Tambahan (Opsional)</label>
                    <input type="text" name="notes" class="form-control" placeholder="Contoh: Dibeli di warung sebelah, nota fisik disimpan di laci kasir" value="{{ old('notes') }}">
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-danger px-4 py-2 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Simpan Pengeluaran Kas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function quickFill(title, category, amount) {
        document.getElementById('titleInput').value = title;
        document.getElementById('categorySelect').value = category;
        document.getElementById('amountInput').value = amount;
    }
</script>
@endsection
