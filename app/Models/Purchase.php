<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'user_id',
        'purchase_number',
        'total',
        'status', // 'completed', 'cancelled'
        'notes',
        'purchased_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    /**
     * Relasi: Pembelian dilakukan ke 1 Supplier (belongsTo)
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relasi: Pembelian dicatat oleh 1 User/Staff (belongsTo)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: 1 Nota Pembelian memiliki banyak item produk (hasMany)
     */
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
