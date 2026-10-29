@extends('layouts.public')

@section('title', 'Cek Status Nota Pesanan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="text-center mb-4">
                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill fw-semibold mb-2" style="font-size: 0.8rem;">
                    <i class="bi bi-search me-1"></i> Pencarian Fleksibel
                </span>
                <h3 class="fw-bold" style="color: #0f2b5c;">Cek Status Pesanan Anda</h3>
                <p class="text-muted small">
                    Tidak perlu repot mengingat kode rumit! Cukup ketik <strong>Nama Anda</strong> atau <strong>Nomor Nota</strong> Anda.
                </p>
            </div>

            <!-- Form Cari Nota / Nama -->
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white mb-4">
                <form action="{{ route('public.order.track') }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person-search fs-5"></i></span>
                        <input type="text" name="keyword" class="form-control border-start-0 py-2.5" placeholder="Ketik nama Anda (misal: Fajar) atau nomor nota (misal: 2915)" value="{{ $keyword ?? request('sale_number') }}" required>
                        <button type="submit" class="btn btn-primary fw-bold px-4" style="background-color: #2563eb; border: none;">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                    </div>
                </form>
                <div class="d-flex justify-content-between align-items-center mt-2 px-1 text-muted" style="font-size: 0.75rem;">
                    <span>Bisa cari pakai: <strong>Nama</strong>, <strong>No. Meja</strong>, atau <strong>Angka Nota</strong></span>
                    <a href="{{ route('public.menu') }}" class="text-decoration-none text-primary fw-semibold">Pesan Baru &rarr;</a>
                </div>
            </div>

            <!-- Hasil Pencarian -->
            @if(!empty($keyword))
                @if(isset($sales) && $sales->count() > 1)
                    <!-- Jika Ditemukan Lebih Dari 1 Pesanan (Contoh: mencari dengan nama yang punya beberapa riwayat) -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
                            <h6 class="fw-bold mb-0 text-dark">
                                Ditemukan {{ $sales->count() }} Pesanan untuk "<span class="text-primary">{{ $keyword }}</span>"
                            </h6>
                            <span class="small text-muted">Pilih pesanan Anda:</span>
                        </div>

                        <div class="d-flex flex-column gap-2">
                            @foreach($sales as $itemSale)
                                <div class="p-3 rounded-3 bg-light border d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <strong class="font-monospace text-primary fs-6">{{ $itemSale->sale_number }}</strong>
                                            @if($itemSale->status === 'completed')
                                                <span class="badge bg-success-subtle text-success small">Selesai</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger small">Batal</span>
                                            @endif
                                        </div>
                                        <div class="fw-semibold text-dark small">{{ $itemSale->customer_name }}</div>
                                        <div class="text-muted" style="font-size: 0.74rem;">
                                            <i class="bi bi-clock me-1"></i>{{ $itemSale->sold_at->format('d/m/Y H:i') }} WIB &bull; {{ $itemSale->items->count() }} Menu
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success mb-1">Rp {{ number_format($itemSale->total, 0, ',', '.') }}</div>
                                        <a href="{{ route('public.order.success', $itemSale->sale_number) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                            Buka Nota <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif(isset($sales) && $sales->count() === 1)
                    <!-- Jika Ditemukan Tepat 1 Pesanan -->
                    @php $singleSale = $sales->first(); @endphp
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
                            <div>
                                <span class="small text-muted d-block">Nomor Nota:</span>
                                <span class="fw-bold fs-5 font-monospace text-primary">{{ $singleSale->sale_number }}</span>
                                <span class="badge bg-light text-muted border ms-1">#{{ $singleSale->id }}</span>
                            </div>
                            <div>
                                @if($singleSale->status === 'cancelled')
                                    <span class="badge bg-danger-subtle text-danger fs-6 fw-bold">DIBATALKAN</span>
                                @elseif($singleSale->isServed())
                                    <span class="badge bg-success text-white fs-6 fw-bold px-3 py-1.5 rounded-pill shadow-xs">
                                        <i class="bi bi-check2-circle me-1"></i> SUDAH DIANTAR KE MEJA
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark fs-6 fw-bold px-3 py-1.5 rounded-pill shadow-xs">
                                        <i class="bi bi-fire me-1"></i> SEDANG DIRACIK DI DAPUR
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Banner Info Status Realtime -->
                        @if($singleSale->status === 'completed')
                            @if($singleSale->isServed())
                                <div class="alert alert-success py-2.5 px-3 rounded-3 small mb-3 border-0 d-flex align-items-center gap-2" style="background: #ecfdf5; color: #065f46;">
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    <div><strong>Pesanan Selesai!</strong> Makanan &amp; minuman sudah siap dan telah diantar ke meja Anda. Selamat menikmati sajian Stencu Coffee!</div>
                                </div>
                            @else
                                <div class="alert alert-warning py-2.5 px-3 rounded-3 small mb-3 border-0 d-flex align-items-center gap-2" style="background: #fffbeb; color: #92400e;">
                                    <i class="bi bi-hourglass-split text-warning fs-5"></i>
                                    <div><strong>Pesanan Sedang Diproses!</strong> Barista &amp; Dapur sedang meracik kopi dan memasak pesanan Anda. Mohon ditunggu di meja ya!</div>
                                </div>
                            @endif
                        @endif

                        <div class="row g-2 small mb-3">
                            <div class="col-sm-6">
                                <span class="text-muted d-block">Nama Pemesan:</span>
                                <strong class="text-dark fs-6">{{ $singleSale->customer_name }}</strong>
                            </div>
                            <div class="col-sm-6 text-sm-end">
                                <span class="text-muted d-block">Waktu Transaksi:</span>
                                <span>{{ $singleSale->sold_at->format('d/m/Y H:i') }} WIB</span>
                            </div>
                        </div>

                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <thead class="table-light small">
                                    <tr>
                                        <th>Menu</th>
                                        <th class="text-center" style="width: 70px;">Qty</th>
                                        <th class="text-end" style="width: 120px;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($singleSale->items as $item)
                                        <tr>
                                            <td class="small fw-semibold">{{ $item->product->name ?? 'Menu' }}</td>
                                            <td class="text-center small">{{ $item->quantity }}</td>
                                            <td class="text-end small">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold">Total Tagihan:</td>
                                        <td class="text-end fw-bold text-success fs-6">Rp {{ number_format($singleSale->total, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top">
                            <span class="badge bg-light text-dark border">
                                Metode: <strong class="text-uppercase">{{ $singleSale->payment_method }}</strong>
                            </span>
                            <a href="{{ route('public.order.success', $singleSale->sale_number) }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" style="background-color: #2563eb; border: none;">
                                <i class="bi bi-printer me-1"></i> Buka Nota &amp; Struk Lengkap
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Jika Tidak Ditemukan -->
                    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 text-center">
                        <i class="bi bi-exclamation-circle fs-2 text-warning d-block mb-2"></i>
                        <h6 class="fw-bold">Pesanan Tidak Ditemukan</h6>
                        <p class="small text-muted mb-2">
                            Tidak ada nota yang cocok dengan pencarian "<strong>{{ $keyword }}</strong>".
                        </p>
                        <div class="small text-muted">
                            Tips: Coba ketik <strong>Nama Anda</strong> saat memesan (misal: <em>Fajar</em>) atau angka nota (misal: <em>2916</em>).
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection