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
            // $columns = Schema::getColumnListing('product');
            $shuffledsProductID = $products->take($numOfSupplierProduct);
        
            foreach ($shuffledsProductID->unique() as $productID)
            {
                $product = Product::where('product_id', $productID)->first();

                SupplierProduct::create([
                    'supplier_id' => $supplierID,
                    'company_name' =>$company_name,
                    'product_id' => $productID,
                    'product_name' => $product->name,
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

    public function createProduct()
    {
        $numOfCategory = $this->faker->numberBetween(5, 100);
        $numOfProduct = $this->faker->numberBetween(5, 100);

        $this->createCategory($numOfCategory);

        $prefix = 'PRD';
        $measurement_unit = ['Ons', 'Mg', 'Kg', 'Unit', 'Pcs', 'Sheet', 'Lusin'];

        for ($i=1; $i <= $numOfProduct; $i++)
        {
            $formattedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);

            Product::create([
                'product_id' => $prefix.$formattedNumber,
                'name' => $this->faker->word(),
                'category_id' => $this->faker->numberBetween(1, $numOfCategory),
                'description' => $this->faker->sentence(),
                'measurement_unit' => $this->faker->randomElement($measurement_unit)
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
