<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Branch;
use App\Constants\BranchColumns;

class BranchFactory extends Factory
{
    protected $model = Branch::class;
    
    public function definition(): array
    {
        $cities = ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Makassar', 'Palembang'];
        $city = $this->faker->randomElement($cities);
        
        return [
            BranchColumns::NAME => 'Cabang ' . $city . ' ' . $this->faker->randomElement(['Pusat', 'Utara', 'Selatan', 'Timur', 'Barat']) . ' ' . microtime(true) . rand(1, 9999),
            BranchColumns::ADDRESS => $this->faker->streetAddress . ', ' . $city,
            BranchColumns::PHONE => $this->generateIndonesianPhone(),
            BranchColumns::IS_ACTIVE => $this->faker->boolean(85), // 85% active
        ];
    }

    /**
     * Generate realistic Indonesian phone number
     */
    private function generateIndonesianPhone(): string
    {
        $areaCodes = ['021', '022', '024', '031', '061', '0274', '0411'];
        $areaCode = $this->faker->randomElement($areaCodes);
        $number = $this->faker->numerify('########');
        
        return $areaCode . '-' . $number;
    }
    
    /**
     * Indicate that the branch is active.
     */
    public function active()
    {
        return $this->state(fn (array $attributes) => [
            BranchColumns::IS_ACTIVE => true,
        ]);
    }
    
    /**
     * Indicate that the branch is inactive.
     */
    public function inactive()
    {
        return $this->state(fn (array $attributes) => [
            BranchColumns::IS_ACTIVE => false,
        ]);
    }

    /**
     * Create branch with specific city
     */
    public function inCity(string $city)
    {
        return $this->state(fn (array $attributes) => [
            BranchColumns::NAME => 'Cabang ' . $city . ' ' . $this->faker->randomElement(['Pusat', 'Utara', 'Selatan']) . ' ' . microtime(true) . rand(1, 999),
            BranchColumns::ADDRESS => $this->faker->streetAddress . ', ' . $city,
        ]);
    }

    /**
     * Create Jakarta specific branch
     */
    public function jakarta()
    {
        return $this->state(fn (array $attributes) => [
            BranchColumns::NAME => 'Cabang Jakarta ' . $this->faker->randomElement(['Pusat', 'Utara', 'Selatan', 'Timur', 'Barat']) . ' ' . microtime(true) . rand(1, 999),
            BranchColumns::ADDRESS => $this->faker->streetAddress . ', Jakarta',
            BranchColumns::PHONE => '021-' . $this->faker->numerify('########'),
        ]);
    }

    /**
     * Create branch with minimal valid data
     */
    public function minimal()
    {
        return $this->state(fn (array $attributes) => [
            BranchColumns::NAME => 'Test Branch',
            BranchColumns::ADDRESS => 'Test Address 123',
            BranchColumns::PHONE => '021-12345678',
        ]);
    }

    /**
     * Create branch with maximum length data
     */
    public function maxLength()
    {
        return $this->state(fn (array $attributes) => [
            BranchColumns::NAME => str_repeat('A', 50), // Max 50 chars
            BranchColumns::ADDRESS => str_repeat('B', 100), // Max 100 chars
            BranchColumns::PHONE => str_repeat('1', 30), // Max 30 chars
        ]);
    }
}
