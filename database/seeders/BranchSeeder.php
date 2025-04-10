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
        $numOfBranch = $this->faker->numberBetween(int1: 1, int2: 10);     

        for ($i=0; $i<=$numOfBranch; $i++)
        {
            Branch::create([
<<<<<<< HEAD
                $colBranch['branch_name'] => 'Cabang'.' '.$this->faker->word(),
                $colBranch['branch_address'] => $this->faker->address(),
                $colBranch['branch_telephone'] => $this->faker->phoneNumber(),
                $colBranch['branch_status'] => $this->faker->boolean(),
=======
                $colBranch['branch_name'] => 'Cabang'.' '.$this->faker->word(),      
                $colBranch['branch_address'] => $this->faker->address(),         
                $colBranch['branch_telephone'] => $this->faker->phoneNumber(),    
                $colBranch['branch_status'] => $this->faker->randomElement(array:[0, 1, 2, 3]),     
>>>>>>> 98e5a9a (Update Product.php, db_constants.php, and BranchSeeder.php)
            ]);
        }
    }
}
