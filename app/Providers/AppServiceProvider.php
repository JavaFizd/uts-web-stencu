<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gunakan styling pagination Bootstrap 5 yang bersih dan mudah dipahami
        Paginator::useBootstrapFive();

        // 1. Gate: Khusus Owner (Superadmin)
        Gate::define('is-owner', function (User $user) {
            return $user->role === 'owner';
        });

        // 2. Gate: Hak mengelola transaksi penjualan (Owner dan Staff kasir)
        Gate::define('manage-sales', function (User $user) {
            return in_array($user->role, ['owner', 'staff']);
        });

        // 3. Gate: Hak mengelola transaksi pembelian stok (Owner dan Staff)
        Gate::define('manage-purchases', function (User $user) {
            return in_array($user->role, ['owner', 'staff']);
        });

        // 4. Gate: Hak mengubah dan menghapus data master (Hanya Owner)
        Gate::define('manage-master', function (User $user) {
            return $user->role === 'owner';
        });
    }
}
