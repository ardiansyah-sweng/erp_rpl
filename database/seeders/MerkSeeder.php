<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Merk;
use Faker\Factory as Faker;

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
        $colMerk = config('db_constants.column.merk');
        $numOfMerk = $this->faker->numberBetween(1, 100);

        for ($i=0; $i<=$numOfMerk; $i++)
        {
            Merk::create([
                $colMerk['merk'] => $this->faker->word()
            ]);
        }
    }
}
