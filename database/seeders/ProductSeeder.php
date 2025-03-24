<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
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
        $column = config('db_constants.column.products');

        Product::insert([
            
                [
                    $column['id'] => 'KAOS', 
                    $column['name'] => 'Kaos TShirt', 
                    $column['type'] =>'FG', 
                    $column['category'] => 1, 
                    $column['desc'] => 'Kaos TShirt', 
                    $column['created'] => now(), 
                    $column['updated'] => now()
                ],

                [
                    $column['id'] => 'TOPI', 
                    $column['name'] => 'Topi', 
                    $column['type'] =>'FG', 
                    $column['category'] => 2, 
                    $column['desc'] => 'Topi', 
                    $column['created'] => now(), 
                    $column['updated'] => now()
                ],

                [
                    $column['id'] => 'TASS', 
                    $column['name'] => 'Tas', 
                    $column['type'] =>'FG', 
                    $column['category'] => 3, 
                    $column['desc'] => 'Tas', 
                    $column['created'] => now(), 
                    $column['updated'] => now()
                ],

                [
                    $column['id'] => 'TBLR', 
                    $column['name'] => 'Tumbler', 
                    $column['type'] =>'FG', 
                    $column['category'] => 4, 
                    $column['desc'] => 'Tumbler',                     
                    $column['created'] => now(), 
                    $column['updated'] => now()
                ],
                
                [
                    $column['id'] => 'TNJK', 
                    $column['name'] => 'Tanjak', 
                    $column['type'] =>'FG', 
                    $column['category'] => 5, 
                    $column['desc'] => 'Tanjak',                     
                    $column['created'] => now(), 
                    $column['updated'] => now()
                ],
                
                [
                    $column['id'] => 'MNTR', 
                    $column['name'] => 'Miniatur', 
                    $column['type'] =>'FG', 
                    $column['category'] => 6, 
                    $column['desc'] => 'Miniatur',                     
                    $column['created'] => now(), 
                    $column['updated'] => now()
                ],
                
                [
                    $column['id'] => 'CLDR', 
                    $column['name'] => 'Calendar', 
                    $column['type'] =>'FG', 
                    $column['category'] => 7, 
                    $column['desc'] => 'Calendar Nyenyes',                     
                    $column['created'] => now(), 
                    $column['updated'] => now()
                ],
                
                [
                    $column['id'] => 'JAMN', 
                    $column['name'] => 'Jam', 
                    $column['type'] =>'FG', 
                    $column['category'] => 8, 
                    $column['desc'] => 'Jam',                     
                    $column['created'] => now(), 
                    $column['updated'] => now()
                
                ],
                
                [
                    $column['id'] => 'KEYS', 
                    $column['name'] => 'Gantungan Kunci', 
                    $column['type'] =>'FG', 
                    $column['category'] => 9, 
                    $column['desc'] => 'Gantungan Kunci',                     
                    $column['created'] => now(), 
                    $column['updated'] => now()
                ],
                
                [
                    $column['id'] => 'PINN', 
                    $column['name'] => 'Bros PIN', 
                    $column['type'] =>'FG', 
                    $column['category'] => 10, 
                    $column['desc'] => 'Bros PIN',                     
                    $column['created'] => now(), 
                    $column['updated'] => now()
                ],
                
                [
                    $column['id'] => 'DMPT', 
                    $column['name'] => 'Dompet', 
                    $column['type'] =>'FG', 
                    $column['category'] => 11, 
                    $column['desc'] => 'Dompet',                     
                    $column['created'] => now(), 
                    $column['updated'] => now()
                ],
                
                [
                    $column['id'] => 'BOLN', 
                    $column['name'] => 'Kue Bolen', 
                    $column['type'] =>'FG', 
                    $column['category'] => 12, 
                    $column['desc'] => 'Kue Bolen',                     
                    $column['created'] => now(),
                    $column['updated'] => now()
                ],

                [
                    $column['id'] => 'PEMP', 
                    $column['name'] => 'Pempek', 
                    $column['type'] => 'FG', 
                    $column['category'] => 13, 
                    $column['desc'] => 'Pempek Palembang',                    
                    $column['created'] => now(),
                    $column['updated'] => now()
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
        $category = Category::where('active', 1)->inRandomOrder()->take(1)->get();

        $prefix = 'P';
        
        #create raw material products
        for ($i=1; $i<=$numOfRMProduct; $i++)
        {
            $formattedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
            $productID = $prefix . $formattedNumber;
            $categoryID = $category->pluck('id')->toArray();

            Product::create([
                $column['id'] => $productID,
                $column['name'] => $this->faker->word(),
                $column['type'] =>'RM',
                $column['category'] => $categoryID[0],
                $column['desc'] => $this->faker->sentence(),
                $column['created'] => now(),
                $column['updated'] => now()
            ]);
        }

        $category = Category::where('active', 1)->inRandomOrder()->take(1)->get();
        $numOFHFGProduct = $numOfRMProduct + $this->faker->numberBetween(1, 6);

        #create half finished goods products
        for ($i=$numOfRMProduct+1; $i<=$numOFHFGProduct; $i++)
        {
            $formattedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
            $productID = $prefix . $formattedNumber;
            $categoryID = $category->pluck('id')->toArray();

            Product::create([
                $column['id'] => $productID,
                $column['name'] => $this->faker->word(),
                $column['type'] =>'HFG',
                $column['category'] => $categoryID[0],
                $column['desc'] => $this->faker->sentence(),
                $column['created'] => now(),
                $column['updated'] => now()
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
                $colCategory['category'] => $this->faker->word,
                $colCategory['parent_id'] => null,
            ]);
        }

        #ambil id dari parent category
        $parentCategory = Category::whereNull('parent_id')->get();
        $parentCategoryID = $parentCategory->pluck('id')->toArray();
        foreach ($parentCategoryID as $id)
        {
            $numOfSubCategory = $this->faker->numberBetween(1, 5);
            for ($i=1; $i <= $numOfSubCategory; $i++)
            {
                Category::create([
                    $colCategory['category'] => $this->faker->word,
                    $colCategory['parent_id'] => $id,
                    $colCategory['active'] => $this->faker->boolean()
                ]);
            }
        }
    }
}
