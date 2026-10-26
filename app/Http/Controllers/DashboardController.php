<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan Ringkasan Eksekutif & Statistik Operasional Stencu Coffee.
     * Menggunakan query agregat database sesuai Slide 18.
     */
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // 1. Total Produk Aktif
        $totalProducts = Product::count();

        // 2. Total Pemasok
        $totalSuppliers = Supplier::count();

        // 3. Penjualan Hari Ini (hanya transaksi yang selesai / completed)
        $salesTodayTotal = Sale::where('status', 'completed')
            ->whereDate('sold_at', $today)
            ->sum('total');

        $salesTodayCount = Sale::where('status', 'completed')
            ->whereDate('sold_at', $today)
            ->count();

        // 4. Penjualan Bulan Ini
        $salesMonthTotal = Sale::where('status', 'completed')
            ->where('sold_at', '>=', $startOfMonth)
            ->sum('total');

        // 5. Total Seluruh Transaksi Penjualan
        $totalSalesCount = Sale::where('status', 'completed')->count();

        // 6. Produk dengan Stok Menipis (Stok <= Minimum Stock)
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->get();

        // 7. 5 Transaksi Penjualan Terakhir
        $recentSales = Sale::with(['user', 'items.product'])
            ->latest('sold_at')
            ->take(5)
            ->get();

        // 8. 5 Transaksi Pembelian Stok Terakhir
        $recentPurchases = Purchase::with(['supplier', 'user'])
            ->latest('purchased_at')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalSuppliers',
            'salesTodayTotal',
            'salesTodayCount',
            'salesMonthTotal',
            'totalSalesCount',
            'lowStockProducts',
            'recentSales',
            'recentPurchases'
        ));
    }
}
