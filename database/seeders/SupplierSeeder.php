<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $prefix = 'SUP';
        $numOfSupplier = $faker->numberBetween(5, 100);

        for ($i=1; $i <= $numOfSupplier; $i++)
        {
            $formattedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);

            Supplier::create([
                'id' => $prefix . $formattedNumber,
                'company_name' => $faker->company,
                'address' => $faker->address,
                'phone_number' => $faker->numerify('(###) ###-####')
            ]);
        }
    }
}
