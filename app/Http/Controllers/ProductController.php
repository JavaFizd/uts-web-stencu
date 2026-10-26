<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar master produk dengan filter, search, dan pagination.
     */
    public function index(Request $request)
    {
        $categories = Category::all();

        // Gunakan Eager Loading (with) untuk mencegah masalah N+1 Query
        $query = Product::with('category');

        // 1. Filter Pencarian SKU atau Nama Produk
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // 2. Filter Berdasarkan Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 3. Filter Khusus Stok Menipis (Low Stock Alert)
        if ($request->filled('filter') && $request->filter === 'low_stock') {
            $query->whereColumn('stock', '<=', 'minimum_stock');
        }

        // 4. Paginasi dengan mempertahankan query string filter
        $products = $query->latest()->paginate(10)->withQueryString();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Form tambah produk baru.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Simpan produk baru ke database dengan validasi ketat (Slide 15).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'unit' => ['required', 'string', 'max:20'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'sku.required' => 'SKU produk wajib diisi.',
            'sku.unique' => 'SKU ini sudah digunakan oleh produk lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'purchase_price.min' => 'Harga modal tidak boleh bernilai negatif.',
            'selling_price.min' => 'Harga jual tidak boleh bernilai negatif.',
            'stock.min' => 'Stok awal tidak boleh negatif.',
            'minimum_stock.min' => 'Batas minimum stok tidak boleh negatif.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', "Produk {$validated['name']} berhasil ditambahkan.");
    }

    /**
     * Tampilkan detail produk.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'saleItems.sale', 'purchaseItems.purchase']);
        return view('products.show', compact('product'));
    }

    /**
     * Form edit produk.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Perbarui data produk.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku,' . $product->id],
            'name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'unit' => ['required', 'string', 'max:20'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'minimum_stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', "Data produk {$product->name} berhasil diperbarui.");
    }

    /**
     * Hapus produk (Menggunakan Soft Deletes agar integritas transaksi terjaga - Slide 8).
     */
    public function destroy(Product $product)
    {
        // Cek jika produk sudah memiliki relasi di riwayat transaksi
        $hasSales = $product->saleItems()->exists();
        $hasPurchases = $product->purchaseItems()->exists();

        if ($hasSales || $hasPurchases) {
            // Gunakan soft delete agar histori transaksi tetap terjaga
            $product->delete();
            return redirect()->route('products.index')
                ->with('success', "Produk {$product->name} telah dinonaktifkan (soft delete) untuk melindungi riwayat transaksi.");
        }

        // Jika produk baru dan belum ada transaksi sama sekali, boleh di-force delete
        $product->forceDelete();

        return redirect()->route('products.index')
            ->with('success', "Produk {$product->name} berhasil dihapus permanen.");
    }
}
