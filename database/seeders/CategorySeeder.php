<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Constants\CategoryColumns;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data kategori untuk testing
        $categories = [
            [
                CategoryColumns::CATEGORY => 'Electronics',
                CategoryColumns::PARENT => null,
                CategoryColumns::IS_ACTIVE => true,
            ],
            [
                CategoryColumns::CATEGORY => 'Clothing',
                CategoryColumns::PARENT => null,
                CategoryColumns::IS_ACTIVE => true,
            ],
            [
                CategoryColumns::CATEGORY => 'Food & Beverage',
                CategoryColumns::PARENT => null,
                CategoryColumns::IS_ACTIVE => true,
            ],
            [
                CategoryColumns::CATEGORY => 'Books',
                CategoryColumns::PARENT => null,
                CategoryColumns::IS_ACTIVE => true,
            ],
            [
                CategoryColumns::CATEGORY => 'Automotive',
                CategoryColumns::PARENT => null,
                CategoryColumns::IS_ACTIVE => true,
            ],
        ];

        // Insert categories
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
