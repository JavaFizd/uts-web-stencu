<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'sale_number',
        'payment_method', // 'cash', 'qris', 'transfer'
        'total',
        'status', // 'completed', 'cancelled'
        'notes',
        'sold_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'sold_at' => 'datetime',
    ];

    /**
     * Relasi: Penjualan dilayani oleh 1 Kasir/User (belongsTo)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: 1 Transaksi Penjualan memiliki banyak item (hasMany)
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
