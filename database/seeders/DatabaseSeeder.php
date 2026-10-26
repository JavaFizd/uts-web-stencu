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
     * Seed database dengan data realistis Warkop Stencu Coffee Batuphat Barat, Lhokseumawe
     * lengkap dengan foto sajian makanan dan minuman lokal yang 100% tampil dan tidak pernah rusak/hilang.
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
                'name' => 'Rian (Barista / Kasir)',
                'phone' => '085277889900',
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

        // 2. Kategori Menu Warkop Realistis
        $categories = [
            [
                'name' => 'Kopi Tradisional Aceh',
                'slug' => 'kopi-tradisional-aceh',
                'description' => 'Kopi hitam saring khas Aceh, sanger racikan, dan kopi kocok telur.',
            ],
            [
                'name' => 'Minuman Sachet & Dingin',
                'slug' => 'minuman-sachet-dingin',
                'description' => 'Aneka minuman sachet favorit: Nutrisari, Milo, Extra Joss Susu, Teh Tarik, dll.',
            ],
            [
                'name' => 'Mie Aceh & Olahan Mie',
                'slug' => 'mie-aceh-olahan-mie',
                'description' => 'Mie Aceh bumbu rempah asli dan aneka racikan Indomie warkop.',
            ],
            [
                'name' => 'Makanan & Nasi',
                'slug' => 'makanan-nasi',
                'description' => 'Nasi goreng kampung, nasi perang bungkus daun, dan makanan berat.',
            ],
            [
                'name' => 'Camilan & Roti Bakar',
                'slug' => 'camilan-roti-bakar',
                'description' => 'Roti bakar Bandung, pisang crispy, gorengan bakwan, dan kentang.',
            ],
            [
                'name' => 'Bahan Baku Kopi Bubuk',
                'slug' => 'bahan-baku-kopi-bubuk',
                'description' => 'Bubuk kopi saring kasar & halus kemasan untuk seduh di rumah.',
            ],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['slug']] = Category::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }

        // 3. Pemasok Realistis (Suppliers Lokal Lhokseumawe)
        $supGrosir = Supplier::firstOrCreate(
            ['name' => 'Toko Grosir Berkah Sembako Batuphat'],
            [
                'contact_person' => 'Ko Apin / Bang Jamal',
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

        $supMie = Supplier::firstOrCreate(
            ['name' => 'Pabrik Mie Basah Pasar Batuphat'],
            [
                'contact_person' => 'Pak Usman Mie',
                'phone' => '082165009988',
                'email' => 'mie.usmanbatuphat@gmail.com',
                'address' => 'Kompleks Pasar Keude Batuphat, Lhokseumawe',
            ]
        );

        // 4. Daftar Produk Warkop Merakyat (Gambar Lokal 100% Pasti Muncul & Cepat)
        $products = [
            // Kategori: Kopi Tradisional Aceh
            [
                'category_id' => $categoryModels['kopi-tradisional-aceh']->id,
                'sku' => 'STN-KOP-01',
                'name' => 'Kopi Sanger Dingin (Es Sanger)',
                'description' => 'Racikan legendaris Aceh: kopi saring pekat ditarik dipadu susu kental manis dan es batu.',
                'unit' => 'gelas',
                'purchase_price' => 3500,
                'selling_price' => 8000,
                'stock' => 80,
                'minimum_stock' => 15,
                'image' => 'images/products/sanger_dingin.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['kopi-tradisional-aceh']->id,
                'sku' => 'STN-KOP-02',
                'name' => 'Kopi Sanger Panas',
                'description' => 'Sanger panas racikan barista dengan buih khas tarikan saring tradisional Aceh.',
                'unit' => 'cangkir',
                'purchase_price' => 3000,
                'selling_price' => 7000,
                'stock' => 75,
                'minimum_stock' => 15,
                'image' => 'images/products/sanger_panas.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['kopi-tradisional-aceh']->id,
                'sku' => 'STN-KOP-03',
                'name' => 'Kopi Hitam Saring (Kopi O)',
                'description' => 'Kopi hitam pekat saring khas Aceh dengan aroma harum semerbak dan rasa mantap.',
                'unit' => 'cangkir',
                'purchase_price' => 2000,
                'selling_price' => 5000,
                'stock' => 100,
                'minimum_stock' => 20,
                'image' => 'images/products/kopi_hitam.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['kopi-tradisional-aceh']->id,
                'sku' => 'STN-KOP-04',
                'name' => 'Kopi Kocok Telur Bebek',
                'description' => 'Kopi hitam panas dipadu kuning telur bebek yang dikocok hingga mengembang berbusa gurih.',
                'unit' => 'gelas',
                'purchase_price' => 6000,
                'selling_price' => 14000,
                'stock' => 30,
                'minimum_stock' => 5,
                'image' => 'images/products/kopi_telur.jpg',
                'is_active' => true,
            ],

            // Kategori: Minuman Sachet & Dingin
            [
                'category_id' => $categoryModels['minuman-sachet-dingin']->id,
                'sku' => 'STN-SCH-01',
                'name' => 'Es Nutrisari Jeruk Peras',
                'description' => 'Nutrisari sachet rasa jeruk peras segar disajikan dingin dengan es batu melimpah.',
                'unit' => 'gelas',
                'purchase_price' => 2000,
                'selling_price' => 5000,
                'stock' => 60,
                'minimum_stock' => 10,
                'image' => 'images/products/nutrisari.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['minuman-sachet-dingin']->id,
                'sku' => 'STN-SCH-02',
                'name' => 'Es Milo Dingin Cokelat',
                'description' => 'Milo sachet cokelat kental ditambah susu kental manis dan es batu segar.',
                'unit' => 'gelas',
                'purchase_price' => 4000,
                'selling_price' => 9000,
                'stock' => 50,
                'minimum_stock' => 10,
                'image' => 'images/products/milo.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['minuman-sachet-dingin']->id,
                'sku' => 'STN-SCH-03',
                'name' => 'Extra Joss Susu (Jossu Dingin)',
                'description' => 'Extra Joss sachet campur susu kental manis dingin, minuman stamina khas nongkrong malam.',
                'unit' => 'gelas',
                'purchase_price' => 3000,
                'selling_price' => 7000,
                'stock' => 40,
                'minimum_stock' => 10,
                'image' => 'images/products/jossu.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['minuman-sachet-dingin']->id,
                'sku' => 'STN-SCH-04',
                'name' => 'Es Teh Tarik Sachet',
                'description' => 'Teh tarik kemasan diseduh dengan susu kental manis dan ditarik hingga berbusa.',
                'unit' => 'gelas',
                'purchase_price' => 3000,
                'selling_price' => 7000,
                'stock' => 45,
                'minimum_stock' => 10,
                'image' => 'images/products/teh_tarik.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['minuman-sachet-dingin']->id,
                'sku' => 'STN-SCH-05',
                'name' => 'Good Day Freeze Mokafrio',
                'description' => 'Kopi Good Day Freeze kocok dingin dengan sensasi rasa moka yang lembut.',
                'unit' => 'gelas',
                'purchase_price' => 3000,
                'selling_price' => 6000,
                'stock' => 35,
                'minimum_stock' => 10,
                'image' => 'images/products/goodday.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['minuman-sachet-dingin']->id,
                'sku' => 'STN-SCH-06',
                'name' => 'Kukubima Ener-G Susu Dingin',
                'description' => 'Kukubima anggur dipadu susu kental manis creamy dan es batu.',
                'unit' => 'gelas',
                'purchase_price' => 2500,
                'selling_price' => 6000,
                'stock' => 25,
                'minimum_stock' => 8,
                'image' => 'images/products/kukubima.jpg',
                'is_active' => true,
            ],

            // Kategori: Mie Aceh & Olahan Mie
            [
                'category_id' => $categoryModels['mie-aceh-olahan-mie']->id,
                'sku' => 'STN-MIE-01',
                'name' => 'Mie Aceh Goreng Telur',
                'description' => 'Mie kuning basah khas Aceh digoreng dengan bumbu rempah pedas gurih, telur, tauge, dan emping.',
                'unit' => 'porsi',
                'purchase_price' => 7000,
                'selling_price' => 14000,
                'stock' => 40,
                'minimum_stock' => 10,
                'image' => 'images/products/mie_aceh_goreng.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['mie-aceh-olahan-mie']->id,
                'sku' => 'STN-MIE-02',
                'name' => 'Mie Aceh Kuah / Tumis Telur',
                'description' => 'Mie Aceh berkuah kental rempah pedas kaya bumbu dengan potongan sayur segar dan telur ceplok.',
                'unit' => 'porsi',
                'purchase_price' => 7000,
                'selling_price' => 14000,
                'stock' => 35,
                'minimum_stock' => 10,
                'image' => 'images/products/mie_aceh_kuah.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['mie-aceh-olahan-mie']->id,
                'sku' => 'STN-MIE-03',
                'name' => 'Indomie Goreng Telur Ceplok (Internet)',
                'description' => 'Indomie goreng disajikan dengan telur ceplok setengah matang dan taburan bawang goreng gurih.',
                'unit' => 'porsi',
                'purchase_price' => 5000,
                'selling_price' => 10000,
                'stock' => 50,
                'minimum_stock' => 10,
                'image' => 'images/products/indomie_goreng.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['mie-aceh-olahan-mie']->id,
                'sku' => 'STN-MIE-04',
                'name' => 'Indomie Kuah Kari Ayam Telur',
                'description' => 'Indomie kuah kari ayam panas dimasak dengan sawi hijau, cabai rawit potong, dan telur rebus.',
                'unit' => 'porsi',
                'purchase_price' => 5000,
                'selling_price' => 10000,
                'stock' => 45,
                'minimum_stock' => 10,
                'image' => 'images/products/indomie_kuah.jpg',
                'is_active' => true,
            ],

            // Kategori: Makanan & Nasi
            [
                'category_id' => $categoryModels['makanan-nasi']->id,
                'sku' => 'STN-NAS-01',
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
            [
                'category_id' => $categoryModels['makanan-nasi']->id,
                'sku' => 'STN-NAS-02',
                'name' => 'Nasi Perang / Nasi Gurih Bungkus',
                'description' => 'Nasi gurih porsi mini berbalut daun pisang dengan lauk ikan tongkol suwir pedas manis.',
                'unit' => 'bungkus',
                'purchase_price' => 3500,
                'selling_price' => 6000,
                'stock' => 3, // Di bawah minimum_stock (5) untuk pengujian alert stok rendah di dashboard!
                'minimum_stock' => 5,
                'image' => 'images/products/nasi_perang.jpg',
                'is_active' => true,
            ],

            // Kategori: Camilan & Roti Bakar
            [
                'category_id' => $categoryModels['camilan-roti-bakar']->id,
                'sku' => 'STN-ROTI-01',
                'name' => 'Roti Bakar Cokelat Keju',
                'description' => 'Roti tawar tebal dipanggang harum mentega dengan isian meses cokelat dan parutan keju melimpah.',
                'unit' => 'porsi',
                'purchase_price' => 6000,
                'selling_price' => 12000,
                'stock' => 20,
                'minimum_stock' => 5,
                'image' => 'images/products/roti_bakar.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => $categoryModels['camilan-roti-bakar']->id,
                'sku' => 'STN-SNK-01',
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
            [
                'category_id' => $categoryModels['camilan-roti-bakar']->id,
                'sku' => 'STN-SNK-02',
                'name' => 'Pisang Goreng Crispy Keju',
                'description' => 'Pisang kepok manis digoreng tepung krispi dengan taburan gula aren dan keju parut.',
                'unit' => 'porsi',
                'purchase_price' => 4500,
                'selling_price' => 10000,
                'stock' => 15,
                'minimum_stock' => 5,
                'image' => 'images/products/pisang_goreng.jpg',
                'is_active' => true,
            ],

            // Kategori: Bahan Baku Kopi Bubuk
            [
                'category_id' => $categoryModels['bahan-baku-kopi-bubuk']->id,
                'sku' => 'STN-BUB-01',
                'name' => 'Kopi Bubuk Saring Robusta Aceh (500g)',
                'description' => 'Kopi bubuk gilingan kasar siap seduh saring khas warung kopi Aceh, kemasan 500 gram.',
                'unit' => 'bungkus',
                'purchase_price' => 28000,
                'selling_price' => 40000,
                'stock' => 15,
                'minimum_stock' => 5,
                'image' => 'images/products/kopi_bubuk.jpg',
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

        // 5. Riwayat Pembelian Awal (Purchase / Stock-In)
        $purchase = Purchase::firstOrCreate(
            ['purchase_number' => 'PO-202610-001'],
            [
                'supplier_id' => $supGrosir->id,
                'user_id' => $owner->id,
                'total' => 350000,
                'status' => 'completed',
                'notes' => 'Pasokan rutin Indomie 2 karton, Nutrisari 3 renceng, Milo, dan susu kental manis.',
                'purchased_at' => now()->subDays(2),
            ]
        );

        PurchaseItem::firstOrCreate(
            ['purchase_id' => $purchase->id, 'product_id' => $productModels['STN-MIE-03']->id],
            [
                'quantity' => 40,
                'unit_cost' => 5000,
                'subtotal' => 200000,
            ]
        );

        PurchaseItem::firstOrCreate(
            ['purchase_id' => $purchase->id, 'product_id' => $productModels['STN-SCH-01']->id],
            [
                'quantity' => 50,
                'unit_cost' => 2000,
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
                'notes' => 'Sanger dingin manis sedang, Mie Aceh goreng telur pedas sedang.',
                'sold_at' => now()->subHours(2),
            ]
        );

        SaleItem::firstOrCreate(
            ['sale_id' => $sale->id, 'product_id' => $productModels['STN-KOP-01']->id],
            [
                'quantity' => 1,
                'unit_price' => 8000,
                'subtotal' => 8000,
            ]
        );

        SaleItem::firstOrCreate(
            ['sale_id' => $sale->id, 'product_id' => $productModels['STN-MIE-01']->id],
            [
                'quantity' => 1,
                'unit_price' => 14000,
                'subtotal' => 14000,
            ]
        );
    }
}
