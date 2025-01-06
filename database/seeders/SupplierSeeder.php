<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Supplier;
use App\Models\SupplierPic;
use App\Models\Product;
use App\Models\SupplierProduct;

class SupplierSeeder extends Seeder
{
    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefix = 'SUP';
        $numOfSupplier = $this->faker->numberBetween(5, 100);

        #Isi data produk dulu #TODO remake agar pindahkan ProductSeeder ke sini
        $this->call(ProductSeeder::class);
        $products = Product::pluck('product_id')->shuffle();
        $numOfSupplierProduct = $this->faker->numberBetween(1, $products->count());
        // $columns = Schema::getColumnListing('product');
        $shuffledsProductID = $products->take($numOfSupplierProduct);
        
        for ($i=1; $i <= $numOfSupplier; $i++)
        {
            $formattedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
            $supplierID = $prefix . $formattedNumber;
            $bankAccount = 'Bank '.$this->faker->company.' No. Rek '.$this->faker->bankAccountNumber;
            
            Supplier::create([
                'supplier_id' => $supplierID,
                'company_name' => $this->faker->company,
                'address' => $this->faker->address,
                'phone_number' => $this->faker->numerify('(###) ###-####'),
                'bank_account' => $bankAccount
            ]);

            foreach ($shuffledsProductID as $productID)
            {
                SupplierProduct::create([
                    'supplier_id' => $supplierID,
                    'product_id' => $productID,
                    'base_price' => $this->faker->numberBetween(4500, 150000)
                ]);
            }

            $this->createDummySupplierPIC($supplierID);
        }
    }
    
    public function createDummySupplierPIC($supplierID)
    {
        $numOfSupplierPic = $this->faker->numberBetween(1, 5);

        for ($j=0; $j <= $numOfSupplierPic; $j++){
            SupplierPic::create([
                'supplier_id' => $supplierID,
                'name' => $this->faker->name,
                'phone_number' => $this->faker->phonenumber,
                'email' => $this->faker->email,
                'assigned_date' => $this->faker->date
            ]);
        }
    }
}
