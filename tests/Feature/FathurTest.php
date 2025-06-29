<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\Category;
use App\Models\Branch;
use App\Models\SupplierProduct;
use App\Models\SupplierPic;
use App\Models\Merk;
use App\Models\Product;
use App\Models\SupplierMaterial;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;

class FathurTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    /**
     * A basic feature test example.
     */
    public function test_addpic(): void
    {
        $newPIC = [
            'supplier_id' => 'SUP002',
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'assigned_date' => $this->faker->date('d/m/Y'),
            'photo' => null, // Simulasi tidak ada foto
        ];

        $response = $this->post('/supplier/SUP002/add-pic', $newPIC);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'PIC berhasil ditambahkan!');
    }

    public function test_countpobyrange(): void
    {
        $supplierID = Supplier::inRandomOrder()->first()->supplier_id;
        $res = PurchaseOrder::countOrdersByDateSupplier('2025-01-01', '2025-12-31', $supplierID);
        dump($supplierID, $res);
    }

    public function test_addgrn(): void
    {
        $po = PurchaseOrder::inRandomOrder()->first();
        $poDetail = PurchaseOrderDetail::where('po_number', $po->po_number)->inRandomOrder()->first();
        $newGRN = [
            'po_number' => $po->po_number,
            'product_id' => $poDetail->product_id,
            'delivery_date' => $this->faker->date('Y-m-d'),
            'delivered_quantity' => $this->faker->numberBetween(1, 100),
            'comments' => $this->faker->sentence,
        ];
        dump($newGRN);
        
        $response = $this->post('/goods-receipt-note/store', $newGRN);
        // $response->assertRedirect();
        // $response->assertSessionHas('success', 'PIC berhasil ditambahkan!');
    }

    public function test_addproduct(): void
    {
        $newProduct = [
            'product_id' => 'P031',
            'product_name' => $this->faker->word,
            'product_type' => 'FG',
            'product_category' => '15',
            'product_description' => $this->faker->sentence,
        ];

        $response = $this->post('/product/addProduct', $newProduct);
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Produk berhasil ditambahkan.');
    }

    public function test_updateproduct(): void
    {
        $product = Product::inRandomOrder()->first();
        dump($product);
        $newProductName = $this->faker->word;
        $newData = [
            'id' => $product->id,
            'product_name' => $newProductName,
            'product_type' => $product->product_type,
            'product_category' => $product->product_category,
            'product_description' => $this->faker->sentence,
        ];
        $response = $this->put("/product/update/{$product->id}", $newData);
        dump($newProductName);
    }

    public function test_updatemerk(): void
    {
        $merk = Merk::inRandomOrder()->first();
        dump($merk);
        $newMerk = $this->faker->word;
        $newData = [
            'id' => $merk->id,
            'merk' => $newMerk,
        ];
        $response = $this->post("/merk/update/{$merk->id}", $newData);
        $response->assertStatus(200)
        ->assertJson([
            'message' => 'Data Merk berhasil diperbarui',
            'data' => [
                'id' => $merk->id,
                'merk' => $newMerk,
            ],
        ]);
        dump($newMerk);
    }

    public function test_updatepic(): void
    {
        $pic = SupplierPic::inRandomOrder()->first();
        dump($pic);
        $newPhone = $this->faker->phoneNumber;
        $newData = [
            'id' => $pic->id,
            'name' => $pic->name,
            'email' => $pic->email,
            'assigned_date' => $pic->assigned_date,
            'phone_number' => $newPhone,
        ];
        $response = $this->post("/supplier/pic/detail/update/{$pic->id}", $newData);
        dump($newPhone);
    }

    public function test_updatecategory(): void
    {
        $category = Category::inRandomOrder()->first();
        dump($category);

        $newData = [
            'id' => $category->id,
            'category' => $this->faker->word,
            'active' => $category->active,
        ];

        $response = $this->put("/category/update/{$category->id}", $newData);
    }

    public function test_updateitem(): void
    {
        $item = Item::inRandomOrder()->first();
        dump($item);

        $newData = [
            'id' => $item->id,
            'sku' => $item->sku,
            'item_name' => $this->faker->word,
        ];
        #Item::updateItem($item->id, $newData);
        $response = $this->put("/item/update/{$item->id}", $newData);
    }

    public function test_updatesupplier(): void
    {
        $supplier = Supplier::inRandomOrder()->first();
        dump($supplier);

        $newData = [
            'company_name' => $this->faker->company,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
        ];

        $response = $this->post("/supplier/update/{$supplier->supplier_id}", $newData);
    }

    public function test_updatesupplieritem(): void
    {
        $supplierItem = SupplierProduct::inRandomOrder()->first();
        dump($supplierItem->id, $supplierItem->base_price);
        $newBasePrice = $this->faker->numberBetween(1000, 10000);
        dump($newBasePrice);
        $newData = [
            'product_id' => $supplierItem->product_id,
            'product_name' => $supplierItem->product_name,
            'base_price' => $newBasePrice,
        ];

        // SupplierMaterial::updateSupplierMaterial($supplierItem->id, $newData);
        $response = $this->post("/supplier/material/update/{$supplierItem->id}", $newData);
    }

    public function test_getdetail(): void
    {
        $productID = Product::inRandomOrder()->first()->id;
        $response = (new Product) -> getProductById($productID);
        dump($response);
        $this->assertNotNull($response, 'Product tidak ditemukan');
    }

    public function test_getsupplierdetail(): void
    {
        $supplier = Supplier::inRandomOrder()->first();

        // Panggil endpoint
        $response = $this->getJson("/supplier/detail/{$supplier->supplier_id}");
        dump($response);
        // Pastikan data dikembalikan sesuai
        $response->assertStatus(200)
                 ->assertJson([
                     'supplier_id' => $supplier->supplier_id,
                     'company_name' => $supplier->company_name, // pastikan field ini sesuai dengan field asli
                 ]);
    }

    public function test_search(): void
    {
        $searchTerm = 'ab';
        $response = SupplierMaterial::searchSupplierMaterial($searchTerm);
        dump($response);
        $this->assertNotEmpty($response, 'Tidak ada supplier material yang ditemukan');
    }

    public function test_countproductbytype(): void
    {
        $shortType = 'FG';
        $res = Product::countProductByType($shortType);
        dump($shortType, $res);
        $this->assertNotNull($res, 'Tidak ada produk dengan tipe tersebut');
    }

    public function test_count(): void
    {
        $startDate = '01-01-2025';
        $endDate = '31-12-2025';
        $supplierID = Supplier::inRandomOrder()->first()->supplier_id;
        $res = PurchaseOrder::countOrdersByDateSupplier($startDate, $endDate, $supplierID);
        dump($supplierID, $res);
    }

    public function test_countsupplier(): void
    {
        $res = Supplier::countSupplier();
        dump($res);
        $this->assertNotNull($res, 'Tidak ada supplier yang ditemukan');
    }

    public function test_countsuppliermaterial(): void
    {
        $res = SupplierMaterial::countSupplierMaterial();
        dump($res);
        $this->assertNotNull($res, 'Tidak ada supplier yang ditemukan');
    }

    public function test_countPurchaseOrderBySupplier(): void
    {
        $res = PurchaseOrder::inRandomOrder()->first();
        dump($res->supplier_id);
        dump(PurchaseOrder::countPurchaseOrderBySupplier($res->supplier_id));
    }

    public function test_assignmentduration(): void
    {
        $pic = SupplierPic::inRandomOrder()->first();
        dump($pic);
        $response = SupplierPic::assignmentDuration($pic);
        dump($response);
        $this->assertNotNull($response, 'Tidak ada durasi penugasan ditemukan');
    }

    public function test_delete(): void
    {
        $item = Item::inRandomOrder()->first();
        $response = (new Item)->deleteItemById(156);
        dump($item);
        $this->assertTrue($response, 'Item tidak berhasil dihapus');
    }

    public function test_deleteproduct(): void
    {
        // Buat produk menggunakan factory agar data terkontrol
        $product = Product::inRandomOrder()->first();
        dump($product);
        // Lakukan request DELETE ke endpoint yang sesuai
        $response = $product->delete("/product/{$product->product_id}");
    
        // Assert: Pastikan redirect dan pesan sukses sesuai
        // $response->assertRedirect();
        // $response->assertSessionHas('success', 'Produk berhasil dihapus!');
    
        // Assert: Pastikan produk tidak lagi ada di database
        // $this->assertDatabaseMissing('products', ['id' => $product->id]);    
    }

    public function test_delcategory(): void
    {
        $cat = Category::inRandomOrder()->first();
        dump($cat);
        $response = $cat->delete("/category/delete/{$cat->id}");
    
        // Assert: Pastikan redirect dan pesan sukses sesuai
        // $response->assertRedirect();
        // $response->assertSessionHas('success', 'Category tidak berhasil dihapus!');
    
        // Assert: Pastikan produk tidak lagi ada di database
        // $this->assertDatabaseMissing('category', ['id' => $cat->id]);    
    }

    // public function test_delpicmodel(): void
    // {
    //     $pic = SupplierPic::inRandomOrder()->first();
    //     dump($pic);
    //     $response = (new SupplierPic)->deleteSupplierPicById($pic->id);
    //     $this->assertTrue($response, 'PIC tidak berhasil dihapus');
    // }

    public function test_delpicontroller(): void
    {
        $pic = SupplierPic::inRandomOrder()->first();
        dump($pic);
        $pic->delete("/supplier/pic/delete/{$pic->id}");
    }    

    public function test_getallbranch()
    {

        $response = $this->get('/branch');

        $response->assertStatus(200);
        $response->assertViewIs('branch.list');
    }    
    
}