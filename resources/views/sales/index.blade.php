@extends('layouts.app')

@section('title', Auth::user()->isDapurStation() ? 'Antrean Pesanan Dapur & Barista' : 'Riwayat Transaksi Penjualan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        @if(Auth::user()->isDapurStation())
            <h3 class="fw-bold mb-1" style="color: #ea580c;">
                <i class="bi bi-fire text-warning me-1"></i> Antrean Pesanan Dapur &amp; Barista
            </h3>
            <p class="text-muted mb-0 small">Monitor tiket pesanan masuk untuk diracik (Kopi Sanger, Es Milo, Mie Aceh, &amp; Aneka Gorengan).</p>
        @else
            <h3 class="fw-bold mb-1" style="color: #0f2b5c;">Riwayat Transaksi Penjualan (POS)</h3>
            <p class="text-muted mb-0 small">Laporan kasir, pesanan pelanggan, dan mutasi stok keluar (Stock-out).</p>
        @endif
    </div>
    <div class="d-flex gap-2 flex-wrap no-print">
        @if(Auth::user()->isDapurStation())
            <button type="button" class="btn btn-warning btn-sm fw-bold rounded-pill px-3 shadow-sm text-dark" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise me-1"></i> Segarkan Antrean
            </button>
            <a href="{{ route('staff.switch-station', 'kasir') }}" class="btn btn-outline-primary btn-sm fw-semibold rounded-pill px-3 shadow-sm">
                <i class="bi bi-calculator me-1"></i> Buka Layar Kasir
            </a>
        @else
            <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold rounded-pill px-3 shadow-sm" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Rekap
            </button>
            <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm fw-bold rounded-pill px-3 shadow-sm" style="background-color: #2563eb; border: none;">
                <i class="bi bi-cart-plus me-1"></i> Buka Kasir POS
            </a>
        @endif
    </div>
</div>

@if(Auth::user()->isDapurStation())
    <!-- Banner Khusus Stasiun Dapur & Barista -->
    <div class="alert alert-warning border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2 text-dark no-print" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border-left: 5px solid #f97316 !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 rounded-circle bg-warning bg-opacity-25 text-warning d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                <i class="bi bi-cup-hot-fill fs-4 text-warning"></i>
            </div>
            <div>
                <div class="fw-bold mb-0" style="color: #9a3412;">STASIUN TUGAS AKTIF: DAPUR &amp; BARISTA</div>
                <div class="small text-muted" style="font-size: 0.78rem;">Tampilan dioptimalkan untuk melihat tiket pesanan makanan dan racikan kopi secara langsung.</div>
            </div>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">
                <i class="bi bi-broadcast me-1"></i> Live Kitchen Display
            </span>
        </div>
    </div>
@endif

<!-- Banner Realtime Pesanan Baru Masuk (Muncul saat Polling Mendeteksi Pesanan Baru) -->
<div id="liveKdsOrderBanner" class="alert alert-success border-0 rounded-4 shadow p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2 text-dark" style="display: none; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-left: 5px solid #10b981 !important; animation: slideDown 0.3s ease;">
    <div class="d-flex align-items-center gap-3">
        <div class="p-2 rounded-circle bg-success bg-opacity-25 text-success d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
            <i class="bi bi-bell-fill fs-4 text-success"></i>
        </div>
        <div>
            <div class="fw-bold mb-0 text-success fs-6">🔔 ADA PESANAN BARU MASUK DARI PELANGGAN!</div>
            <div class="small text-muted" style="font-size: 0.78rem;">Antrean baru telah dicatat di sistem. Klik tombol di kanan untuk memuat tiket racikan terbaru.</div>
        </div>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <button type="button" class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise me-1"></i> Segarkan Antrean Sekarang
        </button>
    </div>
</div>

@if(Auth::user()->isDapurStation())
    <!-- ========================================================================= -->
    <!-- TAMPILAN KHUSUS DAPUR & BARISTA: PAPAN TIKET PESANAN RACIKAN (KDS)        -->
    <!-- ========================================================================= -->
    <div class="row g-3 mb-4">
        @forelse($sales as $sale)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden" style="border-top: 5px solid {{ $sale->status === 'completed' ? '#f59e0b' : '#dc2626' }} !important; background: #ffffff;">
                    <div class="card-header bg-white py-3 px-3.5 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill" style="font-size: 0.72rem;">
                                <i class="bi bi-clock me-1"></i>{{ $sale->sold_at->format('H:i') }} WIB
                            </span>
                            <span class="text-muted small ms-1" style="font-size: 0.7rem;">
                                {{ $sale->sold_at->diffForHumans() }}
                            </span>
                        </div>
                        <span class="badge bg-light text-primary border fw-bold" style="font-size: 0.75rem;">
                            {{ $sale->sale_number }}
                        </span>
                    </div>

                    <div class="card-body p-3.5">
                        <!-- Info Meja & Pemesan -->
                        <div class="mb-3 p-2.5 rounded-3 bg-light border">
                            <div class="text-muted small fw-semibold" style="font-size: 0.68rem; text-transform: uppercase;">Pemesan / Meja:</div>
                            <div class="fw-bold text-dark fs-6">{{ $sale->customer_name }}</div>
                            @if($sale->notes)
                                <div class="mt-1 text-danger small fst-italic">
                                    <i class="bi bi-chat-dots-fill me-1"></i> "{{ $sale->notes }}"
                                </div>
                            @endif
                        </div>

                        <!-- Daftar Racikan Menu -->
                        <div class="mb-2">
                            <div class="text-muted small fw-bold mb-2" style="font-size: 0.7rem; text-transform: uppercase;">
                                <i class="bi bi-fire text-warning me-1"></i> Menu yang Harus Diracik ({{ $sale->items->sum('quantity') }} Item):
                            </div>
                            <ul class="list-group list-group-flush border rounded-3 overflow-hidden">
                                @foreach($sale->items as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2 px-2.5">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary rounded-circle p-1 fs-6 text-white" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">
                                                {{ $item->quantity }}
                                            </span>
                                            <span class="fw-bold text-dark small">{{ $item->product->name ?? 'Menu' }}</span>
                                        </div>
                                        <span class="badge bg-light text-secondary border small" style="font-size: 0.65rem;">
                                            {{ $item->product->category->name ?? 'Menu' }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="card-footer bg-light p-2.5 d-flex justify-content-between align-items-center border-top">
                        <span class="small text-muted" style="font-size: 0.72rem;">
                            Metode: <strong class="text-dark">{{ strtoupper($sale->payment_method) }}</strong>
                        </span>
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-warning rounded-pill px-3 fw-bold text-dark shadow-xs" style="font-size: 0.75rem;">
                            <i class="bi bi-receipt-cutoff me-1"></i> Buka Tiket
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-check2-circle text-success fs-1 d-block mb-2"></i>
                <h5 class="fw-bold text-dark">Tidak Ada Antrean Masak Saat Ini</h5>
                <p class="text-muted small">Semua pesanan pelanggan sudah selesai diracik.</p>
            </div>
        @endforelse
    </div>

    <!-- Paginasi Antrean Dapur -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="small text-muted">Menampilkan antrean pesanan warkop Stencu</div>
        {{ $sales->links() }}
    </div>

@else
    <!-- ========================================================================= -->
    <!-- TAMPILAN UNTUK KASIR & OWNER: LAPORAN TABEL RIWAYAT TRANSAKSI PENJUALAN   -->
    <!-- ========================================================================= -->
    <!-- 1. Tombol Filter Preset Cepat (Quick Filter Pills) -->
    <div class="d-flex gap-1.5 flex-wrap align-items-center mb-3 no-print">
        <span class="text-muted small fw-bold me-2"><i class="bi bi-clock-history me-1"></i> Periode Cepat:</span>
        <a href="{{ route('sales.index') }}" class="btn btn-sm rounded-pill {{ !request()->hasAny(['preset', 'year', 'month', 'week', 'day_of_week', 'day', 'date_from', 'date_to']) ? 'btn-dark fw-bold' : 'btn-outline-secondary' }}" style="font-size: 0.78rem;">
            Semua
        </a>
        <a href="{{ route('sales.index', ['preset' => 'today']) }}" class="btn btn-sm rounded-pill {{ request('preset') === 'today' ? 'btn-primary fw-bold text-white' : 'btn-outline-secondary' }}" style="font-size: 0.78rem;">
            Hari Ini
        </a>
        <a href="{{ route('sales.index', ['preset' => 'yesterday']) }}" class="btn btn-sm rounded-pill {{ request('preset') === 'yesterday' ? 'btn-primary fw-bold text-white' : 'btn-outline-secondary' }}" style="font-size: 0.78rem;">
            Kemarin
        </a>
        <a href="{{ route('sales.index', ['preset' => 'this_week']) }}" class="btn btn-sm rounded-pill {{ request('preset') === 'this_week' ? 'btn-primary fw-bold text-white' : 'btn-outline-secondary' }}" style="font-size: 0.78rem;">
            Minggu Ini
        </a>
        <a href="{{ route('sales.index', ['preset' => 'this_month']) }}" class="btn btn-sm rounded-pill {{ request('preset') === 'this_month' ? 'btn-primary fw-bold text-white' : 'btn-outline-secondary' }}" style="font-size: 0.78rem;">
            Bulan Ini ({{ date('M') }})
        </a>
        <a href="{{ route('sales.index', ['preset' => 'this_year']) }}" class="btn btn-sm rounded-pill {{ request('preset') === 'this_year' ? 'btn-primary fw-bold text-white' : 'btn-outline-secondary' }}" style="font-size: 0.78rem;">
            Tahun Ini ({{ date('Y') }})
        </a>
    </div>

    <!-- 2. Form Filter Rinci (Tahun, Bulan, Minggu Ke-, Hari, Status, Keyword) -->
    <div class="card card-custom p-3 bg-white mb-4 shadow-sm border no-print">
        <form action="{{ route('sales.index') }}" method="GET" class="row g-2 align-items-center">
            <!-- Filter Tahun -->
            <div class="col-sm-6 col-md-2">
                <label class="form-label small fw-bold text-muted mb-1" style="font-size: 0.72rem;">TAHUN</label>
                <select name="year" class="form-select form-select-sm fw-semibold">
                    <option value="">-- Semua Tahun --</option>
                    @foreach($availableYears as $yr)
                        <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Bulan -->
            <div class="col-sm-6 col-md-2">
                <label class="form-label small fw-bold text-muted mb-1" style="font-size: 0.72rem;">BULAN</label>
                <select name="month" class="form-select form-select-sm fw-semibold">
                    <option value="">-- Semua Bulan --</option>
                    @php
                        $months = [
                            1 => '01 - Januari', 2 => '02 - Februari', 3 => '03 - Maret',
                            4 => '04 - April', 5 => '05 - Mei', 6 => '06 - Juni',
                            7 => '07 - Juli', 8 => '08 - Agustus', 9 => '09 - September',
                            10 => '10 - Oktober', 11 => '11 - November', 12 => '12 - Desember'
                        ];
                    @endphp
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ request('month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Minggu ke- dalam Bulan -->
            <div class="col-sm-6 col-md-2">
                <label class="form-label small fw-bold text-muted mb-1" style="font-size: 0.72rem;">MINGGU KE-</label>
                <select name="week" class="form-select form-select-sm">
                    <option value="">-- Semua Minggu --</option>
                    <option value="1" {{ request('week') == '1' ? 'selected' : '' }}>Minggu 1 (Tgl 1-7)</option>
                    <option value="2" {{ request('week') == '2' ? 'selected' : '' }}>Minggu 2 (Tgl 8-14)</option>
                    <option value="3" {{ request('week') == '3' ? 'selected' : '' }}>Minggu 3 (Tgl 15-21)</option>
                    <option value="4" {{ request('week') == '4' ? 'selected' : '' }}>Minggu 4 (Tgl 22-28)</option>
                    <option value="5" {{ request('week') == '5' ? 'selected' : '' }}>Minggu 5 (Tgl 29-Akhir)</option>
                </select>
            </div>

            <!-- Filter Hari (Senin s/d Minggu) -->
            <div class="col-sm-6 col-md-2">
                <label class="form-label small fw-bold text-muted mb-1" style="font-size: 0.72rem;">HARI APA</label>
                <select name="day_of_week" class="form-select form-select-sm">
                    <option value="">-- Semua Hari --</option>
                    <option value="0" {{ request('day_of_week') === '0' ? 'selected' : '' }}>Senin</option>
                    <option value="1" {{ request('day_of_week') === '1' ? 'selected' : '' }}>Selasa</option>
                    <option value="2" {{ request('day_of_week') === '2' ? 'selected' : '' }}>Rabu</option>
                    <option value="3" {{ request('day_of_week') === '3' ? 'selected' : '' }}>Kamis</option>
                    <option value="4" {{ request('day_of_week') === '4' ? 'selected' : '' }}>Jumat</option>
                    <option value="5" {{ request('day_of_week') === '5' ? 'selected' : '' }}>Sabtu</option>
                    <option value="6" {{ request('day_of_week') === '6' ? 'selected' : '' }}>Minggu</option>
                </select>
            </div>

            <!-- Filter Status Transaksi -->
            <div class="col-sm-6 col-md-2">
                <label class="form-label small fw-bold text-muted mb-1" style="font-size: 0.72rem;">STATUS NOTA</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Search Keyword & Tombol Aksi -->
            <div class="col-sm-6 col-md-2">
                <label class="form-label small fw-bold text-muted mb-1" style="font-size: 0.72rem;">CARI NOTA / NAMA</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="No. Nota / Nama..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary" title="Terapkan Filter" style="background-color: #2563eb; border: none;">
                        <i class="bi bi-search"></i>
                    </button>
                    @if(request()->hasAny(['preset', 'year', 'month', 'week', 'day_of_week', 'day', 'date_from', 'date_to', 'search', 'status']))
                        <a href="{{ route('sales.index') }}" class="btn btn-outline-danger" title="Reset Filter">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- 3. Ringkasan Omset Hasil Filter (KPI Strip) -->
    <div class="card card-custom p-3 bg-white mb-4 shadow-sm border" style="border-left: 4px solid #2563eb !important;">
        <div class="row align-items-center g-3">
            <div class="col-md-5">
                <span class="text-muted small fw-bold d-block" style="font-size: 0.72rem;">TOTAL OMSET PENJUALAN PERIODE INI</span>
                <h4 class="fw-bold text-primary mb-0 mt-1" style="color: #1d4ed8 !important;">
                    Rp {{ number_format($totalOmset, 0, ',', '.') }}
                </h4>
                <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                    @if(request('preset'))
                        Periode: <strong>{{ ucfirst(str_replace('_', ' ', request('preset'))) }}</strong>
                    @elseif(request('year') || request('month') || request('week') || request('day_of_week'))
                        Periode: 
                        @if(request('year')) <strong>Thn {{ request('year') }}</strong> @endif
                        @if(request('month')) &bull; <strong>Bln {{ $months[request('month')] ?? request('month') }}</strong> @endif
                        @if(request('week')) &bull; <strong>Minggu {{ request('week') }}</strong> @endif
                        @if(request('day_of_week') !== null && request('day_of_week') !== '') &bull; <strong>Hari {{ ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'][request('day_of_week')] ?? '' }}</strong> @endif
                    @else
                        Periode: <strong>Semua Riwayat Transaksi</strong>
                    @endif
                </div>
            </div>
            <div class="col-md-7">
                <div class="d-flex justify-content-md-end gap-3 flex-wrap">
                    <div class="px-3 py-2 bg-light rounded-3 text-center border">
                        <div class="h5 fw-bold text-success mb-0">{{ number_format($totalTransactions) }}</div>
                        <span class="text-muted" style="font-size: 0.72rem;">Nota Selesai</span>
                    </div>
                    <div class="px-3 py-2 bg-light rounded-3 text-center border">
                        <div class="h5 fw-bold text-danger mb-0">{{ number_format($totalCancelled) }}</div>
                        <span class="text-muted" style="font-size: 0.72rem;">Nota Dibatalkan</span>
                    </div>
                    <div class="px-3 py-2 bg-light rounded-3 text-center border">
                        <div class="h5 fw-bold text-dark mb-0">
                            Rp {{ $totalTransactions > 0 ? number_format($totalOmset / $totalTransactions, 0, ',', '.') : 0 }}
                        </div>
                        <span class="text-muted" style="font-size: 0.72rem;">Rata-rata / Nota</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Tabel Transaksi Penjualan -->
    <div class="card card-custom bg-white shadow-sm border">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>No. Nota</th>
                        <th>Waktu / Tanggal</th>
                        <th>Hari</th>
                        <th>Nama Pelanggan / Meja</th>
                        <th>Kasir</th>
                        <th>Metode Bayar</th>
                        <th class="text-end">Total Tagihan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center no-print" style="width: 110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td class="fw-bold font-monospace text-primary">
                                <a href="{{ route('sales.show', $sale) }}" class="text-decoration-none">
                                    {{ $sale->sale_number }}
                                </a>
                            </td>
                            <td class="small text-muted">{{ $sale->sold_at->format('d M Y, H:i') }}</td>
                            <td>
                                <span class="badge bg-light text-dark border small">
                                    {{ ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'][$sale->sold_at->dayOfWeek] }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark small">{{ $sale->customer_name }}</div>
                                @if($sale->notes)
                                    <div class="text-muted fst-italic mt-0.5" style="font-size: 0.72rem;">
                                        "{{ $sale->notes }}"
                                    </div>
                                @endif
                            </td>
                            <td class="small text-muted">{{ $sale->user->name ?? 'Kasir' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border text-uppercase">{{ $sale->payment_method }}</span>
                            </td>
                            <td class="text-end fw-bold fs-6">
                                Rp {{ number_format($sale->total, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($sale->status === 'completed')
                                    <span class="badge bg-success-subtle text-success small fw-bold px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger small fw-bold px-2 py-1">
                                        <i class="bi bi-x-circle me-1"></i> Batal
                                    </span>
                                @endif
                            </td>
                            <td class="text-center no-print">
                                <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-0.5 fw-semibold" style="font-size: 0.75rem;" title="Lihat Struk">
                                    <i class="bi bi-receipt"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted small">
                                <i class="bi bi-inbox fs-2 d-block mb-1 text-muted"></i>
                                Tidak ada transaksi penjualan yang cocok dengan filter yang dipilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="small text-muted">
                Menampilkan {{ $sales->firstItem() ?? 0 }} - {{ $sales->lastItem() ?? 0 }} dari {{ $sales->total() }} transaksi
            </div>
            <div>
                {{ $sales->links() }}
            </div>
        </div>
    </div>
@endif
@endsection