<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Merk;

class MerkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some specific well-known brands first
        $knownBrands = [
            ['merk' => 'Samsung', 'is_active' => true],
            ['merk' => 'Apple', 'is_active' => true],
            ['merk' => 'Sony', 'is_active' => true],
            ['merk' => 'LG', 'is_active' => true],
            ['merk' => 'Panasonic', 'is_active' => true],
            ['merk' => 'Philips', 'is_active' => true],
            ['merk' => 'Sharp', 'is_active' => true],
            ['merk' => 'Toshiba', 'is_active' => false], // Some inactive for testing
            ['merk' => 'Hitachi', 'is_active' => false],
        ];

        // Create known brands
        foreach ($knownBrands as $brand) {
            Merk::factory()->create($brand);
        }

        // Create additional random brands using factory
        Merk::factory()
            ->count(15) // Create 15 more random brands
            ->create();

        // Create some inactive brands for testing
        Merk::factory()
            ->count(5)
            ->inactive() // Using the inactive state
            ->create();
    }
}
