<?php

namespace Database\Seeders;

use App\DataGeneration\SkripsiDatasetProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Category;
use App\Models\Item;
use App\Models\LogBasePrice;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierPic;
use App\Models\SupplierProduct;
use App\Helpers\DBConstants;
use App\Enums\ProductType;

class SupplierSeeder extends Seeder
{
    const DATE_FORMAT = 'Y-m-d H:i:s';
    public \Faker\Generator $faker;
    private array $colProduct;
    private array $colItem;
    private array $colSupplier;
    private array $colSupplierProduct;
    private array $colLogBasePriceSupplier;
    private array $table;

    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
        $this->faker->addProvider(new SkripsiDatasetProvider($this->faker));

        $this->colProduct = config('db_constants.column.products');
        $this->colItem = config('db_constants.column.item');
        $this->colSupplier = config('db_constants.column.supplier');
        $this->colSupplierProduct = config('db_constants.column.supplier_product');
        $this->colLogBasePriceSupplier = config('db_constants.column.log_base_price_supplier');
        $this->table = config('db_constants.table');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colLogBasePriceSupplier = config('db_constants.column.log_base_price_supplier');
        $colSupplierProduct = config('db_constants.column.supplier_product');

        $this->generateSupplier();

        if (!isset($this->table['item']) || !is_string($this->table['item']))
        {
            throw new \Exception('Invalid table name provided in'. $this->table["item"]);
        }

        #mendapatkan seluruh item random dari product bertipe RM (raw material)
        $rawMaterial = (new Product()) -> getSKURawMaterialItem();
        $items = $rawMaterial -> get();

        #menetapkan supplier untuk setiap item
        foreach ($items as $sku)
        {

            #mendapatkan sejumlah supplier secara random untuk tiap item yang dipasok
            $supplier = Supplier::all();
            $numOfSupplier = $this->faker->numberBetween(1, $supplier->count());
            $suppliers = $supplier->pluck($this->colSupplier['supplier_id'])->shuffle()->take($numOfSupplier);
            $itemName = Item::where($this->colItem['sku'], $sku->sku)->get()->first()->item_name;

            foreach ($suppliers as $supplierID)
            {
                $res = SupplierProduct::where($this->colSupplierProduct['supplier_id'], $supplierID)
                    ->where($this->colSupplierProduct['product_id'], $sku->sku)
                    ->exists();
                $created_at = $this->faker->dateTimeBetween('-10 years', '2020-01-01 23:59:59')->format(self::DATE_FORMAT);
                $companyName = Supplier::where($this->colSupplier['supplier_id'], $supplierID)->get()->first()->company_name;
                $basePrice = $this->faker->numberBetween(4500, 75000);

                if (!$res)
                {
                    SupplierProduct::create([
                        $this->colSupplierProduct['supplier_id'] => $supplierID,
                        $this->colSupplierProduct['company_name'] => $companyName,
                        $this->colSupplierProduct['product_id'] => $sku->sku,
                        $this->colSupplierProduct['product_name'] => $itemName,
                        $this->colSupplierProduct['base_price'] => $basePrice,
                        $this->colSupplierProduct['created_at'] => $created_at,
                        $this->colSupplierProduct['updated_at'] => $created_at
                    ]);
                }

                # Seeder perubahan harga item produk
                $timesToChange = $this->faker->numberBetween(1, 10);
                for ($j=0; $j <= $timesToChange; $j++)
                {
                    $basePrice = $basePrice + $this->faker->numberBetween(-1000, 15000);

                    $supplierProduct = SupplierProduct::where($colSupplierProduct['product_id'], $sku->sku)
                                                        ->where($colSupplierProduct['supplier_id'], $supplierID);
                    $created_at = $supplierProduct->first()->created_at;
                    $supplierProduct->update([$colSupplierProduct['base_price'] => $basePrice]);

                    {
                        $newDate = (clone $created_at)->modify('+1 day')->format(self::DATE_FORMAT);
                    }

                    {
                        $newDate = $this->faker->dateTimeBetween($newDate, '2020-01-31 23:59:59')->format(self::DATE_FORMAT);
                    }

                    $supplierProduct->update([$colSupplierProduct['updated_at'] => $newDate]);
                    $lastLogBasePrice = LogBasePrice::where($colLogBasePriceSupplier['supplier_id'], $supplierID)
                                ->where($colLogBasePriceSupplier['product_id'], $sku->sku)
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
        }

    }

    public function generateSupplier()
    {
        $colSupplier = config('db_constants.column.supplier');
        $prefix = 'SUP';
        $numOfSupplier = $this->faker->numberBetween(5, 20);

        for ($i=1; $i <= $numOfSupplier; $i++)
        {
            $formattedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
            $supplierID = $prefix . $formattedNumber;

            $company_name = $this->faker->companySuffixPrefix();

            $bankAccount = 'Bank '.$company_name.' No. Rek '.$this->faker->bankAccountNumber;

            Supplier::create([
                $colSupplier['supplier_id'] => $supplierID,
                $colSupplier['company_name'] => $company_name,
                $colSupplier['address'] => $this->faker->address,
                $colSupplier['phone_number'] => $this->faker->phoneNumber(),
                $colSupplier['bank_account'] => $bankAccount
            ]);

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
                $column['assigned_date'] => $this->faker->date,
                $column['active'] => $this->faker->boolean,
                $column['avatar'] => $this->faker->imageUrl
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
