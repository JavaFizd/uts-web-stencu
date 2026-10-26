<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Relasi Eloquent: 1 Kategori memiliki banyak Produk (hasMany)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
