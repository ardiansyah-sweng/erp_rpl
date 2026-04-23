<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Merk;
<<<<<<< HEAD
use Faker\Factory as Faker;
use App\Constants\MerkColumns;

class MerkSeeder extends Seeder
{
    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }

=======

class MerkSeeder extends Seeder
{
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
        $numOfMerk = $this->faker->numberBetween(1, 100);

        for ($i=0; $i<=$numOfMerk; $i++)
        {
            Merk::create([
                MerkColumns::MERK => $this->faker->word(),
                MerkColumns::IS_ACTIVE => $this->faker->boolean(),
            ]);
        }
=======
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
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }
}
