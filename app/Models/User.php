<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role', // 'owner', 'staff', 'customer'
        'password',
    ];

    /**
     * Atribut yang disembunyikan dalam serialisasi array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe casting kolom database.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Helper pengecekan role pengguna
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Stasiun penugasan aktif untuk staf (Kasir vs Dapur & Barista).
     */
    public function getActiveStationAttribute(): string
    {
        if (session()->has('staff_station')) {
            return session('staff_station');
        }
        if ($this->email === 'dapur@stencu.com') {
            return 'dapur';
        }
        return 'kasir';
    }

    public function isDapurStation(): bool
    {
        return $this->isStaff() && $this->active_station === 'dapur';
    }

    public function isKasirStation(): bool
    {
        return $this->isStaff() && $this->active_station === 'kasir';
    }

    /**
     * Relasi: Satu User dapat mencatat banyak transaksi penjualan.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Relasi: Satu User dapat mencatat banyak pembelian dari supplier.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
