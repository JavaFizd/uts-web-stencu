@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" class="text-decoration-none small text-muted">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Manajemen Pengguna
    </a>
    <h3 class="fw-bold mt-2">Edit Pengguna: {{ $user->name }}</h3>
</div>

<div class="card card-custom bg-white p-4" style="max-width: 600px;">
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label small fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Alamat Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Nomor Telepon / WhatsApp</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Peran Akun (Role) <span class="text-danger">*</span></label>
            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff (Kasir & Barista)</option>
                <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner (Superadmin)</option>
                <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer (Pelanggan)</option>
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold">Kata Sandi Baru (Kosongkan jika tidak ingin diubah)</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-warning fw-bold text-dark px-4">
                <i class="bi bi-save me-1"></i> Perbarui Data
            </button>
        </div>
    </form>
</div>
@endsection
