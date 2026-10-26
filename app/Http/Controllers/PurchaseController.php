<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    /**
     * Tampilkan riwayat pembelian stok masuk (Stock-in).
     */
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'user', 'items.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('purchase_number', 'like', "%{$search}%");
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('purchased_at', '>=', $request->date_from);
        }

        $purchases = $query->latest('purchased_at')->paginate(10)->withQueryString();
        $suppliers = Supplier::all();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    /**
     * Form input nota pembelian bahan / stok masuk dari supplier.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Simpan transaksi pembelian dan tambah stok secara atomik dalam DB::transaction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchased_at' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'supplier_id.required' => 'Pemasok / supplier wajib dipilih.',
            'items.required' => 'Minimal tambahkan satu item barang yang dibeli.',
            'items.*.quantity.min' => 'Kuantitas barang minimal 1.',
            'items.*.unit_cost.min' => 'Harga beli barang tidak boleh bernilai negatif.',
        ]);

        try {
            $purchase = DB::transaction(function () use ($request) {
                $purchase = Purchase::create([
                    'supplier_id' => $request->supplier_id,
                    'user_id' => Auth::id(),
                    'purchase_number' => $this->generatePurchaseNumber(),
                    'total' => 0,
                    'status' => 'completed',
                    'notes' => $request->notes,
                    'purchased_at' => $request->purchased_at,
                ]);

                $grandTotal = 0;

                foreach ($request->items as $itemData) {
                    $qty = (int) $itemData['quantity'];
                    $cost = (float) $itemData['unit_cost'];
                    $subtotal = $qty * $cost;
                    $grandTotal += $subtotal;

                    // Buat detail item nota pembelian
                    PurchaseItem::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $itemData['product_id'],
                        'quantity' => $qty,
                        'unit_cost' => $cost,
                        'subtotal' => $subtotal,
                    ]);

                    // Tambah stok produk yang bersangkutan
                    $product = Product::lockForUpdate()->findOrFail($itemData['product_id']);
                    $product->increment('stock', $qty);

                    // Update harga modal terakhir jika diinginkan
                    $product->update(['purchase_price' => $cost]);
                }

                $purchase->update(['total' => $grandTotal]);

                return $purchase;
            });

            return redirect()->route('purchases.show', $purchase)
                ->with('success', "Nota pembelian #{$purchase->purchase_number} berhasil dicatat dan stok telah bertambah.");

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses pembelian: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail nota pembelian.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product.category']);
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Batalkan Transaksi Pembelian (Hanya Role Owner - Slide 5 & 8).
     * Mengurangi kembali stok produk yang sebelumnya sudah ditambah.
     */
    public function cancel(Purchase $purchase)
    {
        if ($purchase->status === 'cancelled') {
            return back()->with('error', 'Nota pembelian ini sudah dibatalkan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($purchase) {
                // Periksa apakah stok di gudang cukup untuk ditarik kembali
                foreach ($purchase->items as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);
                    if ($product) {
                        if ($product->stock < $item->quantity) {
                            throw new \Exception("Stok produk '{$product->name}' tersisa {$product->stock}, tidak cukup untuk ditarik kembali sejumlah {$item->quantity}.");
                        }
                        $product->decrement('stock', $item->quantity);
                    }
                }

                $purchase->update(['status' => 'cancelled']);
            });

            return redirect()->route('purchases.show', $purchase)
                ->with('success', "Nota pembelian #{$purchase->purchase_number} berhasil dibatalkan dan stok telah disesuaikan kembali.");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan pembelian: ' . $e->getMessage());
        }
    }

    /**
     * Generator nomor pembelian otomatis (Contoh: PO-20261024-0005)
     */
    private function generatePurchaseNumber(): string
    {
        $prefix = 'PO-' . date('Ymd') . '-';
        $todayCount = Purchase::whereDate('created_at', today())->count() + 1;
        return $prefix . str_pad($todayCount, 4, '0', STR_PAD_LEFT);
    }
}
