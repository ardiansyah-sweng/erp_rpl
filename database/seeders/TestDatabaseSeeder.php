<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding test database with production-like data...');
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate existing data
        $this->command->info('🗑️  Clearing existing data...');
        Branch::truncate();
        
        // Seed branches with realistic data
        $this->command->info('🏢 Creating branch data...');
        
        // Create some realistic branches
        $branches = [
            [
                'branch_name' => 'Jakarta Pusat',
                'branch_address' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta',
                'branch_telephone' => '021-5551001',
                'is_active' => true,
            ],
            [
                'branch_name' => 'Surabaya Timur', 
                'branch_address' => 'Jl. Raya Gubeng No. 45, Surabaya, Jawa Timur',
                'branch_telephone' => '031-5552002',
                'is_active' => true,
            ],
            [
                'branch_name' => 'Bandung Utara',
                'branch_address' => 'Jl. Dago No. 78, Bandung, Jawa Barat', 
                'branch_telephone' => '022-5553003',
                'is_active' => true,
            ],
            [
                'branch_name' => 'Medan Selatan',
                'branch_address' => 'Jl. Gatot Subroto No. 90, Medan, Sumatera Utara',
                'branch_telephone' => '061-5554004',
                'is_active' => false, // Non-active for testing
            ],
            [
                'branch_name' => 'Yogyakarta Tengah',
                'branch_address' => 'Jl. Malioboro No. 12, Yogyakarta, DI Yogyakarta',
                'branch_telephone' => '0274-5555005',
                'is_active' => true,
            ],
        ];
        
        foreach ($branches as $branch) {
            Branch::create($branch);
        }
        
        // Generate additional random branches using factory
        $this->command->info('🎲 Generating additional random branches...');
        Branch::factory()->count(15)->create();
        
        // Create some inactive branches for testing filters
        Branch::factory()->count(5)->inactive()->create();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $totalBranches = Branch::count();
        $activeBranches = Branch::where('is_active', true)->count();
        $inactiveBranches = Branch::where('is_active', false)->count();
        
        $this->command->info("✅ Test database seeded successfully!");
        $this->command->info("📊 Total branches: {$totalBranches}");
        $this->command->info("✅ Active branches: {$activeBranches}");
        $this->command->info("❌ Inactive branches: {$inactiveBranches}");
    }
}
