<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'unit',
        'purchase_price',
        'selling_price',
        'stock',
        'minimum_stock',
        'image',
        'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi Eloquent: 1 Produk milik 1 Kategori (belongsTo)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: 1 Produk bisa ada di banyak detail penjualan
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Relasi: 1 Produk bisa ada di banyak detail pembelian
     */
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Helper cek apakah stok di bawah batas minimum
     */
    public function isLowStock(): bool
    {
        return $this->stock <= $this->minimum_stock;
    }
}
