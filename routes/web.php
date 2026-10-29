<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Stencu Coffee Inventory & Sales Management System
|--------------------------------------------------------------------------
*/

// 1. Portal Masuk Utama (Tampilan Bentukan Smartphone Modern)
Route::get('/', [AuthController::class, 'showLogin'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// 2. Katalog Menu & Pemesanan Publik (Tanpa Password)
Route::get('/menu', [PublicController::class, 'menu'])->name('public.menu');
Route::post('/order', [PublicController::class, 'storeOrder'])->name('public.order.store');
Route::get('/order/success/{saleNumber}', [PublicController::class, 'orderSuccess'])->name('public.order.success');
Route::get('/track-order', [PublicController::class, 'trackOrder'])->name('public.order.track');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// 3. Akses Cepat / Switch Peran (1-Click Gateway untuk Evaluasi & Pengujian Dosen)
Route::get('/masuk/{role}', [AuthController::class, 'quickLogin'])->name('quick.login');

// Logout (Mendukung POST dan GET agar fleksibel)
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// 3. Rute Internal Operasional (Owner & Staff Kasir)
Route::middleware(['auth', 'role:owner,staff'])->group(function () {
    // Beralih Stasiun Kerja Staf (Kasir <-> Dapur & Barista)
    Route::get('/switch-station/{station}', [AuthController::class, 'switchStation'])->name('staff.switch-station');

    // Dashboard Operasional
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Katalog Produk & Detail (Staff & Owner)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Transaksi Penjualan / POS Kasir (Stock-out)
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');

    // Transaksi Pembelian Stok (Stock-in)
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show');

    // Melihat Daftar Pemasok & Kategori
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
});

// 4. Rute Khusus Owner / Superadmin (Aksi Sensitif & Hak Eksklusif)
Route::middleware(['auth', 'role:owner'])->group(function () {
    // CRUD Modifikasi Produk
    Route::get('/products-create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // CRUD Kategori
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // CRUD Supplier
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

    // Pembatalan Transaksi dengan Pemulihan Stok (Rollback Konsisten)
    Route::post('/sales/{sale}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
    Route::post('/purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');

    // Kelola Akun Staf / User Management
    Route::resource('users', UserController::class);
});
