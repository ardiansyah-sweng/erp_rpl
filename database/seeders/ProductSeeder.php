<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Faker\Factory as Faker;
use App\DataGeneration\SkripsiDatasetProvider;
use App\Constants\CategoryColumns;

class ProductSeeder extends Seeder
{
    public \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
        $this->faker->addProvider(new SkripsiDatasetProvider($this->faker));
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $column = config('db_constants.column.products');

        Product::insert([
            
            [
                'product_id' => 'KAOS', 
                'product_name' => 'Kaos TShirt', 
                'type' => 'FG', 
                'category' => 1, 
                'product_description' => 'Kaos TShirt', 
                'created_at' => now(), 
                'updated_at' => now()
            ],

            [
                'product_id' => 'TOPI', 
                'product_name' => 'Topi', 
                'type' => 'FG', 
                'category' => 2, 
                'product_description' => 'Topi', 
                'created_at' => now(), 
                'updated_at' => now()
            ],

            [
                'product_id' => 'TASS', 
                'product_name' => 'Tas', 
                'type' => 'FG', 
                'category' => 3, 
                'product_description' => 'Tas', 
                'created_at' => now(), 
                'updated_at' => now()
            ],

            [
                'product_id' => 'TBLR', 
                'product_name' => 'Tumbler', 
                'type' => 'FG', 
                'category' => 4, 
                'product_description' => 'Tumbler',                     
                'created_at' => now(), 
                'updated_at' => now()
            ],
            
            [
                'product_id' => 'TNJK', 
                'product_name' => 'Tanjak', 
                'type' => 'FG', 
                'category' => 5, 
                'product_description' => 'Tanjak',                     
                'created_at' => now(), 
                'updated_at' => now()
            ],
            
            [
                'product_id' => 'MNTR', 
                'product_name' => 'Miniatur', 
                'type' => 'FG', 
                'category' => 6, 
                'product_description' => 'Miniatur',                     
                'created_at' => now(), 
                'updated_at' => now()
            ],
            
            [
                'product_id' => 'CLDR', 
                'product_name' => 'Calendar', 
                'type' => 'FG', 
                'category' => 7, 
                'product_description' => 'Calendar Nyenyes',                     
                'created_at' => now(), 
                'updated_at' => now()
            ],
            
            [
                'product_id' => 'JAMN', 
                'product_name' => 'Jam', 
                'type' => 'FG', 
                'category' => 8, 
                'product_description' => 'Jam',                     
                'created_at' => now(), 
                'updated_at' => now()
            
            ],
            
            [
                'product_id' => 'KEYS', 
                'product_name' => 'Gantungan Kunci', 
                'type' => 'FG', 
                'category' => 9, 
                'product_description' => 'Gantungan Kunci',                     
                'created_at' => now(), 
                'updated_at' => now()
            ],
            
            [
                'product_id' => 'PINN', 
                'product_name' => 'Bros PIN', 
                'type' => 'FG', 
                'category' => 10, 
                'product_description' => 'Bros PIN',                     
                'created_at' => now(), 
                'updated_at' => now()
            ],
            
            [
                'product_id' => 'DMPT', 
                'product_name' => 'Dompet', 
                'type' => 'FG', 
                'category' => 11, 
                'product_description' => 'Dompet',                     
                'created_at' => now(), 
                'updated_at' => now()
            ],
            
            [
                'product_id' => 'BOLN', 
                'product_name' => 'Kue Bolen', 
                'type' => 'FG', 
                'category' => 12, 
                'product_description' => 'Kue Bolen',                     
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'product_id' => 'PEMP', 
                'product_name' => 'Pempek', 
                'type' => 'FG', 
                'category' => 13, 
                'product_description' => 'Pempek Palembang',                     
                'created_at' => now(),
                'updated_at' => now()
            ]
    ]);

        $numOfRMProduct = $this->faker->numberBetween(1, 50);
        $numOfCategory = $this->faker->numberBetween(1, 20);

        $products = Product::all();

        while ($products && $numOfCategory < $products->count())
        {
            $numOfCategory = $this->faker->numberBetween(1, 20);
        }

        $this->createCategory($numOfCategory);
        $category = Category::where(CategoryColumns::IS_ACTIVE, 1)->inRandomOrder()->take(1)->get();

        $prefix = 'P';

        #create raw material products
        for ($i=1; $i<=$numOfRMProduct; $i++)
        {
            $formattedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
            $productID = $prefix . $formattedNumber;
            $categoryID = $category->pluck('id')->toArray();

            Product::create([
                'product_id' => $productID,
                'product_name' => $this->faker->fullProduct(),
                'type' => 'RM',
                'category' => $categoryID[0],
                'product_description' => $this->faker->sentence(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $category = Category::where(CategoryColumns::IS_ACTIVE, 1)->inRandomOrder()->take(1)->get();
        $numOFHFGProduct = $numOfRMProduct + $this->faker->numberBetween(1, 6);

        #create half finished goods products
        for ($i=$numOfRMProduct+1; $i<=$numOFHFGProduct; $i++)
        {
            $formattedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
            $productID = $prefix . $formattedNumber;
            $categoryID = $category->pluck('id')->toArray();

            Product::create([
                'product_id' => $productID,
                'product_name' => $this->faker->fullProduct(),
                'type' => 'HFG',
                'category' => $categoryID[0],
                'product_description' => $this->faker->sentence(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function createCategory($numOfCategory)
    {
        $colCategory = config('db_constants.column.category');

        $numOfParentCategory = $this->faker->numberBetween(1, $numOfCategory);

        for ($i=1; $i <= $numOfParentCategory; $i++)
        {
            Category::create([
                CategoryColumns::CATEGORY => $this->faker->asssproductCategory(),
                CategoryColumns::PARENT => null,
            ]);
        }

        #ambil id dari parent category
        $parentCategory = Category::whereNull(CategoryColumns::PARENT)->get();
        $parentCategoryID = $parentCategory->pluck(CategoryColumns::ID)->toArray();
        foreach ($parentCategoryID as $id)
        {
            $numOfSubCategory = $this->faker->numberBetween(1, 5);
            for ($i=1; $i <= $numOfSubCategory; $i++)
            {
                $category_name = $this->faker->asssproductCategory();
                print_r("Category Name: $category_name\n");

                Category::create([
                    CategoryColumns::CATEGORY => $this->faker->asssproductCategory(),
                    CategoryColumns::PARENT => $id,
                    CategoryColumns::IS_ACTIVE => $this->faker->boolean()
                ]);
            }
        }
    }
}
