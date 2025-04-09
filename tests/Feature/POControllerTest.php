<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use App\Models\Branch;
use App\Models\LogBasePrice;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\SupplierProduct;

class POControllerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker(); // ← penting: inisialisasi faker
        // Jalankan seeder kalau perlu (hanya jika kamu pakai seed)
        // $this->artisan('db:seed');
    }

    /**
     * A basic feature test example.
     */
    public function test_add_purchase_order_successfully()
    {
        // Ambil 1 supplier dan 1 branch dari tabel yang sudah ada
        $branch = Branch::inRandomOrder()->first();
        $supplier = Supplier::inRandomOrder()->first();

        // Ambil PO Number terakhir
        $lastPO = PurchaseOrder::orderByRaw('CAST(SUBSTRING(po_number, 3) AS UNSIGNED) DESC')->first();
        
        if ($lastPO) {
            // Ambil angka dari po_number terakhir
            $lastNumber = (int) substr($lastPO->po_number, 2); // hasilnya: 34
            $newNumber = $lastNumber + 1;                      // hasilnya: 35
            $newPoNumber = 'PO' . str_pad($newNumber, 4, '0', STR_PAD_LEFT); // hasil: PO0035
        } else {
            // Jika belum ada data, mulai dari PO0001
            $newPoNumber = 'PO0001';
        }

        // insert ke tabel PurchaseOrder dulu


        // Ambil jumlah item yang dipasok oleh supplier terpilih
        $itemCount = SupplierProduct::where('supplier_id', $supplier->supplier_id)->count();

        $numOfSKU = $this->faker->numberBetween(1, $itemCount);
                                          
        // Ambil data produk yang dipasok oleh supplier
        $selectedRawMaterial = SupplierProduct::where('supplier_id', $supplier->supplier_id)
                                                ->inRandomOrder()
                                                ->limit($numOfSKU)
                                                ->get();

        $start_date = Carbon::parse('2025-01-01');
        $end_date = Carbon::parse('2025-04-10');
        $orderDate = Carbon::parse($start_date->format('Y-m-d'))->addDays(rand(0, $start_date->diffInDays($end_date)))->format('Y-m-d');

        $total = 0;
        $postData = [];

        foreach ($selectedRawMaterial as $item)
        {
            $quantity = $this->faker->numberBetween(1, 10);

            #mendapatkan base price
            $basePrice = LogBasePrice::where('supplier_id', $supplier->supplier_id)
                                        ->where('product_id', $item->product_id)
                                        ->latest('id')
                                        ->first();

            if ($basePrice->new_base_price ?? false)
            {
                $amount = $basePrice->new_base_price * $quantity;
                $total = $total + $amount;

                $postData[] = [
                                'po_number' => $newPoNumber,
                                'sku' => $item->product_id,
                                'qty' => $quantity,
                                'amount' => $amount,
                            ];
;
            }
        }

        $postData[] = [
            'po_number' => $newPoNumber,
            'branch_id' => $branch->id,
            'supplier_id' => $supplier->supplier_id,
            'total' => $total,
            'order_date' => $orderDate,
        ];
        //dd($postData);
        $response = $this->post('/purchase-orders', $postData); // sesuaikan dengan route aslinya

        $response->assertRedirect(); // redirect back on success
        // $response->assertSessionHas('success');

        // Periksa bahwa data PO benar-benar masuk ke database
        $this->assertDatabaseHas('purchase_order', [
            'supplier_id' => $newPoNumber,
            'branch_id' => $branch->id,
        ]);

        $this->assertGreaterThan(0, $itemCount, 'Supplier tidak memiliki produk yang dipasok.');
        $this->assertNotNull($branch, 'Data Branch tidak ditemukan di database.');
        $this->assertNotNull($supplier, 'Data Supplier tidak ditemukan di database.');

        dump("Branch ID: " . $branch->id);
        dump("Supplier ID: " . $supplier->supplier_id);
        dump("Supplier Product Count: " . $itemCount);

        // $response = $this->get('/');
        #$response->assertStatus(200);
    }
}
