<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Merk;
use Faker\Factory as Faker;
use App\Constants\MerkColumns;

class MerkSeeder extends Seeder
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
        $numOfMerk = $this->faker->numberBetween(1, 100);

        for ($i=0; $i<=$numOfMerk; $i++)
        {
            Merk::create([
                MerkColumns::MERK => $this->faker->word(),
                MerkColumns::IS_ACTIVE => $this->faker->boolean(),
            ]);
        }
    }
}
