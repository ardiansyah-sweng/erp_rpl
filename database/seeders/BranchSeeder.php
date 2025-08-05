<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;
use Faker\Factory as Faker;
use App\Constants\BranchColumns;

class BranchSeeder extends Seeder
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
        $numOfBranch = $this->faker->numberBetween(1, 10);

        for ($i=0; $i<=$numOfBranch; $i++)
        {
            Branch::create([
                BranchColumns::NAME => 'Cabang'.' '.$this->faker->word(),
                BranchColumns::ADDRESS => $this->faker->address(),
                BranchColumns::PHONE => $this->faker->phoneNumber()
            ]);
        }

    }
}