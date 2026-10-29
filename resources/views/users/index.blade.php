@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Manajemen Staf & Pengguna</h3>
        <p class="text-muted mb-0">Kelola akun staf kasir, barista, dan akses peran (Khusus Owner Stencu Coffee).</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-warning fw-bold text-dark">
        <i class="bi bi-person-plus me-1"></i> Tambah Pengguna Baru
    </a>
</div>

<div class="card card-custom bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                    <th class="text-center">Peran (Role)</th>
                    <th>Terdaftar Sejak</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="fw-bold text-dark">
                            {{ $user->name }}
                            @if($user->id === Auth::id())
                                <span class="badge bg-secondary-subtle text-secondary ms-1 small">Anda</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?: '-' }}</td>
                        <td class="text-center">
                            @if($user->role === 'owner')
                                <span class="badge bg-danger">Owner</span>
                            @elseif($user->email === 'dapur@stencu.com')
                                <span class="badge bg-warning text-dark"><i class="bi bi-fire me-1"></i>Dapur &amp; Barista</span>
                            @elseif($user->role === 'staff')
                                <span class="badge bg-primary"><i class="bi bi-calculator me-1"></i>Kasir</span>
                            @else
                                <span class="badge bg-secondary">Pelanggan</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning" title="Edit Akun">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($user->id !== Auth::id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top d-flex justify-content-end">
        {{ $users->links() }}
    </div>
</div>
@endsection
