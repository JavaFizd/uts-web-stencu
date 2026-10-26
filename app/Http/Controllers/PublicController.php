<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PublicController extends Controller
{
    /**
     * Halaman depan / Landing Page profil Stencu Coffee Batuphat Barat.
     */
    public function index()
    {
        // Ambil produk unggulan untuk ditampilkan di hero section
        $featuredProducts = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->take(6)
            ->get();

        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        return view('public.landing', compact('featuredProducts', 'categories'));
    }

    /**
     * Katalog Menu Publik Stencu Coffee dengan keranjang belanja online realtime.
     */
    public function menu(Request $request)
    {
        $categories = Category::all();

        // Query produk aktif
        $query = Product::with('category')->where('is_active', true);

        // Filter berdasarkan kategori jika ada
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Pencarian nama atau SKU produk
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Ambil produk dengan paginasi (24 item agar semua menu tampil rapi di satu halaman)
        $products = $query->orderBy('category_id')->orderBy('name')->paginate(24)->withQueryString();

        return view('public.menu', compact('products', 'categories'));
    }

    /**
     * Proses Pemesanan Online Mandiri dari Pelanggan (Tanpa Perlu WhatsApp).
     * Memotong stok langsung di database secara atomik.
     */
    public function storeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'order_type' => ['required', 'in:dine_in,takeaway'],
            'table_number' => ['nullable', 'string', 'max:20'],
            'payment_method' => ['required', 'in:cash,qris,transfer'],
            'notes' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ], [
            'customer_name.required' => 'Mohon masukkan nama pemesan.',
            'items.required' => 'Keranjang pesanan masih kosong.',
            'items.*.quantity.min' => 'Jumlah pesanan minimal 1.',
        ]);

        // Jika Dine-in, wajibkan nomor meja agar barista tahu kemana pesanan diantar
        if ($request->order_type === 'dine_in' && empty($request->table_number)) {
            return back()->withInput()->with('error', 'Mohon isi nomor meja tempat Anda duduk.');
        }

        try {
            $sale = DB::transaction(function () use ($request) {
                // Tentukan keterangan pemesan (Nama + Tipe)
                $typeLabel = $request->order_type === 'dine_in' 
                    ? "Meja " . $request->table_number 
                    : "Bungkus (Takeaway)";
                $formattedName = "{$request->customer_name} [{$typeLabel}]";

                $sale = Sale::create([
                    'user_id' => Auth::check() ? Auth::id() : null,
                    'customer_name' => $formattedName,
                    'sale_number' => $this->generateSaleNumber(),
                    'payment_method' => $request->payment_method,
                    'total' => 0,
                    'status' => 'completed',
                    'notes' => $request->notes,
                    'sold_at' => now(),
                ]);

                $grandTotal = 0;

                foreach ($request->items as $itemData) {
                    $qty = (int) $itemData['quantity'];
                    $product = Product::lockForUpdate()->findOrFail($itemData['product_id']);

                    // Validasi kecukupan stok saat ini
                    if ($qty < 1 || $product->stock < $qty) {
                        throw ValidationException::withMessages([
                            'items' => "Maaf, stok '{$product->name}' tidak mencukupi untuk jumlah yang Anda pesan (Tersisa: {$product->stock}).",
                        ]);
                    }

                    $unitPrice = $product->selling_price;
                    $subtotal = $unitPrice * $qty;
                    $grandTotal += $subtotal;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ]);

                    // Potong stok produk di database
                    $product->decrement('stock', $qty);
                }

                $sale->update(['total' => $grandTotal]);

                return $sale;
            });

            return redirect()->route('public.order.success', $sale->sale_number)
                ->with('success', 'Pesanan Anda berhasil dikirim ke Barista Stencu Coffee!');

        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kendala saat memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Struk & Bukti Pesanan Online Pelanggan.
     */
    public function orderSuccess($saleNumber)
    {
        $sale = Sale::with(['items.product.category'])
            ->where('sale_number', $saleNumber)
            ->firstOrFail();

        return view('public.order_success', compact('sale'));
    }

    /**
     * Pelacakan pesanan pelanggan berdasarkan Nomor Nota.
     */
    public function trackOrder(Request $request)
    {
        $sale = null;
        if ($request->filled('sale_number')) {
            $sale = Sale::with(['items.product.category'])
                ->where('sale_number', trim($request->sale_number))
                ->first();
        }

        return view('public.track_order', compact('sale'));
    }

    /**
     * Generator nomor nota unik
     */
    private function generateSaleNumber(): string
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $todayCount = Sale::whereDate('created_at', today())->count() + 1;
        return $prefix . str_pad($todayCount, 4, '0', STR_PAD_LEFT);
    }
}
