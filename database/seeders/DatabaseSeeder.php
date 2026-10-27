<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed database dengan 3 Kategori Simpel & Populer: Minuman, Makanan, Cemilan
     * untuk Warkop Stencu Coffee Batuphat Barat, Lhokseumawe.
     */
    public function run(): void
    {
        // 1. Akun Pengguna (3 Role)
        $owner = User::firstOrCreate(
            ['email' => 'owner@stencu.com'],
            [
                'name' => 'Owner',
                'phone' => '081269001122',
                'role' => 'owner',
                'password' => Hash::make('password123'),
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'kasir@stencu.com'],
            [
                'name' => 'Rian (Kasir)',
                'phone' => '085277889900',
                'role' => 'staff',
                'password' => Hash::make('password123'),
            ]
        );

        $staffDapur = User::firstOrCreate(
            ['email' => 'dapur@stencu.com'],
            [
                'name' => 'Fajar (Barista & Dapur)',
                'phone' => '085211223344',
                'role' => 'staff',
                'password' => Hash::make('password123'),
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'pelanggan@gmail.com'],
            [
                'name' => 'Cut Nabila (Pelanggan Warkop)',
                'phone' => '082133445566',
                'role' => 'customer',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. 3 Kategori Utama (Minuman, Makanan, Cemilan)
        $categoriesData = [
            [
                'name' => 'Minuman',
                'slug' => 'minuman',
                'description' => 'Aneka racikan Kopi Sanger khas Aceh, kopi hitam saring pekat, teh tarik, dan es milo segar.',
            ],
            [
                'name' => 'Makanan',
                'slug' => 'makanan',
                'description' => 'Aneka Mie Aceh bumbu rempah asli, racikan Indomie goreng/kuah warkop, dan nasi goreng gurih.',
            ],
            [
                'name' => 'Cemilan',
                'slug' => 'cemilan',
                'description' => 'Cemilan hangat khas warkop: Roti bakar cokelat keju, pisang goreng crispy, dan aneka gorengan.',
            ],
        ];

        $categoryModels = [];
        foreach ($categoriesData as $cat) {
            $categoryModels[$cat['slug']] = Category::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // 3. Pemasok Bahan Baku Lokal Lhokseumawe
        $supGrosir = Supplier::firstOrCreate(
            ['name' => 'Toko Grosir Berkah Sembako Batuphat'],
            [
                'contact_person' => 'Bang Jamal',
                'phone' => '081377881122',
                'email' => 'berkah.batuphat@gmail.com',
                'address' => 'Jl. Medan - Banda Aceh Km. 260, Batuphat Barat, Muara Satu, Lhokseumawe',
            ]
        );

        $supKopi = Supplier::firstOrCreate(
            ['name' => 'Penggilingan Kopi Tradisional Aceh'],
            [
                'contact_person' => 'Bang Mukhlis',
                'phone' => '085260114455',
                'email' => 'kopi.acehtradisional@gmail.com',
                'address' => 'Sentra Bubuk Kopi Saring, Takengon - Lhokseumawe',
            ]
        );

        // 4. Daftar 12 Menu Pilihan (Fokus, Realistis & Foto Sesuai 100%)
        $products = [
            // --- KATEGORI: MINUMAN (5 Item) ---
            [
                'category_id' => $categoryModels['minuman']->id,
                'sku' => 'STN-MNM-01',
                'name' => 'Kopi Sanger Dingin (Es Sanger)',
                'description' => 'Racikan legendaris Aceh: kopi saring pekat ditarik dipadu susu kental manis dan es batu segar.',
                'unit' => 'gelas',
                'purchase_price' => 3500,
                'selling_price' => 8000,
                'stock' => 50,
                'minimum_stock' => 10,
                'image' => 'images/products/sanger_dingin.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['minuman']->id,
                'sku' => 'STN-MNM-02',
                'name' => 'Kopi Sanger Panas',
                'description' => 'Sanger panas racikan barista dengan buih khas tarikan saring tradisional Aceh yang harum.',
                'unit' => 'cangkir',
                'purchase_price' => 3000,
                'selling_price' => 7000,
                'stock' => 45,
                'minimum_stock' => 10,
                'image' => 'images/products/sanger_panas.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['minuman']->id,
                'sku' => 'STN-MNM-03',
                'name' => 'Kopi Hitam Saring (Kopi O)',
                'description' => 'Seduhan kopi hitam pekat saring khas Aceh dengan aroma harum semerbak dan rasa mantap.',
                'unit' => 'cangkir',
                'purchase_price' => 2000,
                'selling_price' => 5000,
                'stock' => 60,
                'minimum_stock' => 15,
                'image' => 'images/products/kopi_hitam.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['minuman']->id,
                'sku' => 'STN-MNM-04',
                'name' => 'Es Teh Tarik Aceh',
                'description' => 'Teh hitam seduh dipadu susu creamy ditarik berbusa lembut disajikan dingin dengan es batu.',
                'unit' => 'gelas',
                'purchase_price' => 3000,
                'selling_price' => 7000,
                'stock' => 40,
                'minimum_stock' => 10,
                'image' => 'images/products/teh_tarik.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['minuman']->id,
                'sku' => 'STN-MNM-05',
                'name' => 'Es Milo Dingin Cokelat',
                'description' => 'Minuman cokelat malt Milo kental dipadu susu manis dan taburan bubuk Milo melimpah.',
                'unit' => 'gelas',
                'purchase_price' => 4000,
                'selling_price' => 8000,
                'stock' => 35,
                'minimum_stock' => 10,
                'image' => 'images/products/milo.jpg',
                'is_active' => true,
            ],

            // --- KATEGORI: MAKANAN (4 Item) ---
            [
                'category_id' => $categoryModels['makanan']->id,
                'sku' => 'STN-MKN-01',
                'name' => 'Mie Aceh Goreng Telur',
                'description' => 'Mie kuning basah khas Aceh digoreng bumbu rempah pedas gurih, telur, tauge, dan emping renyah.',
                'unit' => 'porsi',
                'purchase_price' => 7000,
                'selling_price' => 14000,
                'stock' => 30,
                'minimum_stock' => 10,
                'image' => 'images/products/mie_aceh_goreng.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['makanan']->id,
                'sku' => 'STN-MKN-02',
                'name' => 'Mie Aceh Kuah Rempah',
                'description' => 'Mie Aceh kuah kental rempah pedas kaya bumbu khas dengan potongan sayur segar dan telur ceplok.',
                'unit' => 'porsi',
                'purchase_price' => 7000,
                'selling_price' => 14000,
                'stock' => 25,
                'minimum_stock' => 10,
                'image' => 'images/products/mie_aceh_kuah.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['makanan']->id,
                'sku' => 'STN-MKN-03',
                'name' => 'Indomie Goreng Telur Ceplok (Internet)',
                'description' => 'Indomie goreng warkop disajikan telur mata sapi setengah matang dan taburan bawang goreng.',
                'unit' => 'porsi',
                'purchase_price' => 5000,
                'selling_price' => 10000,
                'stock' => 40,
                'minimum_stock' => 10,
                'image' => 'images/products/indomie_goreng.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['makanan']->id,
                'sku' => 'STN-MKN-04',
                'name' => 'Nasi Goreng Kampung Aceh',
                'description' => 'Nasi goreng bumbu kampung harum dengan suwiran ayam, telur dadar, kerupuk, dan acar mentimun.',
                'unit' => 'porsi',
                'purchase_price' => 8000,
                'selling_price' => 15000,
                'stock' => 4, // Di bawah minimum_stock (5) untuk demo alert stok menipis di dashboard!
                'minimum_stock' => 5,
                'image' => 'images/products/nasi_goreng.jpg',
                'is_active' => true,
            ],

            // --- KATEGORI: CEMILAN (3 Item) ---
            [
                'category_id' => $categoryModels['cemilan']->id,
                'sku' => 'STN-CML-01',
                'name' => 'Pisang Goreng Crispy Keju',
                'description' => 'Pisang kepok manis digoreng tepung krispi dengan taburan keju cheddar parut dan susu kental manis.',
                'unit' => 'porsi',
                'purchase_price' => 4500,
                'selling_price' => 10000,
                'stock' => 20,
                'minimum_stock' => 5,
                'image' => 'images/products/pisang_goreng.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['cemilan']->id,
                'sku' => 'STN-CML-02',
                'name' => 'Roti Bakar Cokelat Keju',
                'description' => 'Roti tawar tebal dipanggang harum mentega dengan isian meses cokelat dan parutan keju melimpah.',
                'unit' => 'porsi',
                'purchase_price' => 6000,
                'selling_price' => 12000,
                'stock' => 25,
                'minimum_stock' => 5,
                'image' => 'images/products/roti_bakar.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['cemilan']->id,
                'sku' => 'STN-CML-03',
                'name' => 'Gorengan Bakwan & Tahu Panas (Isi 4)',
                'description' => 'Bakwan sayur renyah dan tahu goreng isi disajikan hangat dengan cabai rawit hijau segar.',
                'unit' => 'porsi',
                'purchase_price' => 3000,
                'selling_price' => 6000,
                'stock' => 30,
                'minimum_stock' => 5,
                'image' => 'images/products/gorengan.jpg',
                'is_active' => true,
            ],
        ];

        $productModels = [];
        foreach ($products as $prod) {
            $productModels[$prod['sku']] = Product::updateOrCreate(
                ['sku' => $prod['sku']],
                $prod
            );
        }

        // Hapus produk lama yang tidak ada di daftar 12 menu ini agar database bersih
        $activeSkus = array_column($products, 'sku');
        Product::whereNotIn('sku', $activeSkus)->delete();

        // Hapus kategori lama yang tidak terpakai
        $activeCategoryIds = array_map(fn($c) => $c->id, $categoryModels);
        Category::whereNotIn('id', $activeCategoryIds)->delete();

        // 5. Riwayat Pembelian Awal (Purchase / Stock-In)
        $purchase = Purchase::firstOrCreate(
            ['purchase_number' => 'PO-202610-001'],
            [
                'supplier_id' => $supGrosir->id,
                'user_id' => $staffDapur->id,
                'total' => 250000,
                'status' => 'completed',
                'notes' => 'Pasokan rutin Indomie 1 karton, susu kental manis, dan keju cheddar.',
                'purchased_at' => now()->subDays(2),
            ]
        );

        PurchaseItem::firstOrCreate(
            ['purchase_id' => $purchase->id, 'product_id' => $productModels['STN-MKN-03']->id],
            [
                'quantity' => 30,
                'unit_cost' => 5000,
                'subtotal' => 150000,
            ]
        );

        PurchaseItem::firstOrCreate(
            ['purchase_id' => $purchase->id, 'product_id' => $productModels['STN-MNM-05']->id],
            [
                'quantity' => 25,
                'unit_cost' => 4000,
                'subtotal' => 100000,
            ]
        );

        // 6. Riwayat Penjualan Awal (Sale / Stock-Out)
        $sale = Sale::firstOrCreate(
            ['sale_number' => 'INV-202610-001'],
            [
                'user_id' => $staff->id,
                'customer_name' => 'Cut Nabila [Meja 03]',
                'payment_method' => 'cash',
                'total' => 22000,
                'status' => 'completed',
                'notes' => 'Sanger manis sedang, Mie Aceh pedas sedang.',
                'sold_at' => now()->subHours(2),
            ]
        );

        SaleItem::firstOrCreate(
            ['sale_id' => $sale->id, 'product_id' => $productModels['STN-MNM-01']->id],
            [
                'quantity' => 1,
                'unit_price' => 8000,
                'subtotal' => 8000,
            ]
        );

        SaleItem::firstOrCreate(
            ['sale_id' => $sale->id, 'product_id' => $productModels['STN-MKN-01']->id],
            [
                'quantity' => 1,
                'unit_price' => 14000,
                'subtotal' => 14000,
            ]
        );
    }
}
