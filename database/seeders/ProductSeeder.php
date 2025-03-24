<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Faker\Factory as Faker;
use App\DataGeneration\SkripsiDatasetProvider;

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
                $column['name'] => $this->faker->fullProduct(),
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
                $column['name'] => $this->faker->fullProduct(),
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
                $category_name = $this->faker->asssproductCategory();
                print_r("Category Name: $category_name\n");

                Category::create([
                    $colCategory['category'] => $this->faker->asssproductCategory(),
                    $colCategory['parent_id'] => $id,
                    $colCategory['active'] => $this->faker->boolean()
                ]);
            }
        }
    }
}
