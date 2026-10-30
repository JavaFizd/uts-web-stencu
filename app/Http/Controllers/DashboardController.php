<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan Ringkasan Eksekutif & Statistik Operasional Stencu Coffee.
     * Khusus Owner: Dilengkapi Rekap Periode (Hari, Minggu, Bulan, Tahun),
     * Grafik Tren Omset vs Pengeluaran, Menu Terlaris, dan Kontrol Stok Kritis.
     */
    public function index(Request $request)
    {
        // Proteksi Hak Akses (Slide 5): Staff diarahkan ke modul operasional masing-masing
        if (Auth::user()->isStaff()) {
            if (Auth::user()->email === 'dapur@stencu.com') {
                return redirect()->route('sales.index');
            }
            return redirect()->route('sales.create');
        }

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        // 1. Total Master Data
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $totalStaff = User::where('role', 'staff')->count();

        // 2. Rekap Pemasukan Penjualan (Hanya Status Completed)
        $salesTodayTotal = Sale::where('status', 'completed')->whereDate('sold_at', $today)->sum('total');
        $salesTodayCount = Sale::where('status', 'completed')->whereDate('sold_at', $today)->count();

        $salesWeekTotal = Sale::where('status', 'completed')->where('sold_at', '>=', $startOfWeek)->sum('total');
        $salesMonthTotal = Sale::where('status', 'completed')->where('sold_at', '>=', $startOfMonth)->sum('total');
        $salesYearTotal = Sale::where('status', 'completed')->where('sold_at', '>=', $startOfYear)->sum('total');
        $salesAllTimeTotal = Sale::where('status', 'completed')->sum('total');
        $totalSalesCount = Sale::where('status', 'completed')->count();

        // 3. Rekap Pengeluaran (Pembelian Stok Bahan + Biaya Operasional Kas)
        $purchasesTodayTotal = Purchase::where('status', 'completed')->whereDate('purchased_at', $today)->sum('total');
        $purchasesWeekTotal = Purchase::where('status', 'completed')->where('purchased_at', '>=', $startOfWeek)->sum('total');
        $purchasesMonthTotal = Purchase::where('status', 'completed')->where('purchased_at', '>=', $startOfMonth)->sum('total');
        $purchasesYearTotal = Purchase::where('status', 'completed')->where('purchased_at', '>=', $startOfYear)->sum('total');
        $purchasesAllTimeTotal = Purchase::where('status', 'completed')->sum('total');

        $expensesTodayTotal = Expense::whereDate('expense_date', $today)->sum('amount');
        $expensesWeekTotal = Expense::where('expense_date', '>=', $startOfWeek)->sum('amount');
        $expensesMonthTotal = Expense::where('expense_date', '>=', $startOfMonth)->sum('amount');
        $expensesYearTotal = Expense::where('expense_date', '>=', $startOfYear)->sum('amount');
        $expensesAllTimeTotal = Expense::sum('amount');

        // Total Gabungan Pengeluaran (Bahan Baku + Biaya Operasional)
        $totalPengeluaranToday = $purchasesTodayTotal + $expensesTodayTotal;
        $totalPengeluaranMonth = $purchasesMonthTotal + $expensesMonthTotal;
        $totalPengeluaranYear = $purchasesYearTotal + $expensesYearTotal;
        $totalPengeluaranAllTime = $purchasesAllTimeTotal + $expensesAllTimeTotal;

        // 4. Perhitungan Laba Bersih (Net Profit = Pemasukan - Seluruh Pengeluaran)
        $profitToday = $salesTodayTotal - $totalPengeluaranToday;
        $profitMonth = $salesMonthTotal - $totalPengeluaranMonth;
        $profitYear = $salesYearTotal - $totalPengeluaranYear;
        $profitAllTime = $salesAllTimeTotal - $totalPengeluaranAllTime;

        // 5. Produk dengan Stok Menipis (Stok <= Minimum Stock)
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock', 'asc')
            ->get();

        // 6. 5 Menu Terlaris (Top 5 Best Seller)
        $topProducts = SaleItem::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_revenue'))
            ->whereHas('sale', fn($q) => $q->where('status', 'completed'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product.category')
            ->take(5)
            ->get();

        // 7. Data Grafik 7 Hari Terakhir (Chart.js)
        $chartLabels = [];
        $chartSalesData = [];
        $chartPurchasesData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $label = $date->format('d M');

            $daySale = Sale::where('status', 'completed')->whereDate('sold_at', $dateStr)->sum('total');
            $dayPurchase = Purchase::where('status', 'completed')->whereDate('purchased_at', $dateStr)->sum('total');
            $dayExpense = Expense::whereDate('expense_date', $dateStr)->sum('amount');

            $chartLabels[] = $label;
            $chartSalesData[] = (int) $daySale;
            $chartPurchasesData[] = (int) ($dayPurchase + $dayExpense);
        }

        // 8. 5 Transaksi Penjualan Terakhir
        $recentSales = Sale::with(['user', 'items.product'])
            ->latest('sold_at')
            ->take(5)
            ->get();

        // 9. 5 Pengeluaran & Pembelian Terakhir
        $recentPurchases = Purchase::with(['supplier', 'user'])
            ->latest('purchased_at')
            ->take(5)
            ->get();

        $recentExpenses = Expense::with('user')
            ->latest('expense_date')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalSuppliers',
            'totalStaff',
            'salesTodayTotal',
            'salesTodayCount',
            'salesWeekTotal',
            'salesMonthTotal',
            'salesYearTotal',
            'salesAllTimeTotal',
            'purchasesTodayTotal',
            'purchasesMonthTotal',
            'purchasesAllTimeTotal',
            'expensesTodayTotal',
            'expensesMonthTotal',
            'expensesAllTimeTotal',
            'totalPengeluaranToday',
            'totalPengeluaranMonth',
            'totalPengeluaranYear',
            'totalPengeluaranAllTime',
            'profitToday',
            'profitMonth',
            'profitYear',
            'profitAllTime',
            'totalSalesCount',
            'lowStockProducts',
            'topProducts',
            'chartLabels',
            'chartSalesData',
            'chartPurchasesData',
            'recentSales',
            'recentPurchases',
            'recentExpenses'
        ));
    }
}
