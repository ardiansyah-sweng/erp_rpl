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
use App\Helpers\DBConstants;

class SupplierSeeder extends Seeder
{
    const DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
        $this->colProduct = config('db_constants.column.product');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colLogBasePriceSupplier = config('db_constants.column.log_base_price_supplier');
        $colSupplier = config('db_constants.column.supplier');
        $colSupplierProduct = config('db_constants.column.supplier_product');

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
                $colSupplier['supplier_id'] => $supplierID,
                $colSupplier['company_name'] => $company_name,
                $colSupplier['address'] => $this->faker->address,
                $colSupplier['phone_number'] => $this->faker->numerify('(###) ###-####'),
                $colSupplier['bank_account'] => $bankAccount
            ]);
            
            $products = Product::pluck($this->colProduct['product_id'])->shuffle();
            $numOfSupplierProduct = $this->faker->numberBetween(1, $products->count());
            $shuffledsProductID = $products->take($numOfSupplierProduct);
        
            foreach ($shuffledsProductID->unique() as $productID)
            {
                $created_at = $this->faker->dateTimeBetween('-10 years', '2020-01-01 23:59:59')->format(self::DATE_FORMAT);
                $product = Product::where($this->colProduct['product_id'], $productID)->get()->first();
        
                SupplierProduct::create([
                    $colSupplierProduct['supplier_id'] => $supplierID,
                    $colSupplierProduct['company_name'] =>$company_name,
                    $colSupplierProduct['product_id'] => $productID,
                    $colSupplierProduct['product_name'] => $product->name,
                    $colSupplierProduct['base_price'] => $this->faker->numberBetween(4500, 75000),
                    $colSupplierProduct['created_at'] =>$created_at,
                    $colSupplierProduct['updated_at'] =>$created_at
                ]);
            }
            
            # Seeder perubahan harga produk
            $supplierProducts = SupplierProduct::where($colSupplierProduct['supplier_id'], $supplierID)->get();
            $numOfProductsToUpdate = $this->faker->numberBetween(1, $supplierProducts->count());
            $updatedProducts = $supplierProducts->pluck($colSupplierProduct['product_id'])->shuffle()->take($numOfProductsToUpdate);
            
            foreach($updatedProducts as $productID)
            {
                $timesToChange = $this->faker->numberBetween(1, 10);

                $product = SupplierProduct::where($colSupplierProduct['product_id'], $productID)
                                            ->where($colSupplierProduct['supplier_id'], $supplierID)
                                            ->first();
                $basePrice = $product->base_price;
                $price = $basePrice;
                $created_at = $product->created_at;

                for ($j=0; $j <= $timesToChange; $j++)
                {
                    $price = $price + $this->faker->numberBetween(-1000, 15000);

                    $supplierProduct = SupplierProduct::where($colSupplierProduct['product_id'], $productID)
                                                        ->where($colSupplierProduct['supplier_id'], $supplierID);
                    $supplierProduct->update([$colSupplierProduct['base_price'] => $price]);
                    
                    {
                        $newDate = (clone $created_at)->modify('+1 day')->format(self::DATE_FORMAT);
                    }

                    {
                        $newDate = $this->faker->dateTimeBetween($newDate, '2020-01-31 23:59:59')->format(self::DATE_FORMAT);
                    }

                    $supplierProduct->update([$colSupplierProduct['updated_at'] => $newDate]);

                    $lastLogBasePrice = LogBasePrice::where($colLogBasePriceSupplier['supplier_id'], $supplierID)
                                                        ->where($colLogBasePriceSupplier['product_id'], $productID)
                                                        ->orderBy($colLogBasePriceSupplier['id'], 'desc') // Mengurutkan berdasarkan ID secara descending
                                                        ->first(); // Ambil record terakhir (ID terbesar)

                    if ($lastLogBasePrice)
                    {
                        $lastLogBasePrice->update([
                            $colLogBasePriceSupplier['created_at'] => $newDate,
                            $colLogBasePriceSupplier['updated_at'] => $newDate
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
        $column = config('db_constants.column.supplier_pic');

        for ($j=0; $j <= $numOfSupplierPic; $j++)
        {
            SupplierPic::create([
                $column['supplier_id'] => $supplierID,
                $column['name'] => $this->faker->name,
                $column['phone_number'] => $this->faker->phonenumber,
                $column['email'] => $this->faker->email,
                $column['assigned_date'] => $this->faker->date
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
                $this->colProduct['product_id'] => $prefix.$formattedNumber,
                $this->colProduct['name'] => $this->faker->word(),
                $this->colProduct['category_id'] => $this->faker->numberBetween(1, $numOfCategory),
                $this->colProduct['description'] => $this->faker->sentence(),
                $this->colProduct['measurement']  => $this->faker->randomElement($measurement_unit),
            ]);
        }
    }

    public function createCategory($numOfCategory)
    {
        for ($i=1; $i <= $numOfCategory; $i++)
        {
            Category::create([
                config('db_constants.column.category.category') => $this->faker->word
            ]);
        }
    }
}
