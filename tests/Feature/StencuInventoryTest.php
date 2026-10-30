<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StencuInventoryTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Skenario 1 (Slide 19 - Auth):
     * Guest tidak dapat membuka dashboard; password salah ditolak.
     */
    public function test_guest_cannot_access_dashboard_and_wrong_password_is_rejected(): void
    {
        // 1. Guest mencoba membuka dashboard -> diarahkan ke login
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');

        // 2. Buat user tes
        $user = User::factory()->create([
            'email' => 'testuser@stencu.com',
            'role' => 'staff',
            'password' => Hash::make('password_benar'),
        ]);

        // 3. Login dengan password salah -> ditolak
        $loginFail = $this->post('/login', [
            'email' => 'testuser@stencu.com',
            'password' => 'password_salah',
        ]);
        $loginFail->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Skenario 2 (Slide 19 - Product):
     * SKU duplikat ditolak; harga negatif ditolak.
     */
    public function test_duplicate_sku_and_negative_price_are_rejected(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $cat = Category::firstOrCreate(['name' => 'Kopi'], ['slug' => 'kopi']);

        Product::create([
            'category_id' => $cat->id,
            'sku' => 'SKU-UNIQUE-01',
            'name' => 'Kopi Original',
            'unit' => 'cup',
            'purchase_price' => 5000,
            'selling_price' => 15000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        // Coba input SKU yang sama -> harus ditolak
        $responseDuplicate = $this->actingAs($owner)->post('/products', [
            'sku' => 'SKU-UNIQUE-01',
            'name' => 'Kopi Duplikat',
            'category_id' => $cat->id,
            'unit' => 'cup',
            'purchase_price' => 5000,
            'selling_price' => 15000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);
        $responseDuplicate->assertSessionHasErrors('sku');

        // Coba input harga jual negatif -> harus ditolak
        $responseNegative = $this->actingAs($owner)->post('/products', [
            'sku' => 'SKU-UNIQUE-02',
            'name' => 'Kopi Harga Negatif',
            'category_id' => $cat->id,
            'unit' => 'cup',
            'purchase_price' => 5000,
            'selling_price' => -10000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);
        $responseNegative->assertSessionHasErrors('selling_price');
    }

    /**
     * Skenario 3 (Slide 19 - Purchase):
     * Pembelian sukses menambah stok sesuai kuantitas.
     */
    public function test_successful_purchase_increments_product_stock(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $supplier = Supplier::create([
            'name' => 'Supplier Gayo Aceh',
            'phone' => '08123456789',
        ]);
        $cat = Category::firstOrCreate(['name' => 'Biji Kopi'], ['slug' => 'biji-kopi']);
        $product = Product::create([
            'category_id' => $cat->id,
            'sku' => 'SKU-PURCHASE-01',
            'name' => 'Biji Kopi Gayo 1kg',
            'unit' => 'kg',
            'purchase_price' => 100000,
            'selling_price' => 150000,
            'stock' => 5,
            'minimum_stock' => 2,
        ]);

        $initialStock = $product->stock;
        $qtyToBuy = 10;

        $response = $this->actingAs($staff)->post('/purchases', [
            'supplier_id' => $supplier->id,
            'purchased_at' => now()->format('Y-m-d'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => $qtyToBuy,
                    'unit_cost' => 95000,
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $product->refresh();

        // Stok harus bertambah 10 (dari 5 menjadi 15)
        $this->assertEquals($initialStock + $qtyToBuy, $product->stock);
    }

    /**
     * Skenario 4 (Slide 19 - Sale):
     * Penjualan sukses mengurangi stok sesuai kuantitas.
     */
    public function test_successful_sale_decrements_product_stock(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $cat = Category::firstOrCreate(['name' => 'Minuman'], ['slug' => 'minuman']);
        $product = Product::create([
            'category_id' => $cat->id,
            'sku' => 'SKU-SALE-01',
            'name' => 'Kopi Susu Stencu',
            'unit' => 'cup',
            'purchase_price' => 7000,
            'selling_price' => 18000,
            'stock' => 20,
            'minimum_stock' => 5,
        ]);

        $initialStock = $product->stock;
        $qtySold = 4;

        $response = $this->actingAs($staff)->post('/sales', [
            'customer_name' => 'Ahmad Lhokseumawe',
            'payment_method' => 'cash',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => $qtySold,
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $product->refresh();

        // Stok berkurang 4 (dari 20 menjadi 16)
        $this->assertEquals($initialStock - $qtySold, $product->stock);
    }

    /**
     * Skenario 5 (Slide 19 - Insufficient Stock):
     * Penjualan dengan stok tidak cukup ditolak tanpa perubahan data.
     */
    public function test_sale_with_insufficient_stock_is_rejected_without_data_change(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $cat = Category::firstOrCreate(['name' => 'Minuman'], ['slug' => 'minuman']);
        $product = Product::create([
            'category_id' => $cat->id,
            'sku' => 'SKU-OVERSELL-01',
            'name' => 'Croissant Coklat',
            'unit' => 'pcs',
            'purchase_price' => 8000,
            'selling_price' => 15000,
            'stock' => 3, // Hanya ada 3
            'minimum_stock' => 2,
        ]);

        // Coba pesan 5 (melebihi stok 3)
        $response = $this->actingAs($staff)->post('/sales', [
            'customer_name' => 'Pembeli Borongan',
            'payment_method' => 'qris',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                ],
            ],
        ]);

        $response->assertSessionHasErrors('items');
        $product->refresh();

        // Stok harus tetap 3 (tidak berkurang sama sekali)
        $this->assertEquals(3, $product->stock);
    }

    /**
     * Skenario 6 (Slide 19 - Atomicity):
     * Jika satu item transaksi gagal, seluruh transaksi rollback.
     */
    public function test_transaction_atomicity_and_rollback_on_insufficient_item(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $cat = Category::firstOrCreate(['name' => 'Minuman'], ['slug' => 'minuman']);

        $productA = Product::create([
            'category_id' => $cat->id,
            'sku' => 'SKU-ITEM-A',
            'name' => 'Kopi A (Stok Cukup)',
            'unit' => 'cup',
            'purchase_price' => 5000,
            'selling_price' => 12000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        $productB = Product::create([
            'category_id' => $cat->id,
            'sku' => 'SKU-ITEM-B',
            'name' => 'Kopi B (Stok Kurang)',
            'unit' => 'cup',
            'purchase_price' => 5000,
            'selling_price' => 12000,
            'stock' => 1,
            'minimum_stock' => 2,
        ]);

        // Kirim transaksi multi-item: item 1 valid, tapi item 2 gagal karena minta 5 sedangkan stok hanya 1
        $response = $this->actingAs($staff)->post('/sales', [
            'customer_name' => 'Multi Item Buyer',
            'payment_method' => 'cash',
            'items' => [
                ['product_id' => $productA->id, 'quantity' => 2],
                ['product_id' => $productB->id, 'quantity' => 5], // INI GAGAL
            ],
        ]);

        $response->assertSessionHasErrors('items');

        $productA->refresh();
        $productB->refresh();

        // Sifat Atomik: Product A TIDAK boleh terpotong, stoknya harus tetap 10!
        $this->assertEquals(10, $productA->stock);
        $this->assertEquals(1, $productB->stock);
    }

    /**
     * Skenario 7 (Slide 19 - Authorization):
     * Staff tidak dapat mengakses fungsi Admin (Owner).
     */
    public function test_staff_cannot_access_owner_admin_functions(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);

        // 1. Staff mencoba membuka halaman kelola pengguna -> 403 Forbidden
        $responseUser = $this->actingAs($staff)->get('/users');
        $responseUser->assertStatus(403);

        // 2. Staff mencoba menghapus kategori -> 403 Forbidden
        $cat = Category::firstOrCreate(['name' => 'Test'], ['slug' => 'test']);
        $responseDelete = $this->actingAs($staff)->delete("/categories/{$cat->id}");
        $responseDelete->assertStatus(403);
    }

    /**
     * Skenario 8 (Slide 19 - Search & Pagination):
     * Filter dan pagination menghasilkan data yang sesuai.
     */
    public function test_search_and_filter_produce_accurate_results(): void
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $cat = Category::firstOrCreate(['name' => 'Khusus Test'], ['slug' => 'khusus-test']);

        Product::create([
            'category_id' => $cat->id,
            'sku' => 'SKU-SEARCH-999',
            'name' => 'Special Secret Blend Gayo',
            'unit' => 'cup',
            'purchase_price' => 5000,
            'selling_price' => 15000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        // Cari dengan keyword SKU
        $response = $this->actingAs($staff)->get('/products?search=999');
        $response->assertStatus(200);
        $response->assertSee('Special Secret Blend Gayo');
    }

    /**
     * Skenario 9:
     * Pelanggan dapat membuat pesanan online mandiri di web (Dine-in / Takeaway)
     * dan stok produk langsung terpotong secara atomik di database.
     */
    public function test_customer_can_place_online_order_via_web_and_stock_decrements(): void
    {
        $cat = Category::firstOrCreate(['name' => 'Kopi'], ['slug' => 'kopi']);
        $product = Product::create([
            'category_id' => $cat->id,
            'sku' => 'SKU-ORDER-ONLINE',
            'name' => 'Kopi Sanger Dingin',
            'unit' => 'gelas',
            'purchase_price' => 3500,
            'selling_price' => 8000,
            'stock' => 15,
            'minimum_stock' => 3,
        ]);

        $initialStock = $product->stock;

        $response = $this->post('/order', [
            'customer_name' => 'Teuku Alif',
            'order_type' => 'dine_in',
            'table_number' => '05',
            'payment_method' => 'cash',
            'notes' => 'Manis sedang, es dikit',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $product->refresh();
        // Stok terpotong 2 (dari 15 menjadi 13)
        $this->assertEquals($initialStock - 2, $product->stock);

        // Verifikasi data pesanan tercatat di tabel sales
        $this->assertDatabaseHas('sales', [
            'payment_method' => 'cash',
            'notes' => 'Manis sedang, es dikit',
            'status' => 'completed',
        ]);
    }

    /**
     * Skenario 13: Login Fleksibel Menggunakan Username (admin & kasir) Tanpa Email.
     */
    public function test_login_with_short_username_without_email(): void
    {
        // 1. Login Admin -> diarahkan ke dashboard
        $resAdmin = $this->post('/login', [
            'login' => 'admin',
            'password' => 'password',
        ]);
        $resAdmin->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $this->post('/logout');

        // 2. Login Kasir -> diarahkan ke kasir POS
        $resKasir = $this->post('/login', [
            'login' => 'kasir',
            'password' => 'password',
        ]);
        $resKasir->assertRedirect(route('sales.create'));
        $this->assertAuthenticated();

        $this->post('/logout');

        // 3. Login Dapur -> diarahkan ke daftar transaksi/antrean pesanan
        $resDapur = $this->post('/login', [
            'login' => 'dapur',
            'password' => 'password',
        ]);
        $resDapur->assertRedirect(route('sales.index'));
        $this->assertAuthenticated();

        $this->post('/logout');

        // 4. Login dengan Email Resmi Owner (admin@stencu.com)
        $resEmailAdmin = $this->post('/login', [
            'login' => 'admin@stencu.com',
            'password' => 'password',
        ]);
        $resEmailAdmin->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $this->post('/logout');

        // 5. Login dengan Email Resmi Kasir (kasir@stencu.com)
        $resEmailKasir = $this->post('/login', [
            'login' => 'kasir@stencu.com',
            'password' => 'password',
        ]);
        $resEmailKasir->assertRedirect(route('sales.create'));
        $this->assertAuthenticated();

        $this->post('/logout');

        // 6. Login dengan Akun Perusahaan Staf (staff@stencu.com) memilih Stasiun Kasir
        $resStaffKasir = $this->post('/login', [
            'login' => 'staff@stencu.com',
            'password' => 'password',
            'station' => 'kasir',
        ]);
        $resStaffKasir->assertRedirect(route('sales.create'));
        $this->assertEquals('kasir', session('staff_station'));
        $this->assertAuthenticated();

        // 7. Beralih Stasiun Kerja ke Dapur & Barista tanpa logout
        $resSwitchDapur = $this->get('/switch-station/dapur');
        $resSwitchDapur->assertRedirect(route('sales.index'));
        $this->assertEquals('dapur', session('staff_station'));

        $this->post('/logout');

        // 8. Login dengan Akun Perusahaan Staf (staff@stencu.com) memilih Stasiun Dapur
        $resStaffDapur = $this->post('/login', [
            'login' => 'staff@stencu.com',
            'password' => 'password',
            'station' => 'dapur',
        ]);
        $resStaffDapur->assertRedirect(route('sales.index'));
        $this->assertEquals('dapur', session('staff_station'));
        $this->assertAuthenticated();
    }

    /**
     * Skenario 14: Fitur Bebas Tambah / Ubah Foto Makanan & Minuman
     * Memverifikasi produk dapat disimpan dengan upload foto, link URL, atau galeri preset.
     */
    public function test_product_image_upload_and_custom_image_support(): void
    {
        $staff = User::where('role', 'staff')->first() ?? User::factory()->create(['role' => 'staff']);
        $cat = Category::firstOrCreate(['name' => 'Makanan'], ['slug' => 'makanan']);

        // 1. Tambah produk baru dengan link URL foto kustom
        $resStore = $this->actingAs($staff)->post('/products', [
            'sku' => 'SKU-FOTO-TEST-01',
            'name' => 'Kopi Sanger Custom Foto',
            'category_id' => $cat->id,
            'unit' => 'cup',
            'purchase_price' => 6000,
            'selling_price' => 12000,
            'stock' => 20,
            'minimum_stock' => 5,
            'image_url' => 'https://images.unsplash.com/photo-test-coffee.jpg',
        ]);
        $resStore->assertRedirect(route('products.index'));

        $product = Product::where('sku', 'SKU-FOTO-TEST-01')->first();
        $this->assertNotNull($product);
        $this->assertEquals('https://images.unsplash.com/photo-test-coffee.jpg', $product->image);
        $this->assertEquals('https://images.unsplash.com/photo-test-coffee.jpg', $product->image_url);

        // 2. Ubah foto produk via updateImage modal
        $resUpdateImage = $this->actingAs($staff)->post("/products/{$product->id}/image", [
            'image_preset' => 'images/products/mie_aceh_goreng_telur.jpg',
        ]);
        $resUpdateImage->assertSessionHas('success');

        $product->refresh();
        $this->assertEquals('images/products/mie_aceh_goreng_telur.jpg', $product->image);

        // 3. Upload file foto fisik dari komputer/HP
        $fakeFile = \Illuminate\Http\UploadedFile::fake()->image('makanan_baru.jpg', 600, 600);
        $resUpload = $this->actingAs($staff)->post("/products/{$product->id}/image", [
            'image_file' => $fakeFile,
        ]);
        $resUpload->assertSessionHas('success');

        $product->refresh();
        $this->assertStringStartsWith('images/products/prod_', $product->image);
        if (file_exists(public_path($product->image))) {
            unlink(public_path($product->image)); // Bersihkan file dummy tes
        }
    }

    /**
     * Skenario 15: Polling Realtime Notifikasi Pesanan Masuk untuk Kasir & Dapur
     */
    public function test_realtime_order_notification_polling_returns_new_sales(): void
    {
        $staff = User::where('role', 'staff')->first() ?? User::factory()->create(['role' => 'staff']);
        
        // 1. Initial check dengan last_id = 0
        $resInit = $this->actingAs($staff)->getJson('/api/check-new-orders?last_id=0');
        $resInit->assertStatus(200);
        $resInit->assertJsonStructure(['new_count', 'latest_id', 'recent_orders']);
        $initialMaxId = $resInit->json('latest_id');

        // 2. Buat pesanan baru
        $cat = Category::firstOrCreate(['name' => 'Minuman'], ['slug' => 'minuman']);
        $product = Product::create([
            'category_id' => $cat->id,
            'sku' => 'SKU-NOTIF-TEST',
            'name' => 'Kopi Notif Live',
            'unit' => 'cup',
            'purchase_price' => 5000,
            'selling_price' => 10000,
            'stock' => 10,
            'minimum_stock' => 2,
        ]);

        $sale = Sale::create([
            'user_id' => null, // Order online dari pelanggan
            'customer_name' => 'Tamu Meja 09',
            'sale_number' => 'NOTA-NOTIF-01',
            'payment_method' => 'qris',
            'total' => 10000,
            'status' => 'completed',
            'notes' => 'Tanpa gula',
            'sold_at' => now(),
        ]);

        \App\Models\SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 10000,
            'subtotal' => 10000,
        ]);

        // 3. Polling dengan last_id = initialMaxId -> harus mendeteksi pesanan baru
        $resPoll = $this->actingAs($staff)->getJson('/api/check-new-orders?last_id=' . $initialMaxId);
        $resPoll->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, $resPoll->json('new_count'));
        $this->assertEquals('Tamu Meja 09', $resPoll->json('orders.0.customer_name'));
        $this->assertTrue($resPoll->json('orders.0.is_online'));
    }

    /**
     * Skenario 16: Dapur & Kasir dapat menandai pesanan selesai diracik dan diantar ke meja pelanggan.
     */
    public function test_staff_can_mark_order_as_served_to_customer(): void
    {
        $staff = User::where('role', 'staff')->first() ?? User::factory()->create(['role' => 'staff']);

        $sale = Sale::create([
            'user_id' => $staff->id,
            'customer_name' => 'Fajar [Meja 03]',
            'sale_number' => 'NOTA-KITCHEN-TEST',
            'payment_method' => 'cash',
            'total' => 25000,
            'status' => 'completed',
            'kitchen_status' => 'cooking',
            'notes' => 'Panas',
            'sold_at' => now(),
        ]);

        $this->assertTrue($sale->isCooking());
        $this->assertFalse($sale->isServed());

        // Staf klik tombol "Tandai Selesai Diantar"
        $res = $this->actingAs($staff)->post("/sales/{$sale->id}/mark-served");
        $res->assertSessionHas('success');

        $sale->refresh();
        $this->assertTrue($sale->isServed());
        $this->assertFalse($sale->isCooking());
        $this->assertEquals('served', $sale->kitchen_status);
    }

    /**
     * Skenario 17: Pencatatan Pengeluaran Kas Operasional Warkop.
     */
    public function test_staff_or_owner_can_record_operational_expense(): void
    {
        $staff = User::where('role', 'staff')->first() ?? User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($staff)->post('/expenses', [
            'title' => 'Beli Es Batu Kristal 2 Karung',
            'category' => 'Operasional Harian',
            'amount' => 25000,
            'expense_date' => now()->format('Y-m-d'),
            'notes' => 'Beli di warung sebelah',
        ]);

        $response->assertRedirect('/expenses');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('expenses', [
            'title' => 'Beli Es Batu Kristal 2 Karung',
            'category' => 'Operasional Harian',
            'amount' => 25000.00,
        ]);
    }

    /**
     * Skenario 18: Hanya Owner yang dapat menghapus catatan pengeluaran kas.
     */
    public function test_only_owner_can_delete_expense(): void
    {
        $owner = User::where('role', 'owner')->first() ?? User::factory()->create(['role' => 'owner']);
        $staff = User::where('role', 'staff')->first() ?? User::factory()->create(['role' => 'staff']);

        $expense = \App\Models\Expense::create([
            'expense_number' => 'EXP-TEST-0001',
            'user_id' => $owner->id,
            'title' => 'Token Listrik PLN',
            'category' => 'Gas & Listrik',
            'amount' => 100000,
            'expense_date' => now()->format('Y-m-d'),
        ]);

        // Staff coba hapus -> ditolak 403
        $resStaff = $this->actingAs($staff)->delete("/expenses/{$expense->id}");
        $resStaff->assertStatus(403);
        $this->assertDatabaseHas('expenses', ['id' => $expense->id]);

        // Owner hapus -> berhasil
        $resOwner = $this->actingAs($owner)->delete("/expenses/{$expense->id}");
        $resOwner->assertRedirect('/expenses');
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }
}
