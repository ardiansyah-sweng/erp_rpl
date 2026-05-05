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
        // Data kategori induk untuk testing
        $parentCategories = [
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

        // Data kategori anak (subcategories) untuk testing GetCategoryByParentTest
        $subCategories = [
            [
                CategoryColumns::CATEGORY => 'Smartphones',
                CategoryColumns::PARENT => 1, // ID dari Electronics
                CategoryColumns::IS_ACTIVE => true,
            ],
            [
                CategoryColumns::CATEGORY => 'Laptops',
                CategoryColumns::PARENT => 1, // ID dari Electronics
                CategoryColumns::IS_ACTIVE => true,
            ],
            [
                CategoryColumns::CATEGORY => 'T-Shirts',
                CategoryColumns::PARENT => 2, // ID dari Clothing
                CategoryColumns::IS_ACTIVE => true,
            ],
        ];

        // Insert kategori induk terlebih dahulu
        foreach ($parentCategories as $category) {
            Category::create($category);
        }

        // Insert kategori anak setelah induk ada
        foreach ($subCategories as $category) {
            Category::create($category);
        }
    }
}
