<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\SupplierPic;
use Faker\Factory as Faker;

class UpdateSupplierPicTest extends TestCase
{
    use WithFaker;

    public function test_modelUpdateSupplierPic(): void
    {
        $faker = Faker::create();

        $supplierPic = SupplierPic::inRandomOrder()->first();

        dump("Before Update:", $supplierPic);

        $newData = [
            'name' => $faker->name,
            'phone_number' => $faker->phoneNumber,
            'email' => $faker->unique()->safeEmail,
            'assigned_date' => $faker->date(),
        ];

        $supplierPic->update($newData);
        $updatedSupplierPic = $supplierPic->fresh();

        dump("After Update:", $updatedSupplierPic);

        $this->assertEquals($newData['name'], $updatedSupplierPic->name);
        $this->assertEquals($newData['phone_number'], $updatedSupplierPic->phone_number);
        $this->assertEquals($newData['email'], $updatedSupplierPic->email);
        $this->assertEquals($newData['assigned_date'], $updatedSupplierPic->assigned_date);
    }
}
