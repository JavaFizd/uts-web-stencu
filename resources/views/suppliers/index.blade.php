@extends('layouts.app')

@section('title', 'Daftar Pemasok')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Mitra Pemasok (Suppliers)</h3>
        <p class="text-muted mb-0">Kelola data petani kopi, roastery partner (Takengon/Bener Meriah), dan distributor susu.</p>
    </div>
    @if(Auth::user()->isOwner())
        <button type="button" class="btn btn-warning fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Supplier Baru
        </button>
    @endif
</div>

<div class="card card-custom bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Supplier</th>
                    <th>Kontak Person</th>
                    <th>No. Telepon / WA</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th class="text-center">Riwayat PO</th>
                    @if(Auth::user()->isOwner())
                        <th class="text-center" style="width: 130px;">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                    <tr>
                        <td class="fw-bold text-dark">{{ $supplier->name }}</td>
                        <td>{{ $supplier->contact_person ?: '-' }}</td>
                        <td>
                            @if($supplier->phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $supplier->phone) }}" target="_blank" class="text-decoration-none">
                                    <i class="bi bi-whatsapp text-success me-1"></i> {{ $supplier->phone }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="small text-muted">{{ $supplier->email ?: '-' }}</td>
                        <td class="small text-muted">{{ Str::limit($supplier->address, 40) ?: '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1">
                                {{ $supplier->purchases_count }} Transaksi
                            </span>
                        </td>
                        @if(Auth::user()->isOwner())
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editSup{{ $supplier->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data supplier ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- Modal Edit Supplier -->
                                <div class="modal fade text-start" id="editSup{{ $supplier->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">Edit Pemasok</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Nama Pemasok / Usaha</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Kontak Person (PIC)</label>
                                                        <input type="text" name="contact_person" class="form-control" value="{{ $supplier->contact_person }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Nomor Telepon / WhatsApp</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Alamat Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold">Alamat Lengkap</label>
                                                        <textarea name="address" class="form-control" rows="2">{{ $supplier->address }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning fw-bold text-dark">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada pemasok yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Supplier -->
@if(Auth::user()->isOwner())
<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Mitra Pemasok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Pemasok / Usaha <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Koperasi Kopi Bener Meriah" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kontak Person (PIC)</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="Nama penanggung jawab...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@supplier.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Alamat gudang / perkebunan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark">Simpan Pemasok</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
