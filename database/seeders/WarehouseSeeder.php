<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use Faker\Factory as Faker;

class WarehouseSeeder extends Seeder
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
        $colWhouse = config('db_constants.column.whouse');
        $numOfWhouse = $this->faker->numberBetween(10, 100);

        for ($i=0; $i<$numOfWhouse; $i++)
        {
            Warehouse::create([
                $colWhouse['name'] => 'Gudang'.' '.$this->faker->word(),
                $colWhouse['address'] => $this->faker->address(),
                $colWhouse['is_rm_whouse'] => $this->faker->boolean(),
                $colWhouse['is_fg_whouse'] => $this->faker->boolean(),
                $colWhouse['phone'] => $this->faker->phoneNumber(),
                $colWhouse['is_active'] => $this->faker->boolean(),
            ]);
        }
    }
}
