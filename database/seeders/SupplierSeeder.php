<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\Supplier;
use App\Models\SupplierPic;

class SupplierSeeder extends Seeder
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
        $prefix = 'SUP'; #testing
        $numOfSupplier = $this->faker->numberBetween(5, 100);

        for ($i=1; $i <= $numOfSupplier; $i++)
        {
            $formattedNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
            $supplierID = $prefix . $formattedNumber;

            Supplier::create([
                'id' => $supplierID,
                'company_name' => $this->faker->company,
                'address' => $this->faker->address,
                'phone_number' => $this->faker->numerify('(###) ###-####')
            ]);

            $this->createDummySupplierPIC($supplierID);
        }
    }
    
    public function createDummySupplierPIC($supplierID)
    {
        $numOfSupplierPic = $this->faker->numberBetween(1, 5);

        for ($j=0; $j <= $numOfSupplierPic; $j++){
            SupplierPic::create([
                'id' => $supplierID,
                'name' => $this->faker->name,
                'phone_number' => $this->faker->phonenumber,
                'email' => $this->faker->email,
                'assigned_date' => $this->faker->date
            ]);
        }
    }
}
