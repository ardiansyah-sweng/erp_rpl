<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;
use Faker\Factory as Faker;

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
        $colBranch = config('db_constants.column.branch');
        $numOfBranch = $this->faker->numberBetween(1, 10);     

        for ($i=0; $i<=$numOfBranch; $i++)
        {
            Branch::create([
            ]);
        }
    }
}
