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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed database aplikasi Stencu Coffee.
     * Mengisi akun pengguna (Owner, Kasir, Dapur, Pelanggan),
     * master data 3 kategori & 12 menu ikonik warkop Aceh,
     * serta simulasi transaksi 1 TAHUN PENUH (Arus Kas, Laba Wajar, Best Sellers, & Stok).
     */
    public function run(): void
    {
        // 1. Akun Pengguna (Sistem Multi-User Stencu Coffee)
        $owner = User::firstOrCreate(
            ['email' => 'admin@stencu.com'],
            [
                'name' => 'Owner (Admin)',
                'phone' => '081269001122',
                'role' => 'owner',
                'password' => Hash::make('password'),
            ]
        );

        $staffKasir = User::firstOrCreate(
            ['email' => 'kasir@stencu.com'],
            [
                'name' => 'Kasir',
                'phone' => '085277889900',
                'role' => 'staff',
                'password' => Hash::make('password'),
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'pelanggan@gmail.com'],
            [
                'name' => 'Cut Nabila',
                'phone' => '082133445566',
                'role' => 'customer',
                'password' => Hash::make('password'),
            ]
        );
            [
                'name' => 'Cut Nabila (Pelanggan Warkop)',
                'phone' => '082133445566',
                'role' => 'customer',
                'password' => Hash::make('password'),
            ]
        );

        // 2. 3 Kategori Utama
        $categoriesData = [
            [
                'name' => 'Minuman',
                'slug' => 'minuman',
                'description' => 'Aneka racikan Kopi Sanger khas Aceh, kopi hitam saring pekat, teh tarik, dan es milo segar.',
            ],
            [
                'name' => 'Makanan',
                'slug' => 'makanan',
                'description' => 'Aneka Mie Aceh bumbu rempah asli, racikan mie warkop, dan nasi goreng kampung gurih.',
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

        // 4. Daftar 12 Menu Ikonik dengan Harga Wajar Warkop Aceh (Margin Laba ~50%)
        $products = [
            // --- KATEGORI: MINUMAN (5 Item) ---
            [
                'category_id' => $categoryModels['minuman']->id,
                'sku' => 'STN-MNM-01',
                'name' => 'Kopi Sanger Dingin (Es Sanger)',
                'description' => 'Racikan legendaris Aceh: kopi saring pekat ditarik dipadu susu kental manis dan es batu segar.',
                'unit' => 'gelas',
                'purchase_price' => 4000,
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
                'purchase_price' => 3500,
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
                'purchase_price' => 2500,
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
                'purchase_price' => 3500,
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
                'purchase_price' => 8000,
                'selling_price' => 15000, // Rp 15.000 Sesuai spesifikasi!
                'stock' => 30,
                'minimum_stock' => 10,
                'image' => 'images/products/mie_aceh_goreng.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['makanan']->id,
                'sku' => 'STN-MKN-02',
                'name' => 'Mie Aceh Kuah Telur',
                'description' => 'Mie Aceh kuah kental rempah pedas kaya bumbu khas dengan potongan sayur segar dan telur ceplok.',
                'unit' => 'porsi',
                'purchase_price' => 8000,
                'selling_price' => 15000, // Rp 15.000
                'stock' => 25,
                'minimum_stock' => 10,
                'image' => 'images/products/mie_aceh_kuah.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['makanan']->id,
                'sku' => 'STN-MKN-03',
                'name' => 'Mie Aceh Kosong (Goreng / Kuah)',
                'description' => 'Mie Aceh bumbu rempah gurih khas warkop tanpa telur, disajikan dengan acar mentimun dan kerupuk.',
                'unit' => 'porsi',
                'purchase_price' => 5000,
                'selling_price' => 10000, // Rp 10.000 Sesuai spesifikasi!
                'stock' => 3, // Di bawah minimum_stock (10) -> Warning Stok Menipis!
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
                'stock' => 4, // Di bawah minimum_stock (5) -> Warning Stok Menipis!
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
                'purchase_price' => 5000,
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

        // Hapus produk lama yang tidak ada di daftar 12 menu
        $activeSkus = array_column($products, 'sku');
        Product::whereNotIn('sku', $activeSkus)->delete();

        // 5. Bersihkan Riwayat Transaksi Lama untuk Simulasi 1 Tahun Baru
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        SaleItem::truncate();
        Sale::truncate();
        PurchaseItem::truncate();
        Purchase::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 6. Data Pelanggan & Catatan Khas Warkop
        $customersPool = [
            'Cut Nabila [Meja 03]',
            'Bang Teuku Faisal [Meja 01]',
            'Dek Cut Raisa [Meja 02]',
            'Bang Dani (Ojol) [Bungkus]',
            'Pak Keuchik Mahmud [Meja 05]',
            'Teungku Amran [Meja 04]',
            'Dek Wan (Mahasiswa) [Meja 06]',
            'Kak Salma [Meja 02]',
            'Bang Ilham Maulana [Meja 01]',
            'Rizki Akbar [Bungkus]',
            'Bang Zul (Kantor Pos) [Meja 03]',
            'Dek Aulia [Meja 05]',
            'Fahmi Ridwan [Meja 04]',
            'Bang Dedi [Bungkus]',
        ];

        $notesPool = [
            'Sanger manis sedang, es jangan banyak',
            'Kopi saring pekat tanpa gula (pahit)',
            'Mie telur setengah matang pedas sedang',
            'Mie kosong jangan pedes banyakin kerupuk',
            'Teh tarik buih tebal dan dingin',
            'Pisang keju banyakin susu kental manis',
            'Roti bakar cokelat lumer mantap',
            'Mie kuah ekstra pedas kuah kental',
            null, null, null, null, null,
        ];

        $paymentMethods = ['cash', 'cash', 'cash', 'qris', 'qris', 'transfer'];

        // Paket Pesanan Warkop (Kombinasi Realistis Meja Warkop Aceh)
        $combos = [
            // Combo 1: Nongkrong Kopi Sanger + Mie Goreng Telur + Pisang Keju
            [
                ['sku' => 'STN-MNM-01', 'qty' => 3], // 3 Sanger Dingin = 24.000
                ['sku' => 'STN-MKN-01', 'qty' => 2], // 2 Mie Aceh Goreng Telur = 30.000
                ['sku' => 'STN-CML-01', 'qty' => 1], // 1 Pisang Keju = 10.000 (Total 64.000)
            ],
            // Combo 2: Ngopi Meja Rame (Sanger Dingin + Teh Tarik + Roti Bakar)
            [
                ['sku' => 'STN-MNM-01', 'qty' => 2], // 2 Sanger = 16.000
                ['sku' => 'STN-MNM-04', 'qty' => 2], // 2 Teh Tarik = 14.000
                ['sku' => 'STN-CML-02', 'qty' => 2], // 2 Roti Bakar = 24.000 (Total 54.000)
            ],
            // Combo 3: Makan Malam Mahasiswa (Mie Goreng Telur + Mie Kosong + Sanger Dingin)
            [
                ['sku' => 'STN-MKN-01', 'qty' => 2], // 2 Mie Telur = 30.000
                ['sku' => 'STN-MKN-03', 'qty' => 2], // 2 Mie Kosong = 20.000
                ['sku' => 'STN-MNM-01', 'qty' => 3], // 3 Sanger = 24.000 (Total 74.000)
            ],
            // Combo 4: Pagi Hari Bapak-bapak (Kopi Hitam Saring + Gorengan Panas + Sanger Panas)
            [
                ['sku' => 'STN-MNM-03', 'qty' => 3], // 3 Kopi Hitam = 15.000
                ['sku' => 'STN-CML-03', 'qty' => 2], // 2 Gorengan = 12.000
                ['sku' => 'STN-MNM-02', 'qty' => 2], // 2 Sanger Panas = 14.000 (Total 41.000)
            ],
            // Combo 5: Makan Siang / Malam Keluarga (Nasi Goreng + Mie Kuah Telur + Es Milo + Pisang Keju)
            [
                ['sku' => 'STN-MKN-04', 'qty' => 2], // 2 Nasi Goreng = 30.000
                ['sku' => 'STN-MKN-02', 'qty' => 2], // 2 Mie Kuah = 30.000
                ['sku' => 'STN-MNM-05', 'qty' => 2], // 2 Es Milo = 16.000
                ['sku' => 'STN-CML-01', 'qty' => 1], // 1 Pisang Keju = 10.000 (Total 86.000)
            ],
            // Combo 6: Nongkrong Santai Sore (Sanger Dingin + Kopi Hitam + Pisang Keju + Roti Bakar)
            [
                ['sku' => 'STN-MNM-01', 'qty' => 2], // 16.000
                ['sku' => 'STN-MNM-03', 'qty' => 2], // 10.000
                ['sku' => 'STN-CML-01', 'qty' => 2], // 20.000
                ['sku' => 'STN-CML-02', 'qty' => 1], // 12.000 (Total 58.000)
            ],
            // Combo 7: Favorit Begadang (Sanger Dingin + Mie Goreng Telur + Es Milo)
            [
                ['sku' => 'STN-MNM-01', 'qty' => 3], // 24.000
                ['sku' => 'STN-MKN-01', 'qty' => 2], // 30.000
                ['sku' => 'STN-MNM-05', 'qty' => 1], // 8.000 (Total 62.000)
            ],
        ];

        // 7. Loop Simulasi 365 Hari ke Belakang (Arus Kas, Laba Wajar Setahun)
        $saleIndex = 1;
        $purchaseIndex = 1;
        $today = Carbon::today();

        DB::transaction(function () use (
            $today,
            $combos,
            $customersPool,
            $notesPool,
            $paymentMethods,
            $productModels,
            $staffKasir,
            $staffDapur,
            $supGrosir,
            $supKopi,
            &$saleIndex,
            &$purchaseIndex
        ) {
            // Jalankan per hari dari 365 hari lalu s.d. 1 hari lalu
            for ($daysAgo = 365; $daysAgo >= 1; $daysAgo--) {
                $currentDate = $today->copy()->subDays($daysAgo);
                $isWeekend = in_array($currentDate->dayOfWeek, [Carbon::FRIDAY, Carbon::SATURDAY, Carbon::SUNDAY]);
                
                // Hari biasa: 6 - 8 pesanan meja warkop per hari
                // Akhir pekan (Jumat - Minggu): 9 - 12 pesanan meja warkop per hari
                // 7 Hari terakhir: disesuaikan agar grafik omset mingguan tampak sangat dinamis & hidup
                $ordersCount = $isWeekend ? rand(8, 11) : rand(6, 8);

                for ($o = 0; $o < $ordersCount; $o++) {
                    $combo = $combos[array_rand($combos)];
                    $customerName = $customersPool[array_rand($customersPool)];
                    $note = $notesPool[array_rand($notesPool)];
                    $payMethod = $paymentMethods[array_rand($paymentMethods)];

                    // Jam buka warkop antara 08.00 s.d. 23.30
                    $soldAt = $currentDate->copy()->setHour(rand(8, 23))->setMinute(rand(0, 59))->setSecond(rand(0, 59));

                    $saleNumber = sprintf('NOTA-%04d', $saleIndex++);

                    $sale = Sale::create([
                        'sale_number' => $saleNumber,
                        'user_id' => $staffKasir->id,
                        'customer_name' => $customerName,
                        'payment_method' => $payMethod,
                        'total' => 0,
                        'status' => 'completed',
                        'notes' => $note,
                        'sold_at' => $soldAt,
                        'created_at' => $soldAt,
                        'updated_at' => $soldAt,
                    ]);

                    $totalSale = 0;
                    foreach ($combo as $item) {
                        $p = $productModels[$item['sku']];
                        $qty = $item['qty'];
                        $subtotal = $p->selling_price * $qty;
                        $totalSale += $subtotal;

                        SaleItem::create([
                            'sale_id' => $sale->id,
                            'product_id' => $p->id,
                            'quantity' => $qty,
                            'unit_price' => $p->selling_price,
                            'subtotal' => $subtotal,
                            'created_at' => $soldAt,
                            'updated_at' => $soldAt,
                        ]);
                    }

                    $sale->update(['total' => $totalSale]);
                }

                // Belanja Bahan Baku (Purchases) dari Supplier: Dilakukan setiap 4 hari sekali oleh Dapur
                // HPP sekitar 48% - 50% dari omset penjualan
                if ($daysAgo % 4 === 0) {
                    $isKopi = ($purchaseIndex % 2 === 0);
                    $supplier = $isKopi ? $supKopi : $supGrosir;
                    $purchasedAt = $currentDate->copy()->setHour(rand(7, 10))->setMinute(rand(10, 50));
                    $poNumber = sprintf('PO-%03d', $purchaseIndex++);

                    $purchase = Purchase::create([
                        'purchase_number' => $poNumber,
                        'supplier_id' => $supplier->id,
                        'user_id' => $staffDapur->id,
                        'total' => 0,
                        'status' => 'completed',
                        'notes' => $isKopi ? 'Pasokan biji kopi saring Takengon gilingan segar.' : 'Pasokan mie kuning basah, telur ayam 3 papan, gula pasir, dan susu kental manis.',
                        'purchased_at' => $purchasedAt,
                        'created_at' => $purchasedAt,
                        'updated_at' => $purchasedAt,
                    ]);

                    $totalPurchase = 0;
                    if ($isKopi) {
                        // Belanja Kopi & Susu
                        $items = [
                            ['sku' => 'STN-MNM-01', 'qty' => rand(60, 90), 'cost' => 4000],
                            ['sku' => 'STN-MNM-03', 'qty' => rand(50, 80), 'cost' => 2500],
                            ['sku' => 'STN-MNM-04', 'qty' => rand(40, 60), 'cost' => 3500],
                        ];
                    } else {
                        // Belanja Bahan Dapur (Mie, Telur, Pisang, Roti, Milo)
                        $items = [
                            ['sku' => 'STN-MKN-01', 'qty' => rand(40, 65), 'cost' => 8000],
                            ['sku' => 'STN-MKN-03', 'qty' => rand(30, 50), 'cost' => 5000],
                            ['sku' => 'STN-CML-01', 'qty' => rand(30, 50), 'cost' => 5000],
                            ['sku' => 'STN-CML-02', 'qty' => rand(25, 40), 'cost' => 6000],
                            ['sku' => 'STN-MNM-05', 'qty' => rand(25, 40), 'cost' => 4000],
                        ];
                    }

                    foreach ($items as $item) {
                        $p = $productModels[$item['sku']];
                        $sub = $item['qty'] * $item['cost'];
                        $totalPurchase += $sub;

                        PurchaseItem::create([
                            'purchase_id' => $purchase->id,
                            'product_id' => $p->id,
                            'quantity' => $item['qty'],
                            'unit_cost' => $item['cost'],
                            'subtotal' => $sub,
                            'created_at' => $purchasedAt,
                            'updated_at' => $purchasedAt,
                        ]);
                    }

                    $purchase->update(['total' => $totalPurchase]);
                }
            }

            // 8. Transaksi HARI INI (28 Oktober 2026)
            // Ada 6 transaksi penjualan dari pagi s.d. siang ini
            $todaySales = [
                [
                    'time' => '08:15',
                    'customer' => 'Pak Keuchik Mahmud [Meja 05]',
                    'pay' => 'cash',
                    'note' => 'Kopi hitam pahit panas, gorengan baru angkat.',
                    'items' => [
                        ['sku' => 'STN-MNM-03', 'qty' => 3],
                        ['sku' => 'STN-CML-03', 'qty' => 2],
                    ],
                ],
                [
                    'time' => '09:40',
                    'customer' => 'Bang Teuku Faisal [Meja 01]',
                    'pay' => 'qris',
                    'note' => 'Sanger dingin manis sedang.',
                    'items' => [
                        ['sku' => 'STN-MNM-01', 'qty' => 3],
                        ['sku' => 'STN-CML-01', 'qty' => 2],
                    ],
                ],
                [
                    'time' => '11:30',
                    'customer' => 'Dek Wan (Mahasiswa Unimal) [Meja 06]',
                    'pay' => 'cash',
                    'note' => 'Mie kosong pedas mantap banyakin kerupuk.',
                    'items' => [
                        ['sku' => 'STN-MKN-03', 'qty' => 2],
                        ['sku' => 'STN-MNM-02', 'qty' => 2],
                    ],
                ],
                [
                    'time' => '12:45',
                    'customer' => 'Bang Dani (Ojol) [Bungkus]',
                    'pay' => 'cash',
                    'note' => 'Bungkus mie kuah pedas sedang, es teh manis.',
                    'items' => [
                        ['sku' => 'STN-MKN-02', 'qty' => 1],
                        ['sku' => 'STN-MNM-04', 'qty' => 1],
                    ],
                ],
                [
                    'time' => '13:50',
                    'customer' => 'Cut Nabila [Meja 03]',
                    'pay' => 'qris',
                    'note' => 'Sanger manis sedang, Mie Aceh telur jangan terlalu pedas ya.',
                    'items' => [
                        ['sku' => 'STN-MNM-01', 'qty' => 2],
                        ['sku' => 'STN-MKN-01', 'qty' => 2],
                        ['sku' => 'STN-CML-01', 'qty' => 1],
                    ],
                ],
                [
                    'time' => '15:20',
                    'customer' => 'Rizki Akbar [Meja 04]',
                    'pay' => 'qris',
                    'note' => 'Roti bakar cokelat lumer keju tebal.',
                    'items' => [
                        ['sku' => 'STN-MNM-01', 'qty' => 2],
                        ['sku' => 'STN-CML-02', 'qty' => 1],
                    ],
                ],
            ];

            foreach ($todaySales as $ts) {
                [$hour, $minute] = explode(':', $ts['time']);
                $soldAt = $today->copy()->setHour((int)$hour)->setMinute((int)$minute)->setSecond(0);
                $saleNumber = sprintf('NOTA-%04d', $saleIndex++);

                $sale = Sale::create([
                    'sale_number' => $saleNumber,
                    'user_id' => $staffKasir->id,
                    'customer_name' => $ts['customer'],
                    'payment_method' => $ts['pay'],
                    'total' => 0,
                    'status' => 'completed',
                    'notes' => $ts['note'],
                    'sold_at' => $soldAt,
                    'created_at' => $soldAt,
                    'updated_at' => $soldAt,
                ]);

                $total = 0;
                foreach ($ts['items'] as $item) {
                    $p = $productModels[$item['sku']];
                    $sub = $p->selling_price * $item['qty'];
                    $total += $sub;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $p->id,
                        'quantity' => $item['qty'],
                        'unit_price' => $p->selling_price,
                        'subtotal' => $sub,
                        'created_at' => $soldAt,
                        'updated_at' => $soldAt,
                    ]);
                }

                $sale->update(['total' => $total]);
            }

            // 1 Pembelian Restok Hari Ini (Pagi Jam 07:15)
            $purchasedAt = $today->copy()->setHour(7)->setMinute(15)->setSecond(0);
            $poToday = Purchase::create([
                'purchase_number' => sprintf('PO-%03d', $purchaseIndex++),
                'supplier_id' => $supGrosir->id,
                'user_id' => $staffDapur->id,
                'total' => 0,
                'status' => 'completed',
                'notes' => 'Pasokan pagi telur ayam 1 papan, es balok kristal, dan susu kental manis.',
                'purchased_at' => $purchasedAt,
                'created_at' => $purchasedAt,
                'updated_at' => $purchasedAt,
            ]);

            $pTelur = $productModels['STN-MKN-01'];
            $pMilo = $productModels['STN-MNM-05'];
            $sub1 = 10 * 8000;
            $sub2 = 8 * 4000;

            PurchaseItem::create([
                'purchase_id' => $poToday->id,
                'product_id' => $pTelur->id,
                'quantity' => 10,
                'unit_cost' => 8000,
                'subtotal' => $sub1,
                'created_at' => $purchasedAt,
                'updated_at' => $purchasedAt,
            ]);

            PurchaseItem::create([
                'purchase_id' => $poToday->id,
                'product_id' => $pMilo->id,
                'quantity' => 8,
                'unit_cost' => 4000,
                'subtotal' => $sub2,
                'created_at' => $purchasedAt,
                'updated_at' => $purchasedAt,
            ]);

            $poToday->update(['total' => $sub1 + $sub2]);
        });
    }
}
