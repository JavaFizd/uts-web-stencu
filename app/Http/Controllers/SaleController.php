<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    /**
     * Tampilkan riwayat transaksi penjualan (Stock-out) dengan filter & search lengkap.
     * Mendukung filter: Hari, Minggu ke-n, Bulan, Tahun, Rentang Tanggal, & Status.
     */
    public function index(Request $request)
    {
        $query = Sale::with(['user', 'items.product']);

        // 1. Filter Preset Cepat (today, yesterday, this_week, this_month, this_year)
        if ($request->filled('preset')) {
            $today = Carbon::today();
            switch ($request->preset) {
                case 'today':
                    $query->whereDate('sold_at', $today);
                    break;
                case 'yesterday':
                    $query->whereDate('sold_at', Carbon::yesterday());
                    break;
                case 'this_week':
                    $query->whereBetween('sold_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereMonth('sold_at', $today->month)->whereYear('sold_at', $today->year);
                    break;
                case 'this_year':
                    $query->whereYear('sold_at', $today->year);
                    break;
            }
        }

        // 2. Filter Tahun
        if ($request->filled('year')) {
            $query->whereYear('sold_at', $request->year);
        }

        // 3. Filter Bulan (1 - 12)
        if ($request->filled('month')) {
            $query->whereMonth('sold_at', $request->month);
        }

        // 4. Filter Minggu ke- (1: tgl 1-7, 2: tgl 8-14, 3: tgl 15-21, 4: tgl 22-28, 5: tgl 29-31)
        if ($request->filled('week')) {
            $week = (int) $request->week;
            $startDay = (($week - 1) * 7) + 1;
            $endDay = min($week * 7, 31);
            $query->whereBetween(DB::raw('DAY(sold_at)'), [$startDay, $endDay]);
        }

        // 5. Filter Hari dalam Seminggu (0: Senin, 1: Selasa, ..., 6: Minggu)
        if ($request->filled('day_of_week')) {
            $query->where(DB::raw('WEEKDAY(sold_at)'), (int) $request->day_of_week);
        }

        // 6. Filter Tanggal Tertentu (Hari tgl 1 - 31)
        if ($request->filled('day')) {
            $query->whereDay('sold_at', (int) $request->day);
        }

        // 7. Filter Rentang Tanggal Manual (Dari - Sampai)
        if ($request->filled('date_from')) {
            $query->whereDate('sold_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('sold_at', '<=', $request->date_to);
        }

        // 8. Filter Pencarian Keyword (Nomor Nota / Nama Pelanggan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        // 9. Filter Status (completed, cancelled)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 10. Filter Status Dapur (cooking: sedang dimasak, served: sudah diantar)
        if ($request->filled('kitchen_status')) {
            $query->where('kitchen_status', $request->kitchen_status);
        }

        // Hitung agregat hasil filter sebelum paginasi
        $aggregateQuery = clone $query;
        $totalOmset = (clone $aggregateQuery)->where('status', 'completed')->sum('total');
        $totalTransactions = (clone $aggregateQuery)->where('status', 'completed')->count();
        $totalCancelled = (clone $aggregateQuery)->where('status', 'cancelled')->count();
        $countCooking = Sale::where('status', 'completed')->where('kitchen_status', 'cooking')->count();
        $countServed = Sale::where('status', 'completed')->where('kitchen_status', 'served')->count();

        // Ambil daftar tahun yang ada di database untuk opsi dropdown
        $availableYears = Sale::selectRaw('YEAR(sold_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $sales = $query->latest('sold_at')->paginate(15)->withQueryString();

        return view('sales.index', compact(
            'sales',
            'totalOmset',
            'totalTransactions',
            'totalCancelled',
            'countCooking',
            'countServed',
            'availableYears'
        ));
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
            'sold_at' => ['nullable', 'date'],
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
                    'kitchen_status' => 'cooking',
                    'notes' => $request->notes,
                    'sold_at' => $request->filled('sold_at') ? Carbon::parse($request->sold_at) : now(),
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
     * Tandai pesanan sudah selesai diracik dan diantar ke meja pelanggan (Dapur & Kasir).
     */
    public function markServed(Sale $sale)
    {
        $sale->update(['kitchen_status' => 'served']);

        return back()->with('success', "Pesanan #{$sale->sale_number} ({$sale->customer_name}) berhasil ditandai SUDAH DIANTAR ke meja pelanggan!");
    }

    /**
     * Endpoint API Pengecekan Pesanan Baru Realtime (Polling untuk Notifikasi Kasir & Dapur).
     * Mengembalikan data pesanan terbaru jika ada pesanan baru setelah last_id.
     */
    public function checkNewOrders(Request $request)
    {
        $lastId = (int) $request->input('last_id', 0);
        $currentMax = (int) (Sale::max('id') ?? 0);

        // Ambil transaksi baru yang ID-nya lebih besar dari last_id
        $newSales = Sale::with(['items.product'])
            ->where('id', '>', $lastId)
            ->where('status', 'completed')
            ->orderBy('id', 'asc')
            ->get();

        $newCount = $newSales->count();
        $formatted = $newSales->map(fn($s) => $this->formatOrderSummary($s));

        // 5 pesanan terakhir untuk baseline dropdown
        $recentOrders = Sale::with(['items.product'])
            ->where('status', 'completed')
            ->latest('id')
            ->take(5)
            ->get()
            ->map(fn($s) => $this->formatOrderSummary($s));

        return response()->json([
            'new_count' => $newCount,
            'latest_id' => $currentMax,
            'orders' => $formatted,
            'recent_orders' => $recentOrders,
        ]);
    }

    /**
     * Format ringkasan pesanan untuk notifikasi toast & dropdown
     */
    private function formatOrderSummary(Sale $sale): array
    {
        $itemsList = $sale->items->map(function ($it) {
            return ($it->quantity ?? 1) . 'x ' . ($it->product->name ?? 'Menu');
        })->implode(', ');

        return [
            'id' => $sale->id,
            'sale_number' => $sale->sale_number,
            'customer_name' => $sale->customer_name,
            'is_online' => is_null($sale->user_id),
            'total' => (float) $sale->total,
            'total_formatted' => 'Rp ' . number_format($sale->total, 0, ',', '.'),
            'payment_method' => strtoupper($sale->payment_method),
            'items_count' => $sale->items->sum('quantity'),
            'items_summary' => $itemsList,
            'notes' => $sale->notes,
            'time' => Carbon::parse($sale->sold_at)->format('H:i:s'),
            'show_url' => route('sales.show', $sale->id),
        ];
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
