@extends('layouts.public')

@section('title', 'Cek Status Nota Pesanan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Lacak Status Nota Pesanan Anda</h3>
                <p class="text-muted small">Masukkan nomor nota penjualan Anda untuk melihat rincian dan status pesanan.</p>
            </div>

            <!-- Form Cari Nota -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <form action="{{ route('public.order.track') }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="sale_number" class="form-control border-start-0 py-2 font-monospace" placeholder="Contoh: INV-20261024-0001" value="{{ request('sale_number') }}" required>
                        <button type="submit" class="btn btn-primary fw-bold px-4" style="background-color: #2563eb; border: none;">Cek Nota</button>
                    </div>
                </form>
            </div>

            <!-- Hasil Pencarian Nota -->
            @if(request()->filled('sale_number'))
                @if($sale)
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
                            <div>
                                <span class="small text-muted d-block">Nomor Nota:</span>
                                <span class="fw-bold fs-5 font-monospace text-primary">{{ $sale->sale_number }}</span>
                            </div>
                            <div>
                                @if($sale->status === 'completed')
                                    <span class="badge bg-success-subtle text-success fs-6 fw-bold">DITERIMA</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger fs-6 fw-bold">DIBATALKAN</span>
                                @endif
                            </div>
                        </div>

                        <div class="row g-2 small mb-3">
                            <div class="col-6">
                                <span class="text-muted d-block">Pemesan:</span>
                                <span class="fw-bold">{{ $sale->customer_name }}</span>
                            </div>
                            <div class="col-6 text-end">
                                <span class="text-muted d-block">Waktu Transaksi:</span>
                                <span>{{ $sale->sold_at->format('d/m/Y H:i') }} WIB</span>
                            </div>
                        </div>

                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered align-middle mb-0">
                                <thead class="table-light small">
                                    <tr>
                                        <th>Menu</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->items as $item)
                                        <tr>
                                            <td class="small fw-semibold">{{ $item->product->name ?? 'Menu' }}</td>
                                            <td class="text-center small">{{ $item->quantity }}</td>
                                            <td class="text-end small">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold">Total:</td>
                                        <td class="text-end fw-bold text-success">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="text-end">
                            <a href="{{ route('public.order.success', $sale->sale_number) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                Lihat Struk Lengkap <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 text-center">
                        <i class="bi bi-exclamation-circle fs-2 text-warning d-block mb-2"></i>
                        <h6 class="fw-bold">Nota Tidak Ditemukan</h6>
                        <p class="small text-muted mb-0">Pastikan nomor nota yang Anda masukkan sesuai, contoh: <strong>INV-20261024-0001</strong>.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
