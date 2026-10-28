<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    /**
     * Tampilkan riwayat transaksi penjualan (Stock-out) dengan filter & search.
     */
    public function index(Request $request)
    {
        $query = Sale::with(['user', 'items.product']);

        // 1. Filter Pencarian Nomor Nota / Nama Pelanggan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        // 2. Filter Tanggal Transaksi
        if ($request->filled('date_from')) {
            $query->whereDate('sold_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sold_at', '<=', $request->date_to);
        }

        // 3. Filter Status (completed, cancelled)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sales = $query->latest('sold_at')->paginate(10)->withQueryString();

        return view('sales.index', compact('sales'));
    }

    /**
     * Antarmuka Kasir POS (Point of Sale) untuk memilih menu Stencu Coffee.
     */
    public function create()
    {
        // Ambil semua produk aktif yang memiliki stok > 0
        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('products'));
    }

    /**
     * Simpan transaksi penjualan kasir (POS) sesuai Business Rules Slide 8 & 16:
     * 1. DB::transaction untuk atomisitas.
     * 2. Product::lockForUpdate() untuk mencegah race condition.
     * 3. Validasi ketersediaan stok & tolak overselling.
     * 4. Perhitungan harga total murni di server (bukan dari input browser).
     * 5. Pengurangan stok hanya jika seluruh transaksi sukses.
     */
    public function store(Request $request)
    {
        // 1. Validasi format request awal
        $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'payment_method' => ['required', 'in:cash,qris,transfer'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'customer_name.required' => 'Nama pelanggan wajib diisi.',
            'items.required' => 'Keranjang pesanan tidak boleh kosong.',
            'items.*.quantity.min' => 'Jumlah pembelian minimal 1.',
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                // Buat Header Penjualan terlebih dahulu
                $sale = Sale::create([
                    'user_id' => Auth::id(),
                    'customer_name' => $request->customer_name,
                    'sale_number' => $this->generateSaleNumber(),
                    'payment_method' => $request->payment_method,
                    'total' => 0, // Akan dihitung dan diakumulasikan di bawah
                    'status' => 'completed',
                    'notes' => $request->notes,
                    'sold_at' => now(),
                ]);

                $grandTotal = 0;

                // Loop setiap item pesanan dalam keranjang
                foreach ($request->items as $itemData) {
                    $qty = (int) $itemData['quantity'];

                    // PENTING (Slide 16): lockForUpdate() mengunci row database untuk mencegah race condition
                    $product = Product::lockForUpdate()->findOrFail($itemData['product_id']);

                    // Validasi stok di server: Tolak jika kuantitas melebihi stok yang ada
                    if ($qty < 1 || $product->stock < $qty) {
                        throw ValidationException::withMessages([
                            'items' => "Stok untuk produk '{$product->name}' tidak mencukupi! Tersedia: {$product->stock}, diminta: {$qty}.",
                        ]);
                    }

                    // Ambil harga asli dari database (tidak mempercayai input frontend)
                    $unitPrice = $product->selling_price;
                    $subtotal = $unitPrice * $qty;
                    $grandTotal += $subtotal;

                    // Buat detail item penjualan
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

                // Update total penjualan di header dengan perhitungan server
                $sale->update(['total' => $grandTotal]);

                return $sale;
            });

            return redirect()->route('sales.show', $sale)
                ->with('success', "Transaksi penjualan #{$sale->sale_number} berhasil diproses!");

        } catch (ValidationException $e) {
            // Lempar error validasi agar pesan muncul di form Blade
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail nota / struk penjualan.
     */
    public function show(Sale $sale)
    {
        $sale->load(['user', 'items.product.category']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Batalkan Transaksi Penjualan & Pulihkan Stok (Hanya Role Owner - Slide 5 & 8).
     */
    public function cancel(Request $request, Sale $sale)
    {
        // Pastikan hanya transaksi yang berstatus 'completed' yang bisa dibatalkan
        if ($sale->status === 'cancelled') {
            return back()->with('error', 'Transaksi ini sudah dibatalkan sebelumnya.');
        }

        DB::transaction(function () use ($sale) {
            // Kembalikan seluruh stok produk yang ada di sale_items
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            // Ubah status nota penjualan menjadi cancelled
            $sale->update(['status' => 'cancelled']);
        });

        return redirect()->route('sales.show', $sale)
            ->with('success', "Transaksi #{$sale->sale_number} berhasil dibatalkan dan seluruh stok produk telah dipulihkan!");
    }

    /**
     * Generator nomor faktur penjualan unik simpel (Contoh: NOTA-0001)
     */
    private function generateSaleNumber(): string
    {
        $nextId = (Sale::max('id') ?? 0) + 1;
        return sprintf('NOTA-%04d', $nextId);
    }
}
