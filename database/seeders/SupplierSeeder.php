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
use App\Models\Category;
use App\Models\SupplierProduct;
use App\Models\LogBasePrice;

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
        $numOfSupplier = $this->faker->numberBetween(5, 20);

        $this->createProduct();

        $counter = 0;
        for ($i=1; $i <= $numOfSupplier; $i++)
        {
            $formattedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
            $supplierID = $prefix . $formattedNumber;
            $bankAccount = 'Bank '.$this->faker->company.' No. Rek '.$this->faker->bankAccountNumber;

            $company_name = $this->faker->company;
            Supplier::create([
                'supplier_id' => $supplierID,
                'company_name' => $company_name,
                'address' => $this->faker->address,
                'phone_number' => $this->faker->numerify('(###) ###-####'),
                'bank_account' => $bankAccount
            ]);
            
            $products = Product::pluck('product_id')->shuffle();
            $numOfSupplierProduct = $this->faker->numberBetween(1, $products->count());
            $shuffledsProductID = $products->take($numOfSupplierProduct);
        
            foreach ($shuffledsProductID->unique() as $productID)
            {
                $product = Product::where('product_id', $productID)->first();

                $created_at = $this->faker->dateTimeBetween('-10 years', '2020-01-01 23:59:59')->format('Y-m-d H:i:s');

                SupplierProduct::create([
                    'supplier_id' => $supplierID,
                    'company_name' =>$company_name,
                    'product_id' => $productID,
                    'product_name' => $product->name,
                    'base_price' => $this->faker->numberBetween(4500, 75000),
                    'created_at'=>$created_at,
                    'updated_at'=>$created_at
                ]);
            }
            
            # Seeder perubahan harga produk
            $supplierProducts = SupplierProduct::where('supplier_id', $supplierID)->get();
            $numOfProductsToUpdate = $this->faker->numberBetween(1, $supplierProducts->count());
            $updatedProducts = $supplierProducts->pluck('product_id')->shuffle()->take($numOfProductsToUpdate);
            
            foreach($updatedProducts as $productID)
            {
                $timesToChange = $this->faker->numberBetween(1, 10);

                $product = SupplierProduct::where('product_id', $productID)
                                            ->where('supplier_id', $supplierID)
                                            ->first();
                $basePrice = $product->base_price;
                $price = $basePrice;
                $created_at = $product->created_at;

                for ($j=0; $j <= $timesToChange; $j++)
                {
                    $price = $price + $this->faker->numberBetween(-1000, 15000);

                    $supplierProduct = SupplierProduct::where('product_id', $productID)
                                                        ->where('supplier_id', $supplierID);
                    $supplierProduct->update(['base_price' => $price]);

                    if ($j == 0) {
                        $newDate = (clone $created_at)->modify('+1 day')->format('Y-m-d H:i:s');
                    } else {
                        $newDate = $this->faker->dateTimeBetween($newDate, '2020-01-31 23:59:59')->format('Y-m-d H:i:s');
                    }

                    $supplierProduct->update(['updated_at'=>$newDate]);

                    $lastLogBasePrice = LogBasePrice::where('supplier_id', $supplierID)
                    ->where('product_id', $productID)
                    ->orderBy('id', 'desc') // Mengurutkan berdasarkan ID secara descending
                    ->first(); // Ambil record terakhir (ID terbesar)

                    if ($lastLogBasePrice) {
                        $lastLogBasePrice->update([
                            'created_at' => $newDate,
                            'updated_at' => $newDate
                        ]);
                    }
                }

            }
            # Akhir seeder

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

    public function createProduct()
    {
        $numOfCategory = $this->faker->numberBetween(5, 15);
        $numOfProduct = $this->faker->numberBetween(5, 50);

        $this->createCategory($numOfCategory);

        $prefix = 'PRD';
        $measurement_unit = ['Ons', 'Mg', 'Kg', 'Unit', 'Pcs', 'Sheet', 'Lusin', 'Boks', 'Dus'];

        for ($i=1; $i <= $numOfProduct; $i++)
        {
            $formattedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);

            Product::create([
                'product_id' => $prefix.$formattedNumber,
                'name' => $this->faker->word(),
                'category_id' => $this->faker->numberBetween(1, $numOfCategory),
                'description' => $this->faker->sentence(),
                'measurement_unit' => $this->faker->randomElement($measurement_unit),
            ]);
        }
    }

    public function createCategory($numOfCategory)
    {
        for ($i=1; $i <= $numOfCategory; $i++)
        {
            Category::create([
                'category' => $this->faker->word
            ]);
        }
    }
}
