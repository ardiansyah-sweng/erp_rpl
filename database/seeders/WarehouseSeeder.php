<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Constants\WarehouseColumns;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific warehouses with known data for testing/demo
        $specificWarehouses = [
            [
                'name' => 'Warehouse Central Jakarta',
                'city' => 'Jakarta',
                'type' => 'mixed',
                'active' => true
            ],
            [
                'name' => 'RM Warehouse Bandung',
                'city' => 'Bandung', 
                'type' => 'rawMaterial',
                'active' => true
            ],
            [
                'name' => 'FG Warehouse Surabaya',
                'city' => 'Surabaya',
                'type' => 'finishedGoods', 
                'active' => true
            ],
            [
                'name' => 'Backup Warehouse Yogyakarta',
                'city' => 'Yogyakarta',
                'type' => 'mixed',
                'active' => false
            ]
        ];

        // Create specific warehouses using factory states
        foreach ($specificWarehouses as $warehouseData) {
            $factory = Warehouse::factory()
                ->withName($warehouseData['name'])
                ->inCity($warehouseData['city']);

            // Apply type state
            switch ($warehouseData['type']) {
                case 'rawMaterial':
                    $factory = $factory->rawMaterial();
                    break;
                case 'finishedGoods':
                    $factory = $factory->finishedGoods();
                    break;
                case 'mixed':
                    $factory = $factory->mixed();
                    break;
            }

            // Apply active/inactive state
            if ($warehouseData['active']) {
                $factory = $factory->active();
            } else {
                $factory = $factory->inactive();
            }

            $factory->create();
        }

        // Create random warehouses using factory
        // Mix of different types and states
        Warehouse::factory()
            ->count(5)
            ->active()
            ->rawMaterial()
            ->create();

        Warehouse::factory()
            ->count(4)
            ->active()
            ->finishedGoods()
            ->create();

        Warehouse::factory()
            ->count(3)
            ->active()
            ->mixed()
            ->create();

        // Some inactive warehouses
        Warehouse::factory()
            ->count(2)
            ->inactive()
            ->create();

        // Additional random warehouses in specific cities
        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Makassar', 'Semarang'];
        
        foreach ($cities as $city) {
            Warehouse::factory()
                ->count(rand(1, 3))
                ->inCity($city)
                ->active()
                ->create();
        }

        $this->command->info('WarehouseSeeder completed: Created ' . Warehouse::count() . ' warehouses');
    }
}
