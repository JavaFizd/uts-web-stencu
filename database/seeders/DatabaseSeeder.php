<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
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
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed database aplikasi Stencu Coffee.
     * Mengisi akun pengguna (Owner, Kasir, Dapur, Pelanggan),
     * master data 3 kategori & 12 menu warkop Aceh,
     * serta simulasi operasional 1 TAHUN PENUH:
     * - Pemasukan Penjualan Kasir (Sales)
     * - Pengeluaran Belanja Stok (Purchases dari Supplier)
     * - Pengeluaran Kas Operasional (Expenses: Listrik, Gas, Es Batu, dll)
     * - Tiket Pesanan Aktif di Dapur & Kasir Hari Ini
     */
    public function run(): void
    {
        // 1. Akun Pengguna Multi-User
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
                'name' => 'Kasir Stencu',
                'phone' => '085277889900',
                'role' => 'staff',
                'password' => Hash::make('password'),
            ]
        );

        $staffDapur = User::firstOrCreate(
            ['email' => 'dapur@stencu.com'],
            [
                'name' => 'Dapur & Barista',
                'phone' => '085211223344',
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

        // 2. 3 Kategori Menu Utama
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

        // 3. Pemasok Mitra Lokal
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

        // 4. Daftar 12 Menu Ikonik
        $products = [
            // --- MINUMAN (5 Item) ---
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

            // --- MAKANAN (4 Item) ---
            [
                'category_id' => $categoryModels['makanan']->id,
                'sku' => 'STN-MKN-01',
                'name' => 'Mie Aceh Goreng Telur',
                'description' => 'Mie kuning basah khas Aceh digoreng bumbu rempah pedas gurih, telur, tauge, dan emping renyah.',
                'unit' => 'porsi',
                'purchase_price' => 8000,
                'selling_price' => 15000,
                'stock' => 30,
                'minimum_stock' => 10,
                'image' => 'images/products/mie_aceh_goreng_telur.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['makanan']->id,
                'sku' => 'STN-MKN-02',
                'name' => 'Mie Aceh Kuah Telur',
                'description' => 'Mie Aceh kuah kental rempah pedas kaya bumbu khas dengan potongan sayur segar dan telur ceplok.',
                'unit' => 'porsi',
                'purchase_price' => 8000,
                'selling_price' => 15000,
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
                'selling_price' => 10000,
                'stock' => 20,
                'minimum_stock' => 10,
                'image' => 'images/products/mie_aceh_kosong.jpg',
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
                'stock' => 25,
                'minimum_stock' => 5,
                'image' => 'images/products/nasi_goreng.jpg',
                'is_active' => true,
            ],

            // --- CEMILAN (3 Item) ---
            [
                'category_id' => $categoryModels['cemilan']->id,
                'sku' => 'STN-CML-01',
                'name' => 'Pisang Goreng Crispy Keju',
                'description' => 'Pisang kepok manis digoreng tepung krispi dengan taburan keju cheddar parut dan susu kental manis.',
                'unit' => 'porsi',
                'purchase_price' => 5000,
                'selling_price' => 10000,
                'stock' => 30,
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
                'stock' => 30,
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
                'stock' => 40,
                'minimum_stock' => 5,
                'image' => 'images/products/bakwan_tahu_goreng.jpg',
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

        // Hapus produk lama yang tidak sesuai
        $activeSkus = array_column($products, 'sku');
        Product::whereNotIn('sku', $activeSkus)->delete();

        // 5. Bersihkan Riwayat Transaksi Lama Sebelum Digenerate Ulang
        Schema::disableForeignKeyConstraints();
        SaleItem::truncate();
        Sale::truncate();
        PurchaseItem::truncate();
        Purchase::truncate();
        Expense::truncate();
        Schema::enableForeignKeyConstraints();

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

        // Paket Pesanan Warkop
        $combos = [
            // Combo 1: Nongkrong Kopi Sanger + Mie Goreng Telur + Pisang Keju
            [
                ['sku' => 'STN-MNM-01', 'qty' => 3], // 24.000
                ['sku' => 'STN-MKN-01', 'qty' => 2], // 30.000
                ['sku' => 'STN-CML-01', 'qty' => 1], // 10.000
            ],
            // Combo 2: Ngopi Meja Rame (Sanger Dingin + Teh Tarik + Roti Bakar)
            [
                ['sku' => 'STN-MNM-01', 'qty' => 2], // 16.000
                ['sku' => 'STN-MNM-04', 'qty' => 2], // 14.000
                ['sku' => 'STN-CML-02', 'qty' => 2], // 24.000
            ],
            // Combo 3: Makan Malam Mahasiswa (Mie Goreng Telur + Mie Kosong + Sanger Dingin)
            [
                ['sku' => 'STN-MKN-01', 'qty' => 2], // 30.000
                ['sku' => 'STN-MKN-03', 'qty' => 2], // 20.000
                ['sku' => 'STN-MNM-01', 'qty' => 3], // 24.000
            ],
            // Combo 4: Pagi Hari Bapak-bapak (Kopi Hitam Saring + Gorengan Panas + Sanger Panas)
            [
                ['sku' => 'STN-MNM-03', 'qty' => 3], // 15.000
                ['sku' => 'STN-CML-03', 'qty' => 2], // 12.000
                ['sku' => 'STN-MNM-02', 'qty' => 2], // 14.000
            ],
            // Combo 5: Cemilan Sore (Es Milo + Roti Bakar)
            [
                ['sku' => 'STN-MNM-05', 'qty' => 2], // 16.000
                ['sku' => 'STN-CML-02', 'qty' => 1], // 12.000
            ],
            // Combo 6: Makan Siang Warkop (Nasi Goreng Kampung + Sanger Dingin)
            [
                ['sku' => 'STN-MKN-04', 'qty' => 2], // 30.000
                ['sku' => 'STN-MNM-01', 'qty' => 2], // 16.000
            ],
            // Combo 7: Teman Nugas Malam (Mie Aceh Kuah Telur + Teh Tarik + Gorengan)
            [
                ['sku' => 'STN-MKN-02', 'qty' => 1], // 15.000
                ['sku' => 'STN-MNM-04', 'qty' => 1], // 7.000
                ['sku' => 'STN-CML-03', 'qty' => 1], // 6.000
            ],
        ];

        // 7. SIMULASI OPERASIONAL 1 TAHUN PENUH (Pemasukan, Pengeluaran Stok, & Kas Biaya)
        DB::transaction(function () use (
            $combos,
            $customersPool,
            $notesPool,
            $paymentMethods,
            $productModels,
            $staffKasir,
            $staffDapur,
            $supGrosir,
            $supKopi,
            $owner
        ) {
            $today = Carbon::today();
            $saleIndex = 1;
            $purchaseIndex = 1;
            $expenseIndex = 1;

            // Loop 365 Hari ke Belakang
            for ($daysAgo = 365; $daysAgo >= 1; $daysAgo--) {
                $currentDate = $today->copy()->subDays($daysAgo);
                $isWeekend = $currentDate->isWeekend();

                // 1. Pemasukan Penjualan Harian (6-12 transaksi per hari)
                $transCount = $isWeekend ? rand(7, 10) : rand(4, 7);

                for ($t = 0; $t < $transCount; $t++) {
                    $hour = rand(7, 23);
                    $minute = rand(0, 59);
                    $second = rand(0, 59);
                    $transTime = $currentDate->copy()->setHour($hour)->setMinute($minute)->setSecond($second);

                    $customer = $customersPool[array_rand($customersPool)];
                    $note = $notesPool[array_rand($notesPool)];
                    $payMethod = $paymentMethods[array_rand($paymentMethods)];
                    $combo = $combos[array_rand($combos)];

                    $saleNumber = sprintf('TRX-%s-%04d', $transTime->format('Ym'), $saleIndex++);

                    $sale = Sale::create([
                        'sale_number' => $saleNumber,
                        'customer_name' => $customer,
                        'user_id' => $staffKasir->id,
                        'total' => 0,
                        'payment_method' => $payMethod,
                        'status' => 'completed',
                        'kitchen_status' => 'served',
                        'notes' => $note,
                        'sold_at' => $transTime,
                        'created_at' => $transTime,
                        'updated_at' => $transTime,
                    ]);

                    $saleTotal = 0;
                    foreach ($combo as $itemSpec) {
                        $p = $productModels[$itemSpec['sku']];
                        $qty = $itemSpec['qty'];
                        $unitPrice = $p->selling_price;
                        $subtotal = $qty * $unitPrice;
                        $saleTotal += $subtotal;

                        SaleItem::create([
                            'sale_id' => $sale->id,
                            'product_id' => $p->id,
                            'quantity' => $qty,
                            'unit_price' => $unitPrice,
                            'subtotal' => $subtotal,
                            'created_at' => $transTime,
                            'updated_at' => $transTime,
                        ]);
                    }

                    $sale->update(['total' => $saleTotal]);
                }

                // 2. Pengeluaran Belanja Stok Bahan Baku (Tiap 5 hari sekali)
                if ($daysAgo % 5 === 0) {
                    $poHour = rand(7, 11);
                    $poTime = $currentDate->copy()->setHour($poHour)->setMinute(rand(10, 50));
                    $supplier = ($daysAgo % 10 === 0) ? $supKopi : $supGrosir;

                    $poNumber = sprintf('PO-%03d', $purchaseIndex++);
                    $purchase = Purchase::create([
                        'purchase_number' => $poNumber,
                        'supplier_id' => $supplier->id,
                        'user_id' => $staffDapur->id,
                        'total' => 0,
                        'status' => 'completed',
                        'notes' => 'Pasokan bahan baku mingguan untuk warkop.',
                        'purchased_at' => $poTime,
                        'created_at' => $poTime,
                        'updated_at' => $poTime,
                    ]);

                    $poTotal = 0;
                    $restockSkus = ($supplier->id === $supKopi->id)
                        ? ['STN-MNM-01', 'STN-MNM-02', 'STN-MNM-03']
                        : ['STN-MKN-01', 'STN-MNM-04', 'STN-CML-01'];

                    foreach ($restockSkus as $rSku) {
                        $p = $productModels[$rSku];
                        $rQty = rand(15, 25);
                        $rCost = $p->purchase_price;
                        $sub = $rQty * $rCost;
                        $poTotal += $sub;

                        PurchaseItem::create([
                            'purchase_id' => $purchase->id,
                            'product_id' => $p->id,
                            'quantity' => $rQty,
                            'unit_cost' => $rCost,
                            'subtotal' => $sub,
                            'created_at' => $poTime,
                            'updated_at' => $poTime,
                        ]);
                    }

                    $purchase->update(['total' => $poTotal]);
                }

                // 3. Pengeluaran Kas Operasional Harian / Mingguan / Bulanan
                // a. Es Batu Kristal (Tiap 2 hari sekali: Rp 25.000)
                if ($daysAgo % 2 === 0) {
                    Expense::create([
                        'expense_number' => sprintf('EXP-%s-%04d', $currentDate->format('Ym'), $expenseIndex++),
                        'user_id' => $staffKasir->id,
                        'title' => 'Beli Es Batu Kristal (2 Karung)',
                        'category' => 'Operasional Harian',
                        'amount' => 25000,
                        'expense_date' => $currentDate->format('Y-m-d'),
                        'notes' => 'Pasokan es batu harian untuk racikan sanger dan es teh.',
                        'created_at' => $currentDate->copy()->setHour(8)->setMinute(30),
                        'updated_at' => $currentDate->copy()->setHour(8)->setMinute(30),
                    ]);
                }

                // b. Tabung Gas LPG 3kg (Tiap 7 hari sekali: Rp 22.000)
                if ($daysAgo % 7 === 0) {
                    Expense::create([
                        'expense_number' => sprintf('EXP-%s-%04d', $currentDate->format('Ym'), $expenseIndex++),
                        'user_id' => $staffKasir->id,
                        'title' => 'Isi Ulang Tabung Gas LPG 3kg',
                        'category' => 'Gas & Listrik',
                        'amount' => 22000,
                        'expense_date' => $currentDate->format('Y-m-d'),
                        'notes' => 'Gas kompor masak Mie Aceh dan air panas kopi.',
                        'created_at' => $currentDate->copy()->setHour(10)->setMinute(15),
                        'updated_at' => $currentDate->copy()->setHour(10)->setMinute(15),
                    ]);
                }

                // c. Galon Air Mineral (Tiap 4 hari sekali: Rp 15.000)
                if ($daysAgo % 4 === 0) {
                    Expense::create([
                        'expense_number' => sprintf('EXP-%s-%04d', $currentDate->format('Ym'), $expenseIndex++),
                        'user_id' => $staffKasir->id,
                        'title' => 'Isi Ulang Galon Air Mineral (3 Galon)',
                        'category' => 'Operasional Harian',
                        'amount' => 15000,
                        'expense_date' => $currentDate->format('Y-m-d'),
                        'notes' => 'Air seduhan kopi saring dan teh tarik.',
                        'created_at' => $currentDate->copy()->setHour(11)->setMinute(0),
                        'updated_at' => $currentDate->copy()->setHour(11)->setMinute(0),
                    ]);
                }

                // d. Token Listrik PLN (Tiap 30 hari sekali: Rp 150.000)
                if ($daysAgo % 30 === 0) {
                    Expense::create([
                        'expense_number' => sprintf('EXP-%s-%04d', $currentDate->format('Ym'), $expenseIndex++),
                        'user_id' => $owner->id,
                        'title' => 'Token Listrik PLN Warkop',
                        'category' => 'Gas & Listrik',
                        'amount' => 150000,
                        'expense_date' => $currentDate->format('Y-m-d'),
                        'notes' => 'Listrik operasional kulkas, lampu, blender, dan wifi.',
                        'created_at' => $currentDate->copy()->setHour(9)->setMinute(0),
                        'updated_at' => $currentDate->copy()->setHour(9)->setMinute(0),
                    ]);
                }
            }

            // 8. TRANSAKSI HARI INI (Agar Dashboard, POS, & Dapur langsung aktif saat login)
            // a. 3 Pesanan Selesai Hari Ini
            $todayCompleted = [
                ['customer' => 'Dek Wan (Mahasiswa) [Meja 04]', 'hour' => 8, 'combo' => 0, 'pay' => 'qris'],
                ['customer' => 'Bang Teuku Faisal [Meja 01]', 'hour' => 9, 'combo' => 3, 'pay' => 'cash'],
                ['customer' => 'Bang Dani (Ojol) [Bungkus]', 'hour' => 10, 'combo' => 5, 'pay' => 'cash'],
            ];

            foreach ($todayCompleted as $tData) {
                $tTime = $today->copy()->setHour($tData['hour'])->setMinute(rand(10, 45));
                $combo = $combos[$tData['combo']];

                $sale = Sale::create([
                    'sale_number' => sprintf('TRX-%s-%04d', $tTime->format('Ym'), $saleIndex++),
                    'customer_name' => $tData['customer'],
                    'user_id' => $staffKasir->id,
                    'total' => 0,
                    'payment_method' => $tData['pay'],
                    'status' => 'completed',
                    'kitchen_status' => 'served',
                    'notes' => 'Pelanggan setia warkop',
                    'sold_at' => $tTime,
                    'created_at' => $tTime,
                    'updated_at' => $tTime,
                ]);

                $sTotal = 0;
                foreach ($combo as $cItem) {
                    $prod = $productModels[$cItem['sku']];
                    $sub = $cItem['qty'] * $prod->selling_price;
                    $sTotal += $sub;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $prod->id,
                        'quantity' => $cItem['qty'],
                        'unit_price' => $prod->selling_price,
                        'subtotal' => $sub,
                        'created_at' => $tTime,
                        'updated_at' => $tTime,
                    ]);
                }
                $sale->update(['total' => $sTotal]);
            }

            // b. 1 PESANAN AKTIF SEDANG DIRACIK DI DAPUR SAAT INI (Live Kitchen Queue)
            $liveKitchenTime = now()->subMinutes(12);
            $liveSale = Sale::create([
                'sale_number' => sprintf('TRX-%s-%04d', now()->format('Ym'), $saleIndex++),
                'customer_name' => 'Cut Nabila [Meja 03]',
                'user_id' => null, // Pesanan online dari meja
                'total' => 0,
                'payment_method' => 'qris',
                'status' => 'completed',
                'kitchen_status' => 'cooking', // Sedang Dimasak di Dapur!
                'notes' => 'Sanger manis sedang, es jangan banyak. Mie pedas sedang.',
                'sold_at' => $liveKitchenTime,
                'created_at' => $liveKitchenTime,
                'updated_at' => $liveKitchenTime,
            ]);

            $pSanger = $productModels['STN-MNM-01'];
            $pMie = $productModels['STN-MKN-01'];
            $subS = 2 * $pSanger->selling_price;
            $subM = 1 * $pMie->selling_price;

            SaleItem::create([
                'sale_id' => $liveSale->id,
                'product_id' => $pSanger->id,
                'quantity' => 2,
                'unit_price' => $pSanger->selling_price,
                'subtotal' => $subS,
                'created_at' => $liveKitchenTime,
                'updated_at' => $liveKitchenTime,
            ]);

            SaleItem::create([
                'sale_id' => $liveSale->id,
                'product_id' => $pMie->id,
                'quantity' => 1,
                'unit_price' => $pMie->selling_price,
                'subtotal' => $subM,
                'created_at' => $liveKitchenTime,
                'updated_at' => $liveKitchenTime,
            ]);

            $liveSale->update(['total' => $subS + $subM]);

            // c. 1 Pengeluaran Kas Hari Ini (Es Batu Pagi Hari)
            Expense::create([
                'expense_number' => sprintf('EXP-%s-%04d', now()->format('Ym'), $expenseIndex++),
                'user_id' => $staffKasir->id,
                'title' => 'Beli Es Batu Kristal (2 Karung)',
                'category' => 'Operasional Harian',
                'amount' => 25000,
                'expense_date' => now()->format('Y-m-d'),
                'notes' => 'Pasokan es batu pagi hari untuk racikan minuman warkop.',
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ]);
        });
    }
}
