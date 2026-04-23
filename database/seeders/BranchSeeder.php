<?php

namespace Database\Seeders;

<<<<<<< HEAD
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;
use Faker\Factory as Faker;
=======
use Illuminate\Database\Seeder;
use App\Models\Branch;
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
use App\Constants\BranchColumns;

class BranchSeeder extends Seeder
{
<<<<<<< HEAD
    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }

=======
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
        $numOfBranch = $this->faker->numberBetween(1, 10);

        for ($i=0; $i<=$numOfBranch; $i++)
        {
            Branch::create([
                BranchColumns::NAME => 'Cabang'.' '.$this->faker->city(),
                BranchColumns::ADDRESS => $this->faker->address(),
                BranchColumns::PHONE => $this->faker->phoneNumber(),
                BranchColumns::IS_ACTIVE => $this->faker->boolean(),
            ]);
        }

=======
        // Clear existing data
        Branch::truncate();
        
        // 1. PRODUCTION DATA - Specific business branches (using Factory with overrides)
        $this->createBusinessBranches();
        
        // 2. DEMO DATA - Regional branches (using Factory states)  
        $this->createRegionalBranches();
        
        // 3. TEST DATA - Random branches (using Factory for bulk creation)
        $this->createTestBranches();
    }
    
    /**
     * Create specific business branches (Production ready)
     */
    private function createBusinessBranches(): void
    {
        // Head Office - Jakarta
        Branch::factory()->jakarta()->create([
            BranchColumns::NAME => 'Cabang Jakarta Pusat - Head Office',
            BranchColumns::ADDRESS => 'Jl. Sudirman No. 1, Jakarta Pusat',
            BranchColumns::PHONE => '021-12345678',
            BranchColumns::IS_ACTIVE => true,
        ]);
        
        // Regional Offices
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang Surabaya - Regional Office',
            BranchColumns::ADDRESS => 'Jl. Pemuda No. 100, Surabaya',
            BranchColumns::PHONE => '031-87654321',
            BranchColumns::IS_ACTIVE => true,
        ]);
        
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang Bandung - Regional Office',
            BranchColumns::ADDRESS => 'Jl. Asia Afrika No. 50, Bandung',
            BranchColumns::PHONE => '022-11111111',
            BranchColumns::IS_ACTIVE => true,
        ]);
        
        Branch::factory()->create([
            BranchColumns::NAME => 'Cabang Medan - Regional Office',
            BranchColumns::ADDRESS => 'Jl. Imam Bonjol No. 25, Medan',
            BranchColumns::PHONE => '061-22222222',
            BranchColumns::IS_ACTIVE => true,
        ]);
    }
    
    /**
     * Create regional branches using Factory states
     */
    private function createRegionalBranches(): void
    {
        // Jakarta branches (active)
        Branch::factory()->jakarta()->active()->count(3)->create();
        
        // Surabaya branches (mixed status)
        Branch::factory()->inCity('Surabaya')->active()->count(2)->create();
        Branch::factory()->inCity('Surabaya')->inactive()->count(1)->create();
        
        // Bandung branches (mixed status)
        Branch::factory()->inCity('Bandung')->active()->count(2)->create();
        Branch::factory()->inCity('Bandung')->inactive()->count(1)->create();
        
        // Medan branches (active)
        Branch::factory()->inCity('Medan')->active()->count(2)->create();
    }
    
    /**
     * Create test/demo branches using Factory
     */
    private function createTestBranches(): void
    {
        // Random cities with random status
        Branch::factory()->count(5)->create();
        
        // Inactive branches for testing scenarios
        Branch::factory()->inactive()->count(2)->create();
        
        // Mixed active/inactive for realistic distribution
        Branch::factory()->active()->count(8)->create();
>>>>>>> 47f61f28a9cfd0339a553818484bca8913c8417d
    }
}